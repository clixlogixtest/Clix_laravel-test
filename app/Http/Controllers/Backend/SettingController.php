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
use App\Model\Global_settings;
use App\Model\Logs;
use App\PasswordReset;
use Validator;
use App\Http\Requests\SettingRequest;
use Carbon\Carbon;

class SettingController extends Controller
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

        $setting= Global_settings::orderBy('id', 'desc')->get();

        $log = Logs::where([['log_category', '=', 'Setting'], ['log_category_id', '=', 1]])->get();

        $breadcrumbs = [];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        return view('pages.page-settings-list', ['pageConfigs' => $pageConfigs, 'setting' => $setting, 'log' => $log], ['breadcrumbs' => $breadcrumbs]);
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
    public function store(SettingRequest $request)
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

        $logo = '';
        if($request->hasFile('terms_and_condition')){
            $profilePic = $request->file('terms_and_condition');
            $avatarName = '_terms_and_condition_'.time().'.'.$profilePic->getClientOriginalExtension();

            $request->terms_and_condition->storeAs('terms_and_condition',$avatarName);
            $destinationPath = url('storage/terms_and_condition/');
            $logo = asset('storage/terms_and_condition/'.$avatarName);
        }

        $setting = Global_settings::orderBy('id', 'desc')->first();
        //print_r($setting);
        if ($setting) {

            $current_date_time = Carbon::now()->toDateTimeString();
            $first_name = auth()->user()->first_name; 
            $surname = auth()->user()->surname;
            $email = auth()->user()->email;

            $Logs = new Logs();
            $Logs->log_category = 'Setting';
            $Logs->log_category_id = $setting['id'];
            $Logs->date = $current_date_time;
            $Logs->timestamp = $current_date_time;
            $Logs->users_name = $first_name.' '.$surname;
            $Logs->email_id = $email;
            $Logs->description = 'A setting is updated';
            
            
            $Global_settings = Global_settings::where('id', $setting['id'])->first();

            $Logs->log_before_changes = $Global_settings;
            $Logs->status = 1;
            $Logs->save();
            $log_id = $Logs->log_id;

            $LogsUpdate = Logs::where('log_id',$log_id)->first();

        	$Global_settings->title = 'Global settings';
        	if( $logo){
	            $Global_settings->terms_and_condition = $logo;
	        }
	        if($input['placeholder_draw_video']){
	            $Global_settings->placeholder_draw_video = $input['placeholder_draw_video'];
	        }
            if($input['alternative_cash_prize_percentage']){
                $Global_settings->alternative_cash_prize_percentage = $input['alternative_cash_prize_percentage'];
            }
	        $Global_settings->created_by = $id;
	        $Global_settings->status = 1;
	        $Global_settings->update();

            $LogsUpdate->log_details = $Global_settings;
            $LogsUpdate->update(); 

            
	        return redirect(route('settings.index'))->with(['message' => 'The global setting is updated!']);
        	
        }else{

        	$Global_settings = new Global_settings;
        	$Global_settings->title = 'Global settings';
	        $Global_settings->terms_and_condition = $logo;
	        $Global_settings->placeholder_draw_video = $input['placeholder_draw_video'];
            $Global_settings->alternative_cash_prize_percentage = $input['alternative_cash_prize_percentage'];
	        $Global_settings->created_by = $id;
	        $Global_settings->status = 1;
	        $Global_settings->save();

            $Logs = new Logs();
            $Logs->log_category = 'Setting';
            $Logs->log_category_id = $setting['id'];
            $Logs->date = $current_date_time;
            $Logs->timestamp = $current_date_time;
            $Logs->users_name = $first_name.' '.$surname;
            $Logs->email_id = $email;
            $Logs->description = 'A setting is created';
            $Logs->log_details = $Global_settings;
            $Logs->status = 1;
            $Logs->save();

	        return redirect(route('settings.index'))->with(['message' => 'The global setting is created!']);

        }
        
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
