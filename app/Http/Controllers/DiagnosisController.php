<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gejala;
use App\Models\Penyakit;
use App\Models\RiwayatDiagnosa;

class DiagnosisController extends Controller
{
    /**
     * Display the consultation form with all available symptoms.
     */
    public function index()
    {
        $gejalas = Gejala::orderBy('id_gejala', 'asc')->get();
        return view('konsultasi', compact('gejalas'));
    }

    /**
     * Process the user's symptoms using the Forward Chaining algorithm.
     */
    public function proses(Request $request)
    {
        // 1. Validate the input
        $request->validate([
            'nama_pasien' => 'required|string|max:255',
            'id_gejala' => 'required|array|min:1',
            'id_gejala.*' => 'string|exists:gejala,id_gejala',
        ], [
            'nama_pasien.required' => 'Nama pasien wajib diisi.',
            'id_gejala.required' => 'Pilih minimal satu gejala untuk konsultasi.',
            'id_gejala.min' => 'Pilih minimal satu gejala untuk konsultasi.',
        ]);

        $userGejalaIds = $request->input('id_gejala', []);

        // 2. Fetch all diseases with their mapped symptoms
        $penyakits = Penyakit::with('gejala')->get();

        $matchedPenyakit = null;

        // --- ALGORITMA FORWARD CHAINING ---
        
        // Tahap 1: Pencarian Exact Set Match
        // Mencari penyakit yang memiliki kumpulan gejala yang SAMA PERSIS dengan input user.
        foreach ($penyakits as $penyakit) {
            $requiredGejalaIds = $penyakit->gejala->pluck('id_gejala')->toArray();

            if (empty($requiredGejalaIds)) {
                continue;
            }

            $tempRequired = $requiredGejalaIds;
            $tempUser = $userGejalaIds;
            sort($tempRequired);
            sort($tempUser);

            if ($tempRequired === $tempUser) {
                $matchedPenyakit = $penyakit;
                break;
            }
        }

        // Tahap 2: Pencarian Subset Match (jika Exact Set Match tidak ditemukan)
        // Mencari penyakit di mana SEMUA gejala wajib penyakit tersebut ada dalam pilihan user (penyakit adalah bagian dari input user).
        if (!$matchedPenyakit) {
            foreach ($penyakits as $penyakit) {
                $requiredGejalaIds = $penyakit->gejala->pluck('id_gejala')->toArray();

                if (empty($requiredGejalaIds)) {
                    continue;
                }

                // Mengecek apakah semua gejala wajib penyakit merupakan subset dari pilihan user
                $diff = array_diff($requiredGejalaIds, $userGejalaIds);
                if (empty($diff)) {
                    $matchedPenyakit = $penyakit;
                    break;
                }
            }
        }

        // 3. Respon Hasil Pencocokan
        if ($matchedPenyakit) {
            // Ambil semua nama gejala yang dipilih user untuk disimpan ke dalam riwayat
            $selectedGejalaNames = Gejala::whereIn('id_gejala', $userGejalaIds)
                ->orderBy('id_gejala', 'asc')
                ->pluck('nama_gejala')
                ->toArray();

            // Simpan riwayat diagnosa ke database
            $riwayat = RiwayatDiagnosa::create([
                'nama_pasien' => $request->input('nama_pasien'),
                'gejala_dipilih' => $selectedGejalaNames,
                'hasil_penyakit' => $matchedPenyakit->nama_penyakit,
                'solusi' => $matchedPenyakit->solusi,
            ]);

            return redirect()->route('konsultasi.hasil', ['id' => $riwayat->id_diagnosa]);
        }

        // Jika tidak ada penyakit yang cocok 100%
        return redirect()->back()
            ->withInput()
            ->with('warning', 'Gejala tidak spesifik. Silakan isi ulang kuesioner dengan lebih akurat atau segera lakukan konsultasi langsung ke Klinik Amanah Riau Kepri.');
    }

    /**
     * Show the diagnosis result page.
     */
    public function hasil($id)
    {
        $riwayat = RiwayatDiagnosa::findOrFail($id);
        
        return view('hasil', compact('riwayat'));
    }
}
