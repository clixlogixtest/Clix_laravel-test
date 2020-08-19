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
use App\Model\prize_categories;
use App\Model\Logs;
use App\PasswordReset;
use Validator;
use App\Http\Requests\CategoryRequest;
use Carbon\Carbon;

class PrizeCategoryController extends Controller
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
        
        $faqList= prize_categories::orderBy('prize_category_id', 'desc')->paginate(25);

        $breadcrumbs = [];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        return view('pages.page-prizecategory-list', ['pageConfigs' => $pageConfigs, 'faqList' => $faqList], ['breadcrumbs' => $breadcrumbs]);
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
        
        $breadcrumbs = [['link' => "category", 'name' => "Category"], ['name' => "Add a Category"]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        return view('pages.page-prizecategory-add', ['pageConfigs' => $pageConfigs], ['breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role != 'global_administrator'){
          return view('pages.page-403');
        }

        $input = $request->all(); 
        $faq = new prize_categories;
        $faq->category_name = $input['category_name'];
        $faq->category_description = $input['category_description'];
        $faq->status = 1;
        $faq->save();

        $current_date_time = Carbon::now()->toDateTimeString();
        $first_name = auth()->user()->first_name; 
        $surname = auth()->user()->surname;
        $email = auth()->user()->email;

        $Logs = new Logs();
        $Logs->log_category = 'PrizeCategory';
        $Logs->log_category_id = $faq->prize_category_id;
        $Logs->date = $current_date_time;
        $Logs->timestamp = $current_date_time;
        $Logs->users_name = $first_name.' '.$surname;
        $Logs->email_id = $email;
        $Logs->description = 'A prize category is created';
        $Logs->log_details = $faq;
        $Logs->status = 1;
        $Logs->save();

        return redirect(route('category.index'))->with(['message' => 'The Prize Category is created!']);
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

        $faq = prize_categories::where('prize_category_id',$id)->get();
        $breadcrumbs = [['link' => "category", 'name' => "Category"], ['name' => "Add a Category"]];
        $log = Logs::where([['log_category', '=', 'PrizeCategory'], ['log_category_id', '=', $id]])->orderBy('created_at', 'desc')->get();
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
        return view('pages.page-prizecategory-edit', ['pageConfigs' => $pageConfigs, 'faq' => $faq, 'log' => $log], ['breadcrumbs' => $breadcrumbs]);
    }

    public function editCategories($id)
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role != 'global_administrator'){
          return view('pages.page-403');
        }

        $faq = prize_categories::where('prize_category_id',$id)->get();
        $breadcrumbs = [['link' => "category", 'name' => "Category"], ['name' => "Add a Category"]];
        $log = Logs::where([['log_category', '=', 'PrizeCategory'], ['log_category_id', '=', $id]])->orderBy('created_at', 'desc')->get();
        $log = json_encode($log);
            $log = json_decode($log, true);
        //print_r($log);
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
        return view('pages.page-prizecategory-edit', ['pageConfigs' => $pageConfigs, 'faq' => $faq, 'logs' => $log], ['breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $prize_category_id)
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role != 'global_administrator'){
          return view('pages.page-403');
        }

        $validator = Validator::make($request->all(), [ 
            'category_name' => 'required', 
            'category_description' => 'required'

        ]);
        if($validator->fails()){ 
            
            return redirect()->back()->withInput()->withErrors($validator); 
        }

        $current_date_time = Carbon::now()->toDateTimeString();
        $first_name = auth()->user()->first_name; 
        $surname = auth()->user()->surname;
        $email = auth()->user()->email;

        $Logs = new Logs();
        $Logs->log_category = 'PrizeCategory';
        $Logs->log_category_id = $prize_category_id;
        $Logs->date = $current_date_time;
        $Logs->timestamp = $current_date_time;
        $Logs->users_name = $first_name.' '.$surname;
        $Logs->email_id = $email;
        $Logs->description = 'A prize category is updated';


        $input = $request->all(); 
        $faq = prize_categories::where('prize_category_id',$prize_category_id)->first();

        $Logs->log_before_changes = $faq;
        $Logs->status = 1;
        $Logs->save();
        $log_id = $Logs->log_id;

        $LogsUpdate = Logs::where('log_id',$log_id)->first();
        
        if($input['category_name']){
            $faq->category_name = $input['category_name'];
        }
        if($input['category_description']){
            $faq->category_description = $input['category_description'];
        }
        $faq->status = 1;
        $faq->update();

        $LogsUpdate->log_details = $faq;
        $LogsUpdate->update(); 

        return redirect(route('category.index'))->with(['message' => 'The Category is updated!']);
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
        
       $faq = prize_categories::find($id); 

        $current_date_time = Carbon::now()->toDateTimeString();
        $first_name = auth()->user()->first_name; 
        $surname = auth()->user()->surname;
        $email = auth()->user()->email;

        $Logs = new Logs();
        $Logs->log_category = 'PrizeCategory';
        $Logs->log_category_id = $id;
        $Logs->date = $current_date_time;
        $Logs->timestamp = $current_date_time;
        $Logs->users_name = $first_name.' '.$surname;
        $Logs->email_id = $email;
        $Logs->description = 'A prize category is deleted';
        $Logs->log_details = $faq;
        $Logs->status = 1;
        $Logs->save();

       $faq->delete();
       return redirect(route('category.index'))->with(['message' => 'The Category is deleted!']);
    }
}
