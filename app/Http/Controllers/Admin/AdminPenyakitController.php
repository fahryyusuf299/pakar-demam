<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penyakit;
use App\Models\AdminLog;

class AdminPenyakitController extends Controller
{
    public function index()
    {
        $penyakits = Penyakit::orderBy('id_penyakit', 'asc')->get();
        return view('admin.penyakit.index', compact('penyakits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_penyakit' => 'required|string|max:10|unique:penyakit,id_penyakit',
            'nama_penyakit' => 'required|string|max:255',
            'solusi' => 'required|string',
        ], [
            'id_penyakit.required' => 'ID Penyakit (misal P13) wajib diisi.',
            'id_penyakit.unique' => 'ID Penyakit sudah ada di database.',
            'nama_penyakit.required' => 'Nama Penyakit wajib diisi.',
            'solusi.required' => 'Solusi / Rekomendasi wajib diisi.',
        ]);

        $penyakit = Penyakit::create([
            'id_penyakit' => strtoupper(trim($request->id_penyakit)),
            'nama_penyakit' => trim($request->nama_penyakit),
            'solusi' => trim($request->solusi),
        ]);

        AdminLog::record('TAMBAH_PENYAKIT', "Menambahkan data penyakit baru [{$penyakit->id_penyakit}] {$penyakit->nama_penyakit}");

        return redirect()->route('admin.penyakit.index')->with('success', 'Data penyakit berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $penyakit = Penyakit::findOrFail($id);

        $request->validate([
            'nama_penyakit' => 'required|string|max:255',
            'solusi' => 'required|string',
        ], [
            'nama_penyakit.required' => 'Nama Penyakit wajib diisi.',
            'solusi.required' => 'Solusi / Rekomendasi wajib diisi.',
        ]);

        $oldName = $penyakit->nama_penyakit;
        $penyakit->update([
            'nama_penyakit' => trim($request->nama_penyakit),
            'solusi' => trim($request->solusi),
        ]);

        AdminLog::record('EDIT_PENYAKIT', "Mengubah data penyakit [{$penyakit->id_penyakit}] dari '{$oldName}' menjadi '{$penyakit->nama_penyakit}'");

        return redirect()->route('admin.penyakit.index')->with('success', 'Data penyakit berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $penyakit = Penyakit::findOrFail($id);
        $name = $penyakit->nama_penyakit;
        $penyakitId = $penyakit->id_penyakit;

        $penyakit->delete();

        AdminLog::record('HAPUS_PENYAKIT', "Menghapus data penyakit [{$penyakitId}] {$name}");

        return redirect()->route('admin.penyakit.index')->with('success', 'Data penyakit berhasil dihapus.');
    }
}
