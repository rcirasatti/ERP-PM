<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proyek;
use App\Models\ProyekBudget;

class ProyekBudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create budgets for existing projects that don't have one
        $proyeks = Proyek::with('penawaran')->get();

        foreach ($proyeks as $proyek) {
            // Check if budget already exists
            $existingBudget = ProyekBudget::where('proyek_id', $proyek->id)->first();
            
            if (!$existingBudget && $proyek->penawaran) {
                ProyekBudget::create([
                    'proyek_id' => $proyek->id,
                    'jumlah_rencana' => $proyek->penawaran->grand_total_with_ppn ?? ($proyek->penawaran->grand_total * 1.11),
                    'jumlah_realisasi' => 0,
                ]);
                
                $this->command->info("Budget created for project: {$proyek->nama}");
            }
        }

        $this->command->info('Budget seeding completed!');
    }
}
