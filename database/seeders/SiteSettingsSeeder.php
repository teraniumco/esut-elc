<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        if (SiteSetting::count() > 0) {
            return; // already seeded — don't overwrite admin edits
        }

        SiteSetting::setMany([
            'about_mission' => 'To provide accessible, free, and confidential legal guidance to members of the ESUT community and the broader public — empowering individuals to understand and exercise their legal rights, while giving law students practical clinical experience.',
            'about_vision'  => 'A society where every person — regardless of economic standing — has access to basic legal information and the ability to assert their rights. We envision the clinic as the leading student-run legal aid initiative in South-East Nigeria.',

            'contact_address' => "Faculty of Law, ESUT,\nAgbani Road, Enugu State",
            'contact_email'   => 'elc@esut.edu.ng',
            'contact_hours'   => "Monday – Friday\n9:00 AM – 5:00 PM",
        ]);
    }
}
