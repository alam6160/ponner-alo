<?php
namespace App\Helper;

class Clib
{
    static function generate_uniqueno($model, $purpose)
    {
        $csn = strtoupper( Helper::csname() ) ;
        $purpose = strtoupper( trim($purpose) );
        $data = $model->select('id')->withTrashed()->orderBy('id', 'desc')->first();
        if ( blank($data)) {
            return $csn.'/'.$purpose.'/0001';
        } else {
            $id = ($data->id) + 1;
            if ( strlen($id) == 1) {
                return $csn.'/'.$purpose.'/000'.($id);
            }elseif( strlen($id) == 2 ){
                return $csn.'/'.$purpose.'/00'.($id);
            }elseif( strlen($id) == 3 ){
                return $csn.'/'.$purpose.'/0'.($id);
            }elseif( strlen($id) >= 4 ){
                return $csn.'/'.$purpose.'/'.($id);
            }
        }
    }
    public static function unique_code($model, $purpose)
    {
        $purpose = strtoupper( trim($purpose) );
        $data = $model->select('id')->withTrashed()->orderBy('id', 'desc')->first();

        if ( blank($data)) {
            return $purpose.'0001';
        } else {
            $id = intval($data->id) + 1;
            if ( strlen($id) == 1) {
                return $purpose.'000'.($id);
            }elseif( strlen($id) == 2 ){
                return $purpose.'00'.($id);
            }elseif( strlen($id) == 3 ){
                return $purpose.'0'.($id);
            }elseif( strlen($id) >= 4 ){
                return $purpose.''.($id);
            }
        }
    }
    public static function generateOrderNo()
    {
        //$purpose = \App\Helper\Helper::csname();
        $purpose = 'ORDER-';
        $data = \App\Models\OrderDetails::select('id')->orderBy('id', 'desc')->first();
        if (blank($data)) {
            return $purpose.'10000';
        } else {
            $id = intval($data->id) + 1;
            if ( strlen($id) == 1) {
                return $purpose.'1000'.($id);
            }elseif( strlen($id) == 2 ){
                return $purpose.'100'.($id);
            }elseif( strlen($id) == 3 ){
                return $purpose.'10'.($id);
            }elseif( strlen($id) == 4 ){
                return $purpose.'1'.($id);
            }elseif( strlen($id) >= 5 ){
                return $purpose.''.($id);
            }
        }
        
    }
    public static function generateSlug($model, $slug, $column_name, $id=NULL)
    {
        //$slug = \Illuminate\Support\Str::slug(strip_tags(trim($slug)));
        $genSlug = \Illuminate\Support\Str::slug(strip_tags(trim($slug)));
        if (empty($id)) {
            $count = $model->select('id')->withTrashed()->where($column_name, $slug)->count();
            if ($count > 0) {
                return $genSlug.'-'.(intval($count) + 1);
            } else {
                return $genSlug;
            }
        } else {

            $data = $model->select(['id', $column_name, 'slug'])->withTrashed()->where($column_name, $slug)->find($id);

            if (!blank($data)) {
                return $data->slug;
            } else {
                $count = $model->select('id')->withTrashed()->where($column_name, $slug)->count();
                if ($count > 0) {
                    return $genSlug.'-'.(intval($count) + 1);
                } else {
                    return $genSlug;
                }
            }
        }
    }
    public static function getMembershipPrice()
    {
        $siteSetting = \App\Models\SiteSetting::select('key_value')->whereKeyName('membership_price')->first();
        return (blank($siteSetting)) ? 0 : floatval($siteSetting->key_value);
    }
    public static function getMemberLateFees()
    {
        $siteSetting = \App\Models\SiteSetting::select('key_value')->whereKeyName('late_fees_per_day')->first();
        return (blank($siteSetting)) ? 0 : floatval($siteSetting->key_value);
    }
    public static function getVendorMembershipPrice($vendor_id)
    {
        $vendor = \App\Models\User::select(['id','created_at'])->find($vendor_id);
        $membership_price = 0;
        if (!blank($vendor)) {
            $subscriptionLog = \App\Models\SubscriptionLog::whereAgentId( $vendor_id )->where('expaire_date', '>=', date('Y-m-d'))->orderBy('id', 'desc')->first();
            if (blank($subscriptionLog)) {

                $membership_price = self::getMembershipPrice();
                $lateFees = self::getMemberLateFees();

                $subscriptionLogs = \App\Models\SubscriptionLog::whereAgentId( $vendor_id )->orderBy('id', 'desc')->first();

                if (blank($subscriptionLogs)) {

                    /*
                    $todate = date('Y-m-d');
                    $agent_createdAt = date('Y-m-d', strtotime( $vendor->created_at));
                    $diffDays = intval(\Carbon\Carbon::parse($todate)->floatDiffInDays($agent_createdAt));
                    if ($diffDays > 1) {
                        $membership_price = $membership_price + (($diffDays-1) * $lateFees);
                    } */

                } else {
                    $todate = date('Y-m-d');
                    $expaire_date = date('Y-m-d', strtotime($subscriptionLogs->expaire_date));
                    $diffDays = intval(\Carbon\Carbon::parse($todate)->floatDiffInDays($expaire_date));
                    if ($diffDays > 1) {
                        $membership_price = $membership_price + (($diffDays-1) * $lateFees);
                    }
                }
            }
        }
        return $membership_price;
    }
}
