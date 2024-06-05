<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomDropdown;

class CustomDropdownSeeder extends Seeder
{
    public function run()
    {
        $dropdowns = [
            'Bilirubin', 'Blood', 'Leucocytes', 'Glucose', 'Nitrite', 'Ketones', 'Urobilinogen', 'Proteins'
        ];

        foreach ($dropdowns as $dropdown) {
            CustomDropdown::firstOrCreate(['dropdown_name' => $dropdown, 'value' => 'default']);
        }
    }
}
