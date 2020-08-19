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
use App\Model\Competitions;
use App\PasswordReset;
use Validator;
use App\Http\Requests\FaqRequest;

class ReportController extends Controller
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

        if(auth()->user()->role == 'organisation_administrator'){



        $breadcrumbs = [];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        return view('pages.page-reports-list', ['pageConfigs' => $pageConfigs], ['breadcrumbs' => $breadcrumbs]);

        }else{
              return view('pages.page-403');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function closedCompetition()
    { 
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role == 'organisation_administrator'){

        
        $CompetitionList = DB::table('competitions')
        ->select('competitions.competition_id','prizes.prize_name','competitions.availabl_tickets','competitions.sold_ticket','competitions.closed_date', 'competitions.status')
        ->join('prizes','competitions.prize_id','=','prizes.prize_id')
        ->where('competitions.status', '=', 2)
        ->orderBy('competition_id', 'desc')
        ->paginate(25);
        
        $breadcrumbs = [['link' => "reports", 'name' => "Reports"], ['name' => "Closed Competition"]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        return view('pages.page-reportsClosed-list', ['pageConfigs' => $pageConfigs, 'CompetitionList' => $CompetitionList], ['breadcrumbs' => $breadcrumbs]);

        }else{
              return view('pages.page-403');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function drawnCompetition()
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role == 'organisation_administrator'){
        
        $CompetitionList = DB::table('competitions')
        ->select('competitions.competition_id','prizes.prize_name','competitions.availabl_tickets','competitions.sold_ticket','competitions.closed_date', 'competitions.status')
        ->join('prizes','competitions.prize_id','=','prizes.prize_id')
        ->where('competitions.status', '=', 3)
        ->orderBy('competition_id', 'desc')
        ->paginate(25);

        $breadcrumbs = [['link' => "reports", 'name' => "Reports"], ['name' => "Drawn Competition"]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        return view('pages.page-reportsDrawn-list', ['pageConfigs' => $pageConfigs, 'CompetitionList' => $CompetitionList], ['breadcrumbs' => $breadcrumbs]);

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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
