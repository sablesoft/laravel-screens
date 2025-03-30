<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AppScreensTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('app.screens')->delete();
        
        \DB::table('app.screens')->insert(array (
            0 => 
            array (
                'id' => 3,
                'user_id' => 1,
                'application_id' => 2,
                'image_id' => 34,
                'code' => 'journey',
                'title' => 'Journey',
                'description' => NULL,
                'is_default' => true,
                'query' => '":type" == screen.code',
                'template' => NULL,
                'before' => NULL,
                'after' => NULL,
                'created_at' => '2025-03-28 04:15:58',
                'updated_at' => '2025-03-28 05:09:39',
            ),
            1 => 
            array (
                'id' => 4,
                'user_id' => 1,
                'application_id' => 2,
                'image_id' => 33,
                'code' => 'coast',
                'title' => 'Coast',
                'description' => NULL,
                'is_default' => false,
                'query' => '":type" == screen.code',
                'template' => NULL,
                'before' => NULL,
                'after' => NULL,
                'created_at' => '2025-03-28 05:53:06',
                'updated_at' => '2025-03-28 05:53:06',
            ),
            2 => 
            array (
                'id' => 5,
                'user_id' => 1,
                'application_id' => 2,
                'image_id' => 39,
                'code' => 'ice-peak',
                'title' => 'Ice Peak',
                'description' => NULL,
                'is_default' => false,
                'query' => '":type" == screen.code',
                'template' => NULL,
                'before' => NULL,
                'after' => NULL,
                'created_at' => '2025-03-28 20:30:12',
                'updated_at' => '2025-03-28 20:30:12',
            ),
        ));
        
        
    }
}