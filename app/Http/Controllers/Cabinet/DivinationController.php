<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReadingResource;
use App\Models\Hexagram;
use App\Models\Reading;
use App\Services\IChingService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class DivinationController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected readonly IChingService $ichingService) {}

    public function index(): Response
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $readings = $user->readings()
            ->with('hexagram')
            ->latest();

        return Inertia::render('Cabinet/Divinations/Index', [
            'readings' => ReadingResource::collection($readings->paginate(12)->withQueryString()),
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
            'question' => $readingData['question'],
            'coin_results' => $readingData['coin_results'],
            'binary' => $readingData['binary'],
        ]);

        return to_route('cabinet.divinations.show', $reading->id);
    }

    public function show(Reading $reading): Response
    {
        if ($reading->user_id !== Auth::id()) {
            abort(403);
        }

        /** @var list<int> $coinResults */
        $coinResults = $reading->coin_results;
        $changingLines = $this->ichingService->getChangingLines($coinResults);

        $secondaryHexagram = null;

        if (! empty($changingLines)) {
            $secondaryBinary = $this->ichingService->applyChangingLines(Str::reverse($reading->binary), $changingLines);
            $secondaryHexagram = Hexagram::where('binary', Str::reverse($secondaryBinary))->first();
        }

        return Inertia::render('Cabinet/Divinations/Show', [
            'reading' => $reading->toResource(),
            'hexagram' => $reading->hexagram->load('hexagramLines')->toResource(), // TODO: optimize
            'secondary_hexagram' => $secondaryHexagram ? $secondaryHexagram->toResource() : null,
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
}
