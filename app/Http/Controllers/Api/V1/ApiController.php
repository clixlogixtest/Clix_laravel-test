<?php

namespace App\Http\Controllers\Api\V1;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller; 
use App\Model\Competitions;
use App\Model\Prizes;
use App\Model\Challenges;
use App\Model\Challenge_answers;
use App\Model\Tickets;
use App\Model\Logs;
use App\Model\Global_settings;
use App\Model\page_faqs;
use App\Model\page_how_to_plays;
use App\Model\userEmails;
use App\User;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Carbon\Carbon;
use App\Notifications\PasswordResetSuccess;
use App\Notifications\UserAccountUpdatedSuccessfully;
use App\Notifications\NewTicketsCreatedMarkedAsAndValidationPassed;
use App\Notifications\EmailVerificationOtp;
use App\Mail\otpEmail;
use Illuminate\Support\Facades\Mail;

class ApiController extends Controller
{
     public function __construct(){

       $this->middleware('auth:api')->except('openCompetitionList', 'openCompetitionDetails', 'termsAndConditions', 'faqs', 'howToPlay');

    }

    public $successStatus = 200;

    public function openCompetitionList(Request $request,$organisation_id){

    	

    	$competitionList = DB::table('competitions')
            ->select('competitions.competition_id','prizes.prize_name', 'prizes.file','competitions.availabl_tickets','competitions.sold_ticket','competitions.ticket_price','competitions.closed_date', 'competitions.status', 'competitions.organisation_id')
            ->join('prizes','competitions.prize_id','=','prizes.prize_id')
            ->where([['competitions.organisation_id', '=', $organisation_id], ['competitions.status', '=', '1']])
            ->orderBy('competitions.closed_date', 'asc')
            ->paginate(50);
        
        $competitionList = json_encode($competitionList);
        $competitionList = json_decode($competitionList, true);

        $data = array();
        if(count($competitionList['data']) > 0){
        	foreach ($competitionList['data'] as $key => $value) {

                $soldTicket = DB::table('tickets')->select(DB::raw('COUNT(tickets.ticket_id) as pass'))->where([["competition_id", "=", $value['competition_id']]])->first();
                
                $soldTicket = json_encode($soldTicket);
                $soldTicket = json_decode($soldTicket, true);
                $soldTicket = $value['availabl_tickets'] - $soldTicket['pass'];

                  $data[$key]['competition_id'] = $value['competition_id'];
                  $data[$key]['prize_name']     = $value['prize_name'];
                  $data[$key]['prize_images']   = json_decode($value['file'], true);
                  //$data[$key]['status']         = $value['status'];
                  $data[$key]['organisation_id'] = $value['organisation_id'];
                  $data[$key]['closed_date'] = $value['closed_date'];
                  $data[$key]['availableRemainingTickets'] = $soldTicket;
                  $data[$key]['competition_enter_price'] = $value['ticket_price'];

	        }
        }
        

        $page = array(
            "first_page_url" => $competitionList['first_page_url'],
            "from" => ['$product->from'],
            "last_page" => $competitionList['last_page'],
            "last_page_url" => $competitionList['last_page_url'],
            "next_page_url" => $competitionList['next_page_url'],
            "path" => $competitionList['path'],
            "per_page" => $competitionList['per_page'],
            "prev_page_url"=> $competitionList['prev_page_url'],
            "to" => $competitionList['to'],
            "total" => $competitionList['total']
            );
        $resp = [
                'message' => 'SUCCESS',
                'status'  => intval(Response::HTTP_OK),
                'data' => $data,
                'page' => $page
            ];
        return response()->json($resp, $this-> successStatus);


    }

