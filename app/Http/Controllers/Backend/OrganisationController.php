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
use App\Notifications\NewUserPasswordSendSuccessfully;
use App\Notifications\UserAccountUpdatedSuccessfully;
use App\Model\Organisations;
use App\Model\Logs;
use App\PasswordReset;
use Validator;
use App\Http\Requests\OrganisationRequest;
use App\Http\Requests\OrganisationUpdateRequest;
use Carbon\Carbon;

class OrganisationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    	if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role != 'global_administrator'){
          return view('pages.page-403');
        }
        
        /*$model = $request->keyable;
        print_r($model);*/

        $organisationList= Organisations::orderBy('organisation_id', 'desc')->paginate(25);

        $breadcrumbs = [];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        return view('pages.page-organisations-list', ['pageConfigs' => $pageConfigs, 'organisationList' => $organisationList], ['breadcrumbs' => $breadcrumbs]);
    }

    public function autocompleteOrganisation(Request $request){

        $input = $request->all();


        $Organisations = Organisations::select("organisation_name")
                ->where([["organisation_name","LIKE",'%'.$input['query'].'%']])
                ->get();

        $data = array();
        foreach ($Organisations as $Organisation)
            {
                $data[] = $Organisation->organisation_name;
            }
   
        return response()->json($data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role != 'global_administrator'){
          return view('pages.page-403');
        }

        $breadcrumbs = [['link' => "organisations", 'name' => "Organisations"], ['name' => "Add a Organisation"]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        return view('pages.page-organisations-add', ['pageConfigs' => $pageConfigs], ['breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrganisationRequest $request)
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
        if($request->hasFile('organisation_logo')){
            $profilePic = $request->file('organisation_logo');
            $avatarName = '_organisation_logo_'.time().'.'.$profilePic->getClientOriginalExtension();

            $request->organisation_logo->storeAs('organisationLogo',$avatarName);
            $destinationPath = url('storage/organisationLogo/');
            $logo = asset('storage/organisationLogo/'.$avatarName);
        }

        $terms = '';
        if($request->hasFile('terms_and_conditions')){
            $profilePic = $request->file('terms_and_conditions');
            $avatarName = '_terms_and_conditions_'.time().'.'.$profilePic->getClientOriginalExtension();

            $request->terms_and_conditions->storeAs('terms_and_conditions',$avatarName);
            $destinationPath = url('storage/terms_and_conditions/');
            $terms = asset('storage/terms_and_conditions/'.$avatarName);
        }


/*        $model = $request->keyable;
        print_r($model); die();*/



        $Organisations = new Organisations;
        $Organisations->organisation_name = $input['organisation_name'];
        $Organisations->company_registration_number = $input['uk_company_registration_number'];
        $Organisations->address = $input['address'];
        $Organisations->image = $logo;
        $Organisations->post_code = $input['postcode'];
        $Organisations->phone_number = $input['phone'];
        $Organisations->website_uri = $input['competition_website_url'];
        $Organisations->player_wallet_balance = $input['player_start_wallet_balance'];
        $Organisations->placeholder_video_uri = $input['placeholder_draw_video'];
        $Organisations->terms_and_conditions_document = $terms;
        $Organisations->payment_gateway_id = $input['paypal_api_credentials'];
        $Organisations->payment_gateway_secret_key = $input['payment_gateway_secret_key'];
        //$Organisations->payment_gateway_type = $input['organisation_name'];
        $Organisations->created_by = $id;
        $Organisations->api_key = $this->apiKey(16);
        $Organisations->status = 1;
        $Organisations->save();

        $current_date_time = Carbon::now()->toDateTimeString();
        $first_name = auth()->user()->first_name; 
        $surname = auth()->user()->surname;
        $email = auth()->user()->email;

        $Logs = new Logs();
        $Logs->log_category = 'Organisation';
        $Logs->log_category_id = $Organisations->organisation_id;
        $Logs->date = $current_date_time;
        $Logs->timestamp = $current_date_time;
        $Logs->users_name = $first_name.' '.$surname;
        $Logs->email_id = $email;
        $Logs->description = 'A organisation is created';
        $Logs->log_details = $Organisations;
        $Logs->status = 1;
        $Logs->save();

        return redirect(route('organisations.index'))->with(['message' => 'The organisation is created!']);
       
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
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role != 'global_administrator'){
          return view('pages.page-403');
        }

        $organisation = Organisations::where('organisation_id',$id)->get();

        $log = Logs::where([['log_category', '=', 'Organisation'], ['log_category_id', '=', $id]])->orderBy('created_at', 'desc')->get();

        $breadcrumbs = [['link' => "organisations", 'name' => "Organisations"], ['name' => "Edit a Organisation"]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
        return view('pages.page-organisations-edit', ['pageConfigs' => $pageConfigs, 'organisation' => $organisation, 'log' => $log], ['breadcrumbs' => $breadcrumbs]);
    }

    public function editOrganisation($id)
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role != 'global_administrator'){
          return view('pages.page-403');
        }

        $organisation = Organisations::where('organisation_id',$id)->get();

        $log = Logs::where([['log_category', '=', 'Organisation'], ['log_category_id', '=', $id]])->orderBy('created_at', 'desc')->get();

        $breadcrumbs = [['link' => "organisations", 'name' => "Organisations"], ['name' => "Edit a Organisation"]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
        return view('pages.page-organisations-edit', ['pageConfigs' => $pageConfigs, 'organisation' => $organisation, 'log' => $log], ['breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OrganisationUpdateRequest $request, $organisation_id)
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
        if($request->hasFile('organisation_logo')){

            $profilePic = $request->file('organisation_logo');
            $avatarName = '_organisation_logo_'.time().'.'.$profilePic->getClientOriginalExtension();

            $request->organisation_logo->storeAs('organisationLogo',$avatarName);
            $destinationPath = url('storage/organisationLogo/');
            $logo = asset('storage/organisationLogo/'.$avatarName);
        }

        $terms = '';
        if($request->hasFile('terms_and_conditions')){
            $profilePic = $request->file('terms_and_conditions');
            if($profilePic->getClientOriginalExtension() != 'html'){
                return redirect()->back()->withInput()->withErrors(["error" => "Please select html file."]); 
            }
            //echo $profilePic->getClientOriginalExtension(); die();
            $avatarName = '_terms_and_conditions_'.time().'.'.$profilePic->getClientOriginalExtension();

            $request->terms_and_conditions->storeAs('terms_and_conditions',$avatarName);
            $destinationPath = url('storage/terms_and_conditions/');
            $terms = asset('storage/terms_and_conditions/'.$avatarName);
        }


        $current_date_time = Carbon::now()->toDateTimeString();
        $first_name = auth()->user()->first_name; 
        $surname = auth()->user()->surname;
        $email = auth()->user()->email;

        $Logs = new Logs();
        $Logs->log_category = 'Organisation';
        $Logs->log_category_id = $organisation_id;
        $Logs->date = $current_date_time;
        $Logs->timestamp = $current_date_time;
        $Logs->users_name = $first_name.' '.$surname;
        $Logs->email_id = $email;
        $Logs->description = 'A organisation is updated';
        

        //$Organisations = new Organisations;
        $Organisations = Organisations::where('organisation_id',$organisation_id)->first();

        $Logs->log_before_changes = $Organisations;
        $Logs->status = 1;
        $Logs->save();
        $log_id = $Logs->log_id;

        $LogsUpdate = Logs::where('log_id',$log_id)->first();

        if($input['organisation_name']){
            $Organisations->organisation_name = $input['organisation_name'];
        }
        if($input['uk_company_registration_number']){
            $Organisations->company_registration_number = $input['uk_company_registration_number'];
        }
        if($input['address']){
            $Organisations->address = $input['address'];
        }
        if($logo){
            $Organisations->image = $logo;
        }
        if($input['postcode']){
            $Organisations->post_code = $input['postcode'];
        }
        if($input['phone']){
            $Organisations->phone_number = $input['phone'];
        }
        if($input['competition_website_url']){
            $Organisations->website_uri = $input['competition_website_url'];
        }
        if($input['player_start_wallet_balance']){
            $Organisations->player_wallet_balance = $input['player_start_wallet_balance'];
        }
        if($input['placeholder_draw_video']){
            $Organisations->placeholder_video_uri = $input['placeholder_draw_video'];
        }
        if($terms){
            $Organisations->terms_and_conditions_document = $terms;
        }
        if($input['paypal_api_credentials']){
            $Organisations->payment_gateway_id = $input['paypal_api_credentials'];
        }
        $api_key = $Organisations->api_key;
        if(!$api_key){
            $Organisations->api_key = $this->apiKey(16);
        }
        if($input['payment_gateway_secret_key']){
            $Organisations->payment_gateway_secret_key = $input['payment_gateway_secret_key'];
        }
        
        $Organisations->created_by = $id;
        $Organisations->status = 1;
        $Organisations->update();

        $LogsUpdate->log_details = $Organisations;
        $LogsUpdate->update(); 

        


        return redirect(route('organisations.index'))->with(['message' => 'The organisation is updated!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role != 'global_administrator'){
          return view('pages.page-403');
        }

        $organisation = Organisations::find($id);    
        $organisation->delete();

        $current_date_time = Carbon::now()->toDateTimeString();
        $first_name = auth()->user()->first_name; 
        $surname = auth()->user()->surname;
        $email = auth()->user()->email;

        $Logs = new Logs();
        $Logs->log_category = 'Organisation';
        $Logs->log_category_id = $id;
        $Logs->date = $current_date_time;
        $Logs->timestamp = $current_date_time;
        $Logs->users_name = $first_name.' '.$surname;
        $Logs->email_id = $email;
        $Logs->description = 'A organisation is deleted';
        $Logs->log_details = $organisation;
        $Logs->status = 1;
        $Logs->save();

       return redirect(route('organisations.index'))->with(['message' => 'The organisation is deleted!']);
    }

    public function organisationProfile($id)
    {

      if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role != 'organisation_administrator'){
          return view('pages.page-403');
        }

        $organisation_id = auth()->user()->organisation_id;

        $organisationList = Organisations::where('organisation_id', '=', $organisation_id)->get();

        $breadcrumbs = [['link' => "administratorList", 'name' => "Users"], ['name' => "Organisation Profile"]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
        return view('pages.page-organisationProfile-view', ['pageConfigs' => $pageConfigs, 'organisationList' => $organisationList], ['breadcrumbs' => $breadcrumbs]);

        
    }

    public function organisationProfileUpdate(Request $request, $organisation_id){

      if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role != 'organisation_administrator'){
          return view('pages.page-403');
        }

        $id = auth()->user()->id;        
        $input = $request->all();

        $logo = '';
        if($request->hasFile('organisation_logo')){

            $profilePic = $request->file('organisation_logo');
            $avatarName = '_organisation_logo_'.time().'.'.$profilePic->getClientOriginalExtension();

            $request->organisation_logo->storeAs('organisationLogo',$avatarName);
            $destinationPath = url('storage/organisationLogo/');
            $logo = asset('storage/organisationLogo/'.$avatarName);
        }

        $terms = '';
        if($request->hasFile('terms_and_conditions')){
            $profilePic = $request->file('terms_and_conditions');
            if($profilePic->getClientOriginalExtension() != 'html'){
                return redirect()->back()->withInput()->withErrors(["error" => "Please select html file."]); 
            }
            //echo $profilePic->getClientOriginalExtension(); die();
            $avatarName = '_terms_and_conditions_'.time().'.'.$profilePic->getClientOriginalExtension();

            $request->terms_and_conditions->storeAs('terms_and_conditions',$avatarName);
            $destinationPath = url('storage/terms_and_conditions/');
            $terms = asset('storage/terms_and_conditions/'.$avatarName);
        }


        $current_date_time = Carbon::now()->toDateTimeString();
        $first_name = auth()->user()->first_name; 
        $surname = auth()->user()->surname;
        $email = auth()->user()->email;

        $Logs = new Logs();
        $Logs->log_category = 'Organisation';
        $Logs->log_category_id = $organisation_id;
        $Logs->date = $current_date_time;
        $Logs->timestamp = $current_date_time;
        $Logs->users_name = $first_name.' '.$surname;
        $Logs->email_id = $email;
        $Logs->description = 'A organisation is updated';
        

        //$Organisations = new Organisations;
        $Organisations = Organisations::where('organisation_id',$organisation_id)->first();

        $Logs->log_before_changes = $Organisations;
        $Logs->status = 1;
        $Logs->save();
        $log_id = $Logs->log_id;

        $LogsUpdate = Logs::where('log_id',$log_id)->first();

        
        if($input['uk_company_registration_number']){
            $Organisations->company_registration_number = $input['uk_company_registration_number'];
        }
        if($input['address']){
            $Organisations->address = $input['address'];
        }
        if($logo){
            $Organisations->image = $logo;
        }
        if($input['postcode']){
            $Organisations->post_code = $input['postcode'];
        }
        if($input['phone']){
            $Organisations->phone_number = $input['phone'];
        }
        if($input['competition_website_url']){
            $Organisations->website_uri = $input['competition_website_url'];
        }
        if($input['player_start_wallet_balance']){
            $Organisations->player_wallet_balance = $input['player_start_wallet_balance'];
        }
        if($input['placeholder_draw_video']){
            $Organisations->placeholder_video_uri = $input['placeholder_draw_video'];
        }
        if($terms){
            $Organisations->terms_and_conditions_document = $terms;
        }
        if($input['paypal_api_credentials']){
            $Organisations->payment_gateway_id = $input['paypal_api_credentials'];
        }
        if($input['payment_gateway_secret_key']){
            $Organisations->payment_gateway_secret_key = $input['payment_gateway_secret_key'];
        }
        
        $Organisations->created_by = $id;
        $Organisations->status = 1;
        $Organisations->update();

        $LogsUpdate->log_details = $Organisations;
        $LogsUpdate->update(); 

        


        return redirect()->back()->withInput()->with(['message' => 'The organisation is updated!']);
        

    }

    public function apiKey($len = 8) {

        //enforce min length 8
        if($len < 8)
            $len = 8;

        //define character libraries - remove ambiguous characters like iIl|1 0oO
        $sets = array();
        $sets[] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $sets[] = 'abcdefghijklmnopqrstuvwxyz';
        $sets[] = '0123456789';
        //$sets[]  = '~!@#$%^&*(){}[],./?';

        $password = '';
        
        //append a character from each set - gets first 4 characters
        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
        }

        //use all characters to fill up to $len
        while(strlen($password) < $len) {
            //get a random set
            $randomSet = $sets[array_rand($sets)];
            
            //add a random char from the random set
            $password .= $randomSet[array_rand(str_split($randomSet))]; 
        }
        
        //shuffle the password string before returning!
        return str_shuffle($password);
    }

}
