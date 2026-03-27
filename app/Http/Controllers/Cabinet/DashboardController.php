<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $user = $request->user();
        $year = $request->integer('year', (int) date('Y'));

        // 1. Статистика по месяцам
        // Заполняем массив нулями для всех 12 месяцев, чтобы график не "прыгал"
        $monthlyCounts = $user->readings()
            ->whereYear('created_at', $year)
            // ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->selectRaw(DB::getDriverName() === 'sqlite'
                ? "strftime('%m', created_at) as month, COUNT(*) as count"
                : 'MONTH(created_at) as month, COUNT(*) as count'
            )
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $monthlyStats = collect(range(1, 12))->map(fn ($m) => [
            'month' => $m,
            'count' => $monthlyCounts[$m] ?? 0,
        ]);

        // 2. Самая частая гексаграмма
        /** @var object{count: int, hexagram: \App\Models\Hexagram}|null $topReading */
        $topReading = $user->readings()
            ->select('binary')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('binary')
            ->orderByDesc('count')
            ->with('hexagram:names,binary,number,character')
            ->first();

        // 3. Энергетический баланс (Инь/Ян)
        // Берем все бинарные строки и считаем символы
        $allBinaries = $user->readings()->pluck('binary')->implode('');
        $totalLines = strlen($allBinaries);
        $yangCount = substr_count($allBinaries, '1');
        $yinCount = substr_count($allBinaries, '0');

        return Inertia::render('Cabinet/Dashboard', [
            'stats' => [
                'monthly' => $monthlyStats,
                'balance' => [
                    'yang' => $yangCount,
                    'yin' => $yinCount,
                    'total' => $totalLines,
                ],
                'top_hexagram' => $topReading ? [
                    'count' => $topReading->count,
                    'hexagram' => $topReading->hexagram->toResource(),
                ] : null,
                'total_readings' => $user->readings()->count(),
            ],
            'filters' => [
                'year' => $year,
                'available_years' => $user->readings()
                    // ->selectRaw('YEAR(created_at) as year')
                    ->selectRaw(DB::getDriverName() === 'sqlite'
                        ? "strftime('%Y', created_at) as year"
                        : 'YEAR(created_at) as year')
                    ->distinct()
                    ->orderByDesc('year')
                    ->pluck('year'),
            ],
        ]);
    }
}
