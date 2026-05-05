<?php

declare(strict_types=1);

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Reading;
use App\Services\IChingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class GuestDivinationController extends Controller
{
    public function __construct(
        protected readonly IChingService $ichingService,
    ) {}

    public function create(): Response
    {
        return Inertia::render('Guest/Divinations/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        if ($request->session()->get('has_performed_divination', false)) {
            return to_route('home')
                ->with('error', __('You have already performed your one guest divination. Please register to continue.'));
        }

        $validated = $request->validate([
            'question' => ['required', 'string', 'max:255', 'min:3'],
            'coin_results' => ['required', 'array', 'size:6'],
            'coin_results.*' => ['integer', 'in:6,7,8,9'],
        ]);

        $readingData = $this->ichingService->makeReading(
            question: $validated['question'],
            coinResults: $validated['coin_results'],
        );

        $guestReading = array_merge($readingData, [
            'id' => (string) Str::uuid(),
            'created_at' => now()->toDateTimeString(),
            'is_guest' => true,
        ]);

        $request->session()->put('guest_reading', $guestReading);
        $request->session()->put('has_performed_divination', true);

        return to_route('divination.show')
            ->with('success', __('Your free divination is complete. Create an account to save and get access to more features.'));
    }

    public function show(Request $request): Response|RedirectResponse
    {
        $readingData = $request->session()->get('guest_reading');

        if (! $readingData) {
            return to_route('home')->with('error', __('Session expired. Try again.'));
        }

        /** @var Reading $reading */
        $reading = Reading::make($readingData);

        $reading->load([
            'hexagram',
            'hexagram.hexagramLines',
            'hexagram.upperTrigram',
            'hexagram.lowerTrigram',
            'secondaryHexagram',
            'secondaryHexagram.upperTrigram',
            'secondaryHexagram.lowerTrigram',
        ]);

        /** @var list<int> $coinResults */
        $coinResults = $reading->coin_results;
        $changingLines = $this->ichingService->getChangingLines($coinResults);

        return Inertia::render('Guest/Divinations/Show', [
            'reading' => $reading->toResource(),
            'changing_lines' => $changingLines,
        ]);
    }
}
