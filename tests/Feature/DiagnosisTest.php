<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Gejala;
use App\Models\Penyakit;
use App\Models\AturanRule;
use App\Models\RiwayatDiagnosa;

class DiagnosisTest extends TestCase
{
    use RefreshDatabase;

    private $penyakit;
    private $gejalas = [];

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create a disease (Penyakit)
        $this->penyakit = Penyakit::create([
            'nama_penyakit' => 'Demam Berdarah Dengue (DBD)',
            'solusi' => 'Istirahat total dan perbanyak minum air putih.',
        ]);

        // 2. Create symptoms (Gejala)
        $gejalaList = [
            1 => 'Demam tinggi mendadak',
            2 => 'Bintik merah pada kulit',
            3 => 'Nyeri sendi',
        ];

        foreach ($gejalaList as $id => $nama) {
            $this->gejalas[$id] = Gejala::create([
                'id_gejala' => $id,
                'nama_gejala' => $nama,
            ]);
        }

        // 3. Map Rules (Many-to-Many)
        // DBD requires symptoms 1 and 2
        AturanRule::create([
            'id_penyakit' => $this->penyakit->id_penyakit,
            'id_gejala' => $this->gejalas[1]->id_gejala,
        ]);
        AturanRule::create([
            'id_penyakit' => $this->penyakit->id_penyakit,
            'id_gejala' => $this->gejalas[2]->id_gejala,
        ]);
    }

    /**
     * Test that the homepage loads successfully.
     */
    public function test_homepage_loads_successfully(): void
    {
        $response = $this->get(route('beranda'));

        $response.assertStatus(200);
        $response.assertSee('Deteksi Awal Penyakit Demam Anda');
    }

    /**
     * Test that the consultation page loads and lists all symptoms.
     */
    public function test_consultation_page_loads_and_lists_symptoms(): void
    {
        $response = $this->get(route('konsultasi.index'));

        $response.assertStatus(200);
        $response.assertSee('Demam tinggi mendadak');
        $response.assertSee('Bintik merah pada kulit');
    }

    /**
     * Test a successful exact match diagnosis.
     */
    public function test_successful_exact_match_diagnosis(): void
    {
        // User inputs exact symptoms: 1 and 2
        $response = $this->post(route('konsultasi.proses'), [
            'nama_pasien' => 'Ahmad Riau',
            'id_gejala' => [
                $this->gejalas[1]->id_gejala,
                $this->gejalas[2]->id_gejala,
            ],
        ]);

        // Expect redirect to result page
        $riwayat = RiwayatDiagnosa::first();
        $this->assertNotNull($riwayat);
        $this->assertEquals('Ahmad Riau', $riwayat->nama_pasien);
        $this->assertEquals('Demam Berdarah Dengue (DBD)', $riwayat->hasil_penyakit);

        $response.assertRedirect(route('konsultasi.hasil', ['id' => $riwayat->id_diagnosa]));
    }

    /**
     * Test a successful subset match diagnosis (user selects extra symptoms).
     */
    public function test_successful_subset_match_diagnosis(): void
    {
        // User inputs 1, 2 (required for DBD) AND symptom 3 (extra)
        $response = $this->post(route('konsultasi.proses'), [
            'nama_pasien' => 'Ahmad Riau',
            'id_gejala' => [
                $this->gejalas[1]->id_gejala,
                $this->gejalas[2]->id_gejala,
                $this->gejalas[3]->id_gejala, // Extra symptom
            ],
        ]);

        $riwayat = RiwayatDiagnosa::first();
        $this->assertNotNull($riwayat);
        $this->assertEquals('Demam Berdarah Dengue (DBD)', $riwayat->hasil_penyakit);
        
        $response.assertRedirect(route('konsultasi.hasil', ['id' => $riwayat->id_diagnosa]));
    }

    /**
     * Test that non-specific symptoms show a warning message.
     */
    public function test_non_specific_symptoms_return_warning(): void
    {
        // User only inputs symptom 3 (which doesn't map to any disease rules)
        $response = $this->from(route('konsultasi.index'))
            ->post(route('konsultasi.proses'), [
                'nama_pasien' => 'Ahmad Riau',
                'id_gejala' => [
                    $this->gejalas[3]->id_gejala,
                ],
            ]);

        // Expect redirect back with warning
        $response.assertRedirect(route('konsultasi.index'));
        $response.assertSessionHas('warning', 'Gejala tidak spesifik. Silakan isi ulang kuesioner dengan lebih akurat atau segera lakukan konsultasi langsung ke Klinik Amanah Riau Kepri.');
        
        // Assert no records created in history
        $this->assertEquals(0, RiwayatDiagnosa::count());
    }
}
