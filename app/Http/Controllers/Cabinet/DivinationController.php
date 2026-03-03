<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Models\Hexagram;
use App\Models\Reading;
use App\Services\IChingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class DivinationController extends Controller
{
    public function __construct(protected readonly IChingService $ichingService) {}

    public function index(): Response
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $readings = $user->readings()
            ->with('hexagram')
            ->latest()
            ->get();

        return Inertia::render('Cabinet/Divinations/Index', [
            'divinations' => $readings->toResourceCollection(),
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
        ]);

        $readingData = $this->ichingService->createReading(
            question: $validated['question']
        );

        $primaryHexagram = Hexagram::where('binary', $readingData['binary'])->first();

        $changingLines = $this->ichingService->getChangingLines($readingData['coin_results']);
        $secondaryHexagram = null;

        if (! empty($changingLines)) {
            $secondaryBinary = $this->ichingService->applyChangingLines(
                $readingData['binary'],
                $changingLines
            );
            $secondaryHexagram = Hexagram::where('binary', $secondaryBinary)->first();
        }

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
}
