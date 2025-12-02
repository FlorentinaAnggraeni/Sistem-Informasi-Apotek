<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Pelanggan;

class CreateMissingPelangganRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pelanggan:create-missing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create missing pelanggan records for users with pelanggan role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for users with pelanggan role without pelanggan records...');

        // Get all users with pelanggan role
        $pelangganUsers = User::where('role', 'pelanggan')->get();

        $created = 0;
        $skipped = 0;

        foreach ($pelangganUsers as $user) {
            // Check if pelanggan record exists
            if (!$user->pelanggan) {
                // Create pelanggan record
                Pelanggan::create([
                    'id_user' => $user->id,
                    'nama_pelanggan' => $user->name,
                    'alamat_pelanggan' => $user->alamat ?? 'Alamat tidak tersedia',
                    'no_telp_pelanggan' => $user->no_hp ?? '000000000',
                    'email_pelanggan' => $user->email,
                ]);

                $this->line("✓ Created pelanggan record for user: {$user->name} (ID: {$user->id})");
                $created++;
            } else {
                $skipped++;
            }
        }

        $this->newLine();
        $this->info("Summary:");
        $this->info("- Total pelanggan users: " . $pelangganUsers->count());
        $this->info("- Created records: {$created}");
        $this->info("- Skipped (already exists): {$skipped}");
        
        if ($created > 0) {
            $this->newLine();
            $this->info('✓ All missing pelanggan records have been created successfully!');
        } else {
            $this->newLine();
            $this->comment('No missing pelanggan records found.');
        }

        return Command::SUCCESS;
    }
}
