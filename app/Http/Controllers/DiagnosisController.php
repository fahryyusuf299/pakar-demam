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
            'pola_demam' => 'required|string|exists:gejala,id_gejala',
            'id_gejala' => 'nullable|array',
            'id_gejala.*' => 'string|exists:gejala,id_gejala',
        ], [
            'nama_pasien.required' => 'Nama pasien wajib diisi.',
            'pola_demam.required' => 'Pola demam utama wajib dipilih.',
        ]);

        $userGejalaIds = $request->input('id_gejala', []);
        if ($request->filled('pola_demam')) {
            $userGejalaIds[] = $request->input('pola_demam');
        }

        // 2. Fetch all diseases with their mapped symptoms
        $penyakits = Penyakit::with('gejala')->get();

        $scores = [];

        foreach ($penyakits as $penyakit) {
            $ruleGejalaIds = $penyakit->gejala->pluck('id_gejala')->toArray();
            $totalRuleGejala = count($ruleGejalaIds);

            if ($totalRuleGejala === 0) {
                continue;
            }

            // Hitung irisan gejala pilihan user dengan aturan penyakit
            $matchingGejalaIds = array_intersect($userGejalaIds, $ruleGejalaIds);
            $matchCount = count($matchingGejalaIds);

            // Rumus Base Score = (Jumlah Cocok / Total Wajib) * 100%
            $baseScore = ($matchCount / $totalRuleGejala) * 100;

            // Hitung gejala tambahan (pilihan user yang TIDAK ADA di rule)
            $extraSymptoms = array_diff($userGejalaIds, $ruleGejalaIds);
            $penalty = count($extraSymptoms) * 2;

            // Skor akhir = Base Score - Penalty (min 0)
            $score = max(0, $baseScore - $penalty);

            $scores[] = [
                'penyakit' => $penyakit,
                'score' => $score,
                'match_count' => $matchCount,
                'matching_gejala_ids' => array_values($matchingGejalaIds)
            ];
        }

        // Urutkan skor secara descending
        // Jika skor sama, urutkan berdasarkan jumlah gejala cocok terbanyak
        usort($scores, function ($a, $b) {
            if (abs($b['score'] - $a['score']) < 0.0001) {
                return $b['match_count'] <=> $a['match_count'];
            }
            return $b['score'] <=> $a['score'];
        });

        $topMatch = !empty($scores) ? $scores[0] : null;

        // Ambil semua nama gejala yang dipilih user untuk disimpan ke dalam riwayat
        $selectedGejalaNames = Gejala::whereIn('id_gejala', $userGejalaIds)
            ->orderBy('id_gejala', 'asc')
            ->pluck('nama_gejala')
            ->toArray();

        if ($topMatch && $topMatch['score'] >= 50) {
            $matchedPenyakit = $topMatch['penyakit'];
            $hasilPenyakit = $matchedPenyakit->nama_penyakit;
            $solusi = $matchedPenyakit->solusi;
            $scoreValue = round($topMatch['score'], 1);

            // Ambil semua nama gejala yang cocok
            $matchedGejalaNames = Gejala::whereIn('id_gejala', $topMatch['matching_gejala_ids'])
                ->orderBy('id_gejala', 'asc')
                ->pluck('nama_gejala')
                ->toArray();
        } else {
            $hasilPenyakit = 'Gejala Tidak Spesifik';
            $solusi = 'Gejala tidak spesifik untuk mengarah ke penyakit infeksi dalam basis pengetahuan. Silakan isi ulang kuesioner atau segera lakukan konsultasi langsung ke Klinik Amanah Riau Kepri.';
            $scoreValue = $topMatch ? round($topMatch['score'], 1) : 0;
            $matchedGejalaNames = [];
        }

        // Simpan riwayat diagnosa ke database
        $riwayat = RiwayatDiagnosa::create([
            'nama_pasien' => $request->input('nama_pasien'),
            'gejala_dipilih' => [
                'selected' => $selectedGejalaNames,
                'matched' => $matchedGejalaNames,
                'score' => $scoreValue,
            ],
            'hasil_penyakit' => $hasilPenyakit,
            'solusi' => $solusi,
        ]);

        return redirect()->route('konsultasi.hasil', ['id' => $riwayat->id_diagnosa]);
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
