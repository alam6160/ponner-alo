<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = \App\Models\Blog::select(['id','blog_title','publish_status','thumbnail','deleted_at'])->withTrashed()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {

                    if ($data->trashed()) {

                        $btn = '<a href="javascript:void(0)" class="btn-ancher link-disable"><i class="fa fa-pencil-square-o fa-lg"></i></a>';

                        $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-success btn-delete" data-row="'.$data->id.'" data-status="1" title="Restore"><i class="fa fa-trash-o fa-lg"></i></a>';

                    } else {
                        $btn = '<a href="'.route('admin.home.blog.edit', ['id'=>$data->id]).'" class="btn-ancher"><i class="fa fa-pencil-square-o fa-lg"></i></a>';

                        $btn .= '<a href="javascript:void(0)" class="btn-ancher text-danger btn-delete" role="button" data-row="'.$data->id.'" data-status="2" title="Delete"><i class="fa fa-trash fa-lg"></i></a>';
                    }
                    return $btn;
                    
                })
                ->editColumn('thumbnail', function($data){
                    if (empty($data->thumbnail)) {
                        return '';
                    } else {
                        return '<img class="img-thumbnail" src="'.asset($data->thumbnail).'" alt="" onclick="viewImage()" style="width: 40px; height: 40px">';
                    } 
                })
                ->editColumn('publish_status', function($data){
                    return ($data->publish_status == 1) ? 'Schedule' : 'Daft';
                })
                ->rawColumns(['actions','thumbnail'])
                ->make(true);
        }
        return view('admin.home.blog.index');
    }
    public function create(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "blog_title"        =>"required|string",
                "slug"              =>"required|string|unique:blogs,slug",
                "publish_status"    =>"required|in:1,2",
                "schedule_date"     =>"nullable|date|required_if:publish_status,1",
                "thumbnail"         =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
                "metakeywords"      =>"nullable|string",
                "metadescriptions"  =>"nullable|string",
                "category_ids.*"    =>"nullable|integer",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                $thumbnail = NULL;
                if ($_FILES['thumbnail']['error'] == 0) {
                    $thumbnail = $request->file('thumbnail')->store('uploads');
                }
                
                $blog = new \App\Models\Blog();
                $blog->blog_title = strip_tags(trim($request->blog_title));
                $blog->slug = strip_tags(trim($request->slug));
                $blog->blog_desc = htmlspecialchars($request->blog_desc);
                $blog->publish_status = strip_tags(trim($request->publish_status));
                $blog->schedule_date = (empty($request->schedule_date)) ? NULL : date('Y-m-d', strtotime($request->schedule_date));
                $blog->metakeywords = (empty($request->metakeywords)) ? NULL : strip_tags(trim($request->metakeywords));
                $blog->metadescriptions = (empty($request->metadescriptions)) ? NULL : strip_tags(trim($request->metadescriptions));
                $blog->categories = (empty($request->category_ids)) ? NULL :  implode(',', $request->category_ids);


                $blog->thumbnail = (empty($thumbnail)) ? NULL : strip_tags(trim($thumbnail));
                if ($blog->save()) {
                    $output = ['type'=>'success', 'message'=>'Successfully create blog'];
                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
            }
            return response()->json($output);
        }
        $data['title'] = 'Create Blog';
        $data['parent_categories'] = \App\Models\BlogCategory::select(['id','cat_title'])->with('children:parent_id,id,cat_title')->whereNull('parent_id')->get();

        return view('admin.home.blog.blog', $data);
    }
    public function edit(Request $request, $id)
    {
        $blog = \App\Models\Blog::find($id);
        if ($request->ajax()) {
            
            if (blank($blog)) {
                return ['type'=>'redirect', 'url'=> route('admin.home.blog.index'),'message'=>'This blog is already delected' ];
            }
            $validator = validator($request->all(), [
                "blog_title"        =>"required|string",
                "slug"              =>"required|string|unique:blogs,slug,{$id}",
                "publish_status"    =>"required|in:1,2",
                "schedule_date"     =>"nullable|date|required_if:publish_status,1",
                "thumbnail"         =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
                "metakeywords"      =>"nullable|string",
                "metadescriptions"  =>"nullable|string",
                "category_ids.*"    =>"nullable|integer",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                
                $thumbnail = $blog->thumbnail;
                if ($_FILES['thumbnail']['error'] == 0) {
                    $thumbnail = $request->file('thumbnail')->store('uploads');
                    if (!empty($blog->thumbnail)) {
                        $path = public_path($blog->thumbnail);
                        if (is_file($path)) {
                            unlink($path);
                        }
                    }
                }
                
                $blog->blog_title = strip_tags(trim($request->blog_title));
                $blog->slug = strip_tags(trim($request->slug));
                $blog->blog_desc = htmlspecialchars($request->blog_desc);
                $blog->publish_status = strip_tags(trim($request->publish_status));
                $blog->schedule_date = (empty($request->schedule_date)) ? NULL : date('Y-m-d', strtotime($request->schedule_date));
                $blog->metakeywords = (empty($request->metakeywords)) ? NULL : strip_tags(trim($request->metakeywords));
                $blog->metadescriptions = (empty($request->metadescriptions)) ? NULL : strip_tags(trim($request->metadescriptions));
                $blog->categories = (empty($request->category_ids)) ? NULL :  implode(',', $request->category_ids);

                $blog->thumbnail = (empty($thumbnail)) ? NULL : strip_tags(trim($thumbnail));
                if ($blog->save()) {
                    $output = ['type'=>'success', 'message'=>'Successfully create blog'];
                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
            }
            return response()->json($output);
        }
        if (blank($blog)) {
            return redirect()->route('admin.home.blog.index');
        }
        $data['title'] = 'Edit Blog';
        $data['blog'] = $blog;
        $data['parent_categories'] = \App\Models\BlogCategory::select(['id','cat_title'])->with('children:parent_id,id,cat_title')->whereNull('parent_id')->get();
        return view('admin.home.blog.blog', $data);
    }
}
