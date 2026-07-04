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
            'id_penyakit' => 'P01',
            'nama_penyakit' => 'Demam Berdarah Dengue (DBD)',
            'solusi' => 'Istirahat total dan perbanyak minum air putih.',
        ]);

        // 2. Create symptoms (Gejala)
        $gejalaList = [
            'G01' => 'Demam tinggi mendadak',
            'G02' => 'Demam bertahap',
            'G03' => 'Bintik merah pada kulit',
            'G04' => 'Nyeri sendi',
        ];

        foreach ($gejalaList as $id => $nama) {
            $this->gejalas[$id] = Gejala::create([
                'id_gejala' => $id,
                'nama_gejala' => $nama,
            ]);
        }

        // 3. Map Rules (Many-to-Many)
        // DBD requires symptoms G01 (pola_demam) and G03 (gejala penyerta)
        AturanRule::create([
            'id_penyakit' => $this->penyakit->id_penyakit,
            'id_gejala' => $this->gejalas['G01']->id_gejala,
        ]);
        AturanRule::create([
            'id_penyakit' => $this->penyakit->id_penyakit,
            'id_gejala' => $this->gejalas['G03']->id_gejala,
        ]);
    }

    /**
     * Test that the homepage loads successfully.
     */
    public function test_homepage_loads_successfully(): void
    {
        $response = $this->get(route('beranda'));

        $response->assertStatus(200);
        $response->assertSee('Deteksi Awal Penyakit Demam Anda');
    }

    /**
     * Test that the consultation page loads and lists all symptoms.
     */
    public function test_consultation_page_loads_and_lists_symptoms(): void
    {
        $response = $this->get(route('konsultasi.index'));

        $response->assertStatus(200);
        $response->assertSee('Demam tinggi mendadak');
        $response->assertSee('Bintik merah pada kulit');
    }

    /**
     * Test a successful exact match diagnosis (100% score).
     */
    public function test_successful_exact_match_diagnosis(): void
    {
        // User inputs exact symptoms: G01 (radio) and G03 (checkbox)
        $response = $this->post(route('konsultasi.proses'), [
            'nama_pasien' => 'Ahmad Riau',
            'pola_demam' => 'G01',
            'id_gejala' => [
                'G03',
            ],
        ]);

        $riwayat = RiwayatDiagnosa::first();
        $this->assertNotNull($riwayat);
        $this->assertEquals('Ahmad Riau', $riwayat->nama_pasien);
        $this->assertEquals('Demam Berdarah Dengue (DBD)', $riwayat->hasil_penyakit);
        
        // Assert JSON details (Score should be 100%)
        $this->assertEquals(100.0, $riwayat->gejala_dipilih['score']);
        $this->assertCount(2, $riwayat->gejala_dipilih['matched']);

        $response->assertRedirect(route('konsultasi.hasil', ['id' => $riwayat->id_diagnosa]));
    }

    /**
     * Test a successful subset match diagnosis with penalty.
     * Base score is 100%. 1 extra symptom (G04) results in -2% penalty. Final score = 98%.
     */
    public function test_successful_subset_match_diagnosis(): void
    {
        // User inputs G01, G03 (required for DBD) AND G04 (extra)
        $response = $this->post(route('konsultasi.proses'), [
            'nama_pasien' => 'Ahmad Riau',
            'pola_demam' => 'G01',
            'id_gejala' => [
                'G03',
                'G04',
            ],
        ]);

        $riwayat = RiwayatDiagnosa::first();
        $this->assertNotNull($riwayat);
        $this->assertEquals('Demam Berdarah Dengue (DBD)', $riwayat->hasil_penyakit);
        $this->assertEquals(98.0, $riwayat->gejala_dipilih['score']);

        $response->assertRedirect(route('konsultasi.hasil', ['id' => $riwayat->id_diagnosa]));
    }

    /**
     * Test that partial match (score >= 50%) works.
     * User selects G01 but not G03. Match score is 50% (1/2).
     */
    public function test_partial_match_diagnosis_above_threshold(): void
    {
        $response = $this->post(route('konsultasi.proses'), [
            'nama_pasien' => 'Ahmad Riau',
            'pola_demam' => 'G01',
            'id_gejala' => [],
        ]);

        $riwayat = RiwayatDiagnosa::first();
        $this->assertNotNull($riwayat);
        $this->assertEquals('Demam Berdarah Dengue (DBD)', $riwayat->hasil_penyakit);
        $this->assertEquals(50.0, $riwayat->gejala_dipilih['score']);

        $response->assertRedirect(route('konsultasi.hasil', ['id' => $riwayat->id_diagnosa]));
    }

    /**
     * Test that match under 50% threshold results in "Gejala Tidak Spesifik".
     * User selects G02 (which is not in rule). Base score is 0%, final is 0%.
     */
    public function test_match_below_threshold_returns_non_specific(): void
    {
        $response = $this->post(route('konsultasi.proses'), [
            'nama_pasien' => 'Ahmad Riau',
            'pola_demam' => 'G02',
            'id_gejala' => [],
        ]);

        $riwayat = RiwayatDiagnosa::first();
        $this->assertNotNull($riwayat);
        $this->assertEquals('Gejala Tidak Spesifik', $riwayat->hasil_penyakit);
        $this->assertEquals(0.0, $riwayat->gejala_dipilih['score']);

        $response->assertRedirect(route('konsultasi.hasil', ['id' => $riwayat->id_diagnosa]));
    }
}
