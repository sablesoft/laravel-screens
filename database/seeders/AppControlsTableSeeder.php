<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AppControlsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('app.controls')->delete();

        \DB::table('app.controls')->insert(array (
            0 =>
            array (
                'id' => 2,
                'screen_id' => 4,
                'scenario_id' => 5,
                'type' => 'input',
                'title' => 'Say',
                'tooltip' => 'Try to say something...',
                'description' => '',
                'before' => NULL,
                'after' => NULL,
                'created_at' => '2025-03-28 05:54:18',
                'updated_at' => '2025-03-29 05:23:11',
            ),
            1 =>
            array (
                'id' => 1,
                'screen_id' => 3,
                'scenario_id' => NULL,
                'type' => 'input',
                'title' => 'Say',
                'tooltip' => 'Try to say something...',
                'description' => 'Just add member ask to screen memories',
                'before' => '[{"memory.create":{"type":"screen.code","data":{"author_id":"member.id","content":"ask"}}},{"chat.refresh":["screen.code"]}]',
                'after' => NULL,
                'created_at' => '2025-03-28 05:51:51',
                'updated_at' => '2025-04-01 04:45:24',
            ),
        ));


    }
}
