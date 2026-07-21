<?php

namespace Database\Seeders;

use App\Models\Machine;
use App\Models\MasterDepartment;
use App\Models\MasterMachineCategory;
use App\Models\MasterProductionArea;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RealMachineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                $machines = [
            // Area: BAHAN BAKU
            ['name' => 'PRESS 1', 'code' => 'A-PS.01', 'area' => 'BAHAN BAKU'],
            ['name' => 'PRESS 2', 'code' => 'A-PS.02', 'area' => 'BAHAN BAKU'],
            ['name' => 'PRESS 3', 'code' => 'A-PS.03', 'area' => 'BAHAN BAKU'],
            ['name' => 'GUNTING POTONG 1', 'code' => 'A-GT.01', 'area' => 'BAHAN BAKU'],
            ['name' => 'CUTTING PLASMA 1', 'code' => 'A-CP.01', 'area' => 'BAHAN BAKU'],
            ['name' => 'CUTTING PLASMA 2', 'code' => 'A-CP.02', 'area' => 'BAHAN BAKU'],
            ['name' => 'HOIST 3', 'code' => 'A-HS.03', 'area' => 'BAHAN BAKU'],
            ['name' => 'HOIST 4', 'code' => 'A-HS.04', 'area' => 'BAHAN BAKU'],
            // Area: COR FLANGE
            ['name' => 'FOUNDRY 2', 'code' => 'B-FD.02', 'area' => 'COR FLANGE'],
            ['name' => 'FOUNDRY 3', 'code' => 'B-FD.03', 'area' => 'COR FLANGE'],
            ['name' => 'FOUNDRY 5', 'code' => 'B-FD.05', 'area' => 'COR FLANGE'],
            ['name' => 'KOMPRESOR SCREW 1', 'code' => 'B-KS.01', 'area' => 'COR FLANGE'],
            ['name' => 'KOMPRESOR SCREW 2', 'code' => 'B-KS.02', 'area' => 'COR FLANGE'],
            ['name' => 'HOIST 1', 'code' => 'B-HS.01', 'area' => 'COR FLANGE'],
            ['name' => 'HOIST 2', 'code' => 'B-HS.02', 'area' => 'COR FLANGE'],
            ['name' => 'MOLEN 1', 'code' => 'B-MN.01', 'area' => 'COR FLANGE'],
            ['name' => 'MOLEN 2', 'code' => 'B-MN.02', 'area' => 'COR FLANGE'],
            ['name' => 'MOULDING 1', 'code' => 'B-MD.01', 'area' => 'COR FLANGE'],
            ['name' => 'MOULDING 2', 'code' => 'B-MD.02', 'area' => 'COR FLANGE'],
            ['name' => 'MOULDING 3', 'code' => 'B-MD.03', 'area' => 'COR FLANGE'],
            ['name' => 'MOULDING 4', 'code' => 'B-MD.04', 'area' => 'COR FLANGE'],
            ['name' => 'MOULDING 5', 'code' => 'B-MD.05', 'area' => 'COR FLANGE'],
            ['name' => 'SAND TREATMENT 1', 'code' => 'B-ST.01', 'area' => 'COR FLANGE'],
            ['name' => 'AYAK PASIR 1', 'code' => 'B-AP.01', 'area' => 'COR FLANGE'],
            ['name' => 'LOADER 1', 'code' => 'B-LD.01', 'area' => 'COR FLANGE'],
            // Area: NETTO FLANGE
            ['name' => 'CUTTING PLASMA 3', 'code' => 'C-CP.03', 'area' => 'NETTO FLANGE'],
            ['name' => 'CUTTING PLASMA 4', 'code' => 'C-CP.04', 'area' => 'NETTO FLANGE'],
            ['name' => 'CUTTING PLASMA 5', 'code' => 'C-CP.05', 'area' => 'NETTO FLANGE'],
            ['name' => 'LAS ARGON 1', 'code' => 'C-LA.01', 'area' => 'NETTO FLANGE'],
            ['name' => 'LAS ARGON 2', 'code' => 'C-LA.02', 'area' => 'NETTO FLANGE'],
            ['name' => 'GERINDA KASAR 1', 'code' => 'C-GK.01', 'area' => 'NETTO FLANGE'],
            ['name' => 'GERINDA KASAR 2', 'code' => 'C-GK.02', 'area' => 'NETTO FLANGE'],
            ['name' => 'SAND BLASTING 1', 'code' => 'C.SB.01', 'area' => 'NETTO FLANGE'],
            ['name' => 'HEAD TREATMENT', 'code' => 'C-HT.01', 'area' => 'NETTO FLANGE'],
            // Area: GUDANG JADI FLANGE
            ['name' => 'STEMPEL ANGIN 1', 'code' => 'D-SA.01', 'area' => 'GUDANG JADI FLANGE'],
            ['name' => 'STEMPEL ANGIN 2', 'code' => 'D-SA.02', 'area' => 'GUDANG JADI FLANGE'],
            ['name' => 'STRAPING TALI 1', 'code' => 'D-SP.01', 'area' => 'GUDANG JADI FLANGE'],
            ['name' => 'STRAPING TALI 2', 'code' => 'D-SP.02', 'area' => 'GUDANG JADI FLANGE'],
            ['name' => 'WRAPPING', 'code' => 'D-WP.01', 'area' => 'GUDANG JADI FLANGE'],
            // Area: QC BALL VALVE
            ['name' => 'KOMPRESOR PISTON', 'code' => 'E-KP.01', 'area' => 'QC BALL VALVE'],
            ['name' => 'TEST BALL VALVE', 'code' => 'E-BV.01', 'area' => 'QC BALL VALVE'],
            ['name' => 'PENCUCI FITTING', 'code' => 'E-PF.01', 'area' => 'QC BALL VALVE'],
            ['name' => 'BOR MILLING 5', 'code' => 'E-ML.05', 'area' => 'QC BALL VALVE'],
            ['name' => 'HAND BLASTING 1', 'code' => 'E-HB.01', 'area' => 'QC BALL VALVE'],
            // Area: FITTING
            ['name' => 'CETAK LILIN 1', 'code' => 'F-CL.01', 'area' => 'FITTING'],
            ['name' => 'CETAK LILIN 2', 'code' => 'F-CL.02', 'area' => 'FITTING'],
            ['name' => 'CETAK LILIN 3', 'code' => 'F-CL.03', 'area' => 'FITTING'],
            ['name' => 'CETAK LILIN 4', 'code' => 'F-CL.04', 'area' => 'FITTING'],
            ['name' => 'CILLER 1', 'code' => 'F-CR.01', 'area' => 'FITTING'],
            ['name' => 'TANJEK LILIN 1', 'code' => 'F-TL.01', 'area' => 'FITTING'],
            ['name' => 'TANJEK LILIN 2', 'code' => 'F-TL.02', 'area' => 'FITTING'],
            ['name' => 'PENAMPUNG LILIN 1', 'code' => 'F-PL.01', 'area' => 'FITTING'],
            ['name' => 'PENAMPUNG LILIN 2', 'code' => 'F-PL.02', 'area' => 'FITTING'],
            ['name' => 'PENAMPUNG LILIN 3', 'code' => 'F-PL.03', 'area' => 'FITTING'],
            ['name' => 'PENAMPUNG LILIN 4', 'code' => 'F-PL.04', 'area' => 'FITTING'],
            ['name' => 'PENAMPUNG LILIN 5', 'code' => 'F-PL.05', 'area' => 'FITTING'],
            ['name' => 'PENAMPUNG LILIN 6', 'code' => 'F-PL.06', 'area' => 'FITTING'],
            ['name' => 'MIXER ADONAN 1', 'code' => 'F-MA.01', 'area' => 'FITTING'],
            ['name' => 'MIXER ADONAN 2', 'code' => 'F-MA.02', 'area' => 'FITTING'],
            ['name' => 'MIXER ADONAN 3', 'code' => 'F-MA.03', 'area' => 'FITTING'],
            ['name' => 'MIXER ADONAN 4', 'code' => 'F-MA.04', 'area' => 'FITTING'],
            ['name' => 'MIXER ADONAN 5', 'code' => 'F-MA.05', 'area' => 'FITTING'],
            ['name' => 'MIXER ADONAN 6', 'code' => 'F-MA.06', 'area' => 'FITTING'],
            ['name' => 'MIXER ADONAN 7', 'code' => 'F-MA.07', 'area' => 'FITTING'],
            ['name' => 'OVEN LILIN 1', 'code' => 'F-OL.01', 'area' => 'FITTING'],
            ['name' => 'PEMANAS LILIN 1', 'code' => 'F-PM.01', 'area' => 'FITTING'],
            ['name' => 'TRANSFER LILIN 1', 'code' => 'F-TF.01', 'area' => 'FITTING'],
            ['name' => 'BLOWER 1', 'code' => 'F-BW.01', 'area' => 'FITTING'],
            ['name' => 'BLOWER 2', 'code' => 'F-BW.02', 'area' => 'FITTING'],
            ['name' => 'BLOWER 3', 'code' => 'F-BW.03', 'area' => 'FITTING'],
            ['name' => 'KOMPRESOR PISTON 1', 'code' => 'F-KP.01', 'area' => 'FITTING'],
            ['name' => 'KOMPRESOR PISTON 2', 'code' => 'F-KP.02', 'area' => 'FITTING'],
            ['name' => 'KOMPRESOR SCREW 1', 'code' => 'F-KS.01', 'area' => 'FITTING'],
            ['name' => 'KOMPRESOR SCREW 2', 'code' => 'F-KS.02', 'area' => 'FITTING'],
            ['name' => 'FOUNDRY 4 175 KW', 'code' => 'F-FD.01', 'area' => 'FITTING'],
            ['name' => 'OVEN COR', 'code' => 'F-OC.01', 'area' => 'FITTING'],
            ['name' => 'BIOMAS', 'code' => 'F-BIO.01', 'area' => 'FITTING'],
            // Area: NETTO FITTING
            ['name' => 'CUTTING PLASMA 6', 'code' => 'G-CP.06', 'area' => 'NETTO FITTING'],
            ['name' => 'CUTTING PLASMA 7', 'code' => 'G-CP.07', 'area' => 'NETTO FITTING'],
            ['name' => 'GERINDA HALUS 1', 'code' => 'G-GH.01', 'area' => 'NETTO FITTING'],
            ['name' => 'GERINDA HALUS 2', 'code' => 'G-GH.02', 'area' => 'NETTO FITTING'],
            ['name' => 'GERINDA KASAR 3', 'code' => 'G-GK.03', 'area' => 'NETTO FITTING'],
            ['name' => 'GERINDA KASAR 4', 'code' => 'G-GK.04', 'area' => 'NETTO FITTING'],
            ['name' => 'GERINDA POTONG 1', 'code' => 'G-GP.01', 'area' => 'NETTO FITTING'],
            ['name' => 'GERINDA POTONG 2', 'code' => 'G-GP.02', 'area' => 'NETTO FITTING'],
            ['name' => 'SAND BLASTING 2', 'code' => 'G-SB.02', 'area' => 'NETTO FITTING'],
            ['name' => 'SAND BLASTING 3', 'code' => 'G-SB.03', 'area' => 'NETTO FITTING'],
            ['name' => 'SAND BLASTING 4', 'code' => 'G-SB.04', 'area' => 'NETTO FITTING'],
            ['name' => 'BOR DUDUK 1', 'code' => 'G-BD.01', 'area' => 'NETTO FITTING'],
            ['name' => 'BOR DUDUK 1', 'code' => 'G-BD.02', 'area' => 'NETTO FITTING'],
            ['name' => 'DRILLING 1', 'code' => 'G-DR.01', 'area' => 'NETTO FITTING'],
            ['name' => 'DRILLING 1', 'code' => 'G-DR.02', 'area' => 'NETTO FITTING'],
            ['name' => 'HAND BLASTING 1', 'code' => 'G-HB.01', 'area' => 'NETTO FITTING'],
            // Area: ALMINI
            ['name' => 'GERINDA POTONG', 'code' => 'H-GP.01', 'area' => 'ALMINI'],
            ['name' => 'LAS LISTRIK', 'code' => 'H-LL.01', 'area' => 'ALMINI'],
            ['name' => 'BUBUT CNC 1', 'code' => 'H-BC.01', 'area' => 'ALMINI'],
            ['name' => 'BUBUT CNC 2', 'code' => 'H-BC.02', 'area' => 'ALMINI'],
            ['name' => 'BUBUT MANUAL 1', 'code' => 'H-BM.01', 'area' => 'ALMINI'],
            ['name' => 'LAS ARGON', 'code' => 'H-LA.01', 'area' => 'ALMINI'],
            ['name' => 'BIOMAS 1', 'code' => 'H-BIO.01', 'area' => 'ALMINI'],
            ['name' => 'BIOMAS 2', 'code' => 'H-BS.02', 'area' => 'ALMINI'],
            ['name' => 'SCRAP', 'code' => 'H-SC.01', 'area' => 'ALMINI'],
            ['name' => 'KOMPRESOR PISTON', 'code' => 'H-KP.01', 'area' => 'ALMINI'],
            ['name' => 'POMPA HIDROLIK 1', 'code' => 'H-PH.01', 'area' => 'ALMINI'],
            ['name' => 'POMPA HIDROLIK 2', 'code' => 'H-PH.02', 'area' => 'ALMINI'],
            // Area: KIMIA FITTING
            ['name' => 'SAND BLASTING', 'code' => 'I-SB.01', 'area' => 'KIMIA FITTING'],
            ['name' => 'GERINDA HALUS', 'code' => 'I-GH.01', 'area' => 'KIMIA FITTING'],
            ['name' => 'LAS ARGON', 'code' => 'I-LA.01', 'area' => 'KIMIA FITTING'],
            ['name' => 'KOMPRESOR PISTON', 'code' => 'I-KP.01', 'area' => 'KIMIA FITTING'],
            // Area: BUBUT OD
            ['name' => 'BUBUT OTOMATIS 17', 'code' => 'J-BO.17', 'area' => 'BUBUT OD'],
            ['name' => 'BUBUT OTOMATIS 18', 'code' => 'J-BO.18', 'area' => 'BUBUT OD'],
            ['name' => 'BUBUT OTOMATIS 30', 'code' => 'J-BO.30', 'area' => 'BUBUT OD'],
            ['name' => 'BUBUT OTOMATIS 29', 'code' => 'J-BO.29', 'area' => 'BUBUT OD'],
            ['name' => 'BUBUT OTOMATIS 40', 'code' => 'J-BO.40', 'area' => 'BUBUT OD'],
            ['name' => 'BUBUT OTOMATIS 20', 'code' => 'J-BO.20', 'area' => 'BUBUT OD'],
            ['name' => 'BUBUT OTOMATIS 21', 'code' => 'J-BO.21', 'area' => 'BUBUT OD'],
            ['name' => 'BUBUT OTOMATIS 11', 'code' => 'J-BO.11', 'area' => 'BUBUT OD'],
            ['name' => 'BUBUT OTOMATIS 38', 'code' => 'J-BO.38', 'area' => 'BUBUT OD'],
            ['name' => 'BUBUT OTOMATIS 39', 'code' => 'J-BO.39', 'area' => 'BUBUT OD'],
            ['name' => 'BUBUT MANUAL 02', 'code' => 'J-BM.02', 'area' => 'BUBUT OD'],
            ['name' => 'BUBUT MANUAL 03', 'code' => 'J-BM.03', 'area' => 'BUBUT OD'],
            ['name' => 'BUBUT MANUAL 04', 'code' => 'J-BM.04', 'area' => 'BUBUT OD'],
            ['name' => 'BUBUT MANUAL 86', 'code' => 'J-BM.86', 'area' => 'BUBUT OD'],
            ['name' => 'BUBUT MANUAL 87', 'code' => 'J-BM.87', 'area' => 'BUBUT OD'],
            ['name' => 'BUBUT MANUAL 14', 'code' => 'J-BM.14', 'area' => 'BUBUT OD'],
            ['name' => 'BUBUT MANUAL 57', 'code' => 'J-BM.57', 'area' => 'BUBUT OD'],
            ['name' => 'BUBUT MANUAL 58', 'code' => 'J-BM.58', 'area' => 'BUBUT OD'],
            ['name' => 'BUBUT MANUAL 59', 'code' => 'J-BM.59', 'area' => 'BUBUT OD'],
            ['name' => 'BUBUT MANUAL 60', 'code' => 'J-BM.60', 'area' => 'BUBUT OD'],
            ['name' => 'GERINDA DUDUK', 'code' => 'J-GD.01', 'area' => 'BUBUT OD'],
            ['name' => 'LAS LISTRIK 01', 'code' => 'J-LL.01', 'area' => 'BUBUT OD'],
            ['name' => 'LAS LISTRIK 02', 'code' => 'J-LL.02', 'area' => 'BUBUT OD'],
            ['name' => 'LAS LISTRIK 03', 'code' => 'J-LL.03', 'area' => 'BUBUT OD'],
            // Area: MARKING FLANGE
            ['name' => 'STAMPEL ANGIN 01', 'code' => 'K-SA.01', 'area' => 'MARKING FLANGE'],
            ['name' => 'STAMPEL ANGIN 02', 'code' => 'K-SA.02', 'area' => 'MARKING FLANGE'],
            ['name' => 'STAMPEL ANGIN 03', 'code' => 'K-SA.03', 'area' => 'MARKING FLANGE'],
            ['name' => 'KOMPRESOR PISTON', 'code' => 'K-KP.01', 'area' => 'MARKING FLANGE'],
            // Area: BUBUT SERVICE
            ['name' => 'BUBUT MANUAL 01', 'code' => 'L-BM.01', 'area' => 'BUBUT SERVICE'],
            ['name' => 'BUBUT MANUAL 05', 'code' => 'L-BM.05', 'area' => 'BUBUT SERVICE'],
            ['name' => 'BUBUT MANUAL 06', 'code' => 'L-BM.06', 'area' => 'BUBUT SERVICE'],
            ['name' => 'BUBUT MANUAL 25', 'code' => 'L-BM.25', 'area' => 'BUBUT SERVICE'],
            ['name' => 'BUBUT MANUAL 27', 'code' => 'L-BM.27', 'area' => 'BUBUT SERVICE'],
            ['name' => 'BUBUT MANUAL 31', 'code' => 'L-BM.31', 'area' => 'BUBUT SERVICE'],
            ['name' => 'BUBUT MANUAL 71', 'code' => 'L-BM.71', 'area' => 'BUBUT SERVICE'],
            ['name' => 'BOR CNC 01', 'code' => 'L-BN.01', 'area' => 'BUBUT SERVICE'],
            ['name' => 'BOR CNC 02', 'code' => 'L-BN.02', 'area' => 'BUBUT SERVICE'],
            ['name' => 'BOR CNC 03', 'code' => 'L-BN.03', 'area' => 'BUBUT SERVICE'],
            ['name' => 'BOR CNC 04', 'code' => 'L-BN.04', 'area' => 'BUBUT SERVICE'],
            ['name' => 'BOR MANUAL 03', 'code' => 'L-BL.03', 'area' => 'BUBUT SERVICE'],
            ['name' => 'BOR MANUAL 04', 'code' => 'L-BL.05', 'area' => 'BUBUT SERVICE'],
            ['name' => 'LAS LISTRIK 01', 'code' => 'L-LL.01', 'area' => 'BUBUT SERVICE'],
            ['name' => 'LAS LISTRIK 02', 'code' => 'L-LL.02', 'area' => 'BUBUT SERVICE'],
            ['name' => 'LAS LISTRIK 03', 'code' => 'L-LL.03', 'area' => 'BUBUT SERVICE'],
            // Area: SOLDER
            ['name' => 'BOR SERONG 01', 'code' => 'M-BS.01', 'area' => 'SOLDER'],
            ['name' => 'BOR SERONG 02', 'code' => 'M-BS.02', 'area' => 'SOLDER'],
            ['name' => 'TES FLANGE HIDROLIS', 'code' => 'M-FH.01', 'area' => 'SOLDER'],
            ['name' => 'KOMPRESOR PISTON', 'code' => 'M-KP.01', 'area' => 'SOLDER'],
            // Area: FLANGE BESI
            ['name' => 'BOR SERONG 01', 'code' => 'O-BS.01', 'area' => 'FLANGE BESI'],
            ['name' => 'BOR SERONG 02', 'code' => 'O-BS.02', 'area' => 'FLANGE BESI'],
            ['name' => 'BOR SERONG 03', 'code' => 'O-BS.03', 'area' => 'FLANGE BESI'],
            ['name' => 'STEMPEL ANGIN 03', 'code' => 'O-SA.03', 'area' => 'FLANGE BESI'],
            // Area: MAINTENANCE
            ['name' => 'SCRAP', 'code' => 'P-SC.01', 'area' => 'MAINTENANCE'],
            ['name' => 'BOR MILLING', 'code' => 'P-ML.01', 'area' => 'MAINTENANCE'],
            ['name' => 'BUBUT MANUAL 01', 'code' => 'P-BM.01', 'area' => 'MAINTENANCE'],
            ['name' => 'BUBUT MANUAL 02', 'code' => 'P-BM.02', 'area' => 'MAINTENANCE'],
            ['name' => 'FORKLIFT 01', 'code' => 'P-FK.01', 'area' => 'MAINTENANCE'],
            ['name' => 'FORKLIFT 02', 'code' => 'P-FK.02', 'area' => 'MAINTENANCE'],
            ['name' => 'FORKLIFT 03', 'code' => 'P-FK.03', 'area' => 'MAINTENANCE'],
            ['name' => 'FORKLIFT 04', 'code' => 'P-FK.04', 'area' => 'MAINTENANCE'],
            ['name' => 'LAS LISTRIK 01', 'code' => 'P-LL.01', 'area' => 'MAINTENANCE'],
            ['name' => 'LAS LISTRIK 02', 'code' => 'P-LL.02', 'area' => 'MAINTENANCE'],
            // Area: GUDANG JADI FITTING
            ['name' => 'STAMPEL LASER 01', 'code' => 'S-SL.01', 'area' => 'GUDANG JADI FITTING'],
            ['name' => 'STAMPEL LASER 02', 'code' => 'S-SL.02', 'area' => 'GUDANG JADI FITTING'],
            ['name' => 'STAMPEL LASER 03', 'code' => 'S-SL.03', 'area' => 'GUDANG JADI FITTING'],
            // Area: GENSET
            ['name' => 'GENSET 01', 'code' => 'T-GEN.01', 'area' => 'GENSET'],
            ['name' => 'GENSET 02', 'code' => 'T-GEN.02', 'area' => 'GENSET'],
            // Area: PATTERN
            ['name' => 'BUBUT MANUAL', 'code' => 'W-BM.01', 'area' => 'PATTERN'],
            ['name' => 'BOR DUDUK', 'code' => 'W-BD.01', 'area' => 'PATTERN'],
            // Area: UMUM
            ['name' => 'AYAK SAND FITTING 01', 'code' => 'Y-AS.01', 'area' => 'UMUM'],
            ['name' => 'AYAK SAND FITTING 02', 'code' => 'Y-AS.02', 'area' => 'UMUM'],
            ['name' => 'BAM 01', 'code' => 'Y-BAM.01', 'area' => 'UMUM'],
            ['name' => 'BAM 02', 'code' => 'Y-BAM.02', 'area' => 'UMUM'],
            ['name' => 'BALL MILL 01', 'code' => 'Y-BA.01', 'area' => 'UMUM'],
        ];

        // 1. Get creator User (Admin)
        $adminUser = User::where('email', 'admin@mrm.local')->first() ?? User::first();
        $createdBy = $adminUser ? $adminUser->id : null;

        // 2. Pre-cache Production Areas, Machine Categories, and Departments to prevent N+1 database queries
        $areaCache = MasterProductionArea::all()->keyBy('name');
        $categoryCache = MasterMachineCategory::all()->keyBy('name');
        $departmentCache = MasterDepartment::all()->keyBy('name');

        foreach ($machines as $data) {
            $code = $data['code'];
            $name = $data['name'];
            $areaName = $data['area'];

            // Production Area resolution & caching
            if (!$areaCache->has($areaName)) {
                $areaObj = MasterProductionArea::firstOrCreate(
                    ['name' => $areaName],
                    ['code' => Str::slug($areaName), 'is_active' => true, 'sort_order' => $areaCache->count() + 1]
                );
                $areaCache->put($areaName, $areaObj);
            } else {
                $areaObj = $areaCache->get($areaName);
            }

            // Automatic Category Classification
            $categoryName = self::classifyCategory($name);
            if (!$categoryCache->has($categoryName)) {
                $catObj = MasterMachineCategory::firstOrCreate(
                    ['name' => $categoryName],
                    ['code' => Str::slug($categoryName), 'is_active' => true, 'sort_order' => $categoryCache->count() + 1]
                );
                $categoryCache->put($categoryName, $catObj);
            }

            // Automatic Department Derivation
            $deptName = self::deriveDepartment($areaName, $categoryName);
            if (!$departmentCache->has($deptName)) {
                $deptObj = MasterDepartment::firstOrCreate(
                    ['name' => $deptName],
                    ['code' => Str::slug($deptName), 'is_active' => true, 'sort_order' => $departmentCache->count() + 1]
                );
                $departmentCache->put($deptName, $deptObj);
            }

            // Update or create machine (idempotent duplicate safety)
            Machine::updateOrCreate(
                ['code' => $code],
                [
                    'name' => $name,
                    'department' => $deptName,
                    'production_area' => $areaName,
                    'production_area_id' => $areaObj->id,
                    'category' => $categoryName,
                    'criticality' => 'medium',
                    'operational_status' => 'running',
                    'is_active' => true,
                    'lifecycle_status' => 'ACTIVE',
                    'created_by' => $createdBy,
                ]
            );
        }
    }

    /**
     * Automatic category classification logic.
     */
    public static function classifyCategory(string $machineName): string
    {
        $upper = strtoupper($machineName);

        if (str_contains($upper, 'PRESS') && !str_contains($upper, 'COMPRESSOR') && !str_contains($upper, 'KOMPRESOR')) {
            return 'Press';
        }
        if (str_contains($upper, 'BUBUT')) {
            return 'Lathe';
        }
        if (str_contains($upper, 'CUTTING PLASMA')) {
            return 'Plasma Cutting';
        }
        if (str_contains($upper, 'BOR')) {
            return 'Drilling';
        }
        if (str_contains($upper, 'LAS ARGON') || str_contains($upper, 'LAS LISTRIK') || str_starts_with($upper, 'LAS ')) {
            return 'Welding';
        }
        if (str_contains($upper, 'GERINDA')) {
            return 'Grinding';
        }
        if (str_contains($upper, 'KOMPRESOR') || str_contains($upper, 'COMPRESSOR')) {
            return 'Compressor';
        }
        if (str_contains($upper, 'GENSET')) {
            return 'Generator';
        }
        if (str_contains($upper, 'FORKLIFT')) {
            return 'Forklift';
        }
        if (str_contains($upper, 'HOIST')) {
            return 'Hoist';
        }
        if (str_contains($upper, 'POMPA') || str_contains($upper, 'PUMP')) {
            return 'Pump';
        }
        if (str_contains($upper, 'MOULDING')) {
            return 'Moulding';
        }
        if (str_contains($upper, 'MIXER') || str_contains($upper, 'MOLEN')) {
            return 'Mixer';
        }
        if (str_contains($upper, 'BLOWER')) {
            return 'Blower';
        }
        if (str_contains($upper, 'FOUNDRY')) {
            return 'Furnace';
        }
        if (str_contains($upper, 'OVEN')) {
            return 'Oven';
        }
        if (str_contains($upper, 'BIOMAS')) {
            return 'Furnace';
        }
        if (str_contains($upper, 'SAND BLASTING') || str_contains($upper, 'HAND BLASTING')) {
            return 'Sand Blasting';
        }
        if (str_contains($upper, 'HEAD TREATMENT') || str_contains($upper, 'HEAT TREATMENT')) {
            return 'Heat Treatment';
        }
        if (str_contains($upper, 'TEST')) {
            return 'Testing Equipment';
        }
        if (str_contains($upper, 'WRAPPING') || str_contains($upper, 'STRAPING')) {
            return 'Packaging';
        }
        if (str_contains($upper, 'LOADER')) {
            return 'Loader';
        }
        if (str_contains($upper, 'SCRAP')) {
            return 'Utility';
        }
        if (str_contains($upper, 'AYAK')) {
            return 'Screening';
        }
        if (str_contains($upper, 'TRANSFER')) {
            return 'Transfer';
        }
        if (str_contains($upper, 'PENAMPUNG')) {
            return 'Tank';
        }
        if (str_contains($upper, 'PEMANAS')) {
            return 'Heater';
        }
        if (str_contains($upper, 'CETAK LILIN')) {
            return 'Wax Injection';
        }
        if (str_contains($upper, 'TANJEK LILIN')) {
            return 'Wax Processing';
        }

        return 'General Machine';
    }

    /**
     * Helper to derive department based on area or category.
     */
    public static function deriveDepartment(string $area, string $category): string
    {
        if (str_contains($area, 'BUBUT') || str_contains($area, 'NETTO') || $category === 'Lathe') {
            return 'Machining';
        }
        if (str_contains($area, 'COR') || $category === 'Furnace') {
            return 'Foundry';
        }
        if (str_contains($area, 'QC')) {
            return 'QC';
        }
        if (str_contains($area, 'GUDANG') || str_contains($area, 'BAHAN BAKU')) {
            return 'Warehouse';
        }

        return 'Production';
    }
}
