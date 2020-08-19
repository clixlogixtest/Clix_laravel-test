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
use App\Model\Challenge_answers;
use App\Model\Challenges;
use App\Model\Logs;
use App\Model\Competitions;
use App\PasswordReset;
use Validator;
use App\Http\Requests\ChallengeRequest;
use App\Http\Requests\ChallengeUpdateRequest;
use Carbon\Carbon;

class ChallengeController extends Controller
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

        if(auth()->user()->role == 'global_administrator' || auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator' || auth()->user()->role == 'user_administrator,prize_administrator'){
        
        $role = auth()->user()->role;
        $global_challenge_bank = 0; 
        if($role == 'global_administrator'){
            $global_challenge_bank = 1;
        }
        
        $input = $request->all(); 
        $Search          =  @$input['Search'] ? $input['Search'] : '';
        $filterCondition = [];
        if($Search.'%'){
           $filterCondition[] = ['global_challenge_bank', '=', $global_challenge_bank];
           $filterCondition[] = ['organisation_id', '=', auth()->user()->organisation_id];
           $filterCondition[] = ['question', 'Like', '%'.$Search.'%'];
        }elseif(!$Search){
           $filterCondition[] = ['global_challenge_bank', '=', $global_challenge_bank];
           $filterCondition[] = ['organisation_id', '=', auth()->user()->organisation_id];
        }


        $questionTotal= Challenges::where($filterCondition)->orderBy('question_id', 'desc')->paginate(50);

        $breadcrumbs = [];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        return view('pages.page-challenges-list', ['pageConfigs' => $pageConfigs, 'questionTotal' => $questionTotal, 'Search' => $Search], ['breadcrumbs' => $breadcrumbs]);
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

        if(auth()->user()->role == 'global_administrator' || auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator' || auth()->user()->role == 'user_administrator,prize_administrator'){
        
            $breadcrumbs = [['link' => "challenges", 'name' => "Challenges"], ['name' => "Add a Challenge"]];
            //Pageheader set true for breadcrumbs
            $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

            return view('pages.page-challenges-add', ['pageConfigs' => $pageConfigs], ['breadcrumbs' => $breadcrumbs]);

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
    public function store(ChallengeRequest $request)
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role == 'global_administrator' || auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator' || auth()->user()->role == 'user_administrator,prize_administrator'){



        $input = $request->all(); 
        $id = auth()->user()->id;
        $role = auth()->user()->role;
        $global_challenge_bank = 0; 
        if($role == 'global_administrator'){
            $global_challenge_bank = 1;
        }
        
        $answer_correct = @$input['answer_correct'];
        //$answer_correct1 = $answer_correct+1;
        if(!$answer_correct){
            return redirect()->back()->withInput()->withErrors(["error" => "Please select correct answer."]); 
        }
        
        /*$corrAns = array();
        foreach ($answer_correct as $key => $value) {
            $corrAns[$value] = 1;
        }
        ksort($corrAns);*/
        

        $challenge = new Challenges;
        $challenge->question = $input['question'];
        $challenge->created_by = $id;
        $challenge->organisation_id = auth()->user()->organisation_id;
        $challenge->status = 1;
        $challenge->global_challenge_bank = $global_challenge_bank;
        $challenge->save();
        $question_id = $challenge->question_id;

        $Logs = new Logs();
        $Logs->log_category = 'Challenge';
        $Logs->log_category_id = $question_id;

        $answer_correct = $answer_correct-1;
        $ans = array();
        for($i=0;$i<$input['answerCount'];$i++) {
            $corr=0;
            
            if($answer_correct == $i){
               $corr=1;
            }
            $answer = $input['answer'];

            $Challenge_answer = new Challenge_answers;
            $Challenge_answer->question_id = $question_id;
            $Challenge_answer->answer = $answer[$i];
            $Challenge_answer->correct_answer = $corr;
            $Challenge_answer->status = 1;
            $Challenge_answer->save();
            $ans[] = $Challenge_answer;
        }

        $current_date_time = Carbon::now()->toDateTimeString();
        $first_name = auth()->user()->first_name; 
        $surname = auth()->user()->surname;
        $email = auth()->user()->email;

        $log_details =array("challenge" => $challenge, "answer" => $ans);
        $Logs->date = $current_date_time;
        $Logs->timestamp = $current_date_time;
        $Logs->users_name = $first_name.' '.$surname;
        $Logs->email_id = $email;
        $Logs->description = 'A challenge is created';
        $Logs->log_details = json_encode($log_details);
        $Logs->status = 1;
        $Logs->save();

        
        return redirect(route('challenges.index'))->with(['message' => 'The question and its answer is created!']);

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

        if(auth()->user()->role == 'global_administrator' || auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator' || auth()->user()->role == 'user_administrator,prize_administrator'){
        
        $question = Challenges::where([['question_id', '=', $id], ['organisation_id', '=', auth()->user()->organisation_id]])->get();
        $Challenge_answers = Challenge_answers::where('question_id',$id)->get();
        $breadcrumbs = [['link' => "challenges", 'name' => "Challenges"], ['name' => "Edit a Challenge"]];
        $log = Logs::where([['log_category', '=', 'Challenge'], ['log_category_id', '=', $id]])->orderBy('created_at', 'desc')->get();

        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
        return view('pages.page-challenges-edit', ['pageConfigs' => $pageConfigs, 'question' => $question, 'Challenge_answers' => $Challenge_answers, 'log' => $log], ['breadcrumbs' => $breadcrumbs]);

        }else{
              return view('pages.page-403');
        }
    }

    public function editChallenge($id)
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role == 'global_administrator' || auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator' || auth()->user()->role == 'user_administrator,prize_administrator'){
        
        $question = Challenges::where([['question_id', '=', $id], ['organisation_id', '=', auth()->user()->organisation_id]])->get();
        if(!$question){
            return redirect(route('challenges.index'))->withErrors(["error" => "You cann't edit this challenge."]); 
        }
        
        $Challenge_answers = Challenge_answers::where('question_id',$id)->get();
        $breadcrumbs = [['link' => "challenges", 'name' => "Challenges"], ['name' => "Edit a Challenge"]];
        $log = Logs::where([['log_category', '=', 'Challenge'], ['log_category_id', '=', $id]])->orderBy('created_at', 'desc')->get();

        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
        return view('pages.page-challenges-edit', ['pageConfigs' => $pageConfigs, 'question' => $question, 'Challenge_answers' => $Challenge_answers, 'log' => $log], ['breadcrumbs' => $breadcrumbs]);

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
    public function update(Request $request, $question)
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        if(auth()->user()->role == 'global_administrator' || auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator' || auth()->user()->role == 'user_administrator,prize_administrator'){

        $validator = Validator::make($request->all(), [ 
            'question' => 'required',
            'answer.*' => 'required',

        ],[
          'answer.*.required' => 'A answer is required',
        ]);
        if($validator->fails()){ 
            
            return redirect()->back()->withInput()->withErrors($validator); 
        }



        $input = $request->all(); 
        $id = auth()->user()->id;
        $role = auth()->user()->role;
        $global_challenge_bank = 0; 
        if($role == 'global_administrator'){
            $global_challenge_bank = 1;
        }
        
        $answer_correct = $request->answer_correct;
        if(!$answer_correct){
            return redirect()->back()->withInput()->withErrors(["error" => "Please select correct answer."]); 
        }
        
        $current_date_time = Carbon::now()->toDateTimeString();
        $first_name = auth()->user()->first_name; 
        $surname = auth()->user()->surname;
        $email = auth()->user()->email;

        $Logs = new Logs();
        $Logs->log_category = 'Challenge';
        $Logs->log_category_id = $question;
        $Logs->date = $current_date_time;
        $Logs->timestamp = $current_date_time;
        $Logs->users_name = $first_name.' '.$surname;
        $Logs->email_id = $email;
        $Logs->description = 'A challenge is updated';


        //$challenge = new Challenges;
        $challenge = Challenges::where('question_id',$question)->first();
        $Challenge_answers = Challenge_answers::where('question_id',$question)->get();

        $log_details =array("challenge" => $challenge, "answer" => $Challenge_answers);

        $Logs->log_before_changes = json_encode($log_details);
        $Logs->status = 1;
        $Logs->save();
        $log_id = $Logs->log_id;

        $LogsUpdate = Logs::where('log_id',$log_id)->first();

        $challenge->question = $input['question'];
        $challenge->created_by = $id;
        $challenge->organisation_id = auth()->user()->organisation_id;
        $challenge->status = 1;
        $challenge->global_challenge_bank = $global_challenge_bank;
        $challenge->update();

        


        /*$corrAns = array();
        foreach ($answer_correct as $key => $value) {
            $corrAns[$value] = 1;
        }
        ksort($corrAns);*/

        
        $ans = array();
        $answer_correct = $answer_correct - 1; 

        for($i=0;$i<$input['answerCount'];$i++) {
            $corr=0;
            if($answer_correct == $i){
               $corr=1;
            }
            $answer = $input['answer'];
            

            
            

                if(@$Challenge_answers[$i]['answer_id']){
                $Challenge_answer = Challenge_answers::where(array('answer_id' => @$Challenge_answers[$i]['answer_id']))->first();
                $Challenge_answer->question_id = $question;
                $Challenge_answer->answer      = $answer[$i];
                $Challenge_answer->correct_answer = $corr;
                $Challenge_answer->status = 1;
                $Challenge_answer->update();
                $ans[] = $Challenge_answer;
                
                }else{
                    $Challenge_answer = new Challenge_answers();
                    $Challenge_answer->question_id = $question;
                    $Challenge_answer->answer      = $answer[$i];
                    $Challenge_answer->correct_answer = $corr;
                    $Challenge_answer->status = 1;
                    $Challenge_answer->save();
                    $ans[] = $Challenge_answer;
                }
            
        }

        if(count($Challenge_answers) > $input['answerCount']){
            for ($i=$input['answerCount']; $i < count($Challenge_answers); $i++) { 
                $Challenge_answer = Challenge_answers::where(array('answer_id' => @$Challenge_answers[$i]['answer_id']))->first();
                $Challenge_answer->delete();
            }

        }

        

        $log_details =array("challenge" => $challenge, "answer" => $ans);
        
        $LogsUpdate->log_details = $log_details;
        $LogsUpdate->update(); 

        return redirect(route('challenges.index'))->with(['message' => 'The question and its answer is updated!']);

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

        if(auth()->user()->role == 'global_administrator' || auth()->user()->role == 'organisation_administrator' || auth()->user()->role == 'prize_administrator' || auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator' || auth()->user()->role == 'competition_administrator,prize_administrator' || auth()->user()->role == 'user_administrator,prize_administrator'){


        $Competitions = Competitions::where([['challenge_id', '=', $id]])->get();
        $Competitions = json_encode($Competitions);
        $Competitions = json_decode($Competitions, true);

        if($Competitions){
            return redirect()->back()->withInput()->withErrors(["error" => "This challange is associated with an active competition."]);
        }

        $Challenges = Challenges::find($id);    
        $Challenges->delete();
        $Challenge_answers = Challenge_answers::where('question_id',$id)->get();
        foreach ($Challenge_answers as $key => $value) {
            $answer_id = $value->answer_id;
            $Challenge_answers = Challenge_answers::find($answer_id);    
            $Challenge_answers->delete();
        }

            $current_date_time = Carbon::now()->toDateTimeString();
            $first_name = auth()->user()->first_name; 
            $surname = auth()->user()->surname;
            $email = auth()->user()->email;

            $log_details =array("challenge" => $Challenges, "answer" => $Challenge_answers);

            $Logs = new Logs();
            $Logs->log_category = 'Challenge';
            $Logs->log_category_id = $id;
            $Logs->date = $current_date_time;
            $Logs->timestamp = $current_date_time;
            $Logs->users_name = $first_name.' '.$surname;
            $Logs->email_id = $email;
            $Logs->description = 'A challenge is deleted';
            $Logs->log_details = json_encode($log_details);
            $Logs->status = 1;
            $Logs->save();


        return redirect(route('challenges.index'))->with(['message' => 'The question and its answer is deleted!']);

        }else{
              return view('pages.page-403');
        }
    }
}
