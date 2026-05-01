<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class DonateController extends Controller
{
    public function instructions(): Response
    {
        return Inertia::render('Cabinet/Donate/Instructions', [
            'payment' => [
                'order_id' => '123-896-ZXC',
                'amount' => 100,
                'status' => 'pending',
            ],
        ]);
    }
}
