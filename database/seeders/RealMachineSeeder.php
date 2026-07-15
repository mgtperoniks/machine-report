<?php

namespace Database\Seeders;

use App\Models\Machine;
use Illuminate\Database\Seeder;

class RealMachineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $machines = [
            ['code' => 'N-BC.80', 'name' => 'BUBUT CNC 80', 'production_area' => 'BUBUT FITTING'],
            ['code' => 'N-BC.78', 'name' => 'BUBUT CNC 78', 'production_area' => 'BUBUT FITTING'],
            ['code' => 'N-BC.79', 'name' => 'BUBUT CNC 79', 'production_area' => 'BUBUT FITTING'],
            ['code' => 'N-BC.76', 'name' => 'BUBUT CNC 76', 'production_area' => 'BUBUT FITTING'],
            ['code' => 'N-BC.77', 'name' => 'BUBUT CNC 77', 'production_area' => 'BUBUT FITTING'],
            ['code' => 'N-BC.63', 'name' => 'BUBUT CNC 63', 'production_area' => 'BUBUT FITTING'],
            ['code' => 'N-BC.43', 'name' => 'BUBUT CNC 43', 'production_area' => 'BUBUT FITTING'],
            ['code' => 'N-BC.61', 'name' => 'BUBUT CNC 61', 'production_area' => 'BUBUT FITTING'],
            ['code' => 'N-BC.62', 'name' => 'BUBUT CNC 62', 'production_area' => 'BUBUT FITTING'],
            ['code' => 'N-BC.83', 'name' => 'BUBUT CNC 83', 'production_area' => 'BUBUT FITTING'],
            ['code' => 'N-BC.64', 'name' => 'BUBUT CNC 64', 'production_area' => 'BUBUT FITTING'],
            ['code' => 'O-BC.88', 'name' => 'BUBUT CNC 88', 'production_area' => 'BUBUT FLANGE BESI'],
            ['code' => 'O-BC.89', 'name' => 'BUBUT CNC 89', 'production_area' => 'BUBUT FLANGE BESI'],
            ['code' => 'O-BC.90', 'name' => 'BUBUT CNC 90', 'production_area' => 'BUBUT FLANGE BESI'],
            ['code' => 'O-BC.91', 'name' => 'BUBUT CNC 91', 'production_area' => 'BUBUT FLANGE BESI'],
            ['code' => 'O-BC.65', 'name' => 'BUBUT CNC 65', 'production_area' => 'BUBUT FLANGE BESI'],
            ['code' => 'O-BC.56', 'name' => 'BUBUT CNC 56', 'production_area' => 'BUBUT FLANGE BESI'],
            ['code' => 'O-BC.50', 'name' => 'BUBUT CNC 50', 'production_area' => 'BUBUT FLANGE BESI'],
            ['code' => 'O-BC.47', 'name' => 'BUBUT CNC 47', 'production_area' => 'BUBUT FLANGE BESI'],
            ['code' => 'O-BC.34', 'name' => 'BUBUT CNC 34', 'production_area' => 'BUBUT FLANGE BESI'],
            ['code' => 'R-BC.85', 'name' => 'BUBUT CNC 85', 'production_area' => 'BUBUT CNC FLANGE'],
            ['code' => 'R-BC.84', 'name' => 'BUBUT CNC 84', 'production_area' => 'BUBUT CNC FLANGE'],
            ['code' => 'R-BC.82', 'name' => 'BUBUT CNC 82', 'production_area' => 'BUBUT CNC FLANGE'],
            ['code' => 'R-BC.81', 'name' => 'BUBUT CNC 81', 'production_area' => 'BUBUT CNC FLANGE'],
            ['code' => 'R-BC.74', 'name' => 'BUBUT CNC 74', 'production_area' => 'BUBUT CNC FLANGE'],
            ['code' => 'R-BC.73', 'name' => 'BUBUT CNC 73', 'production_area' => 'BUBUT CNC FLANGE'],
            ['code' => 'R-BC.70', 'name' => 'BUBUT CNC 70', 'production_area' => 'BUBUT CNC FLANGE'],
            ['code' => 'R-BC.69', 'name' => 'BUBUT CNC 69', 'production_area' => 'BUBUT CNC FLANGE'],
            ['code' => 'R-BC.68', 'name' => 'BUBUT CNC 68', 'production_area' => 'BUBUT CNC FLANGE'],
            ['code' => 'R-BC.67', 'name' => 'BUBUT CNC 67', 'production_area' => 'BUBUT CNC FLANGE'],
            ['code' => 'R-BC.66', 'name' => 'BUBUT CNC 66', 'production_area' => 'BUBUT CNC FLANGE'],
            ['code' => 'R-BC.55', 'name' => 'BUBUT CNC 55', 'production_area' => 'BUBUT CNC FLANGE'],
            ['code' => 'R-BC.53', 'name' => 'BUBUT CNC 53', 'production_area' => 'BUBUT CNC FLANGE'],
            ['code' => 'R-BC.41', 'name' => 'BUBUT CNC 41', 'production_area' => 'BUBUT CNC FLANGE'],
        ];

        foreach ($machines as $data) {
            $machine = Machine::firstOrNew(['code' => $data['code']]);

            if (!$machine->exists) {
                // progressive registration: only populate what is known
                $machine->fill([
                    'name' => $data['name'],
                    'department' => 'Machining',
                    'production_area' => $data['production_area'],
                    'category' => 'Lathe',
                    'criticality' => 'medium',
                    'operational_status' => 'running',
                    'is_active' => true,
                    'lifecycle_status' => 'ACTIVE',
                ])->save();
            } else {
                // update identity fields only if changed
                $updates = [];
                $identityFields = [
                    'name' => $data['name'],
                    'department' => 'Machining',
                    'production_area' => $data['production_area'],
                    'category' => 'Lathe',
                ];

                foreach ($identityFields as $field => $value) {
                    if ($machine->$field !== $value) {
                        $updates[$field] = $value;
                    }
                }

                if (!empty($updates)) {
                    $machine->update($updates);
                }
            }
        }
    }
}
