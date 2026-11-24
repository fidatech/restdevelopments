<?php

namespace App\Http\Controllers;

use App\Models\Vacancy;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function index()
    {
        // open roles first, then closed ones, newest deadline first
        $vacancies = Vacancy::where('status', 'active')->with('translations')
            ->orderByDesc('deadline')->get()
            ->sortBy(fn ($v) => $v->deadline && $v->deadline->endOfDay()->isPast() ? 1 : 0)
            ->values();
        return view('careers.index', compact('vacancies'));
    }

    public function show(string $locale, string $slug)
    {
        $vacancy = Vacancy::where('slug', $slug)->firstOrFail();
        return view('careers.show', compact('vacancy'));
    }
}
