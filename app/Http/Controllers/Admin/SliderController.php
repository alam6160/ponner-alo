<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class SliderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = \App\Models\Slider::withTrashed()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($data) {

                    if ($data->trashed()) {

                        $btn = '<a href="javascript:void(0)" class="btn-ancher link-disable"><i class="fa fa-pencil-square-o fa-lg"></i></a>';

                        $btn .= '<a href="javascript:void(0)" role="button" class="btn-ancher text-success btn-delete" data-row="'.$data->id.'" data-status="1" title="Restore"><i class="fa fa-trash-o fa-lg"></i></a>';

                    } else {
                        $btn = '<a href="'.route('admin.home.slider.edit', ['id'=>$data->id]).'" class="btn-ancher"><i class="fa fa-pencil-square-o fa-lg"></i></a>';

                        $btn .= '<a href="javascript:void(0)" class="btn-ancher text-danger btn-delete" role="button" data-row="'.$data->id.'" data-status="2" title="Delete"><i class="fa fa-trash fa-lg"></i></a>';
                    }
                    return $btn;
                    
                })
                ->editColumn('thumnanail', function($data){
                    return '<a href="'.asset($data->thumnanail).'" target="blank"><img class="img-thumbnail" src="'.asset($data->thumnanail).'" alt="" style="width: 50px; height: 50px;">
                    </a>';
                })
                ->rawColumns(['actions','thumnanail'])
                ->make(true);
        }
    }
    public function create(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "title"         =>"nullable|string",
                "sub_title"     =>"nullable|string",
                "caption"       =>"nullable|string",
                "link"          =>"nullable|url",
                "thumnanail"    =>"required|image|mimes:jpg,jpeg,png|max:2048",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                $slider = new \App\Models\Slider();
                $slider->title = (empty($request->title)) ? NULL : strip_tags($request->title);
                $slider->sub_title = (empty($request->sub_title)) ? NULL : strip_tags($request->sub_title);
                $slider->caption = (empty($request->caption)) ? NULL : strip_tags($request->caption);
                $slider->link = (empty($request->link)) ? NULL : strip_tags($request->link);

                if ($slider->save()) {

                    if (isset($_FILES['thumnanail'])) {
                        if ($_FILES['thumnanail']['error'] == 0) {
                            $slider->thumnanail = $request->file('thumnanail')->store('uploads');
                            $slider->save();
                        }
                    }
                    $output = ['type'=>'success', 'message'=>'Successfully create Slider'];
                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
            }
            return response()->json($output);
        }
        $data['title'] = 'Create Slider';
        return view('admin.home.slider', $data);
    }
    public function edit(Request $request, $id)
    {
        $slider = \App\Models\Slider::find($id);

        if ($request->ajax()) {

            if (blank($slider)) {
                return ['type'=>'redirect', 'url'=> route('admin.home.slider.create'),'message'=>'This slider is already deleted' ];
            }

            $validator = validator($request->all(), [
                "title"         =>"nullable|string",
                "sub_title"     =>"nullable|string",
                "caption"       =>"nullable|string",
                "link"          =>"nullable|url",
                "thumnanail"    =>"nullable|image|mimes:jpg,jpeg,png|max:2048",
            ]);

            if ($validator->fails()) {
                $output = ['type'=>'error', 'message'=> $validator->errors()->all()];
            } else {

                $slider->title = (empty($request->title)) ? NULL : strip_tags($request->title);
                $slider->sub_title = (empty($request->sub_title)) ? NULL : strip_tags($request->sub_title);
                $slider->caption = (empty($request->caption)) ? NULL : strip_tags($request->caption);
                $slider->link = (empty($request->link)) ? NULL : strip_tags($request->link);

                if ($slider->save()) {

                    $old_path = $slider->thumnanail;
                    if (isset($_FILES['thumnanail'])) {
                        if ($_FILES['thumnanail']['error'] == 0) {
                            $slider->thumnanail = $request->file('thumnanail')->store('uploads');
                            $slider->save();

                            if (!empty($old_path)) {
                                if (is_file(public_path($old_path))) {
                                    unlink(public_path($old_path));
                                }
                            }
                        }
                    }

                    $files['thumnanail'] = (empty($slider->thumnanail)) ? NULL : asset($slider->thumnanail) ;

                    $output = ['type'=>'success', 'message'=>'Successfully update Slider', 'files'=> $files];
                } else {
                    $output = ['type'=>'error', 'message'=> 'try again..'];
                }
            }
            return response()->json($output);
        }
        if (blank($slider)) {
            return redirect()->route('admin.home.slider.create');
        }
        $data['title'] = 'Edit Slider';
        $data['slider'] = $slider;
        return view('admin.home.slider', $data);
    }
}
