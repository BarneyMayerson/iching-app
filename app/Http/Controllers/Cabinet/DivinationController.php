<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReadingResource;
use App\Models\Hexagram;
use App\Models\Reading;
use App\Services\IChingService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DivinationController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected readonly IChingService $ichingService) {}

    public function index(Request $request): Response
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $readings = $user->readings()
            ->with('hexagram')
            ->when($request->input('search'), function ($query, $search) {
                $query->where('question', 'like', "%{$search}%");
            })
            ->when($request->input('hexagram'), function ($query, $hex) {
                $query->whereHas('hexagram', function ($q) use ($hex) {
                    $q->where('number', $hex);
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Cabinet/Divinations/Index', [
            'readings' => ReadingResource::collection($readings),
            'filters' => $request->only(['search', 'hexagram']),
            'hexagramList' => Hexagram::getOptionsForSelect(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Cabinet/Divinations/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:255', 'min:3'],
            'coin_results' => ['required', 'array', 'size:6'],
            'coin_results.*' => ['integer', 'in:6,7,8,9'],
        ]);

        $readingData = $this->ichingService->makeReading(
            question: $validated['question'],
            coinResults: $validated['coin_results'],
        );

        $reading = Reading::create([
            'user_id' => Auth::id(),
            ...$readingData,
        ]);

        return to_route('cabinet.divinations.show', $reading);
    }

    public function show(Reading $reading): Response
    {
        $this->authorize('view', $reading);

        $reading->load(
            'hexagram',
            'hexagram.hexagramLines',
            'hexagram.upperTrigram',
            'hexagram.lowerTrigram',
            'secondaryHexagram',
            'secondaryHexagram.upperTrigram',
            'secondaryHexagram.lowerTrigram',
        );

        /** @var list<int> $coinResults */
        $coinResults = $reading->coin_results;
        $changingLines = $this->ichingService->getChangingLines($coinResults);

        return Inertia::render('Cabinet/Divinations/Show', [
            'reading' => $reading->toResource(),
            'changing_lines' => $changingLines,
        ]);
    }

    public function destroy(Reading $reading): RedirectResponse
    {
        $this->authorize('delete', $reading);

        $reading->delete();

        return to_route('cabinet.divinations.index')
            ->with('message', 'Reading deleted successfully.');
    }

    public function export(Reading $reading): HttpResponse
    {
        $this->authorize('export', $reading);

        $reading->load(
            'hexagram',
            'hexagram.hexagramLines',
            'hexagram.upperTrigram',
            'hexagram.lowerTrigram',
            'secondaryHexagram',
            'secondaryHexagram.upperTrigram',
            'secondaryHexagram.lowerTrigram',
        );

        /** @var list<int> $coinResults */
        $coinResults = $reading->coin_results;
        $changingLines = $this->ichingService->getChangingLines($coinResults);

        dd($reading->toArray());
        $pdf = Pdf::loadView('pdf.divination', [
            'reading' => $reading,
            'changing_lines' => $changingLines,
        ]);

        $filename = $reading->created_at->format('Y-m-d').'_divination.pdf';

        return $pdf->download($filename);
    }
}
