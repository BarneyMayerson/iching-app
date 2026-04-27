<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\StaticPage;
use Illuminate\Http\Response as HttpResponse;
use Inertia\Inertia;
use Inertia\Response;

class StaticPageController extends Controller
{
    public function show(StaticPage $staticPage): Response
    {
        // dd($page->toArray());
        if (! $staticPage->is_published) {
            abort(HttpResponse::HTTP_NOT_FOUND);
        }

        return Inertia::render('StaticPage', [
            'page' => $staticPage->only('title', 'content'),
        ]);
    }
}
