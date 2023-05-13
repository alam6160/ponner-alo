<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dt = \Carbon\Carbon::now()->toDateTimeString();
        \Illuminate\Support\Facades\DB::table('site_settings')->insert([
            ['key_name'=>'address', 'key_value'=> NULL, 'created_at'=>$dt, 'updated_at'=>$dt ],
            ['key_name'=>'company_name', 'key_value'=> NULL, 'created_at'=>$dt, 'updated_at'=>$dt ],
            ['key_name'=>'short_about', 'key_value'=> NULL, 'created_at'=>$dt, 'updated_at'=>$dt ],
            ['key_name'=>'email_id', 'key_value'=> NULL, 'created_at'=>$dt, 'updated_at'=>$dt ],
            ['key_name'=>'email_id_2', 'key_value'=> NULL, 'created_at'=>$dt, 'updated_at'=>$dt ],
            ['key_name'=>'contact_no', 'key_value'=> NULL, 'created_at'=>$dt, 'updated_at'=>$dt ],
            ['key_name'=>'contact_no_2', 'key_value'=> NULL, 'created_at'=>$dt, 'updated_at'=>$dt ],
            ['key_name'=>'membership_price', 'key_value'=> NULL, 'created_at'=>$dt, 'updated_at'=>$dt ],
            ['key_name'=>'commission', 'key_value'=> NULL, 'created_at'=>$dt, 'updated_at'=>$dt ],
            ['key_name'=>'currency', 'key_value'=> NULL, 'created_at'=>$dt, 'updated_at'=>$dt ],
        ]);
    }
}
