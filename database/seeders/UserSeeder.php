<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->code = \App\Helper\Clib::unique_code(new User(), 'S');
        $user->fname = 'Super';
        $user->lname = 'Admin';
        $user->email = 'superadmin@gmail.com';
        $user->contact = '1234567890';
        $user->email_verified_at = \Carbon\Carbon::now()->toDateTimeString();
        $user->approve_at = \Carbon\Carbon::now()->toDateTimeString();
        $user->password = Hash::make('123456');
        $user->status = '2';
        $user->user_type = '1';

        $user->save();

        //STATE HEAD
        /*
        $stateHead = new User();
        $stateHead->code = \App\Helper\Clib::unique_code(new User(), 'SH');
        $stateHead->title = 'Mr.';
        $stateHead->fname = 'Statehead';
        $stateHead->lname = 'One';
        $stateHead->email = 'statehead@gmail.com';
        $stateHead->contact = '1234567890';
        $stateHead->email_verified_at = \Carbon\Carbon::now()->toDateTimeString();
        $stateHead->approve_at = \Carbon\Carbon::now()->toDateTimeString();
        $stateHead->password = Hash::make('123456');
        $stateHead->status = '2';
        $stateHead->user_type = '3';
        $stateHead->save();
        $stateheadProfile = new \App\Models\StateheadProfile();
        $stateheadProfile->licence = 'licence123';
        $stateheadProfile->address = 'stateHead address';
        $stateheadProfile->pin_code = '700103';
        $stateheadProfile->state_id = '35';
        $stateheadProfile->servicable_state_id = '35';
        $stateHead->statehead_prfile()->save($stateheadProfile);
        */

        //AGENT
        $agent1 = new User();
        $agent1->code = \App\Helper\Clib::unique_code(new User(), 'A');
        $agent1->fname = 'Agent';
        $agent1->lname = 'One';
        $agent1->email = 'agent@gmail.com';
        $agent1->contact = '1234567890';
        $agent1->email_verified_at = \Carbon\Carbon::now()->toDateTimeString();
        $agent1->approve_at = \Carbon\Carbon::now()->toDateTimeString();
        $agent1->password = Hash::make('123456');
        $agent1->status = '2';
        $agent1->user_type = '4';
        $agent1->vendor_type = '1';
        $agent1->wallet = 7000;
        $agent1->save();

        $agent1Profile = new \App\Models\AgentProfile();
        $agent1Profile->organization_name = 'Organization';
        $agent1Profile->licence = 'licence123';
        $agent1Profile->address = 'agent1 address';
        $agent1Profile->pin_code = '700103';
        $agent1Profile->state_id = '35';
        $agent1->agent_prfile()->save($agent1Profile);
        $agentBank = new \App\Models\AgentBank();
        $agent1->agent_bank()->save($agentBank);

        //RETAILER
        /*
        $reailer = new User();
        $reailer->code = \App\Helper\Clib::unique_code(new User(), 'R');
        $reailer->fname = 'Retailer';
        $reailer->lname = 'One';
        $reailer->email = 'reatailer@gmail.com';
        $reailer->contact = '1234567890';
        $reailer->email_verified_at = \Carbon\Carbon::now()->toDateTimeString();
        $reailer->approve_at = \Carbon\Carbon::now()->toDateTimeString();
        $reailer->password = Hash::make('123456');
        $reailer->status = '2';
        $reailer->user_type = '5';
        $reailer->save();
        $reailerProfile = new \App\Models\RetailerProfile();
        $reailerProfile->agent_id = $agent1->id;
        $reailerProfile->statehead_id = $stateHead->id;
        $reailerProfile->organization_name = 'Organization';
        $reailerProfile->licence = 'licence123';
        $reailerProfile->address ='reailer address';
        $reailerProfile->pin_code = '700103';
        $reailerProfile->state_id = '35';
        $reailerProfile->servicable_pincodes = '700103,700104,700105';
        $reailer->retailer_prfile()->save($reailerProfile);
        */

        //DELIVERY BOY
        $deliveryboy = new User();
        $deliveryboy->code = \App\Helper\Clib::unique_code(new User(), 'DB');
        $deliveryboy->fname = 'Delivery Boy';
        $deliveryboy->lname = 'One';
        $deliveryboy->email = 'deliveryboy@gmail.com';
        $deliveryboy->contact = '1234567890';
        $deliveryboy->email_verified_at = \Carbon\Carbon::now()->toDateTimeString();
        $deliveryboy->approve_at = \Carbon\Carbon::now()->toDateTimeString();
        $deliveryboy->password = Hash::make('123456');
        $deliveryboy->status = '2';
        $deliveryboy->user_type = '9';
        $deliveryboy->save();
        $deliveryboyProfile = new \App\Models\DeliveryboyProfile();
        $deliveryboyProfile->agent_id = $agent1->id;
        //$deliveryboyProfile->statehead_id = $stateHead->id;
        $deliveryboyProfile->driving_licence = 'DL123456';
        $deliveryboyProfile->address = 'deliveryboy address';
        $deliveryboyProfile->pin_code = '700103';
        $deliveryboyProfile->state_id ='35';
        $deliveryboyProfile->servicable_pincodes = '700103,700104';
        $deliveryboy->deliveryboy_profile()->save($deliveryboyProfile);
    }
}
