<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function historique()
    {
        $logs = ActivityLog::with('user')
            ->where('categorie', 'connexion')
            ->latest()
            ->paginate(20);

        return view('admin.activites.historique', compact('logs'));
    }

    public function journal()
    {
        $logs = ActivityLog::with('user')
            ->where('categorie', 'operation')
            ->latest()
            ->paginate(20);

        return view('admin.activites.journal', compact('logs'));
    }
}