    public function openCompetitionDetails(Request $request, $competition_id){

        $competitionList = DB::table('competitions')
            ->select('competitions.competition_id','prizes.prize_name', 'prizes.file', 'prizes.description','competitions.availabl_tickets','competitions.sold_ticket','competitions.closed_date', 'competitions.status', 'competitions.ticket_price', 'competitions.organisation_id', 'competitions.challenge_id')
            ->join('prizes','competitions.prize_id','=','prizes.prize_id')
            ->where([['competitions.competition_id', '=', $competition_id], ['competitions.status', '=', '1']])
            ->orderBy('competitions.closed_date', 'asc')
            ->get();
        
        $competitionList = json_encode($competitionList);
        $competitionList = json_decode($competitionList, true);

        $data = array();
        if(count($competitionList) > 0){
            foreach ($competitionList as $key => $value) { //print_r($value);
                $soldTicket = DB::table('tickets')->select(DB::raw('COUNT(tickets.ticket_id) as pass'))->where([["competition_id", "=", $value['competition_id']]])->first();
                
                $soldTicket = json_encode($soldTicket);
                $soldTicket = json_decode($soldTicket, true);
                $soldTicket = $value['availabl_tickets'] - $soldTicket['pass'];
                
                $fail = DB::table('tickets')->select(DB::raw('COUNT(tickets.ticket_id) as fail'))->where([["competition_id", "=", $value['competition_id']], ["answer_status", "=", 'Fail']])->first();
                $fail = json_encode($fail);
                $fail = json_decode($fail, true);


                $data[$key]['competition_id'] = $value['competition_id'];
                $data[$key]['prize_name']     = $value['prize_name'];
                $data[$key]['prize_images']   = json_decode($value['file'], true);
                $data[$key]['prize_description'] = $value['description'];
                $data[$key]['closed_date'] = $value['closed_date'];
                //$data[$key]['challenge'] = $this->openCompetitionChallengeDetails($value['challenge_id']);
                if($soldTicket-$fail['fail']){
                    $data[$key]['odds'] = '1/'.($soldTicket-$fail['fail']);
                }else{
                    $data[$key]['odds'] = 0;
                }
                
                $data[$key]['availableRemainingTickets'] = $soldTicket;
                $data[$key]['competition_enter_price'] = $value['ticket_price'];
                //$data[$key]['ticket_price']   = $value['ticket_price'];
                $data[$key]['status']         = $value['status'];
                $data[$key]['organisation_id'] = $value['organisation_id'];

            }
        }
        

        
        $resp = [
                'message' => 'SUCCESS',
                'status'  => intval(Response::HTTP_OK),
                'data' => $data
            ];
        return response()->json($resp, $this-> successStatus);


    }

    public function challengesQuestion(Request $request){
        $validator = Validator::make([
            'competition_id' => request('competition_id')
            
        ],
        [
            'competition_id' => 'required'
            
        ]);

        if($validator->fails()){ 
            $resp = [
                'error' => $validator->errors(),
                'status'  => intval(Response::HTTP_NOT_FOUND)
            ];
            return response()->json($resp, Response::HTTP_OK);    
        }

        $competitionList = DB::table('competitions')
            ->select('competitions.competition_id','prizes.prize_name', 'prizes.file', 'prizes.description','competitions.availabl_tickets','competitions.sold_ticket','competitions.closed_date', 'competitions.status', 'competitions.ticket_price', 'competitions.organisation_id', 'competitions.challenge_id', 'challenges.*')
            ->join('prizes','competitions.prize_id','=','prizes.prize_id')
            ->join('challenges','competitions.challenge_id','=','challenges.question_id')
            ->where([['competitions.competition_id', '=', $request->competition_id], ['competitions.status', '=', '1']])
            ->orderBy('competitions.closed_date', 'asc')
            ->get();
        
        $competitionList = json_encode($competitionList);
        $competitionList = json_decode($competitionList, true);

        

        $answer = DB::table('challenge_answers')->select('challenge_answers.answer')
            ->where([["challenge_answers.question_id", "=", $competitionList['0']['challenge_id']]])->get();
        $answer = json_encode($answer);
        $answer = json_decode($answer, true); 
        /*$competitionList['0']['file'] = json_decode($competitionList['0']['file'], true);
        $competitionList['0']['prize_title'] = $competitionList['0']['prize_name'];*/
        $competitionList['0']['challenge_answers'] = $answer; 

        $data = array();
        foreach ($competitionList as $key => $value) {
            $data[$key]['question_id'] = $value['question_id'];
            $data[$key]['challenge_question'] = $value['question'];
            $data[$key]['challenge_answers'] = $answer;
        }

        $resp = [
                'message' => 'SUCCESS',
                'status'  => intval(Response::HTTP_OK),
                'data' => $data
            ];
        return response()->json($resp, $this-> successStatus); 


    }

