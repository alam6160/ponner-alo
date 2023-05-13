<?php

namespace App\Helper;

class Helper
{
    public static function csname()
    {
        $company_name = env('APP_NAME');
        $siteSetting = \App\Models\SiteSetting::select(['key_value'])->where('key_name', 'company_name')->first();
        if (!blank($siteSetting)) {
            if (!empty($siteSetting->key_value)) {
                $company_name = $siteSetting->key_value;
            }
        }
        return $company_name;
    }
    public static function ccurrency()
    {
        $siteSetting = \App\Models\SiteSetting::select(['key_value'])->where('key_name', 'currency')->first();

        if(!empty($siteSetting->key_value)){ return $siteSetting->key_value; }
        else{ return '&#x20B9;'; }
    }

    public static function site_data($key_name)
    {
        $site_settings = \App\Models\SiteSetting::select(['key_value'])->where('key_name', $key_name)->first();

        if (blank($site_settings)) {
            return NULL;
        } else {
            return (empty($site_settings->key_value)) ? NULL : $site_settings->key_value ;
        }
    }
    public static function cemail()
    {
        $email = self::site_data('email_id');
        return (empty($email)) ? 'demo@gmail.com' : $email;
    }
    public static function ccontact1()
    {
        $contact_no = self::site_data('contact_no');
        return (empty($contact_no)) ? '123456' : $contact_no;
    }
    public static function ccontact2()
    {
        $contact_no_2 = self::site_data('contact_no_2');
        return (empty($contact_no_2)) ? '123456' : $contact_no_2;
    }
    public static function caddress()
    {
        $address = self::site_data('address');
        return (empty($address)) ? '123456' : $address;
    }
    public static function favIcon()
    {
        return asset('assests/icon/favicon_1.png');
    }
    public static function frontendLogo()
    {
        return asset('assests/icon/logo_1.png');
    }
    public static function adminLogo()
    {
        return asset('assests/icon/logo_1.png');
    }
    public static function loginLogo()
    {
        return asset('assests/icon/logo_1.png');
    }
    public static function adminUser()
    {
        return asset('assests/theme/images/user.png');
    }
    public static function setUserType()
    {
        return [
            '1'=> 'Superadmin',
            '2'=> 'Admin',
            '3'=> 'Statehead',
            '4'=> 'Agent',
            '5'=> 'Retailers',
            '6'=> 'Pharmacist',
            '7'=> 'Support',
            '8'=> 'Marketing',
        ];
    }
    public static function getUserType($var)
    {
        $array = self::setUserType();
        if (array_key_exists($var,  $array )) {
            return $array[$var];
        } else {
            return '';
        }
    }
    public static function setProductType()
    {
        return [
            '1'=>'Simple',
            '2'=>'Variable',
        ];
    }
    public static function getProductType($var)
    {
        $array = self::setProductType();
        if (array_key_exists($var,  $array )) {
            return $array[$var];
        } else {
            return '';
        }
    }
    public static function applicationStatus($var)
    {
        if ($var == '1') {
            return 'Pending';
        }elseif ($var == '2') {
            return 'Approved';
        }elseif ($var == '3') {
            return 'Cancel';
        }
    }
    public static function getPaymentMode($var)
    {
        return ($var == '1') ? 'Offline' : 'Online' ;
    }
    public static function getDiscountType($var)
    {
        return ($var == '1') ? 'Flat Discount' : 'Percentage Discount' ;
    }
    public static function user_address_state($state_id)
    {
        $state_data = \App\Models\State::where('id', $state_id)->first();

        if(!empty($state_data->state_name)){ return $state_data->state_name; }
        else{ return 'N/A'; }
    }
    public static function getOrderPaymentModel($var)
    {
        return ($var == '1') ? 'Cash On Delivery' : 'Online';
    }
    public static function getOrderStatus($var)
    {
        switch ($var) {
            case '1':
                $status = 'Recieved';
                break;
            case '2':
                $status = 'Processed';
                break;
            case '3':
                $status = 'Shipped';
                break;
            case '4':
                $status = 'Delivered';
                break;
            case '5':
                $status = 'Cancel';
                break;
            case '6':
                $status = 'Return Request';
                break;
            
            default:
                $status = 'Recieved';
                break;
        }

        return $status;
    }
    public static function getOrderReturnStatus($var)
    {
        switch ($var) {
            case '1':
                $status = 'Pending';
                break;
            case '2':
                $status = 'Approved';
                break;
            case '3':
                $status = 'Reject';
                break;
            
            default:
                $status = 'Pending';
                break;
        }

        return $status;
    }
}

?>