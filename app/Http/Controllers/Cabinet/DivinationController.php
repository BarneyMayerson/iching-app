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
use Inertia\Inertia;
use Inertia\Response;

class DivinationController extends Controller
{
    public function __construct(protected readonly IChingService $ichingService) {}

    public function index(): Response
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return Inertia::render('Cabinet/Divinations/Index', [
            'divinations' => $user->readings()->latest()->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Cabinet/Divinations/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $readingData = $this->ichingService->createReading(
            question: $request->input('question', 'Consultation with the Oracle')
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

        return to_route('dashboard.divinations.show', $reading->id);
    }

    public function show(Reading $reading): Response
    {
        if ($reading->user_id !== Auth::id()) {
            abort(403);
        }

        $primaryHexagram = Hexagram::where('binary', $reading->binary)->first();

        /** @var list<int> $coinResults */
        $coinResults = $reading->coin_results;
        $changingLines = $this->ichingService->getChangingLines($coinResults);
        $secondaryHexagram = null;

        if (! empty($changingLines)) {
            $secondaryBinary = $this->ichingService->applyChangingLines($reading->binary, $changingLines);
            $secondaryHexagram = Hexagram::where('binary', $secondaryBinary)->first();
        }

        return Inertia::render('Cabinet/Divinations/Show', [
            'reading' => $reading,
            'hexagram' => $primaryHexagram,
            'secondary_hexagram' => $secondaryHexagram,
            'changing_lines' => $changingLines,
        ]);
    }
}
