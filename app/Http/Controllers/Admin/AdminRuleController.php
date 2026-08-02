<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AturanRule;
use App\Models\Penyakit;
use App\Models\Gejala;
use App\Models\AdminLog;

class AdminRuleController extends Controller
{
    public function index()
    {
        $penyakits = Penyakit::with(['gejala'])->orderBy('id_penyakit', 'asc')->get();
        return view('admin.rules.index', compact('penyakits'));
    }

    public function show($id)
    {
        $penyakit = Penyakit::with(['gejala'])->where('id_penyakit', $id)->firstOrFail();
        
        // Ambil ID gejala yang sudah terhubung dengan penyakit ini
        $existingGejalaIds = $penyakit->gejala->pluck('id_gejala')->toArray();
        
        // Ambil daftar gejala yang BELUM terhubung dengan penyakit ini
        $availableGejalas = Gejala::whereNotIn('id_gejala', $existingGejalaIds)
            ->orderBy('id_gejala', 'asc')
            ->get();

        return view('admin.rules.show', compact('penyakit', 'availableGejalas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_penyakit' => 'required|string|exists:penyakit,id_penyakit',
            'id_gejala' => 'required|string|exists:gejala,id_gejala',
        ], [
            'id_penyakit.required' => 'Penyakit wajib dipilih.',
            'id_gejala.required' => 'Gejala wajib dipilih.',
        ]);

        $exists = AturanRule::where('id_penyakit', $request->id_penyakit)
            ->where('id_gejala', $request->id_gejala)
            ->first();

        if ($exists) {
            return back()->withErrors(['id_gejala' => 'Gejala ini sudah terhubung dengan penyakit tersebut.'])->withInput();
        }

        $rule = AturanRule::create([
            'id_penyakit' => $request->id_penyakit,
            'id_gejala' => $request->id_gejala,
        ]);

        AdminLog::record('TAMBAH_RULE', "Menambahkan indikator gejala [{$rule->id_gejala}] ke Penyakit [{$rule->id_penyakit}]");

        return redirect()->route('admin.rules.show', $request->id_penyakit)->with('success', 'Gejala baru berhasil ditambahkan ke aturan penyakit.');
    }

    public function destroy($id)
    {
        $rule = AturanRule::findOrFail($id);
        $pId = $rule->id_penyakit;
        $gId = $rule->id_gejala;

        $rule->delete();

        AdminLog::record('HAPUS_RULE', "Menghapus pemetaan gejala [{$gId}] dari Penyakit [{$pId}]");

        return redirect()->route('admin.rules.show', $pId)->with('success', 'Indikator gejala berhasil dihapus dari aturan penyakit.');
    }
}