    public function getPlayerWalletBallance(Request $request){
        $user = auth::user(); 
        $user = json_encode($user);
        $user = json_decode($user, true);

        $resp = [
                'message' => 'SUCCESS',
                'status'  => intval(Response::HTTP_OK),
                'data' => array("walletBalance" => $user['total_coin'])
            ];
        
        return response()->json($resp, $this-> successStatus);
    }

    public function termsAndConditions(Request $request){
        $Global_settings = Global_settings::first();
         
        $Global_settings = json_encode($Global_settings);
        $Global_settings = json_decode($Global_settings, true);

        $resp = [
                'message' => 'SUCCESS',
                'status'  => intval(Response::HTTP_OK),
                'data' => array("termsUrl" => $Global_settings['terms_and_condition'])
            ];
        
        return response()->json($resp, $this-> successStatus);
    }

    public function faqs(Request $request){
        $faqs = page_faqs::get();
         
        $faqs = json_encode($faqs);
        $faqs = json_decode($faqs, true);



        $resp = [
                'message' => 'SUCCESS',
                'status'  => intval(Response::HTTP_OK),
                'data' => $faqs
            ];
        
        return response()->json($resp, $this-> successStatus);
    }

    public function howToPlay(Request $request){
        $play = page_how_to_plays::first();
         
        $play = json_encode($play);
        $play = json_decode($play, true);



        $resp = [
                'message' => 'SUCCESS',
                'status'  => intval(Response::HTTP_OK),
                'data' => $play
            ];
        
        return response()->json($resp, $this->successStatus);
    }

    public function myAccount(Request $request){
        $user = auth::user(); 
        $user = json_encode($user);
        $user = json_decode($user, true); 

        $contact_number = $user['contact_number'];

        $userDetails = array(
            "user_id" => $user['id'],
            "first_name" => $user['first_name'],
            "surname" => $user['surname'],
            "email" => $user['email'],
            "town" => $user['town'],
            "address" => $user['address'],
            "post_code" => $user['post_code'],
            "contact_number" => "$contact_number",
            "date_of_birth" => $user['date_of_birth'],

        );

        $resp = [
                'message' => 'SUCCESS',
                'status'  => intval(Response::HTTP_OK),
                'data' => $userDetails
            ];
        
        return response()->json($resp, $this->successStatus);
    }

    public function sendEmailVerificationOtp(Request $request){

        $validator = Validator::make([
            'email' => request('email')
            
        ],
        [
           
            'email' => 'required|email|unique:users,email,'.auth::user()->id

        ]);

        if($validator->fails()){ 
            $resp = [
                'error' => $validator->errors(),
                'status'  => intval(Response::HTTP_NOT_FOUND)
            ];
            return response()->json($resp, Response::HTTP_OK);    
        }

        $generator = "1357902468"; 
        $result = ""; 
  
        for ($i = 1; $i <= 6; $i++) { 
            $result .= substr($generator, (rand()%(strlen($generator))), 1); 
        } 

        $userEmails = new userEmails();
        $userEmails->email = $request->email;
        $userEmails->otp = $result;
        $userEmails->verifyStatus = 1;
        $userEmails->save();

        
        $objDemo = new \stdClass();
        $objDemo->otp    = $result;
        $objDemo->sender = 'Huxley';
 
        Mail::to($request->email)->send(new otpEmail($objDemo));


        //$userEmails->notify(new EmailVerificationOtp($result, $request->email));

        $resp = [
                'message' => 'SUCCESS',
                'status'  => intval(Response::HTTP_OK),
                'data' => array("message" => "OTP has beed successfully send on your email.")
            ];
        
        return response()->json($resp, $this->successStatus);



    }

