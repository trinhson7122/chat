<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Config;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // User::create([
        //     'name' => 'Trinh xuan son',
        //     'email' => 'admin@admin.com',
        //     'password' => Hash::make(123),
        // ]);
        // User::create([
        //     'name' => 'Le van an',
        //     'email' => 'an@admin.com',
        //     'password' => Hash::make(123),
        // ]);
        $listIcon = '😀,😃,😄,😁,😆,😅,😂,🤣,🥲,😊,😇,🙂,'
                    .'🙃,😉,😌,😍,🥰,😘,😗,😙,😚,😋,😛,😝,'
                    .'😜,🤪,🤨,🧐,🤓,😎,🥸,🤩,🥳,😏,😒,😞,'
                    .'😔,😟,😕,🙁,☹️,😣,😖,😫,😩,🥺,😢,😭,'
                    .'😮‍💨,😤,😠,😡,🤬,🤯,😳,🥵,🥶,😱,😨,😰,'
                    .'😥,😓,🤗,🤔,🤭,🤫,🤥,😶,😶‍🌫️,😐,😑,😬,'
                    .'🙄,😯,😦,😧,😮,😲,🥱,😴,🤤,😪,😵,😵‍💫,'
                    .'🤐,🥴,🤢,🤮,🤧,😷,🤒,🤕,🤑,🤠,😈,👿,'
                    .'👹,👺,🤡,💩,👻,💀,☠️,👽,👾,🤖,🎃,😺,'
                    .'😸,😹,😻,😼,😽,🙀,😿,😾';
        Config::create([
            'key' => 'list_icon',
            'value' => $listIcon,
        ]);
    }
}
