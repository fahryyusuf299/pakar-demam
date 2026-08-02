<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AturanRule;

class RuleSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            // R01 (P01 - DBD)
            'P01' => ['G01', 'G03', 'G04', 'G06', 'G07', 'G12', 'G13', 'G16'],
            // R02 (P02 - Tifus)
            'P02' => ['G01', 'G02', 'G05', 'G06', 'G12', 'G13', 'G15'],
            // R03 (P03 - Malaria)
            'P03' => ['G01', 'G06', 'G08', 'G09', 'G14', 'G21', 'G35', 'G40'],
            // R04 (P04 - Chikungunya)
            'P04' => ['G01', 'G06', 'G10', 'G11', 'G12', 'G13', 'G14', 'G19'],
            // R05 (P05 - Leptospirosis)
            'P05' => ['G01', 'G06', 'G08', 'G11', 'G12', 'G13', 'G15', 'G37'],
            // R06 (P06 - Influenza)
            'P06' => ['G01', 'G06', 'G11', 'G12', 'G27', 'G29', 'G30', 'G39'],
            // R07 (P07 - Campak)
            'P07' => ['G01', 'G12', 'G21', 'G23', 'G24', 'G27', 'G30'],
            // R08 (P08 - Rubella)
            'P08' => ['G01', 'G06', 'G12', 'G23', 'G30', 'G36'],
            // R09 (P09 - Pneumonia)
            'P09' => ['G01', 'G08', 'G12', 'G15', 'G28', 'G29', 'G38'],
            // R10 (P10 - Radang Tenggorokan)
            'P10' => ['G01', 'G06', 'G12', 'G13', 'G30', 'G31'],
            // R11 (P11 - Hepatitis A)
            'P11' => ['G01', 'G12', 'G13', 'G14', 'G15', 'G25', 'G26', 'G34'],
            // R12 (P12 - Demam Scarlet)
            'P12' => ['G01', 'G06', 'G22', 'G30', 'G32', 'G33'],

            // Rule Alternatif (R13 - R20)
            // R13 (P01 - DBD)
            'P01_ALT' => ['G01', 'G03', 'G04', 'G07', 'G12'],
            // R14 (P02 - Tifus)
            'P02_ALT' => ['G01', 'G02', 'G05', 'G15', 'G40'],
            // R15 (P03 - Malaria)
            'P03_ALT' => ['G01', 'G08', 'G09', 'G35', 'G40'],
            // R16 (P04 - Chikungunya)
            'P04_ALT' => ['G01', 'G10', 'G11', 'G19'],
            // R17 (P05 - Leptospirosis)
            'P05_ALT' => ['G01', 'G08', 'G11', 'G37', 'G40'],
            // R18 (P06 - Influenza)
            'P06_ALT' => ['G01', 'G27', 'G30', 'G39'],
            // R19 (P07 - Campak)
            'P07_ALT' => ['G01', 'G21', 'G24', 'G30'],
            // R20 (P08 - Rubella)
            'P08_ALT' => ['G01', 'G23', 'G30', 'G36'],
        ];

        // Seed data
    }
}
