<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penyakit;
use App\Models\Gejala;
use App\Models\AturanRule;
use App\Models\RiwayatDiagnosa;
use App\Models\AdminLog;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalPenyakit = Penyakit::count();
        $totalGejala = Gejala::count();
        $totalRule = AturanRule::count();
        $totalRiwayat = RiwayatDiagnosa::count();

        $recentLogs = AdminLog::with('admin')->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'totalPenyakit',
            'totalGejala',
            'totalRule',
            'totalRiwayat',
            'recentLogs'
        ));
    }

    public function logs()
    {
        $logs = AdminLog::with('admin')->latest()->paginate(20);
        return view('admin.logs', compact('logs'));
    }
}
