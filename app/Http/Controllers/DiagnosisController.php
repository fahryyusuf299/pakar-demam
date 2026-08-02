<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gejala;
use App\Models\Penyakit;
use App\Models\RiwayatDiagnosa;
use App\Models\AturanRule;

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
            'id_gejala.required' => 'Pilih minimal satu gejala untuk berkonsultasi.',
            'id_gejala.min' => 'Pilih minimal satu gejala untuk berkonsultasi.',
        ]);

        $userGejalaIds = array_unique($request->input('id_gejala', []));
        // Otomatis tambahkan G01 (Demam tinggi) karena ini adalah aplikasi diagnosa demam
        if (!in_array('G01', $userGejalaIds)) {
            $userGejalaIds[] = 'G01';
        }

        // 2. Ambil Aturan Knowledge Base secara dinamis dari Database Supabase (aturan_rule)
        // Mendukung beberapa variasi/kelompok rule untuk penyakit yang sama
        $allRules = AturanRule::select('id_penyakit', 'id_gejala')->get();
        $ruleBase = [];

        // Kelompokkan rule unik per penyakit (Rule Utama & Rule Alternatif jika ada)
        $diseaseRulesMap = [];
        foreach ($allRules as $r) {
            $diseaseRulesMap[$r->id_penyakit][] = $r->id_gejala;
        }

        $ruleCounter = 1;
        foreach ($diseaseRulesMap as $penyakitId => $gejalaList) {
            $uniqueGejalaList = array_values(array_unique($gejalaList));
            $ruleBase[] = [
                'code' => 'R' . str_pad($ruleCounter++, 2, '0', STR_PAD_LEFT),
                'id_penyakit' => $penyakitId,
                'gejala' => $uniqueGejalaList,
            ];
        }

        $penyakits = Penyakit::all()->keyBy('id_penyakit');
        $diseaseScores = [];

        // Evaluasi ke-20 rule
        foreach ($ruleBase as $rule) {
            $penyakitId = $rule['id_penyakit'];
            if (!isset($penyakits[$penyakitId])) {
                continue;
            }

            $ruleGejalaIds = $rule['gejala'];
            $totalRuleGejala = count($ruleGejalaIds);

            // Hitung gejala cocok dengan rule ini
            $matchingGejalaIds = array_intersect($userGejalaIds, $ruleGejalaIds);
            $matchCount = count($matchingGejalaIds);

            if ($matchCount === 0) {
                continue;
            }

            $baseScore = ($matchCount / $totalRuleGejala) * 100;
            $extraSymptoms = array_diff($userGejalaIds, $ruleGejalaIds);

            // Jika 100% gejala dari rule terpenuhi (Exact Match), skor = 100%
            if ($matchCount === $totalRuleGejala) {
                $score = 100;
            } else {
                $penalty = count($extraSymptoms) * 2;
                $score = max(0, $baseScore - $penalty);
            }

            // Simpan skor tertinggi per penyakit
            if (!isset($diseaseScores[$penyakitId]) || $score > $diseaseScores[$penyakitId]['score']) {
                $diseaseScores[$penyakitId] = [
                    'penyakit' => $penyakits[$penyakitId],
                    'score' => $score,
                    'match_count' => $matchCount,
                    'matching_gejala_ids' => array_values($matchingGejalaIds),
                    'matched_rule' => $rule['code']
                ];
            }
        }

        $scores = array_values($diseaseScores);

        // Urutkan skor secara descending
        // Jika skor sama, urutkan berdasarkan jumlah gejala cocok terbanyak
        usort($scores, function ($a, $b) {
            if (abs($b['score'] - $a['score']) < 0.0001) {
                return $b['match_count'] <=> $a['match_count'];
            }
            return $b['score'] <=> $a['score'];
        });

        $topMatch = !empty($scores) ? $scores[0] : null;

        // Ambil semua nama gejala yang dipilih user (tanpa G01) untuk disimpan ke dalam riwayat
        $displayUserGejalaIds = array_values(array_diff($userGejalaIds, ['G01']));

        $selectedGejalaNames = Gejala::whereIn('id_gejala', $displayUserGejalaIds)
            ->orderBy('id_gejala', 'asc')
            ->pluck('nama_gejala')
            ->toArray();

        if ($topMatch && $topMatch['score'] >= 50) {
            $matchedPenyakit = $topMatch['penyakit'];
            $hasilPenyakit = $matchedPenyakit->nama_penyakit;
            $solusi = $matchedPenyakit->solusi;
            $scoreValue = round($topMatch['score'], 1);

            // Ambil semua nama gejala yang cocok (tanpa G01 untuk tampilan)
            $displayMatchedGejalaIds = array_values(array_diff($topMatch['matching_gejala_ids'], ['G01']));

            $matchedGejalaNames = Gejala::whereIn('id_gejala', $displayMatchedGejalaIds)
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
