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
use App\Model\Prizes;
use App\Model\Logs;
use App\Model\Competitions;
use App\Model\prize_categories;
use App\PasswordReset;
use Validator;
use App\Http\Requests\PrizeRequest;
use App\Http\Requests\PrizeUploadImagesRequest;
use App\Http\Requests\PrizeUpdateRequest;
use Carbon\Carbon;

class PrizeController extends Controller
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

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator' || auth()->user()->role == 'user_administrator,prize_administrator'){

            $input = $request->all(); 
            $available_to_win  =  @$input['available_to_win'];
            $category          =  @$input['category'] ? $input['category'] : '';
            $filterCondition = [];
            if($available_to_win && $category){
               $filterCondition[] = ['prize_category', '=', $category];
               $filterCondition[] = ['available_to_win', '=', $available_to_win];
               $filterCondition[] = ['organisation_id', '=', auth()->user()->organisation_id];
            }elseif ($available_to_win && !$category) {
               $filterCondition[] = ['available_to_win', '=', $available_to_win];
               $filterCondition[] = ['organisation_id', '=', auth()->user()->organisation_id];
            }elseif (!$available_to_win && $category) {
               $filterCondition[] = ['prize_category', '=', $category];
               $filterCondition[] = ['organisation_id', '=', auth()->user()->organisation_id];
            }elseif(!$available_to_win && !$category){
               $filterCondition[] = ['organisation_id', '=', auth()->user()->organisation_id];
            }

            $prizeTotal= Prizes::select('prizes.*', 'prize_categories.category_name')
                        ->join('prize_categories', 'prizes.prize_category', '=', 'prize_categories.prize_category_id')
                        ->where($filterCondition)->orderBy('prize_id', 'desc')->paginate(25);
            $prize_categories = prize_categories::get();
             //echo '<pre>';  print_r($prizeTotal); echo '</pre>'; die();
            $breadcrumbs = [];
            //Pageheader set true for breadcrumbs
            $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

            return view('pages.page-prizes-list', ['pageConfigs' => $pageConfigs, 'prizeTotal' => $prizeTotal, 'prize_categories' => $prize_categories, 'available_to_win' => $available_to_win, 'category' => $category], ['breadcrumbs' => $breadcrumbs]);
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

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator' || auth()->user()->role == 'user_administrator,prize_administrator'){

            $breadcrumbs = [['link' => "prizes", 'name' => "Prizes"], ['name' => "Add a Prize"]];
            $prize_categories = prize_categories::get();
            //Pageheader set true for breadcrumbs
            $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
            return view('pages.page-prizes-add', ['pageConfigs' => $pageConfigs, 'prize_categories' => $prize_categories], ['breadcrumbs' => $breadcrumbs]);
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
    public function uploadImages(PrizeUploadImagesRequest $request)
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        $input = $request->all(); 
        $data = array();
        if($request->hasFile('add_file')){
            $add_file = $request->file('add_file');
            //print_r($add_file);
            foreach ($add_file as $key => $value) {
                $avatarName = 'prize_'.$key.time().'.'.$value->getClientOriginalExtension();
                $value->storeAs(auth()->user()->organisation_id, $avatarName);
                $destinationPath = url('storage/'.auth()->user()->organisation_id);
                $file = asset('storage/'.auth()->user()->organisation_id.'/'.$avatarName);
                $data[] = $file;  
            }
        }else{
            //return response()->json($data);
            return redirect()->json(["error" => "Please select image."]);
        }
        
        return response()->json($data);
        //return redirect(route('prizes.create'))->with(['image' => $data]);
    }

    public function store(PrizeRequest $request)
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator' || auth()->user()->role == 'user_administrator,prize_administrator'){

        $input = $request->all(); //print_r($input); die();
        $data = array();
        $images = @$input['images'];
        if(empty($images)){
            return redirect()->back()->withInput()->withErrors(["error" => "Please select image."]);
        }

        $primary = @$input['primary'];
        if($primary){
            $images['primary'] = $primary;
        }else{
            $images['primary'] = $images['0'];
        }

        /*if($request->hasFile('add_file')){
            $add_file = $request->file('add_file');

            foreach ($add_file as $key => $value) {
                $avatarName = time().'.'.$value->getClientOriginalExtension();
                //dd();

                $value->storeAs('prizes',$avatarName);
                $destinationPath = url('storage/prizes/');
                $file = asset('storage/prizes/'.$avatarName);
                $data[] = $file;  
            }
        }*/

        $id = auth()->user()->id;
        $user = new Prizes;
        $user->prize_name = $input['prize_name'];
        $user->created_by = $id;
        $user->cash_value = $input['cash_value'];
        $user->currency = $input['currency'];
        $user->prize_category = $input['category'];
        $user->description = $input['description'];
        $user->file = json_encode($images);
        $user->available_to_win = @$input['available'];
        $user->organisation_id = auth()->user()->organisation_id;
        $user->status = 1;
        $user->save();
        
        $current_date_time = Carbon::now()->toDateTimeString();
        $first_name = auth()->user()->first_name; 
        $surname = auth()->user()->surname;
        $email = auth()->user()->email;

        $Logs = new Logs();
        $Logs->log_category = 'Prize';
        $Logs->log_category_id = $user->prize_id;
        $Logs->date = $current_date_time;
        $Logs->timestamp = $current_date_time;
        $Logs->users_name = $first_name.' '.$surname;
        $Logs->email_id = $email;
        $Logs->description = 'A prize is created';
        $Logs->log_details = $user;
        $Logs->status = 1;
        $Logs->save();


        return redirect(route('prizes.index'))->with(['message' => 'The prize is created!']);
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

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator' || auth()->user()->role == 'user_administrator,prize_administrator'){

            $prize = Prizes::where([['prize_id', '=', $id], ['organisation_id', '=', auth()->user()->organisation_id]])->get();
            $log = Logs::where([['log_category', '=', 'Prize'], ['log_category_id', '=', $id]])->orderBy('created_at', 'desc')->get();
            $breadcrumbs = [['link' => "prizes", 'name' => "Prizes"], ['name' => "Edit a Prize"]];
            $prize_categories = prize_categories::get();
            //Pageheader set true for breadcrumbs
            $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
            return view('pages.page-prize-edit', ['pageConfigs' => $pageConfigs, 'prize' => $prize, 'log' => $log, 'prize_categories' => $prize_categories], ['breadcrumbs' => $breadcrumbs]);
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
    public function editPrize($id)
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator' || auth()->user()->role == 'user_administrator,prize_administrator'){

        $prize = Prizes::where([['prize_id', '=', $id], ['organisation_id', '=', auth()->user()->organisation_id]])->get();
        if(!$prize){
            return redirect(route('prizes.index'))->withErrors(["error" => "You cann't edit this prize."]); 
        }

        $log = Logs::where([['log_category', '=', 'Prize'], ['log_category_id', '=', $id]])->orderBy('created_at', 'desc')->get();
        $breadcrumbs = [['link' => "prizes", 'name' => "Prizes"], ['name' => "Edit a Prize"]];
        $prize_categories = prize_categories::get();
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
        return view('pages.page-prize-edit', ['pageConfigs' => $pageConfigs, 'prize' => $prize, 'log' => $log, 'prize_categories' => $prize_categories], ['breadcrumbs' => $breadcrumbs]);

        }else{
              return view('pages.page-403');
        }
    }

    /**
     * Show the log details.
     *
     * @param  int  $log_id
     * @return \Illuminate\Http\Response
     */
    public function revision(Request $request, $log_id)
    {
        
        $log = Logs::where('log_id',$log_id)->get();
        $breadcrumbs = [];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
        return view('pages.page-prize-log', ['pageConfigs' => $pageConfigs, 'log' => $log], ['breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PrizeUpdateRequest $request, $prize_id)
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator' || auth()->user()->role == 'user_administrator,prize_administrator'){

        $current_date_time = Carbon::now()->toDateTimeString();
        $first_name = auth()->user()->first_name; 
        $surname = auth()->user()->surname;
        $email = auth()->user()->email;

        $Logs = new Logs();
        $Logs->log_category = 'Prize';
        $Logs->log_category_id = $prize_id;
        $Logs->date = $current_date_time;
        $Logs->timestamp = $current_date_time;
        $Logs->users_name = $first_name.' '.$surname;
        $Logs->email_id = $email;
        $Logs->description = 'A prize is updated';
        

        $id = auth()->user()->id;
        $prize = Prizes::where('prize_id',$prize_id)->first();
        //print_r($prize);
        $Logs->log_before_changes = $prize;
        $Logs->status = 1;
        $Logs->save();
        $log_id = $Logs->log_id;

        $LogsUpdate = Logs::where('log_id',$log_id)->first();


        $input = $request->all(); 
        $prize->created_by = $id;

        if($input['prize_name']){
            $prize->prize_name = $input['prize_name'];
        }

        if($input['cash_value']){
            $prize->cash_value = $input['cash_value'];
        }

        if($input['currency']){
            $prize->currency = $input['currency'];
        }

        if($input['category']){
            $prize->prize_category = $input['category'];
        }

        if($input['description']){
            $prize->description = $input['description'];
        }

        if(@$input['available']){
            $prize->available_to_win = @$input['available'];
        }else{
            $prize->available_to_win = '';
        }
        
        

        $prize->organisation_id = auth()->user()->organisation_id;


        /*$data = array();
        if($request->hasFile('add_file')){
            $add_file = $request->file('add_file');

            foreach ($add_file as $key => $value) {
                $avatarName = time().'.'.$value->getClientOriginalExtension();
                //dd();

                $value->storeAs('prizes',$avatarName);
                $destinationPath = url('storage/prizes/');
                $file = asset('storage/prizes/'.$avatarName);
                $data[] = $file;  
            }

            $prize->file = json_encode($data);
        }*/
        $images = $input['images'];
        if(empty($images)){
            return redirect()->back()->withInput()->withErrors(["error" => "Please select image."]);
        }

        $primary = $input['primary'];
        if($primary){
            $images['primary'] = $primary;
        }else{
            $images['primary'] = $images['0'];
        }

        $prize->file = json_encode($images);

        $prize->status = 1;
        $prize->update();

        //print_r($prize); 
        $LogsUpdate->log_details = $prize;
        $LogsUpdate->update(); 

        return redirect(route('prizes.index'))->with(['message' => 'The prize is updated!']);
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

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator' || auth()->user()->role == 'user_administrator,prize_administrator'){


        $Competitions = Competitions::where([['prize_id', '=', $id]])->get();
        $Competitions = json_encode($Competitions);
        $Competitions = json_decode($Competitions, true);
    
        if($Competitions){ 
            return redirect()->back()->withInput()->withErrors(["error" => "This prize is associated with an active competition."]);
        }

        $prize = Prizes::find($id);

        $current_date_time = Carbon::now()->toDateTimeString();
        $first_name = auth()->user()->first_name; 
        $surname = auth()->user()->surname;
        $email = auth()->user()->email;

        $Logs = new Logs();
        $Logs->log_category = 'Prize';
        $Logs->log_category_id = $id;
        $Logs->date = $current_date_time;
        $Logs->timestamp = $current_date_time;
        $Logs->users_name = $first_name.' '.$surname;
        $Logs->email_id = $email;
        $Logs->description = 'A prize is deleted';
        $Logs->log_details = $prize;
        $Logs->status = 1;
        $Logs->save();

        $prize->delete();
        return redirect(route('prizes.index'))->with(['message' => 'The prize is deleted!']);
        }else{
              return view('pages.page-403');
        }
    }
}