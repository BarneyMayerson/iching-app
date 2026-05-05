<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\StaticPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Inertia\Inertia;
use Inertia\Response;

class PublicController extends Controller
{
    public function home(): Response
    {
        return Inertia::render('Welcome');
    }

    public function showStaticPage(StaticPage $staticPage): Response
    {
        if (! $staticPage->is_published) {
            abort(HttpResponse::HTTP_NOT_FOUND);
        }

        return Inertia::render('StaticPage', [
            'page' => $staticPage->only('title', 'content'),
        ]);
    }

    public function language(Request $request): RedirectResponse
    {
        $request->session()->put('locale', $request->language);

        return back();
    }
}
