<?php

namespace App\Http\Controllers;

use App\Models\Vacancy;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function index()
    {
        $vacancies = Vacancy::where('status', 'active')->get();
        return view('careers.index', compact('vacancies'));
    }

    public function show(string $locale, string $slug)
    {
        $vacancy = Vacancy::where('slug', $slug)->firstOrFail();
        return view('careers.show', compact('vacancy'));
    }
}
