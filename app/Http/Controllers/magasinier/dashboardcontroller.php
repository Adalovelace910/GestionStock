<?php

namespace App\Http\Controllers\Magasinier;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('magasinier.dashboard');
    }
}