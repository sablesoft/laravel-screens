<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AppMasksTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('app.masks')->delete();
        
        
        
    }
}