    public function validateEmailVerificationOtp(Request $request){

        $validator = Validator::make([
            'email' => request('email'),
            'otp' => request('otp')
            
        ],
        [
           
            'email' => 'required|email|unique:users,email,'.auth::user()->id,
            'otp' => 'required'

        ]);

        if($validator->fails()){ 
            $resp = [
                'error' => $validator->errors(),
                'status'  => intval(Response::HTTP_NOT_FOUND)
            ];
            return response()->json($resp, Response::HTTP_OK);    
        }

        


        $userEmails = userEmails::select('id')->where([['email', '=', $request->email], ['otp', '=', $request->otp], ['verifyStatus', '=', 1]])->get();
        
        $userEmails = json_encode($userEmails);
        $userEmails = json_decode($userEmails, true);

        if($userEmails){

            $userEmailUpdate = userEmails::select('id')->where([['id', '=', $userEmails['0']['id']]])->first();
            $userEmailUpdate->verifyStatus = 2;
            $userEmailUpdate->update();

            $resp = [
                'message' => 'SUCCESS',
                'status'  => intval(Response::HTTP_OK),
                'data' => array("message" => "OTP is verify.")
            ];
        
        return response()->json($resp, $this->successStatus);

        }

        $resp = [
                'message' => 'SUCCESS',
                'status'  => intval(Response::HTTP_NOT_FOUND),
                'data' => array("message" => "Worng OTP!")
            ];
        
        return response()->json($resp, $this->successStatus);



    }

    public function updateProfile(Request $request){

        /*$validator = Validator::make([
            'password' => request('password'),
            'confirm_password' => request('confirm_password'),
            'first_name' => request('first_name'), 
            'surname' => request('surname'), 
            'email' => request('email'), 
            'date_of_birth' => request('date_of_birth'), 
            'town' => request('town')
            
        ],
        [
            'password' => 'required|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'confirm_password' => 'required|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/|same:password',
            'first_name' => 'required', 
            'surname' => 'required', 
            'email' => 'required|email|unique:users,email,'.auth::user()->id, 
            'date_of_birth' => 'required|date_format:d/m/Y|before:18 years ago', 
            'town' => 'required'
            
        ],[
            'confirm_password.required'=>'The confirm password field is required.',
            'confirm_password.same'=>'The confirm password is not the same password must match same value.',
            'confirm_password.min'=>'The confirm password must be at least 8 characters.',
            'confirm_password.regex'=>'The confirm password format is invalid.',
            'date_of_birth.required' => 'Please enter Date of Birth.',
            'date_of_birth.before' => 'User must be over 18 to register.',
            ]);

        if($validator->fails()){ 
            $resp = [
                'error' => $validator->errors(),
                'status'  => intval(Response::HTTP_NOT_FOUND)
            ];
            return response()->json($resp, Response::HTTP_OK);    
        }*/

        $user = auth::user(); 
        $user = json_encode($user);
        $user = json_decode($user, true); 

        $date_of_birth = str_replace('/', '-', $request->date_of_birth);
        $date_of_birth = strtotime($date_of_birth);
        $date_of_birth= date('Y-m-d H:i:s', $date_of_birth);
        //$input['date_of_birth'] = $date_of_birth;

        //$updateUser = User::where('id', '=', $user['id'])->first();
        $updateUser = User::find($user['id']);
        //print($updateUser); die();
        if($request->input('password')){
            $updateUser->password = bcrypt($request->input('password'));
        }
        
        if($request->input('first_name')){
            $updateUser->first_name = $request->first_name;
        }

        if($request->input('surname')){
            $updateUser->surname = $request->surname;
        }

        if($request->input('email')){
            $updateUser->email = $request->email;
        }

        if($date_of_birth){
            $updateUser->date_of_birth = $date_of_birth;
        }

        if($request->input('town')){
            $updateUser->town = $request->town;
        }

        $updateUser->update();
        $updateUser->notify(new UserAccountUpdatedSuccessfully());

        
        $resp = [
                'message' => 'SUCCESS',
                'status'  => intval(Response::HTTP_OK),
                'data' => array("message" => "The user account is updated successfully!")
            ];
        
        return response()->json($resp, $this->successStatus);
    }

