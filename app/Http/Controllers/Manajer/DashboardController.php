<?php

namespace App\Http\Controllers\Manajer;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard Manajer.
     */
    public function index()
    {
        return view('manajer.dashboard');
    }
}