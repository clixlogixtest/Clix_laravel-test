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
use App\Model\page_faqs;
use App\PasswordReset;
use Validator;
use App\Http\Requests\FaqRequest;

class FaqController extends Controller
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
        
        $faqList= page_faqs::orderBy('id', 'desc')->paginate(25);

        $breadcrumbs = [];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        return view('pages.page-faqs-list', ['pageConfigs' => $pageConfigs, 'faqList' => $faqList], ['breadcrumbs' => $breadcrumbs]);
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
        
        $breadcrumbs = [['link' => "faqs", 'name' => "FAQs"], ['name' => "Add a FAQ"]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        return view('pages.page-faqs-add', ['pageConfigs' => $pageConfigs], ['breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FaqRequest $request)
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role != 'global_administrator'){
          return view('pages.page-403');
        }

        $input = $request->all(); 
        $faq = new page_faqs;
        $faq->title = 'FAQs';
        $faq->question = $input['question'];
        $faq->answer = $input['faq_answer'];
        $faq->status = 1;
        $faq->save();

        return redirect(route('faqs.index'))->with(['message' => 'The faq is created!']);
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

        $faq = page_faqs::where('id',$id)->get();
        $breadcrumbs = [['link' => "faqs", 'name' => "FAQs"], ['name' => "Edit a FAQ"]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
        return view('pages.page-faqs-edit', ['pageConfigs' => $pageConfigs, 'faq' => $faq], ['breadcrumbs' => $breadcrumbs]);
    }

    public function editFaq($id)
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role != 'global_administrator'){
          return view('pages.page-403');
        }

        $faq = page_faqs::where('id',$id)->get();
        $breadcrumbs = [['link' => "faqs", 'name' => "FAQs"], ['name' => "Edit a FAQ"]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
        return view('pages.page-faqs-edit', ['pageConfigs' => $pageConfigs, 'faq' => $faq], ['breadcrumbs' => $breadcrumbs]);
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

        $input = $request->all(); 
        $faq = page_faqs::where('id',$id)->first();
        $faq->title = 'FAQs';
        if($input['question']){
            $faq->question = $input['question'];
        }
        if($input['faq_answer']){
            $faq->answer = $input['faq_answer'];
        }
        $faq->status = 1;
        $faq->update();

        return redirect(route('faqs.index'))->with(['message' => 'The faq is updated!']);
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
        
       $faq = page_faqs::find($id);    
       $faq->delete();
       return redirect(route('faqs.index'))->with(['message' => 'The FAQ is deleted!']);
    }
}
