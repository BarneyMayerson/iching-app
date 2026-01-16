<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Models\Reading;
use App\Services\IChingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DivinationController extends Controller
{
    public function __construct(protected readonly IChingService $ichingService) {}

    public function index(): void
    {
        dd('index');
    }

    public function store(Request $request): void
    {
        $reading = $this->ichingService->createReading(question: $request->input('question'));

        $r = Reading::create([...$reading, 'user_id' => Auth::id()]);
    }
}
