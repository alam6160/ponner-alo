<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $data['allblogs'] = \App\Models\Blog::where('publish_status', '1')->where('schedule_date', '<=', date('Y-m-d'))->orderBy('schedule_date', 'desc')->get();
        return view('fontend.blogs', $data);
    }
    public function details(Request $request, $slug)
    {
        $blog = \App\Models\Blog::where([
            ['publish_status','=','1'],
            ['schedule_date','<=',date('Y-m-d')],
            ['slug','=', $slug],
        ])->first();
        if (blank($blog)) {
            return redirect()->route('blog.index');
        }
        $data['blog'] = $blog;
        $data['allblogs'] = \App\Models\Blog::where('publish_status', '1')->where('schedule_date', '<=', date('Y-m-d'))->orderBy('schedule_date', 'desc')->limit(3)->where('id', '!=', $blog->id)->get();
        return view('fontend.blogdetails', $data);
    }
}
