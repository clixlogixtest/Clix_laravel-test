<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\administrator;
use Illuminate\Http\Request;
use Auth;
use View;
use Storage;
use App\Model\page_how_to_plays;
use App\PasswordReset;
use Validator;
use App\Http\Requests\How_to_playRequest;

class How_to_playController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role != 'global_administrator'){
          return view('pages.page-403');
        }

        $play= page_how_to_plays::orderBy('id', 'desc')->get();

        $breadcrumbs = [];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        return view('pages.page-how_to_play-view', ['pageConfigs' => $pageConfigs, 'play' => $play], ['breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(How_to_playRequest $request)
    {

        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role != 'global_administrator'){
          return view('pages.page-403');
        }
        
        
        $id = auth()->user()->id;
        $input = $request->all();

         $setting = page_how_to_plays::orderBy('id', 'desc')->first();
        //print_r($setting);
        if ($setting) {
            
            $play = page_how_to_plays::where('id', $setting['id'])->first();
            
            if($input['content']){
                $play->content = $input['content'];
            }
            $play->status = 1;
            $play->update();
            return redirect(route('how_to_play.index'))->with(['message' => 'The how to plays is updated!']);
            
        }else{

            $play = new page_how_to_plays;
            $play->content = $input['content'];
            $play->status = 1;
            $play->save();
            return redirect(route('how_to_play.index'))->with(['message' => 'The how to plays is created!']);

        }

        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
