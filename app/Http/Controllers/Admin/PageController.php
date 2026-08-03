<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class PageController extends Controller
{
    public function apropos()
    {
        $parametre = Setting::first();

        return view('admin.apropos', compact('parametre'));
    }
}