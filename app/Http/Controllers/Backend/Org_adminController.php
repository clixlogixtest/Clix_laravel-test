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
use App\User;
use App\Model\Logs;
use App\Model\Organisations;
use App\PasswordReset;
use Validator;
use App\Http\Requests\UserRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response as FacadeResponse;

class Org_adminController extends Controller
{
    /*public function __construct()
    {  //echo auth()->user()->role; die();
      if(auth()->user()->role != 'global_administrator'){
        return view('pages.page-403');
      }
    }*/
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

        $input = $request->all(); 
        //$result =  @$input['result'];
        $search_field  =  @$input['search_field'];
        //$search        =  @$input['search'] ? $input['search'] : '';
        $Search =  @$input['Search'] ? $input['Search'] : '';
        $search =  @$input['search'] ? $input['search'] : '';
        
        $filter = [];
        if($search_field == 'organisation_name') {
          $Organisations = Organisations::where('organisation_name', '=', $Search)->get();
          $Organisations = json_encode($Organisations);
          $Organisations = json_decode($Organisations, true);
          if($Organisations){
            $filter[] = ['role', 'like', '%' .'organisation_administrator'.'%'];
            $filter[] = ['organisation_id', '=', $Organisations['0']['organisation_id']];
          }else{
            $filter[] = ['role', 'like', '%' .'organisation_administrator'.'%'];
            $filter[] = ['organisation_id', '=', 0];
          }
          
        }elseif($search_field == 'first_name'){
            $filter[] = ['first_name', 'LIKE', '%'.$search.'%'];
            $filter[] = ['role', 'like', '%' .'organisation_administrator'.'%'];
        }elseif($search_field == 'surname'){
            $filter[] = ['surname', 'LIKE', '%'.$search.'%'];
            $filter[] = ['role', 'like', '%' .'organisation_administrator'.'%'];
        }elseif($search_field == 'town'){
            $filter[] = ['town', 'LIKE', '%'.$search.'%'];
            $filter[] = ['role', 'like', '%' .'organisation_administrator'.'%'];
        }elseif($search_field == 'email'){
            $filter[] = ['email', 'LIKE', '%'.$search.'%'];
            $filter[] = ['role', 'like', '%' .'organisation_administrator'.'%'];
        }else{
          $filter[] = ['role', 'like', '%' .'organisation_administrator'.'%'];
        }
        //print_r($filter);
        $userTotal= User::where($filter)->orderBy('id', 'desc')->paginate(25);

        

