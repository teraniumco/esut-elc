<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PhaseTwoSeeder extends Seeder
{
    public function run(): void
    {
        // ── Default Admin ─────────────────────────────────────────────────────
        // Change these credentials before deploying to production!
        $admin = User::firstOrCreate(
            ['email' => 'admin.elc@esut.edu.ng'],
            [
                'name'               => 'Clinic Administrator',
                'password'           => Hash::make('ELC.Admin@2026'),
                'role'               => 'admin',
                'department'         => 'Faculty of Law Administration',
                'is_active'          => true,
                'invite_accepted_at' => now(),
                'email_verified_at'  => now(),
            ]
        );

        $this->command->info("✅ Admin account ready: admin.elc@esut.edu.ng");
        $this->command->warn("⚠  Change the default password immediately after first login!");

        // ── Clinic Supervisor ─────────────────────────────────────────────────
        $supervisor = User::firstOrCreate(
            ['email' => 'supervisor.elc@esut.edu.ng'],
            [
                'name'               => 'Clinic Supervisor',
                'password'           => Hash::make('ELC.Supervisor@2026'),
                'role'               => 'supervisor',
                'department'         => 'Faculty of Law',
                'invited_by'         => $admin->id,
                'is_active'          => true,
                'invite_accepted_at' => now(),
                'email_verified_at'  => now(),
            ]
        );

        // ── Clinic Advisor ────────────────────────────────────────────────────
        $advisor = User::firstOrCreate(
            ['email' => 'advisor.elc@esut.edu.ng'],
            [
                'name'               => 'Clinic Student Advisor',
                'password'           => Hash::make('ELC.Advisor@2026'),
                'role'               => 'advisor',
                'department'         => 'Faculty of Law — 500L',
                'invited_by'         => $admin->id,
                'is_active'          => true,
                'invite_accepted_at' => now(),
                'email_verified_at'  => now(),
            ]
        );

        $this->command->info("✅ Default users created. Portal: /portal/login");
        $this->command->line("   admin.elc@esut.edu.ng  (Admin)");
        $this->command->line("   supervisor.elc@esut.edu.ng  (Supervisor)");
        $this->command->line("   advisor.elc@esut.edu.ng  (Advisor)");
    }
}