    public function updatePassword(Request $request){

        $validator = Validator::make([
            'password' => request('password'),
            'confirm_password' => request('confirm_password')
            
        ],
        [
            'password' => 'required|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'confirm_password' => 'required|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/|same:password'
            
        ],[
            'confirm_password.required'=>'The confirm password field is required.',
            'confirm_password.same'=>'The confirm password is not the same password must match same value.',
            'confirm_password.min'=>'The confirm password must be at least 8 characters.',
            'confirm_password.regex'=>'The confirm password format is invalid.',
            ]);

        if($validator->fails()){ 
            $resp = [
                'error' => $validator->errors(),
                'status'  => intval(Response::HTTP_NOT_FOUND)
            ];
            return response()->json($resp, Response::HTTP_OK);    
        }

        $user = auth::user(); 
        $user = json_encode($user);
        $user = json_decode($user, true); 

        $updateUser = User::where('id', '=', $user['id'])->first();
        $updateUser->password = bcrypt($request->password);
        $updateUser->save();
        $updateUser->notify(new PasswordResetSuccess($user));

        
        $resp = [
                'message' => 'SUCCESS',
                'status'  => intval(Response::HTTP_OK),
                'data' => array("message" => "Your password has been successfully updated")
            ];
        
        return response()->json($resp, $this->successStatus);
    }

    public function myTickets(Request $request){
        $validator = Validator::make([
            'type' => request('type')
            
        ],
        [
            'type' => 'required'
            
        ]);

        if($validator->fails()){ 
            $resp = [
                'error' => $validator->errors(),
                'status'  => intval(Response::HTTP_NOT_FOUND)
            ];
            return response()->json($resp, Response::HTTP_OK);    
        }

        

        $type = $request->type;
        if($type == 'current'){            

            $ticket = DB::table('tickets')
                    ->select('tickets.*', 'competitions.*', 'prizes.*')
                    ->join('competitions', 'competitions.competition_id', '=', 'tickets.competition_id')
                    ->join('prizes', 'competitions.prize_id', '=', 'prizes.prize_id')
                    ->groupBy('tickets.ticket_id')
                    ->where(function($q) {
                         
                      $q->where([['competitions.status', '=', 1], ['tickets.player_id', '=', auth::user()->id]])
                        ->orWhere([['competitions.status', '=', 2], ['tickets.player_id', '=', auth::user()->id]]);
                    })
                    ->get();
            $ticket = json_encode($ticket);
            $ticket = json_decode($ticket, true);
            

        }elseif ($type == 'past') {

            $ticket = DB::table('tickets')
                    ->select('tickets.*', 'competitions.*', 'prizes.*')
                    ->join('competitions', 'competitions.competition_id', '=', 'tickets.competition_id')
                    ->join('prizes', 'competitions.prize_id', '=', 'prizes.prize_id')
                    ->groupBy('tickets.ticket_id')
                    ->where(function($q) {
                         
                      $q->where([['competitions.status', '=', 4], ['tickets.player_id', '=', auth::user()->id]])
                        ->orWhere([['competitions.status', '=', 5], ['tickets.player_id', '=', auth::user()->id]]);
                    })
                    ->get();
            $ticket = json_encode($ticket);
            $ticket = json_decode($ticket, true);
            
        }else{
            $resp = [
                'error' => 'Please enter correct type.',
                'status'  => intval(Response::HTTP_NOT_FOUND)
            ];
            return response()->json($resp, Response::HTTP_OK); 
        }
        
        $tickets = array();
        foreach ($ticket as $key => $value) {

            $soldTicket = DB::table('tickets')->select(DB::raw('COUNT(tickets.ticket_id) as pass'))->where([["competition_id", "=", $value['competition_id']]])->first();
                
            $soldTicket = json_encode($soldTicket);
            $soldTicket = json_decode($soldTicket, true);
            $soldTicket = $value['availabl_tickets'] - $soldTicket['pass'];
            
            $fail = DB::table('tickets')->select(DB::raw('COUNT(tickets.ticket_id) as fail'))->where([["competition_id", "=", $value['competition_id']], ["answer_status", "=", 'Fail']])->first();
            $fail = json_encode($fail);
            $fail = json_decode($fail, true);
            

            $tickets[$key]['prize_id'] = $value['prize_id'];
            $tickets[$key]['competition_id'] = $value['competition_id'];
            $tickets[$key]['ticket_id'] = $value['ticket_id'];
            $tickets[$key]['prize_name'] = $value['prize_name'];
            $tickets[$key]['prize_images'] = json_decode($value['file'], true);
            $tickets[$key]['no_of_ticket'] = 1;
            $tickets[$key]['closed_date'] = $this->calculate_time_span($value['closed_date']);
            $tickets[$key]['odds'] = '1/'.($soldTicket-$fail['fail']);
        }



        $resp = [
                'message' => 'SUCCESS',
                'status'  => intval(Response::HTTP_OK),
                'data' => $tickets
            ];
        
        return response()->json($resp, $this->successStatus);
    }