        $breadcrumbs = [];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        return view('pages.page-org_admins-list', ['pageConfigs' => $pageConfigs, 'userTotal' => $userTotal, 'search_field' => $search_field, 'Search' => $Search, 'search' => $search], ['breadcrumbs' => $breadcrumbs]);
    }

    public function getAllOrgAdminInCSV(Request $request)
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role != 'global_administrator'){
          return view('pages.page-403');
        }

        $input = $request->all(); 
        
        $Search =  @$input['Search'] ? $input['Search'] : '';
        
        $filter = [];
        if($Search) {
          $Organisations = Organisations::where('organisation_name', '=', $Search)->get();
          $Organisations = json_encode($Organisations);
          $Organisations = json_decode($Organisations, true);
          if($Organisations){
            $filter[] = ['role', 'like', '%' .'organisation_administrator'.'%'];
            $filter[] = ['organisation_id', '=', $Organisations['0']['organisation_id']];
          }else{
            $filter[] = ['role', 'like', '%' .'organisation_administrator'.'%'];
            $filter[] = ['organisation_id', '=', 0];
          }
          
        }else{
          $filter[] = ['role', 'like', '%' .'organisation_administrator'.'%'];
        }
        //print_r($filter); 
        $userTotal = User::where($filter)->orderBy('id', 'desc')->get();
        //print_r($userTotal); die();
          $filename = storage_path('app/public/competitionsCSV/org_admins'.strtotime('now').'.csv');
          $handle = fopen($filename, 'w+');
          fputcsv($handle, array('Name', 'Email Address', 'Organisation', 'Role'));
          $userTotal = json_encode($userTotal);
          $userTotal = json_decode($userTotal, true);
          foreach($userTotal as $user){ 
            $role = $user['role'];
            $role = explode(',', $role);
            $roles = '';
            if(in_array("organisation_administrator", $role)){
                if($roles){
                  $roles .= ', '; 
                }  
                $roles .= 'Organisation Admin';   
                
            }

            $org = DB::Table('organisations')->select('organisation_name')->where('organisation_id', '=', $user['organisation_id'])->get();

              fputcsv($handle, array($user['first_name'].' '.$user['surname'], $user['email'], $org['0']->organisation_name, $roles));
          }

          fclose($handle);

          $headers = array(
              'Content-Type' => 'text/csv',
          );
           //return Storage::download('file.jpg', $name, $headers);  
          return FacadeResponse::download($filename, 'org_admins.csv', $headers);
        
        //print_r($competitionList);die();

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
      
      $organisationList= Organisations::orderBy('organisation_id', 'desc')->get();

      $breadcrumbs = [['link' => "org_admins", 'name' => "Org Admins"], ['name' => "Add a User"]];
      //Pageheader set true for breadcrumbs
      $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
      return view('pages.page-org_admins-add', ['pageConfigs' => $pageConfigs,  'organisationList' => $organisationList], ['breadcrumbs' => $breadcrumbs]);
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

        if(auth()->user()->role != 'global_administrator'){
          return view('pages.page-403');
        }

        $validator = Validator::make($request->all(), [ 
            'organisation' => 'required', 
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
        
        /*if((in_array("competition_administrator", $role) || in_array("user_administrator", $role)  || in_array("organisation_administrator", $role) || in_array("prize_administrator", $role)) && in_array("player", $role)){
            
            return redirect()->back()->withInput()->withErrors(["error" => "Please select either Player or other roles. You can not select player with other roles."]); 
            
        }*/
        
        /*$roles = implode(",", $role);
        $input['role'] = $roles; */
        $date_of_birth = str_replace('/', '-', $input['date_of_birth']);
        $date_of_birth = strtotime($date_of_birth);
        $date_of_birth= date('Y-m-d H:i:s', $date_of_birth);
        $input['date_of_birth'] = $date_of_birth;


        //$pass= str_random(12);
        /*$email= $input['email'];
        $UserCheck = User::where([['organisation_id', '=', $input['organisation']], ['role', '=', $role]])->get(); 
        $UserCheck = json_encode($UserCheck);
        $UserCheck = json_decode($UserCheck);
        if ($UserCheck) {

          return redirect()->back()->withInput()->withErrors(["error" => "This organisation has already an organisation admin."]); 
          
        }*/
        $pass= $this->randomPassword(12);
        $input['password1'] = bcrypt($pass); 
        $user = new User;
        $user->role = $role;
        $user->organisation_id = $input['organisation'];
        $user->first_name = $input['first_name'];
        $user->surname = $input['surname'];
        $user->email = $input['email'];
        $user->date_of_birth = $date_of_birth;
        $user->address = $input['address'];
        $user->town = $input['town'];
        $user->post_code = $input['post_code'];
        $user->contact_number = $input['contact_number'];
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


        return redirect(route('org_admins.index'))->with(['message' => 'The user account is created!']);

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

        if(auth()->user()->role != 'global_administrator'){
          return view('pages.page-403');
        }

        $breadcrumbs = [];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        return view('pages.page-org_admins-view', ['pageConfigs' => $pageConfigs], ['breadcrumbs' => $breadcrumbs]);
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
        
        $user = User::where('id',$id)->get();

        $log = Logs::where([['log_category', '=', 'User'], ['log_category_id', '=', $id]])->orderBy('created_at', 'desc')->get();

        $organisationList= Organisations::orderBy('organisation_id', 'desc')->get();

        $breadcrumbs = [['link' => "org_admins", 'name' => "Org Admins"], ['name' => "Add a User"]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
        return view('pages.page-org_admins-edit', ['pageConfigs' => $pageConfigs, 'user' => $user, 'log' => $log, 'organisationList' => $organisationList], ['breadcrumbs' => $breadcrumbs]);
    }

    public function editOrg_admin($id)
    {
      if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role != 'global_administrator'){
          return view('pages.page-403');
        }
        
        $user = User::where('id',$id)->get();

        $log = Logs::where([['log_category', '=', 'User'], ['log_category_id', '=', $id]])->orderBy('created_at', 'desc')->get();

        $organisationList= Organisations::orderBy('organisation_id', 'desc')->get();

        $breadcrumbs = [['link' => "org_admins", 'name' => "Org Admins"], ['name' => "Add a User"]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
        return view('pages.page-org_admins-edit', ['pageConfigs' => $pageConfigs, 'user' => $user, 'log' => $log, 'organisationList' => $organisationList], ['breadcrumbs' => $breadcrumbs]);
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

        if(auth()->user()->role != 'global_administrator'){
          return view('pages.page-403');
        }

        $validator = Validator::make($request->all(), [ 
            'organisation' => 'required', 
            'first_name' => 'required', 
            'surname' => 'required', 
            'email' => 'required|email', 
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
        
        /*if((in_array("competition_administrator", $role) || in_array("user_administrator", $role)  || in_array("organisation_administrator", $role) || in_array("prize_administrator", $role)) && in_array("player", $role)){
            
            return redirect()->back()->withInput()->withErrors(["error" => "Please select either Player or other roles. You can not select player with other roles."]); 
            
        }*/
        
        /*$roles = implode(",", $role);
        $input['role'] = $roles; */
        
        $date_of_birth = str_replace('/', '-', $input['date_of_birth']);
        $date_of_birth= date('Y-m-d H:i:s', strtotime($date_of_birth));
        $input['date_of_birth'] = $date_of_birth;

        $email= $input['email'];

        /*$UserCheck = User::where([['organisation_id', '=', $input['organisation']], ['role', '=', $role]])->get();
        if ($UserCheck) {

          return redirect()->back()->withInput()->withErrors(["error" => "This organisation has already an organisation admin."]); 
          
        }*/

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

        $user = User::where('id',$id)->first();
        $Logs->log_before_changes = $user;
        $Logs->status = 1;
        $Logs->save();
        $log_id = $Logs->log_id;

        $LogsUpdate = Logs::where('log_id',$log_id)->first();

        $user->role = $role;
        $user->organisation_id = $input['organisation'];
        $user->first_name = $input['first_name'];
        $user->surname = $input['surname'];
        $user->email = $input['email'];
        $user->date_of_birth = $date_of_birth;
        $user->address = $input['address'];
        $user->town = $input['town'];
        $user->post_code = $input['post_code'];
        $user->contact_number = $input['contact_number'];
        $user->status = $input['status'];
        $user->update();
        $user->notify(new UserAccountUpdatedSuccessfully());

        

        $LogsUpdate->log_details = $user;
        $LogsUpdate->update();      

        return redirect(route('org_admins.index'))->with(['message' => 'The user account is updated successfully!']);

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

        $user = User::find($id);
        $user->delete();
       return redirect(route('org_admins.index'))->with(['message' => 'The Ord Admin is deleted!']);

       
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
