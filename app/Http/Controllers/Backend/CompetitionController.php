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
use App\Model\Prizes;
use App\Model\Challenges;
use App\Model\Challenge_answers;
use App\Model\Tickets;
use App\Model\Logs;
use App\Model\Global_settings;
use App\User;
use Validator;
use App\Http\Requests\CompetitionRequest;
use App\Http\Requests\CompetitionUpdateRequest;
use App\Http\Requests\ticketRequest;
use Carbon\Carbon;
use Illuminate\Routing\UrlGenerator;
use App\Notifications\NewTicketsCreatedMarkedAsFreeAndValidationPassed;
use App\Notifications\NewTicketsCreatedMarkedAsFreeAndValidationFailed;
use Mail;
//use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Response as FacadeResponse;

class CompetitionController extends Controller
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

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator'){

        $input = $request->all(); 
        $result =  @$input['result'];
        $Search =  @$input['Search'] ? $input['Search'] : '';
        
        if(($result==0 || $result==1 || $result==2 || $result==3) && $Search){
            

            $competitionList = DB::table('competitions')
            ->select('competitions.competition_id','prizes.prize_name','competitions.availabl_tickets','competitions.sold_ticket','competitions.closed_date', 'competitions.status')
            ->join('prizes','competitions.prize_id','=','prizes.prize_id')
            ->where([['competitions.status', 'LIKE', '%'.$result.'%'], ['prizes.prize_name', 'LIKE', '%'.$Search.'%'], ['competitions.organisation_id', '=', auth()->user()->organisation_id], ['prizes.organisation_id', '=', auth()->user()->organisation_id]])
            ->orderBy('competitions.closed_date', 'asc')
            ->paginate(50);

        }else if(($result==0 || $result==1 || $result==2 || $result==3) && !$Search){
            

            $competitionList = DB::table('competitions')
            ->select('competitions.competition_id','prizes.prize_name','competitions.availabl_tickets','competitions.sold_ticket','competitions.closed_date', 'competitions.status')
            ->join('prizes','competitions.prize_id','=','prizes.prize_id')
            ->where([['competitions.status', 'LIKE', '%'.$result.'%'], ['competitions.organisation_id', '=', auth()->user()->organisation_id], ['prizes.organisation_id', '=', auth()->user()->organisation_id]])
            ->orderBy('competitions.closed_date', 'asc')
            ->paginate(50);

        }else if((!$result==0 || !$result==1 || !$result==2 || !$result==3) && $Search){
            

            $competitionList = DB::table('competitions')
            ->select('competitions.competition_id','prizes.prize_name','competitions.availabl_tickets','competitions.sold_ticket','competitions.closed_date', 'competitions.status')
            ->join('prizes','competitions.prize_id','=','prizes.prize_id')
            ->where([['prizes.prize_name', 'LIKE', '%'.$Search.'%'], ['competitions.organisation_id', '=', auth()->user()->organisation_id], ['prizes.organisation_id', '=', auth()->user()->organisation_id]])
            ->orderBy('competitions.closed_date', 'asc')
            ->paginate(50);

        }else{

            $competitionList = DB::table('competitions')
            ->select('competitions.competition_id','prizes.prize_name','competitions.availabl_tickets','competitions.sold_ticket','competitions.closed_date', 'competitions.status')
            ->join('prizes','competitions.prize_id','=','prizes.prize_id')
            ->where([['competitions.organisation_id', '=', auth()->user()->organisation_id], ['prizes.organisation_id', '=', auth()->user()->organisation_id]])
            ->orderBy('competitions.closed_date', 'asc')
            ->paginate(50);

        }

        

        $breadcrumbs = [];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        return view('pages.page-competitions-list', ['pageConfigs' => $pageConfigs, 'competitionList' => $competitionList, 'result'=> $result, 'Search'=> $Search], ['breadcrumbs' => $breadcrumbs]);

        }else{
              return view('pages.page-403');
        }
    }

    public function getAllCompetitionInCSV(Request $request)
    {

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator'){

        $input = $request->all(); 
        $result =  @$input['result']; //print_r($result);
        $search =  @$input['search'] ? $input['search'] : '';
        
        if(($result==0 || $result==1 || $result==2 || $result==3) && $search){
            

            $competitionList = DB::table('competitions')
            ->select('competitions.competition_id','prizes.prize_name','competitions.availabl_tickets','competitions.sold_ticket','competitions.closed_date', 'competitions.status')
            ->join('prizes','competitions.prize_id','=','prizes.prize_id')
            ->where([['competitions.status', 'LIKE', '%'.$result.'%'], ['prizes.prize_name', 'LIKE', '%'.$search.'%'], ['competitions.organisation_id', '=', auth()->user()->organisation_id], ['prizes.organisation_id', '=', auth()->user()->organisation_id]])
            ->orderBy('competitions.closed_date', 'asc')
            ->get();

        }else if(($result==0 || $result==1 || $result==2 || $result==3) && !$search){
            

            $competitionList = DB::table('competitions')
            ->select('competitions.competition_id','prizes.prize_name','competitions.availabl_tickets','competitions.sold_ticket','competitions.closed_date', 'competitions.status')
            ->join('prizes','competitions.prize_id','=','prizes.prize_id')
            ->where([['competitions.status', 'LIKE', '%'.$result.'%'], ['prizes.prize_name', 'LIKE', '%'.$search.'%'], ['competitions.organisation_id', '=', auth()->user()->organisation_id], ['prizes.organisation_id', '=', auth()->user()->organisation_id]])
            ->orderBy('competitions.closed_date', 'asc')
            ->get();

        }else if((!$result==0 || !$result==1 || !$result==2 || !$result==3) && $search){
            

            $competitionList = DB::table('competitions')
            ->select('competitions.competition_id','prizes.prize_name','competitions.availabl_tickets','competitions.sold_ticket','competitions.closed_date', 'competitions.status')
            ->join('prizes','competitions.prize_id','=','prizes.prize_id')
            ->where([['competitions.status', 'LIKE', '%'.$result.'%'], ['prizes.prize_name', 'LIKE', '%'.$search.'%'], ['competitions.organisation_id', '=', auth()->user()->organisation_id], ['prizes.organisation_id', '=', auth()->user()->organisation_id]])
            ->orderBy('competitions.closed_date', 'asc')
            ->get();

        }else{

            $competitionList = DB::table('competitions')
            ->select('competitions.competition_id','prizes.prize_name','competitions.availabl_tickets','competitions.sold_ticket','competitions.closed_date', 'competitions.status')
            ->join('prizes','competitions.prize_id','=','prizes.prize_id')
            ->orderBy('competitions.closed_date', 'asc')
            ->where([['competitions.organisation_id', '=', auth()->user()->organisation_id], ['prizes.organisation_id', '=', auth()->user()->organisation_id]])
            ->get();

        }

        
        

        //print_r($competitionList);die();

        $filename = storage_path('app/public/competitionsCSV/competitions'.strtotime('now').'.csv');
        $handle = fopen($filename, 'w+');
        fputcsv($handle, array('CID', 'Prize', 'Tickets Sold', 'Close Date', 'State'));
        $competitionList = json_encode($competitionList);
        $competitionList = json_decode($competitionList, true);
        foreach($competitionList as $competition){ 
                  $color = '';
                  $stausText = '';
                  if($competition['status'] ==0){
                    $color = 'blue';
                    $stausText = 'Draft';
                  }
                  if($competition['status'] ==1){
                    $color = 'green';
                    $stausText = 'Active';
                  }
                  if($competition['status'] ==2){
                    $color = 'orange';
                    $stausText = 'Closed';
                  }
                  if($competition['status'] ==3){
                    $color = 'red';
                    $stausText = 'Drawn';
                  }

                  $avl = $competition['availabl_tickets']; 
                  $avlVal = $avl;
                  $avl = strlen($avl);
                  
                  if($avl<4){
                    $avdiff = 4-$avl;
                    $zero = '';
                    for ($i=0; $i < $avdiff; $i++) { 
                      $zero .= 0;
                    }
                    $avlVal = $zero.$competition['availabl_tickets'];

                  }

                  $ticketList = DB::table('tickets')
                  ->select('tickets.ticket_id')
                  ->where('tickets.competition_id', '=', $competition['competition_id'])
                  ->orderBy('tickets.created_at', 'desc')
                  ->get();

                  $TotalSoldTicket = '';
                  if($ticketList){
                    $TotalSoldTicket = count($ticketList);
                  }

                  $sold = $TotalSoldTicket ? $TotalSoldTicket : '0000'; 
                  $soldVal = $sold;
                  $sold = strlen($sold);
                  
                  if($sold<4){
                    $avdiff = 4-$sold;
                    $zero = '';
                    for ($i=0; $i < $avdiff; $i++) { 
                      $zero .= 0;
                    }
                    $soldVal = $zero.$TotalSoldTicket;

                  }

            fputcsv($handle, array($competition['competition_id'], $competition['prize_name'], $soldVal."/".$avlVal, date('d/m/Y', strtotime($competition['closed_date'])), $stausText));
        }

        fclose($handle);

        $headers = array(
            'Content-Type' => 'text/csv',
        );
         //return Storage::download('file.jpg', $name, $headers);  
        return FacadeResponse::download($filename, 'competitions.csv', $headers);

        }else{
              return view('pages.page-403');
        }


    }

    public function autocompletePrize(Request $request){

		$input = $request->all();


		$Prizes = Prizes::select("prize_name")
                ->where([["prize_name","LIKE",'%'.$input['query'].'%'], ['organisation_id', '=', auth()->user()->organisation_id], ['available_to_win', '=', 1]])
                ->get();

        $data = array();
        foreach ($Prizes as $Prize)
            {
                $data[] = $Prize->prize_name;
            }
   
        return response()->json($data);
    }

    public function autocompleteChallenge(Request $request){

		$input = $request->all();


		$Prizes = Challenges::select("question")
                ->where([["question","LIKE",'%'.$input['query'].'%'], ['global_challenge_bank', '=', 0], ['organisation_id', '=', auth()->user()->organisation_id]])
                ->get();

        $data = array();
        foreach ($Prizes as $Prize)
            {
                $data[] = $Prize->question;
            }
   
        return response()->json($data);
    }

    public function autocompleteCompetition(Request $request){

        $input = $request->all();


        $Prizes = Competitions::select("competition_id")
                ->where([["competition_id", "LIKE", '%'.$input['query'].'%'], ['organisation_id', '=', auth()->user()->organisation_id]])
                ->get();

        $data = array();
        foreach ($Prizes as $Prize)
            {
                $data[] = $Prize->competition_id;
            }
   
        return response()->json($data);
    }

    public function autocompleteUser(Request $request){

        $input = $request->all();


        $Prizes = User::select("email")
                ->where([["email","LIKE",'%'.$input['query'].'%'], ['role', '=', 'player'], ['organisation_id', '=', auth()->user()->organisation_id]])
                ->get();

        $data = array();
        foreach ($Prizes as $Prize)
            {
                $data[] = $Prize->email;
            }
   
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCompetitionChallengeList(Request $request, $id)
    {
        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator'){

        $input = $request->all(); 
        $data = array();
        $competitionList = DB::table('competitions')
			->select('competitions.*', 'challenges.*')
			->join('challenges','competitions.challenge_id','=','challenges.question_id')
			->where('competitions.competition_id', $id)
			->get();
        if($competitionList){
        	$Challenge_answers = Challenge_answers::where('question_id', '=', $competitionList['0']->question_id)->get();
        	$data = array('challengesName' => $competitionList['0']->question, "challengesAns" => $Challenge_answers);
        	return response()->json($data);
            
        }else{
            return redirect()->json(["error" => "No data found!"]);
        }

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

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator'){

        $prizeTotal= Prizes::select('prize_id', 'prize_name', 'prize_category', 'cash_value', 'currency', 'available_to_win', 'status')->orderBy('prize_id', 'desc')->get();
        //$questionTotal= Challenges::where('global_challenge_bank', '=', 0)->orderBy('question_id', 'desc')->get();
        $questionTotal= Challenges::orderBy('question_id', 'desc')->get();

        $breadcrumbs = [['link' => "competitions", 'name' => "Competitions"], ['name' => "Add a Competition"]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        return view('pages.page-competitions-add', ['pageConfigs' => $pageConfigs, 'prizeTotal' => $prizeTotal, 'questionTotal' => $questionTotal], ['breadcrumbs' => $breadcrumbs]);

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
    public function store(CompetitionRequest $request)
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator'){

        $input = $request->all(); 
        $id = auth()->user()->id;
        $role = auth()->user()->role;
        $prize = $input['prize'];
        $ticket_price = $input['ticket_price'];
        $available_ticket = $input['available_ticket'];
        //$competition_id = $input['competition_id'];

        $closed_date = $input['closed_date'];
        $closed_date = str_replace('/', '-', $closed_date);
        $closed_date = strtotime($closed_date);
        $closed_date = date('Y-m-d 23:59:59', $closed_date);

        $Challenge = $input['Challenge'];
        $state = $input['state'];
        $challengeID = '';

        if(!$available_ticket){
            return redirect()->back()->withInput()->withErrors(["error" => "Please select Prize and enter Ticket Price or enter available ticket in its field."]); 
        }
        
        if(!$Challenge){
            $questionTotal = Challenges::where([['global_challenge_bank', '=', 1]])->whereNull('last_used_timestamp')->get();
            $questionTotal = json_encode($questionTotal);
            $questionTotal = json_decode($questionTotal, true);
            if(!$questionTotal){

              $date = strtotime(Carbon::now()->toDateTimeString().' -1 year');
              $date = date('Y-m-d H:i:s', $date);
              $questionTotal = Challenges::where([['global_challenge_bank', '=', 1], ['last_used_timestamp', '<', $date]])->orderBy('last_used_timestamp', 'desc')->get();
              $questionTotal = json_encode($questionTotal);
              $questionTotal = json_decode($questionTotal, true);

              if(!$questionTotal){
                $questionTotal = Challenges::where([['global_challenge_bank', '=', 1]])->orderBy('last_used_timestamp', 'desc')->get();
                $questionTotal = json_encode($questionTotal);
                $questionTotal = json_decode($questionTotal, true); 
              }

            }

            

            
            //print_r($questionTotal);
            
            $idies = array();
            foreach ($questionTotal as $key => $value) {
              if($key){
                $idies[] = $value['question_id'];
              }
            }
            //print_r($idies); die();

            $key = array_rand($idies); 
              
            // Display the random array element 
            $challengeID = $idies[$key];
        }else{
            $questionTotal = Challenges::where('question', '=', $Challenge)->first();
            $questionTotal = json_encode($questionTotal);
            $questionTotal = json_decode($questionTotal, true);
            if(!$questionTotal){
                return redirect()->back()->withInput()->withErrors(["error" => "Challenge you have entered is not exist."]); 
            }
            
            $challengeID = $questionTotal['question_id'];
        }



            //echo $challengeID;

            $PrizeTotal = Prizes::where('prize_name', '=', $prize)->first();
            $PrizeTotal = json_encode($PrizeTotal);
            $PrizeTotal = json_decode($PrizeTotal, true);
            if(!$PrizeTotal){
                return redirect()->back()->withInput()->withErrors(["error" => "Prize you have entered is not exist."]); 
            }
            $prizeID = $PrizeTotal['prize_id'];
            
            
       // die();

        /*$answer_correct = $input['answer_correct'];
        //$answer_correct1 = $answer_correct+1;
        if(!$answer_correct){
            return redirect()->back()->withInput()->withErrors(["error" => "Please select correct answer."]); 
        }*/

        $competition = new Competitions;
        //$competition->competition_id = $competition_id;
        $competition->prize_id = $prizeID;
        $competition->challenge_id = $challengeID;
        $competition->ticket_price = $ticket_price;
        $competition->availabl_tickets = $available_ticket;
        $competition->closed_date = $closed_date;
        $competition->status = $state;
        $competition->organisation_id = auth()->user()->organisation_id;
        $competition->save();

        $ChallengesUpdate = Challenges::where('question_id', $challengeID)->first();
        $ChallengesUpdate->last_used_timestamp = Carbon::now()->toDateTimeString();
        $ChallengesUpdate->update();

        $current_date_time = Carbon::now()->toDateTimeString();
        $first_name = auth()->user()->first_name; 
        $surname = auth()->user()->surname;
        $email = auth()->user()->email;

        $Logs = new Logs();
        $Logs->log_category = 'Competition';
        $Logs->log_category_id = $competition->competition_id;
        $Logs->date = $current_date_time;
        $Logs->timestamp = $current_date_time;
        $Logs->users_name = $first_name.' '.$surname;
        $Logs->email_id = $email;
        $Logs->description = 'A competition is created';
        $Logs->log_details = $competition;
        $Logs->status = 1;
        $Logs->save();

        return redirect(route('competitions.index'))->with(['message' => 'A competition is created!']);

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

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator'){

        //$competition = Competitions::where('competition_id',$id)->first();
        $competition = DB::table('competitions')
    		->select('competitions.*','prizes.prize_name','challenges.question')
    		->join('prizes','competitions.prize_id','=','prizes.prize_id')
    		->join('challenges','competitions.challenge_id','=','challenges.question_id')
    		->where(['competitions.competition_id' => $id])
    		->groupby('competitions.competition_id')
    		->get();

        $log = Logs::where([['log_category', '=', 'Competition'], ['log_category_id', '=', $id]])->orderBy('created_at', 'desc')->get();

        $breadcrumbs = [['link' => "competitions", 'name' => "Competitions"], ['name' => "View a Competition"]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
        return view('pages.page-competitions-view', ['pageConfigs' => $pageConfigs, 'competition' => $competition, 'log' => $log], ['breadcrumbs' => $breadcrumbs]);

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

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator'){

        $competition = DB::table('competitions')
		->select('competitions.*','prizes.prize_name','challenges.question')
		->join('prizes','competitions.prize_id','=','prizes.prize_id')
		->join('challenges','competitions.challenge_id','=','challenges.question_id')
		->where([['competitions.competition_id', '=', $id]])
		->groupby('competitions.competition_id')
		->get();
        //print_r($competition); echo $id;

		$prizeTotal= Prizes::select('prize_id', 'prize_name', 'prize_category', 'cash_value', 'currency', 'available_to_win', 'status')->orderBy('prize_id', 'desc')->get();
        $questionTotal= Challenges::where('global_challenge_bank', '=', 0)->orderBy('question_id', 'desc')->get();

        $log = Logs::where([['log_category', '=', 'Competition'], ['log_category_id', '=', $id]])->orderBy('created_at', 'desc')->get();
        //print_r($log);die();
        $breadcrumbs = [['link' => "competitions", 'name' => "Competitions"], ['name' => "Edit a Competition"]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
        return view('pages.page-competitions-edit', ['pageConfigs' => $pageConfigs, 'competition' => $competition, 'prizeTotal' => $prizeTotal, 'questionTotal' => $questionTotal, 'log' => $log], ['breadcrumbs' => $breadcrumbs]);
        }else{
              return view('pages.page-403');
        }
    }

    public function editCompetition($id)
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator'){

        $role = auth()->user()->role;
        $global_challenge_bank = 0; 
        if($role == 'global_administrator'){
            $global_challenge_bank = 1;
        }

        $competition = DB::table('competitions')
        ->select('competitions.*','prizes.prize_name','challenges.question')
        ->join('prizes','competitions.prize_id','=','prizes.prize_id')
        ->join('challenges','competitions.challenge_id','=','challenges.question_id')
        ->where([['competitions.competition_id', '=', $id],['prizes.organisation_id', '=', auth()->user()->organisation_id]])
        /*->where([['prizes.organisation_id', '=', auth()->user()->organisation_id], ['prizes.organisation_id', '=', auth()->user()->organisation_id]])*/
        ->groupby('competitions.competition_id')
        ->get();
        //print_r($competition); echo $id;

        $prizeTotal= Prizes::select('prize_id', 'prize_name', 'prize_category', 'cash_value', 'currency', 'available_to_win', 'status')->where([['organisation_id', '=', auth()->user()->organisation_id]])->orderBy('prize_id', 'desc')->get();
        $questionTotal= Challenges::where([['global_challenge_bank', '=', $global_challenge_bank], ['organisation_id', '=', auth()->user()->organisation_id]])->orderBy('question_id', 'desc')->get();

        $log = Logs::where([['log_category', '=', 'Competition'], ['log_category_id', '=', $id]])->orderBy('created_at', 'desc')->get();
        //print_r($log);die();
        $breadcrumbs = [['link' => "competitions", 'name' => "Competitions"], ['name' => "Edit a Competition"]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
        return view('pages.page-competitions-edit', ['pageConfigs' => $pageConfigs, 'competition' => $competition, 'prizeTotal' => $prizeTotal, 'questionTotal' => $questionTotal, 'log' => $log], ['breadcrumbs' => $breadcrumbs]);

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
    public function update(CompetitionUpdateRequest $request, $competition_id)
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator'){

    	$input = $request->all(); 
        $id = auth()->user()->id;
        $role = auth()->user()->role;
        $prize = $input['prize'];
        $ticket_price = $input['ticket_price'];
        $available_ticket = $input['available_ticket'];
        //$competition_title = $input['competition_title'];

        $closed_date = $input['closed_date'];
        $closed_date = str_replace('/', '-', $closed_date);
        $closed_date = strtotime($closed_date);
        $closed_date = date('Y-m-d 23:59:59', $closed_date);

        $Challenge = $input['Challenge'];
        $state = $input['state'];
        $challengeID = '';

        if(!$available_ticket){
            return redirect()->back()->withInput()->withErrors(["error" => "Please select Prize and enter Ticket Price or enter available ticket in its field."]); 
        }
        
        if(!$Challenge){
            $questionTotal = Challenges::where([['global_challenge_bank', '=', 1]])->whereNull('last_used_timestamp')->get();
            $questionTotal = json_encode($questionTotal);
            $questionTotal = json_decode($questionTotal, true);
            if(!$questionTotal){

              $date = strtotime(Carbon::now()->toDateTimeString().' -1 year');
              $date = date('Y-m-d H:i:s', $date);
              $questionTotal = Challenges::where([['global_challenge_bank', '=', 1], ['last_used_timestamp', '<', $date]])->orderBy('last_used_timestamp', 'desc')->get();
              $questionTotal = json_encode($questionTotal);
              $questionTotal = json_decode($questionTotal, true);

              if(!$questionTotal){
                $questionTotal = Challenges::where([['global_challenge_bank', '=', 1]])->orderBy('last_used_timestamp', 'desc')->get();
                $questionTotal = json_encode($questionTotal);
                $questionTotal = json_decode($questionTotal, true); 
              }

            }

            /*if(!$questionTotal){
                return redirect()->back()->withInput()->withErrors(["error" => "Cannot find a challenge that hasn't been used in 12 months."]); 
            }*/

            
            //print_r($questionTotal);
            
            $idies = array();
            foreach ($questionTotal as $key => $value) {
              if($key){
                $idies[] = $value['question_id'];
              }
            }
            //print_r($idies); die();

            $key = array_rand($idies); 
              
            // Display the random array element 
            $challengeID = $idies[$key];
        }else{ //echo trim($Challenge);
            $questionTotal = Challenges::where('question', '=', trim($Challenge))->get();
            $questionTotal = json_encode($questionTotal);
            $questionTotal = json_decode($questionTotal, true); //print_r($questionTotal); die();
            if(!$questionTotal){
                return redirect()->back()->withInput()->withErrors(["error" => "Challenge you have entered is not exist."]); 
            }
            $challengeID = $questionTotal['0']['question_id'];
        }



            //echo $challengeID;

            $questionTotal = Prizes::where('prize_name', '=', trim($prize))->first();
            $questionTotal = json_encode($questionTotal);
            $questionTotal = json_decode($questionTotal, true);
            if(!$questionTotal){
                return redirect()->back()->withInput()->withErrors(["error" => "Prize you have entered is not exist."]); 
            }
            $prizeID = $questionTotal['prize_id'];

            /*if(!$prizeID){
                return redirect()->back()->withInput()->withErrors(["error" => "Prize is not available in Prize list."]); 
            }*/
       // die();

        /*$answer_correct = $input['answer_correct'];
        //$answer_correct1 = $answer_correct+1;
        if(!$answer_correct){
            return redirect()->back()->withInput()->withErrors(["error" => "Please select correct answer."]); 
        }*/ //echo $id; echo $available_ticket;

        $current_date_time = Carbon::now()->toDateTimeString();
        $first_name = auth()->user()->first_name; 
        $surname = auth()->user()->surname;
        $email = auth()->user()->email;

        $Logs = new Logs();
        $Logs->log_category = 'Competition';
        $Logs->log_category_id = $competition_id;
        $Logs->date = $current_date_time;
        $Logs->timestamp = $current_date_time;
        $Logs->users_name = $first_name.' '.$surname;
        $Logs->email_id = $email;
        $Logs->description = 'A competition is updated';
        

        $competition = Competitions::where('competition_id',$competition_id)->first();
        $Logs->log_before_changes = $competition;
        $Logs->status = 1;
        $Logs->save();

        $log_id = $Logs->log_id;

        $LogsUpdate = Logs::where('log_id',$log_id)->first();

        //$competition = new Competitions;
        //$competition->competition_title = $competition_title;
        $competition->prize_id = $prizeID;
        $competition->challenge_id = $challengeID;
        $competition->ticket_price = $ticket_price;
        $competition->availabl_tickets = $available_ticket;
        $competition->closed_date = $closed_date;
        $competition->status = $state;
        $competition->organisation_id = auth()->user()->organisation_id;
        $competition->update();

        $ChallengesUpdate = Challenges::where('question_id', $challengeID)->first();
        $ChallengesUpdate->last_used_timestamp = Carbon::now()->toDateTimeString();
        $ChallengesUpdate->update();

        $LogsUpdate->log_details = $competition;
        $LogsUpdate->update();         

        return redirect(route('competitions.index'))->with(['message' => 'Competition is updated!']);

        }else{
              return view('pages.page-403');
        }

    }

    public function updateState(Request $request, $id){
    	if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator'){

    	$input = $request->all(); 

        $current_date_time = Carbon::now()->toDateTimeString();
        $first_name = auth()->user()->first_name; 
        $surname = auth()->user()->surname;
        $email = auth()->user()->email;

        $Logs = new Logs();
        $Logs->log_category = 'Competition';
        $Logs->log_category_id = $id;
        $Logs->date = $current_date_time;
        $Logs->timestamp = $current_date_time;
        $Logs->users_name = $first_name.' '.$surname;
        $Logs->email_id = $email;
        $Logs->description = 'A competition status is updated';

        //echo $id;
        $competition = Competitions::where('competition_id',$id)->first();

        $Logs->log_before_changes = $competition;
        $Logs->status = 1;
        $Logs->save();
        $log_id = $Logs->log_id;

        $LogsUpdate = Logs::where('log_id',$log_id)->first();
        
        $competition->status = $input['state'];
        $competition->description = $input['description'];
        $competition->update();

        $LogsUpdate->log_details = $competition;
        $LogsUpdate->update();  

        return redirect(route('competitions.index'))->with(['message' => 'Competition status is updated!']);

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

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator'){

        $Competitions = Competitions::find($id);

        $current_date_time = Carbon::now()->toDateTimeString();
        $first_name = auth()->user()->first_name; 
        $surname = auth()->user()->surname;
        $email = auth()->user()->email;

        $Logs = new Logs();
        $Logs->log_category = 'Competition';
        $Logs->log_category_id = $id;
        $Logs->date = $current_date_time;
        $Logs->timestamp = $current_date_time;
        $Logs->users_name = $first_name.' '.$surname;
        $Logs->email_id = $email;
        $Logs->description = 'A competition is deleted';
        $Logs->log_details = $Competitions;
        $Logs->status = 1;
        $Logs->save();

       $Competitions->delete();
       return redirect(route('competitions.index'))->with(['message' => 'The competition is deleted!']);

       }else{
              return view('pages.page-403');
        }
    }

    /**
     * add the free tickets in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addFreeTicket()
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator'){
        
        $competitionList = DB::table('competitions')->select('competitions.competition_id', 'prizes.prize_name')
                            ->join('prizes', 'prizes.prize_id', '=', 'competitions.prize_id')
                            ->where([['competitions.status', '=', 1], ['competitions.organisation_id', '=', auth()->user()->organisation_id], ['prizes.organisation_id', '=', auth()->user()->organisation_id]])

                           ->get(); //print_r($competitionList);

        $userList = User::where('role', '=', 'player')->get();

        $data = array('name'=>"Virat Gandhi");

        

        $breadcrumbs = [['link' => "competitions", 'name' => "Competitions"], ['name' => "Add free Ticket"]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        return view('pages.page-freeTicket-add', ['pageConfigs' => $pageConfigs, 'competitionList' => $competitionList,  'userList' => $userList], ['breadcrumbs' => $breadcrumbs]);

        }else{
              return view('pages.page-403');
        }
    }

    /**
     * add the free tickets in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function storeFreeTicket(ticketRequest $request)
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator'){

        $input = $request->all(); 
        $competition = $input['competition'];
        $competition = explode(' - ', $competition);
        $competitionList = '';
        $Challenge_answersList = '';
        $freeFive = '';
        if($competition)
        {
            if(!is_numeric($competition['0'])){
               return redirect()->back()->withInput()->withErrors(["error" => "This is not a CID. Please select competitionfrom list or follow correct format."]);  
            }
        	$competitionList = Competitions::where('competition_id', '=', $competition['0'])->get();
        	if(!$competitionList){
        		return redirect()->back()->withInput()->withErrors(["error" => "Competition is not available in Competition list."]); 
        	}

        	$competition_id = $competitionList['0']['competition_id'];
        	$prize_id = $competitionList['0']['prize_id'];
        	$challenge_id = $competitionList['0']['challenge_id'];
            $availabl_tickets = $competitionList['0']['availabl_tickets'];

            $freeFive = (5 / 100) * $availabl_tickets;
            $freeFive = round($freeFive);

            $Challenge_answersList = Challenge_answers::where('question_id', '=', $challenge_id)->get();        	

        }else{

        	return redirect()->back()->withInput()->withErrors(["error" => "Competition should not empty."]); 

        }
        
        $player = $input['player'];
        $playerList = '';
        if($player)
        {
        	$playerList = User::where('email', '=', $player)->get();
        	if(!$playerList){
        		return redirect()->back()->withInput()->withErrors(["error" => "Player is not available in Player list."]); 
        	}
        	

        }else{

        	return redirect()->back()->withInput()->withErrors(["error" => "Player should not empty."]); 

        }

        $challenge_answer = $input['challenge_answer'];
        
        $ansStatus = 'Fail';
        if($challenge_answer)
        {
        	$challen = $this->toNum($challenge_answer);
        	foreach ($Challenge_answersList as $key => $value) {
        		$k = $key;
        		if($k == $challen){
        		   $correct_answer = $value['correct_answer'];
        		   if($correct_answer){
        		   	$ansStatus = 'Pass';
        		   }
                   
        		}
                
        		
        	}
        	

        }else{

        	return redirect()->back()->withInput()->withErrors(["error" => "Challenge Answer should not empty."]); 

        }

        $user_id = auth()->user()->id;

        $Tickets = Tickets::where('ticket_type', '=', 'free')->get();
        $Tickets = json_encode($Tickets);
        $Tickets = json_decode($Tickets, true);
        $TicketsTotal = count($Tickets);
        $Ticket = new Tickets();
        if($TicketsTotal <= $freeFive){

            
            $Ticket->competition_id = $competition_id;
            $Ticket->prize_id = $prize_id;
            $Ticket->player_id = $playerList['0']['id'];
            $Ticket->answer_status = $ansStatus;
            $Ticket->answer = $challenge_answer;
            $Ticket->created_by = $user_id;
            $Ticket->ticket_type = 'free';
            $Ticket->status = 1;
            $Ticket->save();
            $UserPlayer = User::where('email', '=', $player)->first();
            $UserPlayer->notify(new NewTicketsCreatedMarkedAsFreeAndValidationPassed());
            $ticket_id = $Ticket->ticket_id;

            $current_date_time = Carbon::now()->toDateTimeString();
            $first_name = auth()->user()->first_name; 
            $surname = auth()->user()->surname;
            $email = auth()->user()->email;

            $Logs = new Logs();
            $Logs->log_category = 'Ticket';
            $Logs->log_category_id = $ticket_id;
            $Logs->date = $current_date_time;
            $Logs->timestamp = $current_date_time;
            $Logs->users_name = $first_name.' '.$surname;
            $Logs->email_id = $email;
            $Logs->description = 'A ticket is created';
            $Logs->log_details = $Ticket;
            $Logs->status = 1;
            $Logs->save();


            return redirect(route('competitions.ticketList', $competition_id))->with(['message' => 'A ticket is created!']);

        }
        
        $UserPlayer = User::where('email', '=', $player)->first();
        $UserPlayer->notify(new NewTicketsCreatedMarkedAsFreeAndValidationFailed());

        return redirect()->back()->withInput()->withErrors(["error" => "You have already reached at your free ticket limit."]); 

        }else{
              return view('pages.page-403');
        }
    }
    

    public function ticketList(Request $request, $competition_id){
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator'){
        $input = $request->all(); 
        
        if(@$input['result']){
        	$ticketList = DB::table('tickets')
	        ->select('tickets.ticket_id', 'users.id as player_id', 'users.first_name', 'users.surname', 'tickets.answer_status','tickets.created_at')
	        ->join('users','users.id','=','tickets.player_id')
	        ->where([['tickets.competition_id', '=', $competition_id], ['tickets.answer_status', 'LIKE', '%'.@$input['result'].'%'], ['users.organisation_id', '=', auth()->user()->organisation_id]])
	        ->orderBy('tickets.created_at', 'desc')
	        ->paginate(50);

        }else{

        	$ticketList = DB::table('tickets')
	        ->select('tickets.ticket_id', 'users.id as player_id', 'users.first_name', 'users.surname', 'tickets.answer_status','tickets.created_at')
	        ->join('users','users.id','=','tickets.player_id')
	        ->where([['tickets.competition_id', '=', $competition_id], ['users.organisation_id', '=', auth()->user()->organisation_id]])
	        ->orderBy('tickets.created_at', 'desc')
	        ->paginate(50);

        }

        

        $prizeName = DB::table('competitions')
        ->select('prizes.prize_name')
        ->join('prizes','prizes.prize_id','=','competitions.prize_id')
        ->where([['competitions.competition_id', '=', $competition_id], ['prizes.organisation_id', '=', auth()->user()->organisation_id], ['competitions.organisation_id', '=', auth()->user()->organisation_id]])
        ->orderBy('competitions.competition_id', 'desc')
        ->get();

        $competitionList = Competitions::where('competition_id', '=', $competition_id)->get();

        $breadcrumbs = [['link' => "competitions", 'name' => "Competitions"], ['name' => "Tickets"]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        return view('pages.page-competitionTicket-list', ['pageConfigs' => $pageConfigs, 'ticketList' => $ticketList, 'competitionList' => $competitionList, 'prizeName' => $prizeName['0']->prize_name, 'competition_id' => $competition_id, 'result' => @$input['result']], ['breadcrumbs' => $breadcrumbs]);

        }else{
              return view('pages.page-403');
        }

    }

    public function getAllTicketInCSV(Request $request, $competition_id)
    {

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator'){

        $input = $request->all(); 
        $result =  @$input['result']; //print_r($result);
        //$search =  @$input['search'] ? $input['search'] : '';
        
        if(@$input['result']){
          $ticketList = DB::table('tickets')
          ->select('tickets.ticket_id', 'users.id as player_id', 'users.first_name', 'users.surname', 'tickets.answer_status','tickets.created_at')
          ->join('users','users.id','=','tickets.player_id')
          ->where([['tickets.competition_id', '=', $competition_id], ['tickets.answer_status', 'LIKE', '%'.@$input['result'].'%'], ['users.organisation_id', '=', auth()->user()->organisation_id]])
          ->orderBy('tickets.created_at', 'desc')
          ->get();

        }else{

          $ticketList = DB::table('tickets')
          ->select('tickets.ticket_id', 'users.id as player_id', 'users.first_name', 'users.surname', 'tickets.answer_status','tickets.created_at')
          ->join('users','users.id','=','tickets.player_id')
          ->where([['tickets.competition_id', '=', $competition_id], ['users.organisation_id', '=', auth()->user()->organisation_id]])
          ->orderBy('tickets.created_at', 'desc')
          ->get();

        }

        
        

        //print_r($competitionList);die();

        $filename = storage_path('app/public/competitionsCSV/tickets'.strtotime('now').'.csv');
        $handle = fopen($filename, 'w+');
        fputcsv($handle, array('TID', 'Entry Date', 'Player Name', 'Result'));
        $ticketList = json_encode($ticketList);
        $ticketList = json_decode($ticketList, true); //print_r($ticketList);
        foreach($ticketList as $ticket){ //print_r($ticket);
                  $color = '';
                  if($ticket['answer_status'] == 'Pass'){
                    $color = 'blue';
                  }
                  if($ticket['answer_status'] == 'Fail'){
                    $color = 'red';
                    
                  }

                  

            fputcsv($handle, array($ticket['ticket_id'], $ticket['created_at'], $ticket['first_name'].' '.$ticket['surname'], $ticket['answer_status']));
        }

        fclose($handle);

        $headers = array(
            'Content-Type' => 'text/csv',
        );
         //return Storage::download('file.jpg', $name, $headers);  
        return FacadeResponse::download($filename, 'tickets.csv', $headers);

        }else{
              return view('pages.page-403');
        }


    }

    public function ticketEdit(Request $request, $competition_id){
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator'){

        $ticketList = DB::table('tickets')
        ->select('tickets.*', 'users.email', 'competitions.competition_id', 'prizes.prize_name')
        ->join('users','users.id','=','tickets.player_id')
        ->join('competitions','competitions.competition_id','=','tickets.competition_id')
        ->join('prizes', 'prizes.prize_id', '=', 'competitions.prize_id')
        ->where([['tickets.ticket_id', '=', $competition_id], ['users.organisation_id', '=', auth()->user()->organisation_id], ['competitions.organisation_id', '=', auth()->user()->organisation_id], ['prizes.organisation_id', '=', auth()->user()->organisation_id]])
        ->orderBy('tickets.ticket_id', 'desc')
        ->get();

        $log = Logs::where([['log_category', '=', 'Ticket'], ['log_category_id', '=', $competition_id]])->orderBy('created_at', 'desc')->get();


        $competitionList = DB::table('competitions')->select('competitions.competition_id', 'prizes.prize_name')
                            ->join('prizes', 'prizes.prize_id', '=', 'competitions.prize_id')
                            ->where([['competitions.status', '=', 1], ['competitions.organisation_id', '=', auth()->user()->organisation_id], ['prizes.organisation_id', '=', auth()->user()->organisation_id]])

                           ->get(); //print_r($competitionList); 

        $userList = User::where('role', '=', 'player')->get();

        $breadcrumbs = [['link' => "tickets/".$ticketList['0']->competition_id, 'name' => "Tickets"], ['name' => "Edit a Ticket"]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        return view('pages.page-freeTicket-edit', ['pageConfigs' => $pageConfigs, 'ticketList' => $ticketList, 'competitionList' => $competitionList, 'userList' => $userList, 'log' => $log], ['breadcrumbs' => $breadcrumbs]);

        }else{
              return view('pages.page-403');
        }

    }

    public function ticketUpdate(Request $request, $ticket_id){
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'competition_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator'){

        $input = $request->all(); 
        $competition = $input['competition'];
        $competition = explode(' - ', $competition);
        $competitionList = '';
        $Challenge_answersList = '';
        $freeFive = '';
        if($competition)
        {
            if(!is_numeric($competition['0'])){
               return redirect()->back()->withInput()->withErrors(["error" => "This is not a CID. Please select competitionfrom list or follow correct format."]);  
            }

            $competitionList = Competitions::where('competition_id', '=', $competition['0'])->get();
            if(!$competitionList){
                return redirect()->back()->withInput()->withErrors(["error" => "Competition is not available in Competition list."]); 
            }

            $competition_id = $competitionList['0']['competition_id'];
            $prize_id = $competitionList['0']['prize_id'];
            $challenge_id = $competitionList['0']['challenge_id'];
            $availabl_tickets = $competitionList['0']['availabl_tickets'];

            $freeFive = (5 / 100) * $availabl_tickets;
            $freeFive = round($freeFive);

            $Challenge_answersList = Challenge_answers::where('question_id', '=', $challenge_id)->get();            

        }else{

            return redirect()->back()->withInput()->withErrors(["error" => "Competition should not empty."]); 

        }

        $player = $input['player'];
        $playerList = '';
        if($player)
        {
            $playerList = User::where('email', '=', $player)->get();
            if(!$playerList){
                return redirect()->back()->withInput()->withErrors(["error" => "Player is not available in Player list."]); 
            }
            

        }else{

            return redirect()->back()->withInput()->withErrors(["error" => "Player should not empty."]); 

        }

        $challenge_answer = $input['challenge_answer'];
        
        $ansStatus = 'Fail';
        if($challenge_answer)
        {
            $challen = $this->toNum($challenge_answer);
            foreach ($Challenge_answersList as $key => $value) {
                $k = $key;
                if($k == $challen){
                   $correct_answer = $value['correct_answer'];
                   if($correct_answer){
                    $ansStatus = 'Pass';
                   }
                   
                }
                
                
            }
            

        }else{

            return redirect()->back()->withInput()->withErrors(["error" => "Challenge Answer should not empty."]); 

        }

        $user_id = auth()->user()->id;

        $Tickets = Tickets::where('ticket_type', '=', 'free')->get();
        $Tickets = json_encode($Tickets);
        $Tickets = json_decode($Tickets, true);
        $TicketsTotal = count($Tickets);
        //if($TicketsTotal <= $freeFive){
            $current_date_time = Carbon::now()->toDateTimeString();
            $first_name = auth()->user()->first_name; 
            $surname = auth()->user()->surname;
            $email = auth()->user()->email;

            $Logs = new Logs();
            $Logs->log_category = 'Ticket';
            $Logs->log_category_id = $ticket_id;
            $Logs->date = $current_date_time;
            $Logs->timestamp = $current_date_time;
            $Logs->users_name = $first_name.' '.$surname;
            $Logs->email_id = $email;
            $Logs->description = 'A ticket is updated';
            

            $Ticket = Tickets::where('ticket_id',$ticket_id)->first();
            $Logs->log_before_changes = $Ticket;
            $Logs->status = 1;
            $Logs->save();
            $log_id = $Logs->log_id;

            $LogsUpdate = Logs::where('log_id',$log_id)->first();

            $Ticket->competition_id = $competition_id;
            $Ticket->prize_id = $prize_id;
            $Ticket->player_id = $playerList['0']['id'];
            $Ticket->answer_status = $ansStatus;
            $Ticket->answer = $challenge_answer;
            $Ticket->created_by = $user_id;
            $Ticket->ticket_type = 'free';
            $Ticket->status = 1;
            $Ticket->update();
            //$ticket_id = $Ticket->id;

            $LogsUpdate->log_details = $Ticket;
            $LogsUpdate->update(); 

           

            return redirect(route('competitions.ticketList', $competition_id))->with(['message' => 'A ticket is updated!']);

            }else{
              return view('pages.page-403');
            }

        //}

    }

    public function closedCompetitionDateExtention(Request $request){

    	$competition = Competitions::get();
    	$competition = json_encode($competition);
    	$competition = json_decode($competition, true);

    	foreach ($competition as $key => $value) {
    		$competition_id = $value['competition_id'];
    		$prize_id = $value['prize_id'];
    		$challenge_id = $value['challenge_id'];
    		$organisation_id = $value['organisation_id'];
    		$ticket_price = $value['ticket_price'];
    		$availabl_tickets = $value['availabl_tickets'];
    		$sold_ticket = $value['sold_ticket'];
    		$closed_date = $value['closed_date'];
    		$closed_date_extension_count = $value['closed_date_extension_count'];
    		$alternative_cash_prize = $value['alternative_cash_prize'];
    		$status = $value['status'];

    		if($availabl_tickets == $sold_ticket){

    			$competition = Competitions::where('competition_id',$competition_id)->first();
		        
		        $competition->status = 2;
		        $competition->update();

    		}elseif ($availabl_tickets != $sold_ticket) { 
                $now = Carbon::now()->toDateTimeString();
    			if($closed_date <= $now){
                    
					$competition = Competitions::where('competition_id',$competition_id)->first();
                    $setting = Global_settings::orderBy('id', 'desc')->first();
			        $ticketSoldPercentage = ($sold_ticket*100)/$availabl_tickets; 
			        $totalSoldTicketCash = $ticket_price*$sold_ticket; 
                    $alternative_cash_prize_percentage = $setting['alternative_cash_prize_percentage'];
			        $cash_prize = ($totalSoldTicketCash * $alternative_cash_prize_percentage)/100;
			        if($closed_date_extension_count >= 3){

			        	$competition->status = 2;
			        	$competition->closed_date = $now;
			        	$competition->alternative_cash_prize = $cash_prize;

			        }else{
			        	$date = $closed_date;
						$date = strtotime($date);
						$date = strtotime("+7 day", $date);
						$date = date('Y-m-d 23:59:59', $date);
						$competition->closed_date_extension_count = $closed_date_extension_count+1;
			        	$competition->closed_date = $date;

			        }
			        $competition->update();
    			}
    			
    		}
    	}

    }

    public function toNum($data) {
    $alphabet = array( 'a', 'b', 'c', 'd', 'e',
                       'f', 'g', 'h', 'i', 'j',
                       'k', 'l', 'm', 'n', 'o',
                       'p', 'q', 'r', 's', 't',
                       'u', 'v', 'w', 'x', 'y',
                       'z'
                       );
    $alpha_flip = array_flip($alphabet);
    $return_value = -1;
    $length = strlen($data);
    for ($i = 0; $i < $length; $i++) {
        $return_value +=
            ($alpha_flip[$data[$i]] + 1) * pow(26, ($length - $i - 1));
    }
    return $return_value;
}

}
