<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gejala;
use App\Models\Penyakit;
use App\Models\AturanRule;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with initial symptoms, diseases, and rules.
     */
    public function run(): void
    {
        // 1. Seed Gejala (Symptoms)
        $gejalas = [
            1 => ['nama_gejala' => 'Demam tinggi mendadak (2-7 hari)'],
            2 => ['nama_gejala' => 'Bintik-bintik merah pada kulit yang tidak pudar saat ditekan'],
            3 => ['nama_gejala' => 'Nyeri pada sendi, otot, dan tulang'],
            4 => ['nama_gejala' => 'Menggigil hebat disertai keringat dingin secara berkala'],
            5 => ['nama_gejala' => 'Nyeri di belakang bola mata'],
            6 => ['nama_gejala' => 'Sakit kepala hebat'],
            7 => ['nama_gejala' => 'Mual, muntah, atau nafsu makan menurun drastis'],
            8 => ['nama_gejala' => 'Diare atau gangguan pencernaan'],
            9 => ['nama_gejala' => 'Lidah kotor (berwarna putih di tengah, tepi kemerahan)'],
            10 => ['nama_gejala' => 'Batuk kering atau berdahak'],
            11 => ['nama_gejala' => 'Pilek atau hidung tersumbat'],
            12 => ['nama_gejala' => 'Sakit tenggorokan saat menelan'],
            13 => ['nama_gejala' => 'Ruam kulit kemerahan yang meluas dari wajah ke seluruh tubuh'],
            14 => ['nama_gejala' => 'Mata merah, berair, dan sensitif terhadap cahaya (Konjungtivitis)'],
        ];

        foreach ($gejalas as $id => $data) {
            Gejala::updateOrCreate(
                ['id_gejala' => $id],
                ['nama_gejala' => $data['nama_gejala']]
            );
        }

        // 2. Seed Penyakit (Diseases) & Solusi
        $penyakits = [
            1 => [
                'nama_penyakit' => 'Demam Berdarah Dengue (DBD)',
                'solusi' => 'Lakukan istirahat total (bedrest). Konsumsi cairan dalam jumlah banyak (minimal 2-3 liter per hari seperti air putih, air kelapa, oralit, atau jus buah). Berikan kompres hangat jika demam tinggi. Konsumsi obat penurun panas jenis parasetamol (hindari aspirin/ibuprofen karena dapat memicu perdarahan). Pantau kadar trombosit secara berkala dan segera bawa ke rumah sakit jika muncul tanda bahaya seperti perdarahan (mimisan, gusi berdarah), nyeri perut hebat, muntah terus menerus, atau tubuh terasa dingin/lemas.'
            ],
            2 => [
                'nama_penyakit' => 'Malaria',
                'solusi' => 'Lakukan istirahat baring yang cukup. Konsumsi makanan bergizi dan banyak minum air putih hangat untuk mencegah dehidrasi akibat demam menggigil. Gunakan pakaian hangat atau selimut tebal saat fase menggigil, dan gunakan pakaian tipis/kompres hangat saat fase demam tinggi. Segera periksakan diri ke dokter atau klinik terdekat agar mendapatkan diagnosis pasti melalui pemeriksaan darah dan mendapatkan terapi obat antimalaria (seperti Artemisin-based Combination Therapy/ACT) yang tepat.'
            ],
            3 => [
                'nama_penyakit' => 'Demam Tifoid (Tipes)',
                'solusi' => 'Lakukan istirahat total (bedrest) di tempat tidur. Konsumsi makanan lunak/rendah serat seperti bubur saring atau sup hangat agar usus yang sedang meradang tidak bekerja terlalu keras. Jaga kebersihan sanitasi diri dan lingkungan, terutama mencuci tangan sebelum makan. Hindari makanan pedas, asam, atau berminyak. Konsumsi obat antibiotik yang diresepkan oleh dokter secara teratur hingga habis guna mencegah kekambuhan dan komplikasi perforasi usus.'
            ],
            4 => [
                'nama_penyakit' => 'Influenza (Flu)',
                'solusi' => 'Lakukan isolasi mandiri dan istirahat yang cukup di rumah untuk memulihkan daya tahan tubuh serta mencegah penularan. Perbanyak minum cairan hangat (air putih hangat, teh jahe hangat, atau sup ayam hangat) untuk membantu mengencerkan lendir di saluran napas. Gunakan masker saat berinteraksi dengan orang lain. Konsumsi obat pereda gejala flu (seperti kombinasi parasetamol, dekongestan, dan antihistamin) yang dijual bebas di apotek sesuai dosis anjuran.'
            ],
            5 => [
                'nama_penyakit' => 'Campak (Morbili)',
                'solusi' => 'Lakukan istirahat total di ruangan dengan pencahayaan sejuk (karena mata cenderung sensitif terhadap cahaya terang). Berikan asupan cairan yang melimpah untuk mencegah dehidrasi. Mandikan atau seka tubuh penderita dengan air hangat untuk menjaga kebersihan kulit dari ruam. Konsumsi obat penurun demam jika diperlukan. Sangat disarankan untuk berkonsultasi dengan dokter untuk pemberian Vitamin A dosis tinggi guna mencegah komplikasi serius pada mata dan paru-paru.'
            ]
        ];

        foreach ($penyakits as $id => $data) {
            Penyakit::updateOrCreate(
                ['id_penyakit' => $id],
                [
                    'nama_penyakit' => $data['nama_penyakit'],
                    'solusi' => $data['solusi']
                ]
            );
        }

        // 3. Seed AturanRule (Mappings)
        $rules = [
            // DBD: Demam tinggi, Bintik merah, Nyeri sendi, Nyeri bola mata, Sakit kepala, Mual muntah
            1 => [1, 2, 3, 5, 6, 7],
            // Malaria: Demam tinggi, Menggigil, Sakit kepala, Mual muntah
            2 => [1, 4, 6, 7],
            // Demam Tifoid: Demam tinggi, Sakit kepala, Mual muntah, Diare, Lidah kotor
            3 => [1, 6, 7, 8, 9],
            // Influenza: Demam tinggi, Sakit kepala, Batuk, Pilek, Sakit tenggorokan
            4 => [1, 6, 10, 11, 12],
            // Campak: Demam tinggi, Batuk, Pilek, Ruam kulit meluas, Mata merah
            5 => [1, 10, 11, 13, 14],
        ];

        // Hapus rule lama untuk menghindari duplikasi
        AturanRule::truncate();

        foreach ($rules as $penyakitId => $gejalaIds) {
            foreach ($gejalaIds as $gejalaId) {
                AturanRule::create([
                    'id_penyakit' => $penyakitId,
                    'id_gejala' => $gejalaId
                ]);
            }
        }
    }
}
