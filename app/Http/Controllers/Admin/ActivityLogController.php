<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function historique()
    {
        $logs = ActivityLog::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.activites.historique', compact('logs'));
    }

    public function journal()
    {
        $logs = ActivityLog::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.activites.journal', compact('logs'));
    }
}