    public function allDrawn(Request $request){

        $ticket = DB::table('tickets')
                ->select('tickets.*', 'competitions.*', 'competitions.status as competitions_state', 'prizes.*')
                ->join('competitions', 'competitions.competition_id', '=', 'tickets.competition_id')
                ->join('prizes', 'competitions.prize_id', '=', 'prizes.prize_id')
                ->groupBy('tickets.ticket_id')
                ->where(function($q) {
                         
                      $q->where([['competitions.status', '=', 2], ['tickets.player_id', '=', auth::user()->id]])
                        ->orWhere([['competitions.status', '=', 3], ['tickets.player_id', '=', auth::user()->id]]);
                    })
                ->get();
        $ticket = json_encode($ticket);
        $ticket = json_decode($ticket, true);

        //print_r($ticket);

        $tickets = array();
        foreach ($ticket as $key => $value) {

            $tickets[$key]['prize_id'] = $value['prize_id'];
            $tickets[$key]['competition_id'] = $value['competition_id'];
            $tickets[$key]['ticket_id'] = $value['ticket_id'];
            $tickets[$key]['prize_name'] = $value['prize_name'];
            $tickets[$key]['prize_images'] = json_decode($value['file'], true);
            if($value['competitions_state'] == 2){
                $tickets[$key]['closed_date'] = date('Y/m/d', strtotime($value['closed_date']));
            }
            
        }



        $resp = [
                'message' => 'SUCCESS',
                'status'  => intval(Response::HTTP_OK),
                'data' => $tickets
            ];
        
        return response()->json($resp, $this->successStatus);




    }

