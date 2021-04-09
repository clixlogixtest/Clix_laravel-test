<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AddonService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AddonServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $addon_services = AddonService::latest()->get();
        return view('admin.addon_services.index', compact('addon_services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.addon_services.create');
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
            'price' => 'required|numeric',
            'period_value' => 'required|integer',
        ]);

        $data=[
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'price' => $request->price,
            'period_type' => $request->period_type,
            'period_value' => $request->period_value,
        ];

        $addonService=AddonService::create($data);

        if($addonService)
        {
            return redirect()->route('admin.addon_services.index')->with('success','Addon Service successfully added.');
        }
        else
        {
            return redirect()->back()->with('error','Whoops! Something went wrong.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AddonService  $addonService
     * @return \Illuminate\Http\Response
     */
    public function show(AddonService $addonService)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AddonService  $addonService
     * @return \Illuminate\Http\Response
     */
    public function edit(AddonService $addonService)
    {
        return view('admin.addon_services.edit', compact('addonService'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AddonService  $addonService
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AddonService $addonService)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'period_value' => 'required|integer',
            'status' => 'in:active,inactive',
        ]);

        $data=[
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'price' => $request->price,
            'period_type' => $request->period_type,
            'period_value' => $request->period_value,
            'status' => $request->status,
        ];

        $addonService->update($data);

        if($addonService)
        {
            return redirect()->route('admin.addon_services.index')->with('success','Addon Service successfully updated.');
        }
        else
        {
            return redirect()->back()->with('error','Whoops! Something went wrong.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AddonService  $addonService
     * @return \Illuminate\Http\Response
     */
    public function destroy(AddonService $addonService)
    {
        $addonService->delete();

        return redirect()->route('admin.addon_services.index')->with('success','Addon Service successfully deleted.');
    }
}
