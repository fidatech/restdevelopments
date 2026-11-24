<?php

namespace App\Http\Controllers;

use App\Models\FounderSetting;

class FounderController extends Controller
{
    public function index()
    {
        $founder = FounderSetting::with('translations')->where('status', true)->first();
        abort_unless($founder, 404);
        return view('pages.founder', compact('founder'));
    }
}
