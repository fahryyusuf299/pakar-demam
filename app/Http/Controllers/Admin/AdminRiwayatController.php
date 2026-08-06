<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RiwayatDiagnosa;
use App\Models\AdminLog;

class AdminRiwayatController extends Controller
{
    /**
     * Display a listing of client consultation histories.
     */
    public function index(Request $request)
    {
        $search = trim($request->query('search', ''));

        $query = RiwayatDiagnosa::latest('tanggal_konsultasi');

        if (!empty($search)) {
            $query->where('nama_pasien', 'ilike', "%{$search}%")
                  ->orWhere('hasil_penyakit', 'ilike', "%{$search}%");
        }

        $riwayats = $query->paginate(15)->withQueryString();

        return view('admin.riwayat.index', compact('riwayats', 'search'));
    }

    /**
     * Remove the specified consultation history from storage.
     */
    public function destroy($id)
    {
        $riwayat = RiwayatDiagnosa::findOrFail($id);
        $namaPasien = $riwayat->nama_pasien;
        $hasilPenyakit = $riwayat->hasil_penyakit;

        $riwayat->delete();

        AdminLog::record('HAPUS_RIWAYAT', "Menghapus riwayat konsultasi pasien '{$namaPasien}' (Hasil: {$hasilPenyakit})");

        return redirect()->route('admin.riwayat.index')->with('success', "Riwayat konsultasi untuk '{$namaPasien}' berhasil dihapus.");
    }
}
