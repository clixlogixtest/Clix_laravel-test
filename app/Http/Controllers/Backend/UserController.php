<?php

namespace App\Http\Controllers\Backend;

/*use App\Http\Controllers\Controller;
use Illuminate\Http\Request;*/

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
use App\Notifications\PlayerWalletBalanceUpdated;
use App\User;
use App\Model\Logs;
use App\Model\Organisations;
use App\PasswordReset;
use Validator;
use App\Http\Requests\UserRequest;
use Carbon\Carbon;
use Hash;
use Illuminate\Support\Facades\Response as FacadeResponse;

class UserController extends Controller
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

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'user_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator' || auth()->user()->role == 'user_administrator,prize_administrator'){

        $userTotal= User::where([['role', '!=', 'global_administrator'],['role', 'NOT LIKE', '%' .'organisation_administrator'.'%'], ['organisation_id', '=', auth()->user()->organisation_id]])->orderBy('id', 'desc')->paginate(25);

        

        $breadcrumbs = [];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        return view('pages.page-users-list', ['pageConfigs' => $pageConfigs, 'userTotal' => $userTotal], ['breadcrumbs' => $breadcrumbs]);
        }else{
              return view('pages.page-403');
        }
    }

    public function playerList(Request $request)
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'user_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator' || auth()->user()->role == 'user_administrator,prize_administrator'){

          $input = $request->all(); 
          $search_field  =  @$input['search_field'];
          $search        =  @$input['search'] ? $input['search'] : '';
          
          $where = [['role', '=', 'player'], ['organisation_id', '=', auth()->user()->organisation_id]];
          if($search_field == 'first_name') {
             $where[] = ['first_name', 'LIKE', '%'.$search.'%'];
          }elseif ($search_field == 'surname') {
            $where[] = ['surname', 'LIKE', '%'.$search.'%'];
          }elseif ($search_field == 'town') {
            $where[] = ['town', 'LIKE', '%'.$search.'%'];
          }elseif ($search_field == 'email') {
            $where[] = ['email', 'LIKE', '%'.$search.'%'];
          }

        $userTotal= User::where($where)->orderBy('id', 'desc')->paginate(25);

        

        $breadcrumbs = [];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        return view('pages.page-usersPlayer-list', ['pageConfigs' => $pageConfigs, 'userTotal' => $userTotal, 'search_field' => $search_field, 'search' => $search], ['breadcrumbs' => $breadcrumbs]);

        }else{
              return view('pages.page-403');
        }
    }

    public function getAllUserInCSV(Request $request)
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'user_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator' || auth()->user()->role == 'user_administrator,prize_administrator'){

        $input = $request->all(); 
        $result =  @$input['result']; 
        
        if($result == 'player'){
          $userTotal= User::where([['role', '=', 'player'], ['organisation_id', '=', auth()->user()->organisation_id]])->orderBy('id', 'desc')->get();
          $filename = storage_path('app/public/competitionsCSV/users'.strtotime('now').'.csv');
          $handle = fopen($filename, 'w+');
          fputcsv($handle, array('Name', 'Email Address', 'Coins'));
          $userTotal = json_encode($userTotal);
          $userTotal = json_decode($userTotal, true);
          foreach($userTotal as $user){ 
              fputcsv($handle, array($user['first_name'].' '.$user['surname'], $user['email'], $user['total_coin']));
          }

          fclose($handle);

          $headers = array(
              'Content-Type' => 'text/csv',
          );
           //return Storage::download('file.jpg', $name, $headers);  
          return FacadeResponse::download($filename, 'users.csv', $headers);
        }else{

          $userTotal= User::where([['role', '!=', 'global_administrator'],['role', 'NOT LIKE', '%' .'organisation_administrator'.'%'],['role', '!=', 'player'], ['organisation_id', '=', auth()->user()->organisation_id]])->orderBy('id', 'desc')->get();
          $filename = storage_path('app/public/competitionsCSV/users'.strtotime('now').'.csv');
          $handle = fopen($filename, 'w+');
          fputcsv($handle, array('Name', 'Email Address', 'Organisation', 'Role'));
          $userTotal = json_encode($userTotal);
          $userTotal = json_decode($userTotal, true);
          foreach($userTotal as $user){ 
            $role = $user['role'];
            $role = explode(',', $role);
            $roles = '';
            if(in_array("competition_administrator", $role)){

                if($roles){
                  $roles .= ', '; 
                }
                
                $roles .= 'Competition Admin'; 
                
            }

            if( in_array("user_administrator", $role)){
              
              if($roles){
                $roles .= ', '; 
              }
               
              $roles .= 'User Admin';  
                
            }

            if(in_array("organisation_administrator", $role)){
                if($roles){
                  $roles .= ', '; 
                }  
                $roles .= 'Organisation Admin';   
                
            }

            if(in_array("prize_administrator", $role)){
                
                if($roles){
                  $roles .= ', '; 
                }
                $roles .= 'Prize Admin';  
                
            }

            if(in_array("player", $role)){

                if($roles){
                  $roles .= ', '; 
                }
                $roles .= 'Player';
                
            }

              $org = DB::Table('organisations')->select('organisation_name')->where('organisation_id', '=', $user['organisation_id'])->get();
              fputcsv($handle, array($user['first_name'].' '.$user['surname'], $user['email'], $org['0']->organisation_name, $roles));
          }

          fclose($handle);

          $headers = array(
              'Content-Type' => 'text/csv',
          );
           //return Storage::download('file.jpg', $name, $headers);  
          return FacadeResponse::download($filename, 'users.csv', $headers);

        }
        //print_r($competitionList);die();

        

        

        }else{
              return view('pages.page-403');
        }
    }

    public function administratorList(Request $request)
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'user_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator' || auth()->user()->role == 'user_administrator,prize_administrator'){

          $input = $request->all(); 
          $search_field  =  @$input['search_field'];
          $search        =  @$input['search'] ? $input['search'] : '';
          
          $where = array();
          $where[] = array('role', '!=', 'global_administrator');
          $where[] = array('role', 'NOT LIKE', '%' .'organisation_administrator'.'%');
          $where[] = array('role', '!=', 'player');
          $where[] = array('organisation_id', '=', auth()->user()->organisation_id);
          if($search_field == 'first_name') {
             $where[] = array('first_name', 'LIKE', '%'.$search.'%');
          }elseif ($search_field == 'surname') {
            $where[] = array('surname', 'LIKE', '%'.$search.'%');
          }elseif ($search_field == 'town') {
            $where[] = array('town', 'LIKE', '%'.$search.'%');
          }elseif ($search_field == 'email') {
            $where[] = array('email', 'LIKE', '%'.$search.'%');
          }

          //print_r($where);
            
        $userTotal= User::where($where)->orderBy('id', 'desc')->paginate(25);

        //print_r($userTotal);

        

        $breadcrumbs = [];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        return view('pages.page-usersAdministrator-list', ['pageConfigs' => $pageConfigs, 'userTotal' => $userTotal, 'search_field' => $search_field, 'search' => $search], ['breadcrumbs' => $breadcrumbs]);

        }else{
              return view('pages.page-403');
        }
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

      if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'user_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator' || auth()->user()->role == 'user_administrator,prize_administrator'){

      $organisationList= Organisations::orderBy('organisation_id', 'desc')->get();

      $breadcrumbs = [['link' => "playerList", 'name' => "Users"], ['name' => "Add a User"]];
      //Pageheader set true for breadcrumbs
      $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
      return view('pages.page-users-add', ['pageConfigs' => $pageConfigs,  'organisationList' => $organisationList], ['breadcrumbs' => $breadcrumbs]);

      }else{
              return view('pages.page-403');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'user_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator' || auth()->user()->role == 'user_administrator,prize_administrator'){

        $validator = Validator::make($request->all(), [ 
            'first_name' => 'required', 
            'surname' => 'required', 
            'email' => 'required|email|unique:users', 
            'contact_number' => 'required',
            'role' => 'required',
            'date_of_birth' => 'required|date_format:d/m/Y|before:18 years ago', 
            'address' => 'required', 
            'town' => 'required', 
            'post_code' => 'required', 
            'status' => 'required', 

        ],[
          'role.required' => 'The roles field is required.',
          'date_of_birth.required' => 'Please enter Date of Birth.',
          'date_of_birth.before' => 'User must be over 18 to register.',
          'post_code.required' => 'Please enter Postcode.', 
          'status.required' => 'Please activate Acceptd terms and conditions.', 
        ]);
        if($validator->fails()){ 
            
            return redirect()->back()->withInput()->withErrors($validator); 
        }

        $input = $request->all(); 
        $role= $input['role'];
        
        if((in_array("competition_administrator", $role) || in_array("user_administrator", $role)  || in_array("organisation_administrator", $role) || in_array("prize_administrator", $role)) && in_array("player", $role)){
            
            return redirect()->back()->withInput()->withErrors(["error" => "Please select either Player or other roles. You can not select player with other roles."]); 
            
        }

        /*if(!Postcode::postcodeLookup($input['post_code'])){
          return redirect()->back()->withInput()->withErrors(["error" => "Please enter valid Postcode."]); 
        }*/
        
        $roles = implode(",", $role);
        $redirect = 'users.playerList';
        if($roles != 'player'){
          $redirect = 'users.administratorList';
        }
        $input['role'] = $roles; 
        $date_of_birth = str_replace('/', '-', $input['date_of_birth']);
        $date_of_birth = strtotime($date_of_birth);
        $date_of_birth= date('Y-m-d H:i:s', $date_of_birth);
        $input['date_of_birth'] = $date_of_birth;


        //$pass= str_random(12);
        $email= $input['email'];
        $pass= $this->randomPassword(12);
        $input['password1'] = bcrypt($pass); 
        $user = new User;
        $user->role = $roles;
        $user->first_name = $input['first_name'];
        $user->surname = $input['surname'];
        $user->email = $input['email'];
        $user->date_of_birth = $date_of_birth;
        $user->address = $input['address'];
        $user->town = $input['town'];
        $user->post_code = $input['post_code'];
        $user->contact_number = $input['contact_number'];
        $user->organisation_id = auth()->user()->organisation_id;
        $user->status = $input['status'];
        
        
        $user->password = bcrypt($pass);
        $user->save();
        $user->notify(new NewUserPasswordSendSuccessfully($pass, $email));

        $current_date_time = Carbon::now()->toDateTimeString();
        $first_name = auth()->user()->first_name; 
        $surname = auth()->user()->surname;
        $email = auth()->user()->email;

        $Logs = new Logs();
        $Logs->log_category = 'User';
        $Logs->log_category_id = $user->id;
        $Logs->date = $current_date_time;
        $Logs->timestamp = $current_date_time;
        $Logs->users_name = $first_name.' '.$surname;
        $Logs->email_id = $email;
        $Logs->description = 'A user account is created';
        $Logs->log_details = $user;
        $Logs->status = 1;
        $Logs->save();


        return redirect(route($redirect))->with(['message' => 'The user account is created!']);

        }else{
              return view('pages.page-403');
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

      if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'user_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator' || auth()->user()->role == 'user_administrator,prize_administrator'){
        $breadcrumbs = [];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        return view('pages.page-users-view', ['pageConfigs' => $pageConfigs], ['breadcrumbs' => $breadcrumbs]);

        }else{
              return view('pages.page-403');
        }
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

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'user_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator' || auth()->user()->role == 'user_administrator,prize_administrator'){
        
        $user = User::where([['id', '=', $id], ['organisation_id', '=', auth()->user()->organisation_id]])->get();

        $log = Logs::where([['log_category', '=', 'User'], ['log_category_id', '=', $id]])->orderBy('created_at', 'desc')->get();
        
        $organisationList= Organisations::orderBy('organisation_id', 'desc')->get();

        $breadcrumbs = [['link' => "playerList", 'name' => "Users"], ['name' => "Edit a User"]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
        return view('pages.page-users-edit', ['pageConfigs' => $pageConfigs, 'user' => $user, 'log' => $log, 'organisationList' => $organisationList], ['breadcrumbs' => $breadcrumbs]);

        }else{
              return view('pages.page-403');
        }
    }

    public function editUser($id)
    {

      if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'user_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator' || auth()->user()->role == 'user_administrator,prize_administrator'){
        
        $user = User::where([['id', '=', $id], ['organisation_id', '=', auth()->user()->organisation_id]])->get();

        $log = Logs::where([['log_category', '=', 'User'], ['log_category_id', '=', $id]])->orderBy('created_at', 'desc')->get();
        
        $organisationList= Organisations::orderBy('organisation_id', 'desc')->get();
        
        $redirect = 'playerList';
        if($user['0']->role != 'player'){

          $redirect = 'administratorList';

        }

        $breadcrumbs = [['link' => $redirect, 'name' => "Users"], ['name' => "Edit a User"]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
        return view('pages.page-users-edit', ['pageConfigs' => $pageConfigs, 'user' => $user, 'log' => $log, 'organisationList' => $organisationList], ['breadcrumbs' => $breadcrumbs]);

        }else{
              return view('pages.page-403');
        }
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
        
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        $user = User::where('id',$id)->first();

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'user_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator' || auth()->user()->role == 'user_administrator,prize_administrator'){

        $validator = Validator::make($request->all(), [ 
            'first_name' => 'required', 
            'surname' => 'required', 
            'email' => 'required|email|unique:users,email,'.$user->id, 
            'contact_number' => 'required',
            'role' => 'required',
            'date_of_birth' => 'required|date_format:d/m/Y|before:18 years ago', 
            'address' => 'required', 
            'town' => 'required', 
            'post_code' => 'required', 
            'status' => 'required', 

        ],[
          'role.required' => 'The roles field is required.',
          'date_of_birth.required' => 'Please enter Date of Birth.',
          'date_of_birth.before' => 'User must be over 18 to register.',
          'post_code.required' => 'Please enter Postcode.', 
          'status.required' => 'Please activate Acceptd terms and conditions.', 
        ]);
        if($validator->fails()){ 
            
            return redirect()->back()->withInput()->withErrors($validator); 
        }

        $input = $request->all(); 
        $role= $input['role'];
        
        if((in_array("competition_administrator", $role) || in_array("user_administrator", $role)  || in_array("organisation_administrator", $role) || in_array("prize_administrator", $role)) && in_array("player", $role)){
            
            return redirect()->back()->withInput()->withErrors(["error" => "Please select either Player or other roles. You can not select player with other roles."]); 
            
        }
        
        $roles = implode(",", $role);

        $redirect = 'users.playerList';
        if($roles != 'player'){
          $redirect = 'users.administratorList';
        }
        
        $input['role'] = $roles; 
        
        $date_of_birth = str_replace('/', '-', $input['date_of_birth']);
        $date_of_birth= date('Y-m-d H:i:s', strtotime($date_of_birth));
        $input['date_of_birth'] = $date_of_birth;

        $email= $input['email'];

        $current_date_time = Carbon::now()->toDateTimeString();
        $first_name = auth()->user()->first_name; 
        $surname = auth()->user()->surname;
        $email = auth()->user()->email;

        $Logs = new Logs();
        $Logs->log_category = 'User';
        $Logs->log_category_id = $id;
        $Logs->date = $current_date_time;
        $Logs->timestamp = $current_date_time;
        $Logs->users_name = $first_name.' '.$surname;
        $Logs->email_id = $email;
        $Logs->description = 'A user account is updated';

        
        $Logs->log_before_changes = $user;
        $Logs->status = 1;
        $Logs->save();
        $log_id = $Logs->log_id;

        $LogsUpdate = Logs::where('log_id',$log_id)->first();

        $user->role = $roles;
        $user->first_name = $input['first_name'];
        $user->surname = $input['surname'];
        if($user->email != $input['email']){
          $user->email = $input['email'];
        }
        
        $user->date_of_birth = $date_of_birth;
        $user->address = $input['address'];
        $user->town = $input['town'];
        $user->post_code = $input['post_code'];
        $user->contact_number = $input['contact_number'];
        $user->status = $input['status'];
        $user->organisation_id = auth()->user()->organisation_id;
        $user->update();
        $user->notify(new UserAccountUpdatedSuccessfully());

        

        $LogsUpdate->log_details = $user;
        $LogsUpdate->update();      

        return redirect(route($redirect))->with(['message' => 'The user account is updated successfully!']);

        }else{
              return view('pages.page-403');
        }

    }

    public function playerWalletBalanceUpdate(Request $request, $id)
    {
        
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        $user = User::where('id',$id)->first();

        if(auth()->user()->role == 'user_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'user_administrator,prize_administrator' || auth()->user()->role == 'organisation_administrator'){

        

        $input = $request->all(); 
        $current_date_time = Carbon::now()->toDateTimeString();
        $first_name = auth()->user()->first_name; 
        $surname = auth()->user()->surname;
        $email = auth()->user()->email;

        $Logs = new Logs();
        $Logs->log_category = 'User';
        $Logs->log_category_id = $id;
        $Logs->date = $current_date_time;
        $Logs->timestamp = $current_date_time;
        $Logs->users_name = $first_name.' '.$surname;
        $Logs->email_id = $email;
        $Logs->description = 'Player wallet balance updated';

        
        $Logs->log_before_changes = $user;
        $Logs->status = 1;
        $Logs->save();
        $log_id = $Logs->log_id;

        $LogsUpdate = Logs::where('log_id',$log_id)->first();

        
        
        $user->total_coin = $input['wallet_balance'];
        $user->update();
        $user->notify(new PlayerWalletBalanceUpdated($input['wallet_balance']));

        

        $LogsUpdate->log_details = $user;
        $LogsUpdate->update();      

        return redirect()->back()->with(['message' => 'Player wallet balance updated.']);

        }else{
              return view('pages.page-403');
        }

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

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'user_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator' || auth()->user()->role == 'user_administrator,prize_administrator'){



        $user = User::find($id);
        $redirect = 'users.playerList';
        if($user->role != 'player'){
          $redirect = 'users.administratorList';
        }
        $user->delete();
        return redirect(route($redirect))->with(['message' => 'The user is deleted!']);

        }else{
              return view('pages.page-403');
        }
    }

    public function userProfile($id)
    {

      if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        
        
        $user = User::where([['id', '=', $id]])->get();

        $organisation_id = auth()->user()->organisation_id;
        
        $organisationList= '';
        if($organisation_id){

          $organisationList= Organisations::orderBy('organisation_id', 'desc')->get();

        }
        

        $redirect = 'playerList';
        if($user['0']->role != 'player'){

          $redirect = 'administratorList';

        }
        

        $breadcrumbs = [['link' => $redirect, 'name' => "Users"], ['name' => "User Profile"]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
        return view('pages.page-userProfile-view', ['pageConfigs' => $pageConfigs, 'user' => $user, 'organisationList' => $organisationList], ['breadcrumbs' => $breadcrumbs]);

        
    }

    public function userProfileUpdate(Request $request, $id){

      $user = User::where('id',$id)->first();
      $input = $request->all(); 
      
      if($input['first_name']){
        $user->first_name = $input['first_name'];
      }
      
      if($input['surname']){
        $user->surname = $input['surname'];
      }
      
      if($input['email']){
        $user->email = $input['email'];
      }

      /*if($input['password']){
        $user->password = bcrypt($input['password']);
      }*/

      $user->update();
      $user->notify(new UserAccountUpdatedSuccessfully());
      return redirect()->back()->withInput()->with(['message' => 'The user account is updated successfully!']);

    }

    public function changePassword($id)
    {

      if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        
        
        $user = User::where([['id', '=', $id]])->get();

        $organisation_id = auth()->user()->organisation_id;
        
        $organisationList= '';
        if($organisation_id){

          $organisationList= Organisations::orderBy('organisation_id', 'desc')->get();

        }
        

        $redirect = 'playerList';
        if($user['0']->role != 'player'){

          $redirect = 'administratorList';

        }
        

        $breadcrumbs = [['link' => $redirect, 'name' => "Users"], ['name' => "Change Password"]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
        return view('pages.page-changePassword-view', ['pageConfigs' => $pageConfigs, 'user' => $user, 'organisationList' => $organisationList], ['breadcrumbs' => $breadcrumbs]);

        
    }

    public function changePasswordUpdate(Request $request, $id){

      $validatedData = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'confirm_new_password' => 'required|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/|same:new_password',
        ]);

      if($validatedData->fails()){
            return redirect()->back()->withInput()->withErrors($validator); 
        } 

      $user = User::where('id',$id)->first();
      $input = $request->all(); 

       if (!(Hash::check($input['current_password'], $user->password))) {
            // The passwords matches
            return redirect()->back()->withInput()->withErrors(["error" => "Your current password does not matches with the password you provided. Please try again."]);
        }

        if(strcmp($input['current_password'], $input['new_password']) == 0){
            //Current password and new password are same
            return redirect()->back()->withInput()->withErrors(["error" => "New Password cannot be same as your current password. Please choose a different password."]);
        }

        
      
      $user->password = bcrypt($input['new_password']);

      $user->update();
      $user->notify(new UserAccountUpdatedSuccessfully());
      return redirect()->back()->withInput()->with(['message' => 'The password changed successfully!']);

    }

    public function randomPassword($len = 8) {

        //enforce min length 8
        if($len < 8)
            $len = 8;

        //define character libraries - remove ambiguous characters like iIl|1 0oO
        $sets = array();
        $sets[] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $sets[] = 'abcdefghijklmnopqrstuvwxyz';
        $sets[] = '0123456789';
        $sets[]  = '~!@#$%^&*(){}[],./?';

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