    public function bookTicket(Request $request){

        $validator = Validator::make([
            'competition_id' => request('competition_id'),
            'challenge_answer' => request('challenge_answer')
            
        ],
        [
            'competition_id' => 'required',
            'challenge_answer' => 'required'
            
        ]);

        if($validator->fails()){ 
            $resp = [
                'error' => $validator->errors(),
                'status'  => intval(Response::HTTP_NOT_FOUND)
            ];
            return response()->json($resp, Response::HTTP_OK);    
        }

        $input = $request->all(); 
        $competitionList = '';
        $Challenge_answersList = '';
            
            $competitionList = Competitions::where([['competition_id', '=', $request->competition_id], ['status', '=', 1]])->get();
            if(!$competitionList){
                
                $resp = [
                    'error' => 'Competition is not available in Competition list or Competition is not active.',
                    'status'  => intval(Response::HTTP_NOT_FOUND)
                ];
                return response()->json($resp, Response::HTTP_OK); 

            }

            $competition_id = $competitionList['0']['competition_id'];
            $prize_id = $competitionList['0']['prize_id'];
            $challenge_id = $competitionList['0']['challenge_id'];
            $availabl_tickets = $competitionList['0']['availabl_tickets'];

            $Challenge_answersList = Challenge_answers::where('question_id', '=', $challenge_id)->get(); 

        $challenge_answer = $request->challenge_answer;
        
        $ansStatus = 'Fail';
        
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

        $user_id = auth()->user()->id;

        $Tickets = Tickets::where('competition_id', '=', $competition_id)->get();
        $Tickets = json_encode($Tickets);
        $Tickets = json_decode($Tickets, true);
        $TicketsTotal = count($Tickets);
        $Ticket = new Tickets();
        if($TicketsTotal < $availabl_tickets){

            
            $Ticket->competition_id = $competition_id;
            $Ticket->prize_id = $prize_id;
            $Ticket->player_id = auth()->user()->id;
            $Ticket->answer_status = $ansStatus;
            $Ticket->answer = $challenge_answer;
            $Ticket->created_by = $user_id;
            $Ticket->ticket_type = '';
            $Ticket->status = 1;
            $Ticket->save();
            $UserPlayer = User::where('id', '=', auth()->user()->id)->first();
            $UserPlayer->notify(new NewTicketsCreatedMarkedAsAndValidationPassed());
            $ticket_id = $Ticket->ticket_id;

            //return redirect(route('competitions.ticketList', $competition_id))->with(['message' => 'A ticket is created!']);

            $resp = [
                'message' => 'SUCCESS',
                'status'  => intval(Response::HTTP_OK),
                'data' => 'A ticket is created!'
            ];
        
        return response()->json($resp, $this->successStatus);

        }

        $competitionLi = Competitions::where([['competition_id', '=', $request->competition_id], ['status', '=', 1]])->get();
        $competitionLi->state = 2;
        $competitionLi->update();

        $resp = [
                    'error' => 'There is no ticket is available in this competition',
                    'status'  => intval(Response::HTTP_NOT_FOUND)
                ];
        return response()->json($resp, Response::HTTP_OK); 

        //return redirect()->back()->withInput()->withErrors(["error" => "You have already reached at your free ticket limit."]); 

    }

    public function logout(Request $request){

        $user = User::where([['id', '=', auth::user()->id]])->first();
        $user->mobile_access_token = '';
        $user->update();

        $resp = [
                'message' => 'SUCCESS',
                'status'  => intval(Response::HTTP_OK),
                'data' => 'Mobile access code removed successfully!'
            ];
        
        return response()->json($resp, $this->successStatus);

    }

    function openCompetitionChallengeDetails($challenge_id){
            $fail = DB::table('challenges')->select('challenges.question_id', 'challenges.question', 'challenges.organisation_id', 'challenges.global_challenge_bank')
            ->where([["challenges.question_id", "=", $challenge_id]])->first();
            $fail = json_encode($fail);
            $fail = json_decode($fail, true);   

            $answer = DB::table('challenge_answers')->select('challenge_answers.answer', 'challenge_answers.correct_answer')
            ->where([["challenge_answers.question_id", "=", $challenge_id]])->get();
            $answer = json_encode($answer);
            $answer = json_decode($answer, true); 

            $fail['challenge_answers'] = $answer;  

            return $fail;     

    }

    function calculate_time_span($date){ 
            $seconds  = strtotime(date('Y-m-d H:i:s')) - strtotime($date);
            $seconds = abs($seconds);

            $months = $seconds / (3600*24*30); 
            $day = floor($seconds / (3600*24)); 
            $hours = floor($seconds / 3600); 
            $mins = floor(($seconds - ($hours*3600)) / 60); 
            $secs = floor($seconds % 60); 

            if($seconds < 60)
                $time = $secs." seconds ago";
            else if($seconds < 60*60 )
                $time = $mins." min ago";
            else if($seconds < 60*60*60)
                $time = $hours." hours ago";
            else if($seconds < 24*60*60*60)
                $time = $day." day ago";
            else
                $time = $months." month ago";

            return $time;
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
