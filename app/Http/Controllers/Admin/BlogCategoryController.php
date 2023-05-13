<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class BlogCategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = \App\Models\BlogCategory::with('parent:id,cat_title')->withTrashed()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {

                    if ($data->trashed()) {

                        $btn = '<a href="javascript:void(0)" class="btn-ancher link-disable"><i class="fa fa-pencil-square-o fa-lg"></i></a>';

                        $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-success btn-delete" data-row="'.$data->id.'" data-status="1" title="Restore"><i class="fa fa-trash-o fa-lg"></i></a>';

                    } else {
                        $btn = '<a href="'.route('admin.home.blog.category.edit', ['id'=>$data->id]).'" class="btn-ancher"><i class="fa fa-pencil-square-o fa-lg"></i></a>';

                        $btn .= '<a href="javascript:void(0)" class="btn-ancher text-danger btn-delete" role="button" data-row="'.$data->id.'" data-status="2" title="Delete"><i class="fa fa-trash fa-lg"></i></a>';
                    }
                    return $btn;
                    
                })
                ->addColumn('parent_cat', function($data){
                    return $data->parent->cat_title;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    }
    public function create(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "cat_title" =>"required|string",
                "slug"      =>"required|string|unique:blog_categories,slug",
                "parent_id" =>"nullable|integer",
                "logo"      =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                $logo = NULL;
                if (isset($_FILES['logo'])) {
                    if ($_FILES['logo']['error'] == 0) {
                        $logo = $request->file('logo')->store('uploads');
                    }
                }
                
                $blogCategory = new \App\Models\BlogCategory();
                $blogCategory->cat_title = strip_tags(trim($request->cat_title));
                $blogCategory->slug = strip_tags(trim($request->slug));
                $blogCategory->parent_id = (empty($request->parent_id)) ? NULL : strip_tags(trim($request->parent_id));
                
                $blogCategory->logo = (empty($logo)) ? NULL : strip_tags(trim($logo));
                if ($blogCategory->save()) {
                    $parent_categories = \App\Models\BlogCategory::select(['id','cat_title'])->whereNull('parent_id')->get();
                    $output = ['type'=>'success', 'message'=>'Successfully create category', 'parent_categories'=> $parent_categories];
                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
            }
            return response()->json($output);
        }
        $data['title'] = 'Create Category';
        $data['parent_categories'] = \App\Models\BlogCategory::select(['id','cat_title'])->whereNull('parent_id')->get();
        return view('admin.home.blog.category.category', $data);
    }
    public function edit(Request $request, $id)
    {
        $blogCategory = \App\Models\BlogCategory::find($id);
        if ($request->ajax()) {

            if (blank($blogCategory)) {
                return ['type'=>'redirect', 'url'=> route('admin.home.blog.category.create'),'message'=>'This category is already deleted' ];
            }

            $validator = validator($request->all(), [
                "cat_title" =>"required|string",
                "slug"      =>"required|string|unique:blog_categories,slug,{$id}",
                "parent_id" =>"nullable|integer",
                "logo"      =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                $logo = NULL;
                if (isset($_FILES['logo'])) {
                    if ($_FILES['logo']['error'] == 0) {
                        $logo = $request->file('logo')->store('uploads');
                        if (!empty($blogCategory->logo)) {
                            $path = public_path($blogCategory->logo);
                            if (is_file($path)) {
                                unlink($path);
                            }
                        }
                    }
                }
                
                $blogCategory->cat_title = strip_tags(trim($request->cat_title));
                $blogCategory->slug = strip_tags(trim($request->slug));
                $blogCategory->parent_id = (empty($request->parent_id)) ? NULL : strip_tags(trim($request->parent_id));
                
                $blogCategory->logo = (empty($logo)) ? NULL : strip_tags(trim($logo));
                if ($blogCategory->save()) {
                    $parent_categories = \App\Models\BlogCategory::select(['id','cat_title'])->whereNull('parent_id')->get();
                    $output = ['type'=>'success', 'message'=>'Successfully create category', 'parent_categories'=> $parent_categories];
                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
            }
            return response()->json($output);
        }
        if (blank($blogCategory)) {
            return redirect()->route('admin.home.blog.category.create');
        }
        $data['title'] = 'Create Category';
        $data['parent_categories'] = \App\Models\BlogCategory::select(['id','cat_title'])->whereNull('parent_id')->get();
        $data['category'] = $blogCategory;
        return view('admin.home.blog.category.category', $data);
    }
}
