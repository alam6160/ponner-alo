<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = \App\Models\Page::select(['id','page_title'])->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {

                    $btn = '<a href="'.route('admin.home.page.edit', ['id'=>$data->id]).'" class="btn-ancher"><i class="fa fa-pencil-square-o fa-lg"></i></a>';
                    return $btn;
                    
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('admin.home.page.index');
    }
    public function create(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "page_title"            =>"required|string",
                "slug"                  =>"required|string|unique:pages,slug",
                "metakeywords"          =>"nullable|string",
                "metadescriptions"      =>"nullable|string",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                $page = new \App\Models\Page();
                $page->page_title = strip_tags(trim($request->page_title));
                $page->slug = strip_tags(trim($request->slug));
                $page->metakeywords = (empty($request->metakeywords)) ? NULL : strip_tags(trim($request->metakeywords));
                $page->metadescriptions = (empty($request->metadescriptions)) ? NULL : strip_tags(trim($request->metadescriptions));
                $page->page_desc = htmlspecialchars($request->page_desc);

                if ($page->save()) {
                    $output = ['type'=>'success', 'message'=>'Successfully create page'];
                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
            }
            return response()->json($output);
        }
        $data['title'] = 'Create Page';
        return view('admin.home.page.page', $data);
    }
    public function edit(Request $request, $id)
    {
        $page = \App\Models\Page::find($id);
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "page_title"            =>"required|string",
                "slug"                  =>"required|string|unique:pages,slug,{$id}",
                "metakeywords"          =>"nullable|string",
                "metadescriptions"      =>"nullable|string",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                
                $page->page_title = strip_tags(trim($request->page_title));
                $page->slug = strip_tags(trim($request->slug));
                $page->metakeywords = (empty($request->metakeywords)) ? NULL : strip_tags(trim($request->metakeywords));
                $page->metadescriptions = (empty($request->metadescriptions)) ? NULL : strip_tags(trim($request->metadescriptions));
                $page->page_desc = htmlspecialchars($request->page_desc);

                if ($page->save()) {
                    $output = ['type'=>'success', 'message'=>'Successfully create page'];
                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
            }
            return response()->json($output);
        }
        if (blank($page)) {
            return redirect()->route('admin.home.page.index');
        }
        $data['title'] = 'Edit Page';
        $data['page'] = $page;
        return view('admin.home.page.page', $data);
    }
}
