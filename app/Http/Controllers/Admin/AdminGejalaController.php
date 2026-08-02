<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gejala;
use App\Models\AdminLog;

class AdminGejalaController extends Controller
{
    public function index()
    {
        $gejalas = Gejala::orderBy('id_gejala', 'asc')->get();
        return view('admin.gejala.index', compact('gejalas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_gejala' => 'required|string|max:10|unique:gejala,id_gejala',
            'nama_gejala' => 'required|string',
        ], [
            'id_gejala.required' => 'ID Gejala (misal G41) wajib diisi.',
            'id_gejala.unique' => 'ID Gejala sudah terdaftar.',
            'nama_gejala.required' => 'Deskripsi / Nama Gejala wajib diisi.',
        ]);

        $gejala = Gejala::create([
            'id_gejala' => strtoupper(trim($request->id_gejala)),
            'nama_gejala' => trim($request->nama_gejala),
        ]);

        AdminLog::record('TAMBAH_GEJALA', "Menambahkan gejala baru [{$gejala->id_gejala}] {$gejala->nama_gejala}");

        return redirect()->route('admin.gejala.index')->with('success', 'Gejala baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $gejala = Gejala::findOrFail($id);

        $request->validate([
            'nama_gejala' => 'required|string',
        ], [
            'nama_gejala.required' => 'Deskripsi / Nama Gejala wajib diisi.',
        ]);

        $oldDesc = $gejala->nama_gejala;
        $gejala->update([
            'nama_gejala' => trim($request->nama_gejala),
        ]);

        AdminLog::record('EDIT_GEJALA', "Mengubah gejala [{$gejala->id_gejala}] dari '{$oldDesc}' menjadi '{$gejala->nama_gejala}'");

        return redirect()->route('admin.gejala.index')->with('success', 'Data gejala berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $gejala = Gejala::findOrFail($id);
        $desc = $gejala->nama_gejala;
        $gejalaId = $gejala->id_gejala;

        $gejala->delete();

        AdminLog::record('HAPUS_GEJALA', "Menghapus gejala [{$gejalaId}] {$desc}");

        return redirect()->route('admin.gejala.index')->with('success', 'Data gejala berhasil dihapus.');
    }
}
