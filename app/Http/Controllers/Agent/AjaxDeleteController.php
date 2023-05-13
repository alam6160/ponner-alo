<?php

namespace App\Http\Controllers\Agent;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AjaxDeleteController extends Controller
{
    private function change_status($model, $module)
    {
        $request = Request();
        if ($request->isMethod('post')) {
            
            $validator = Validator::make($request->all(), [
                'id'    => 'required|integer',
                'status'=> "required|in:1,2",
            ]);
            if ($validator->fails() ) {
                $output = array('type' => 'error', 'message'=> $validator->errors()->all() );
            }else{
                if ($request->status == '2') {
                    if ($model::withTrashed()->find($request->id)->delete() ) {
                        $output = array('type' => 'success', 'message'=> 'Successfully deleted '.$module);
                    } else {
                        $output = array('type' => 'error', 'message'=> 'Whoops! try again...');
                    }
                    
                } elseif($request->status == '1') {
                    if ($model::withTrashed()->find($request->id)->restore()) {
                        $output = array('type' => 'success', 'message'=> 'Successfully restore '.$module);
                    } else {
                        $output = array('type' => 'error', 'message'=> 'Whoops! try again...');
                    }
                }
                
            }
            return response()->json($output);
        }
    }
    public function product_status()
    {
        $model = new \App\Models\Product;
        return $this->change_status($model, 'product');
    }
    public function productAddon_status()
    {
        $model = new \App\Models\ProductAddon;
        return $this->change_status($model, 'product addon');
    }
}
