<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Language;
use Illuminate\Support\Str;
class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        

        $languages = [
            'English',
            'Spanish',
            'Chinese',
            'French',
            'German',
            'Vietnamese',
            'Tagalog',
            'Korean',
            'Russian',
            'Arabic',
            'Portuguese',
            'Italian',
            'Hindi',
            'Japanese',
            'Polish',
            'Ukrainian',
            'Persian',
            'Haitian Creole',
            'Greek',
            'Hebrew',
            'Urdu',
        ];

        foreach ($languages as $lang) {
            Language::updateOrCreate(
                ['slug' => Str::slug($lang)],
                [
                    'name' => $lang,
                    'active' => true,
                ]
            );
        }
       
    }
}
