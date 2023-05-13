<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dt = \Carbon\Carbon::now()->toDateTimeString();
        DB::table('states')->insert([
            ['state_name'=>'ANDAMAN AND NICOBAR ISLANDS', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'ANDHRA PRADESH', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'ARUNACHAL PRADESH', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'ASSAM', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'BIHAR', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'CHATTISGARH', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'CHANDIGARH', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'DAMAN AND DIU', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'DELHI', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'DADRA AND NAGAR HAVELI', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'GOA', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'GUJARAT', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'HIMACHAL PRADESH', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'HARYANA', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'JAMMU AND KASHMIR', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'JHARKHAND', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'KERALA', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'KARNATAKA', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'LAKSHADWEEP', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'MEGHALAYA', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'MAHARASHTRA', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'MANIPUR', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'MADHYA PRADESH', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'MIZORAM', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'NAGALAND', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'ORISSA', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'PUNJAB', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'PONDICHERRY', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'RAJASTHAN', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'SIKKIM', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'TAMIL NADU', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'TRIPURA', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'UTTARAKHAND', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'UTTAR PRADESH', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'WEST BENGAL', 'created_at'=> $dt, 'updated_at'=> $dt],
            ['state_name'=>'TELANGANA', 'created_at'=> $dt, 'updated_at'=> $dt],
        ]);
    }
}
