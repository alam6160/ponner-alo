<?php

namespace App\Http\Controllers\Agent;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $selectField = ['id','name','short_desc','sku','product_type','deleted_at'];
            $data = \App\Models\Product::select($selectField)->where(['user_id' => Auth::id(), 'admin_verify' => '1'])->withTrashed()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {

                    if ($data->trashed()) {

                        $btn = '<a href="javascript:void(0)" class="btn-ancher link-disable"><i class="fa fa-pencil-square-o fa-lg"></i></a>';

                        $btn .= '<a target="_blank" href="'.url("agent/product/view").'/'.$data->id.'" class="btn-ancher"><i class="fa fa-eye fa-lg"></i></a>';

                        $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-success btn-delete" data-row="'.$data->id.'" data-status="1" title="Restore"><i class="fa fa-trash-o fa-lg"></i></a>';

                    } else {
                        $btn = '<a href="'.route('agent.product.edit', ['id'=>$data->id]).'" class="btn-ancher"><i class="fa fa-pencil-square-o fa-lg"></i></a>';

                        $btn .= '<a target="_blank" href="'.url("agent/product/view").'/'.$data->id.'" class="btn-ancher"><i class="fa fa-eye fa-lg"></i></a>';

                        $btn .= '<a href="javascript:void(0)" class="btn-ancher text-danger btn-delete" role="button" data-row="'.$data->id.'" data-status="2" title="Delete"><i class="fa fa-trash fa-lg"></i></a>';
                    }
                    return $btn;
                    
                })
				->editColumn('sku', function($data){
                    if ($data->product_type == '1') {
                        return $data->sku;
                    } else {
                        $skus = array_filter(json_decode($data->sku));
                        return implode(',', $skus);
                    }
              
                })
                ->editColumn('product_type', function($data){
                    return  \App\Helper\Helper::getProductType($data->product_type);
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('agent.product.index');
    }
    public function create(Request $request)
    {
        if ($request->ajax()) {

            $validation = [
                "name"                  =>"required|string",
                //"slug"                  =>"required|string|unique:products,slug",
                "components"            =>"nullable|string",
                "category_ids.*"        =>"nullable|integer",
                "saletype"              =>"nullable|integer",
                "product_filter_id.*"   =>"nullable|integer",
                "product_addon_id.*"    =>"nullable|integer",
                "number_of_items"       =>"required|integer|min:1",

                "short_desc"            =>"nullable|string",
                "sku_code.*"            =>"nullable|string",
                "product_type"          =>"required|in:1,2",

                "varient_set.*"         =>"nullable|string|required_if:product_type,2",
                "mrp.*"                 =>"required|numeric",
                "discounted_price.*"    =>"nullable|numeric",

                "metakeywords"          =>"nullable|string",
                "metadescriptions"      =>"nullable|string",

                "featured_image"        =>"nullable|mimes:jpg,jpeg,png,webp|max:2048",
                "images.*"              =>"nullable|mimes:jpg,jpeg,png,webp|max:2048",

                "specifications_1.*"    =>"nullable|string|required_if:product_type,2",
                "attribute_1"           =>"nullable|string|required_if:product_type,2",
            ];

            if (!empty($request->specifications_2)) {
                $validation['specifications_2.*']   = "required|string";
                $validation['attribute_2']          = "required|string";
            }

            if (!empty($request->specifications_3)) {
                $validation['specifications_3.*']   = "required|string";
                $validation['attribute_3']          = "required|string";
            }

            $validator = validator($request->all(), $validation);

            if ($validator->fails()) {
                $output = ['type'=>"error", 'message'=> $validator->errors()->all()];
            } else {
				
				$slug = \App\Helper\Clib::generateSlug(new \App\Models\Product(),$request->name, 'name');
                //$p = (empty($request->discounted_price)) ? '666' :  implode(',', $request->discounted_price);
                //$p = (empty($request->discounted_price)) ? '666' :  '2366666';
                
                //dd( $p ) ;
                //dd($request->all());

                $product = new \App\Models\Product();
                $product->name = (empty($request->name)) ? NULL : strip_tags(trim($request->name));
                $product->slug = $slug;
                $product->components = (empty($request->components)) ? NULL : strip_tags(trim($request->components));
                $product->short_desc = (empty($request->short_desc)) ? NULL : strip_tags(trim($request->short_desc));
                $product->description = htmlspecialchars($request->description);
                $product->product_type = (empty($request->product_type)) ? NULL : strip_tags(trim($request->product_type));
                $product->number_of_items = (empty($request->number_of_items)) ? NULL : strip_tags(trim($request->number_of_items));

                $product->categories = (empty($request->category_ids)) ? NULL :  implode(',', $request->category_ids);
                $product->product_filter_id = (empty($request->product_filter_ids)) ? NULL :  implode(',', $request->product_filter_ids);
                $product->product_addon_id = (empty($request->product_addon_ids)) ? NULL :  implode(',', $request->product_addon_ids);
                $product->admin_verify = '2';
                $product->saletype = (empty($request->saletype)) ? NULL : $request->saletype;
                
                if ($request->product_type == '1') {
					
					$product->sku = (empty($request->sku_code[0])) ? NULL : strip_tags($request->sku_code[0]);
                    $product->mrp = (empty($request->mrp)) ? NULL :  implode(',', $request->mrp);
                    if (!empty( array_filter($request->discounted_price))) {
                        $product->discounted_price = implode(',', $request->discounted_price);
                    }

                }elseif ($request->product_type == '2') {
                    $product->attribute_1 = (empty($request->attribute_1)) ? NULL : strip_tags(trim($request->attribute_1));
                    $product->attribute_2 = (empty($request->attribute_2)) ? NULL : strip_tags(trim($request->attribute_2));
                    $product->attribute_3 = (empty($request->attribute_3)) ? NULL : strip_tags(trim($request->attribute_3));

                    $product->specifications_1 = (empty($request->specifications_1)) ? NULL :  implode(',', $request->specifications_1);
                    $product->specifications_2 = (empty($request->specifications_2)) ? NULL :  implode(',', $request->specifications_2);
                    $product->specifications_3 = (empty($request->specifications_3)) ? NULL :  implode(',', $request->specifications_3);
					$product->specifications = (empty($request->varient_set)) ? NULL :  json_encode( $request->varient_set);
					
					$product->sku = (empty($request->sku_code)) ? NULL : json_encode($request->sku_code);
                    $product->mrp = (empty($request->mrp)) ? NULL :  json_encode( $request->mrp);
                    $product->discounted_price = (empty($request->discounted_price)) ? NULL :  json_encode( $request->discounted_price);
                }

                $product->metakeywords = (empty($request->metakeywords)) ? NULL : strip_tags(trim($request->metakeywords));
                $product->metadescriptions = (empty($request->metadescriptions)) ? NULL : strip_tags(trim($request->metadescriptions));

                $product->user_id = Auth::id();

                if ($product->save()) {
                    $featured_image = NULL;
                    if ($_FILES['featured_image']['error'] == 0) {
                        $featured_image = $request->file('featured_image')->store('uploads');
                    }
                    $upload_images = [];
                    if ($_FILES['images']['error'][0] == 0) {
                        if ($request->hasFile('images')) {
                            foreach ($request->images as $key => $file) {
                                $upload_images[] = \Illuminate\Support\Facades\Storage::putFile('uploads', $file);
                            }
                        }
                    }
                    $product->featuredimage = (empty($featured_image)) ? NULL : $featured_image;
                    $product->images = (empty($upload_images)) ? NULL : implode(',', $upload_images);
                    $product->save();

                    $output = ['type'=>"success", 'message'=> "product is created successfully"];

                } else {
                    $output = ['type'=>"error", 'message'=> "try again.."];
                } 
            }
            return response()->json($output);
        }
        $data['title'] = 'Create Product';
        $data['parent_categories'] = \App\Models\Category::select(['id','cat_title'])->has('children')->with('children:parent_id,id,cat_title')->whereNull('parent_id')->get();

        $data['parentFilters'] = \App\Models\ProductFilter::select(['id','filter_title'])->has('children')->with('children:parent_id,id,filter_title')->whereNull('parent_id')->get();
        $data['productAddon'] = \App\Models\ProductAddon::where('user_id', Auth::id())->get();

        return view('agent.product.addproduct', $data);
    }
    public function edit(Request $request, $id)
    {
        $product = \App\Models\Product::where('user_id', Auth::id())->find($id);
        if ($request->ajax()) {

            if (blank($product)) {
                return ['type'=>'redirect', 'url'=> route('agent.product.index'),'message'=>'This product is already delected' ];
            }

            $validation = [
                "name"                  =>"required|string",
                //"slug"                  =>"required|string|unique:products,slug,{$id}",
                "components"            =>"nullable|string",
                "category_ids.*"        =>"nullable|integer",
                "product_filter_id.*"   =>"nullable|integer",
                "product_addon_id.*"    =>"nullable|integer",
                "number_of_items"       =>"required|integer|min:1",

                "short_desc"            =>"nullable|string",
                "sku_code.*"            =>"nullable|string",
                "product_type"          =>"required|in:1,2",

                "varient_set.*"         =>"nullable|string|required_if:product_type,2",
                "mrp.*"                 =>"required|numeric",
                "discounted_price.*"    =>"nullable|numeric",

                "metakeywords"          =>"nullable|string",
                "metadescriptions"      =>"nullable|string",

                "featured_image"        =>"nullable|mimes:jpg,jpeg,png,webp|max:2048",
                "images.*"              =>"nullable|mimes:jpg,jpeg,png,webp|max:2048",

                "specifications_1.*"    =>"nullable|string|required_if:product_type,2",
                "attribute_1"           =>"nullable|string|required_if:product_type,2",
            ];

            if (!empty($request->specifications_2)) {
                $validation['specifications_2.*']   = "required|string";
                $validation['attribute_2']          = "required|string";
            }

            if (!empty($request->specifications_3)) {
                $validation['specifications_3.*']   = "required|string";
                $validation['attribute_3']          = "required|string";
            }

            $validator = validator($request->all(), $validation);

            if ($validator->fails()) {
                $output = ['type'=>"error", 'message'=> $validator->errors()->all()];
            } else {
				
				$slug = \App\Helper\Clib::generateSlug(new \App\Models\Product(),$request->name, 'name', $id);
				
                $product->name = (empty($request->name)) ? NULL : strip_tags(trim($request->name));
                $product->slug = $slug;
                $product->components = (empty($request->components)) ? NULL : strip_tags(trim($request->components));
                $product->short_desc = (empty($request->short_desc)) ? NULL : strip_tags(trim($request->short_desc));
                $product->description = htmlspecialchars($request->description);
                $product->product_type = (empty($request->product_type)) ? NULL : strip_tags(trim($request->product_type));
                $product->number_of_items = (empty($request->number_of_items)) ? NULL : strip_tags(trim($request->number_of_items));

                $product->categories = (empty($request->category_ids)) ? NULL :  implode(',', $request->category_ids);
                $product->product_filter_id = (empty($request->product_filter_ids)) ? NULL :  implode(',', $request->product_filter_ids);
                $product->product_addon_id = (empty($request->product_addon_ids)) ? NULL :  implode(',', $request->product_addon_ids);
                $product->saletype = (empty($request->saletype)) ? NULL : $request->saletype;
                
                if ($request->product_type == '1') {
					
					$product->sku = (empty($request->sku_code[0])) ? NULL : strip_tags($request->sku_code[0]);
                    
                    $product->mrp = (empty($request->mrp)) ? NULL :  implode(',', $request->mrp);
                    if (!empty( array_filter($request->discounted_price))) {
                        $product->discounted_price = implode(',', $request->discounted_price);
                    }

                    $product->attribute_1 = NULL;
                    $product->attribute_2 = NULL;
                    $product->attribute_3 = NULL;

                    $product->specifications_1 = NULL;
                    $product->specifications_2 = NULL;
                    $product->specifications_3 = NULL;

                    $product->specifications = NULL;

                }elseif ($request->product_type == '2') {
                    $product->attribute_1 = (empty($request->attribute_1)) ? NULL : strip_tags(trim($request->attribute_1));
                    $product->attribute_2 = (empty($request->attribute_2)) ? NULL : strip_tags(trim($request->attribute_2));
                    $product->attribute_3 = (empty($request->attribute_3)) ? NULL : strip_tags(trim($request->attribute_3));

                    $product->specifications_1 = (empty($request->specifications_1)) ? NULL :  implode(',', $request->specifications_1);
                    $product->specifications_2 = (empty($request->specifications_2)) ? NULL :  implode(',', $request->specifications_2);
                    $product->specifications_3 = (empty($request->specifications_3)) ? NULL :  implode(',', $request->specifications_3);
                    $product->specifications = (empty($request->varient_set)) ? NULL :  json_encode( $request->varient_set);
					
					$product->sku = (empty($request->sku_code)) ? NULL : json_encode($request->sku_code);
                    $product->mrp = (empty($request->mrp)) ? NULL :  json_encode( $request->mrp);
                    $product->discounted_price = (empty($request->discounted_price)) ? NULL :  json_encode( $request->discounted_price);
                }

                $product->metakeywords = (empty($request->metakeywords)) ? NULL : strip_tags(trim($request->metakeywords));
                $product->metadescriptions = (empty($request->metadescriptions)) ? NULL : strip_tags(trim($request->metadescriptions));

                if ($product->save()) {

                    $featured_image = $product->featuredimage;
                    if ($_FILES['featured_image']['error'] == 0) {
                        $featured_image = $request->file('featured_image')->store('uploads');
                        /*
                        if (!empty($product->featuredimage)) {
                            $path = public_path($product->featuredimage);
                            if (is_file($path)) {
                                unlink($path);
                            }
                        } */
                    }
                    $newupload_images = [];
                    if ($_FILES['images']['error'][0] == 0) {
                        if ($request->hasFile('images')) {
                            foreach ($request->images as $key => $file) {
                                $newupload_images[$key] = \Illuminate\Support\Facades\Storage::putFile('uploads', $file);
                            }
                        }
                    }
                    $upload_images = (empty($product->images)) ? [] : explode(',',$product->images);
                    $newimages = array_merge($upload_images, $newupload_images);
                    $product->featuredimage = (empty($featured_image)) ? NULL : $featured_image;
                    $product->images = (empty($newimages)) ? NULL : implode(',', $newimages);
                    $product->save();

                    $output = ['type'=>"success", 'message'=> "product is updated successfully"];

                } else {
                    $output = ['type'=>"error", 'message'=> "try again.."];
                } 
            }
            return response()->json($output);
        }

        if (blank($product)) {
            return redirect()->route('agent.product.index');
        }
        $data['title'] = 'Edit Product';
        $data['parent_categories'] = \App\Models\Category::select(['id','cat_title'])->has('children')->with('children:parent_id,id,cat_title')->whereNull('parent_id')->get();
        $data['product'] = $product;
        $data['parentFilters'] = \App\Models\ProductFilter::select(['id','filter_title'])->has('children')->with('children:parent_id,id,filter_title')->whereNull('parent_id')->get();
        $data['productAddon'] = \App\Models\ProductAddon::where('user_id', Auth::id())->get();
        return view('agent.product.editproduct', $data);
    }
    public function delete_image(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "row"       =>"required|integer",
                "product_id"=>"required|integer"
            ]);
            if ($validator->fails()) {
                $output = ['type'=>"error", 'message'=> $validator->errors()->all()];
            } else {
                $product = \App\Models\Product::select(['id','images'])->find($request->product_id);
                if (blank($product)) {
                    $output = ['type'=>"error", 'message'=> "Product data not found"];
                } else {
                    $imagesArr = explode(',', $product->images);
                    if (isset($imagesArr[$request->row])) {
                        $deleteImage = $imagesArr[$request->row];
                        \Illuminate\Support\Arr::forget($imagesArr, $request->row);
                        $newimages = implode(',', $imagesArr);
                        $product->images = $newimages;
                        if ($product->save()) {
                            $path = public_path($deleteImage);
                            if (is_file($path)) {
                                unlink($path);
                            }
                            $output = ['type'=>"success", 'message'=> "Images are updated successfully"];
                        } 
                    } else {
                        $output = ['type'=>"error", 'message'=> "Invalid Image ID"];
                    }
                }
            }
            return response()->json($output);
        }
    }
    public function get_image(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "product_id"=>"required|integer"
            ]);
            if ($validator->fails()) {
                $output = ['type'=>"error", 'message'=> $validator->errors()->all()];
            } else {
                $product = \App\Models\Product::select(['id','images'])->find($request->product_id);
                if (blank($product)) {
                    $output = ['type'=>"error", 'message'=> "Product data not found"];
                } else {
                    if (empty($product->images)) {
                        $output = ['type'=>"error", 'message'=> "No Images"];
                    } else {
                        $imagesArr = explode(',', $product->images);
                        $output = ['type'=>"success", 'message'=> "Successfully fetch data", 'imagesArr'=> $imagesArr];
                    } 
                }
            }
            return response()->json($output);
        }
    }
    public function searchProduct(Request $request)
    {
        $query = $request->input('query', NULL);
        $product_ids = $request->input('product_ids');

        $output = array();
        if (empty($query)) {
            if (empty($product_ids)) {
                $products = \App\Models\Product::select(['name','id'])->where('name','like', "%$query%")->get();
            } else {
                $product_ids = array_filter(explode(',', $product_ids));
                $products = \App\Models\Product::select(['name','id'])->where('name','like', "%$query%")->whereNotIn('id', $product_ids)->get();
            }
        }else{
            if (empty($product_ids)) {
                $products = \App\Models\Product::select(['name','id'])->inRandomOrder()->limit(20)->get();
            } else {
                $product_ids = array_filter(explode(',', $product_ids));
                $products = \App\Models\Product::select(['name','id'])->inRandomOrder()->whereNotIn('id', $product_ids)->limit(20)->get();
            }
        }
        if ($products->isNotEmpty()) {
            foreach ($products as $key => $value) {
                $output[] = [
                    'id'    => $value->id,
                    'text'  => $value->name
                ];
            }
        }
        return response()->json($output);
    }

    public function list_product($type)
    {
        if ($type == 'pending') {

            $data['list'] = \App\Models\Product::where(['user_id' => Auth::id(), 'admin_verify' => '2'])->withTrashed()->orderBy('id', 'DESC')->paginate(10);
            $data['list_title'] = 'Pending Products';
        } elseif ($type == 'rejected') {
            
            $data['list'] = \App\Models\Product::where(['user_id' => Auth::id(), 'admin_verify' => '0'])->withTrashed()->orderBy('id', 'DESC')->paginate(10);
            $data['list_title'] = 'Rejected Products';
        } else {
            return redirect()->back();
        }
        return view('agent.product.list_product', $data);
    }

    public function tr_delete($id)
    {
        if(\App\Models\Product::where(['user_id' => Auth::id(), 'id' => $id])->forcedelete()){
            $response = [
                'status'    =>  1,
                'message'   =>  'Product Deleted Successfully.',
            ];
        }else{
            $response = [
                'status'    =>  0,
                'message'   =>  'Something Went Wrong!',
            ];
        }
        return response()->json($response);
    }

    public function view($id)
    {
        $product = \App\Models\Product::where(['id' => $id, 'user_id' => Auth::id()])->withTrashed()->first();
        if (blank($product)) {
            return redirect()->route('admin.product.index');
        }
        $data['title'] = 'View Product';
        $data['parent_categories'] = \App\Models\Category::select(['id','cat_title'])->with('children:parent_id,id,cat_title')->whereNull('parent_id')->get();
        $data['product'] = $product;
        $data['parentFilters'] = \App\Models\ProductFilter::select(['id','filter_title'])->with('children:parent_id,id,filter_title')->whereNull('parent_id')->get();
        $data['productAddon'] = \App\Models\ProductAddon::all();
        return view('agent.product.view', $data);
        
    }
}
