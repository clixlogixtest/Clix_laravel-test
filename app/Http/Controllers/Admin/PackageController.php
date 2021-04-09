<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PackageDetail;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    use ImageUploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = Package::latest()->get();
        return view('admin.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.packages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'content.*' => 'required',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048',
            'period_value' => 'required|integer',
        ]);

        $data=[
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'price' => $request->price,
            'image' => $this->saveImage($request,'packages'),
            'period_type' => $request->period_type,
            'period_value' => $request->period_value,
        ];

        $package=Package::create($data);

        if($package)
        {
            $contents=$request->content;
            foreach ($contents as $key => $content) 
            {
                $package_detail_data=[
                    'package_id' => $package->id,
                    'content' => $content,
                ];

                $package_detail=PackageDetail::create($package_detail_data);
            }

            return redirect()->route('admin.packages.index')->with('success','Package successfully added.');
        }
        else
        {
            return redirect()->back()->with('error','Whoops! Something went wrong.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function show(Package $package)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Package $package)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'content.*' => 'required',
            'price' => 'required|numeric',
            'image' => 'image|mimes:jpg,png,jpeg,gif|max:2048',
            'period_value' => 'required|integer',
            'status' => 'in:active,inactive',
        ]);

        $data=[
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'price' => $request->price,
            'image' => $this->saveImage($request,'packages',$package->image),
            'period_type' => $request->period_type,
            'period_value' => $request->period_value,
            'status' => $request->status,
        ];

        $package->update($data);

        if($package)
        {
            $package_detail_id_arr=[];
            if($request->has('package_detail_id'))
            {
                $package_detail_id_arr=$request->package_detail_id;
            }

            $contents=$request->content;
            foreach ($contents as $key => $content) 
            {
                $package_detail_data=[
                    'package_id' => $package->id,
                    'content' => $content,
                ];
                if(!empty($package_detail_id_arr))
                {
                    $package_detail=PackageDetail::where('id',$package_detail_id_arr[$key])->update($package_detail_data);
                }
                else
                {
                    $package_detail=PackageDetail::create($package_detail_data);
                }
            }

            return redirect()->route('admin.packages.index')->with('success','Package successfully updated.');
        }
        else
        {
            return redirect()->back()->with('error','Whoops! Something went wrong.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function destroy(Package $package)
    {
        $this->unlinkImage('packages', $package->image);
        
        $package->package_details()->delete();
        $package->delete();

        return redirect()->route('admin.packages.index')->with('success','Package successfully deleted.');
    }
}
