{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','View a Competition')

{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-users.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-timeline.css')}}">
@endsection

{{-- page content  --}}
@section('content')
<!-- Competition add start -->
<div class="section users-edit">
  <div class="card">
    <div class="card-content">
      <ul class="tabs mb-2 row">
        <li class="tab">
          <a class="display-flex align-items-center active" id="account-tab" href="#competitionView">
            <i class="material-icons mr-1">remove_red_eye</i><span>View</span>
          </a>
        </li>
        <li class="tab">
          <a class="display-flex align-items-center" id="information-tab" href="#competitionRevision">
            <i class="material-icons mr-2">timeline</i><span>Revision</span>
          </a>
        </li>
      </ul>
      <!-- <div class="card-body"> -->
      <div class="row">
        <div class="col s12" id="competitionRevision">
          <!-- users add account form start -->
          <?php
            $log = json_encode($log);
            $log = json_decode($log, true);
            $i = 0;
          ?>
          <div class="row">
            <!-- datatable start -->
            <div class="" id="player">
              <div class="responsive-table">
                <!-- <table id="multi-select" class="display table"> -->
                  <ul class="timeline">
              <?php foreach($log as $k=>$user){//print_r($user);die();
                $clas = ''; 
                if($i%2==0){
                  $clas = ''; 
                }else{
                  $clas = 'timeline-inverted'; 
                }
                $log_before_changes = $user['log_before_changes'];
                $log_before_changes = json_decode($log_before_changes, true);
                $log_details = $user['log_details'];
                $log_details = json_decode($log_details, true);
                $description = $user['description'];

                $prize_id = $log_details['prize_id'];
                $priz = DB::table('prizes')->select('prizes.prize_name')->where('prize_id', '=', $prize_id)->get();
                $challenge_id = $log_details['challenge_id'];
                $challenge = DB::table('challenges')->select('challenges.question')->where('question_id', '=', $challenge_id)->get();
                

                $before_prize_id = @$log_before_changes['prize_id'];
                $before_priz = DB::table('prizes')->select('prizes.prize_name')->where('prize_id', '=', $prize_id)->get();
                $before_challenge_id = @$log_before_changes['challenge_id'];
                $before_challenge = DB::table('challenges')->select('challenges.question')->where('question_id', '=', $challenge_id)->get();

                if($description == 'A competition is created'){
                  ?>
                  <li class="<?= $clas; ?>">
                  <div class="timeline-badge cyan">
                    <a class="tooltipped" data-position="top" data-tooltip="<?= date('M d Y', strtotime($user['timestamp'])); ?>"><i
                        class="material-icons white-text">language</i></a>
                  </div>
                  <div class="timeline-panel">
                    <div class="card m-0 hoverable">
                      <div class="card-content">
                        <div class="card-header pb-1">
                          <div class="avatar mr-2">
                            <img src="{{asset('images/avatar/user-profile-placeholder-image-png.jpg')}}"
                              class="responsive-img border-radius-4" width="38">
                          </div>
                          <div class="card-text">
                            <h6 class="m-0"><?= $user['users_name']; ?></h6>
                            <small><?= $user['description']; ?> at <?= $user['timestamp']; ?></small>
                          </div>
                        </div>
                        <p><i class="material-icons profile-card-i">email</i> <?= @$user['email_id']; ?></p>
                        <div class="divider"></div>
                        
                        <p class="card-text mt-1"> Prize Name : <?= $priz['0']->prize_name; ?>
                        </p>
                        <p class="card-text mt-1"> Challenge : <?= $challenge['0']->question; ?>
                        </p>
                        <p class="card-text mt-1"> Ticket Price : <?= $log_details['ticket_price']; ?>
                        </p>
                        <p class="card-text mt-1"> Availabl Tickets : <?= $log_details['availabl_tickets']; ?>
                        </p>
                        <p class="card-text mt-1"> Closed Date : <?= $log_details['closed_date']; ?>
                        </p>
                      </div>
                      
                    </div>
                  </div>
                </li>
                 <?php
                }elseif($description == 'A competition is updated' || $description == 'A competition status is updated'){
                  ?>
                   <li class="<?= $clas; ?>">
                  <div class="timeline-badge cyan">
                    <a class="tooltipped" data-position="top" data-tooltip="<?= date('M d Y', strtotime($user['timestamp'])); ?>"><i
                        class="material-icons white-text">language</i></a>
                  </div>
                  <div class="timeline-panel">
                    <div class="card m-0 hoverable">
                      <div class="card-content">
                        <div class="card-header pb-1">
                          <div class="avatar mr-2">
                            <img src="{{asset('images/avatar/user-profile-placeholder-image-png.jpg')}}" 
                              class="responsive-img border-radius-4" width="38">
                          </div>
                          <div class="card-text">
                            <h6 class="m-0"><?= $user['users_name']; ?></h6>
                            <small><?= $user['description']; ?> at <?= $user['timestamp']; ?></small>
                          </div>
                        </div>
                        <p><i class="material-icons profile-card-i">email</i> <?= $user['email_id']; ?></p>
                        <div class="divider"></div>
                        
                          <?php
                          if($before_prize_id != $prize_id){
                          ?>
                          <p class="card-text mt-1"> Prize Name : <?= $priz['0']->prize_name; ?>
                          </p>
                          <?php
                          }
                          if($before_challenge_id != $challenge_id){
                          ?>
                          <p class="card-text mt-1"> Category : <?= $challenge['0']->question; ?>
                          </p>
                          <?php
                          }
                          if($log_before_changes['ticket_price'] != $log_details['ticket_price']){
                          ?>
                          <p class="card-text mt-1"> Ticket Price : <?= $log_details['ticket_price']; ?>
                          </p>
                          <?php
                          }
                          if($log_before_changes['availabl_tickets'] != $log_details['availabl_tickets']){
                          ?>
                          <p class="card-text mt-1"> Availabl Tickets : <?= $log_details['availabl_tickets']; ?>
                          </p>
                          <?php
                          }
                          if($log_before_changes['closed_date'] != $log_details['closed_date']){
                          ?>
                          <p class="card-text mt-1"> Closed Date : <?= $log_details['closed_date']; ?>
                          </p>
                          <?php
                          }
                          ?>
                      </div>
                      
                    </div>
                  </div>
                </li>
                  <?php
                }
                ?>
                
                
              <?php $i++; } ?>
            </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="col s12" id="competitionView">
            <div class="row">
              <div class="col s12">
                <div class="row">

                    <?php 
                    $competition = json_encode($competition);
                    $competition = json_decode($competition, true);
                    ?>
                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s7 input-field">
                        <h2 for="question" style="color:#9e9e9e; font-size: 1rem; margin: 0rem 0 1.424rem 0;">Prize</h2>
                        <input list="prizeList" id="prize" name="prize" class="validate" value="<?php echo $competition[0]['prize_name']; ?>" disabled="true"/>
                        <datalist id="prizeList">
                          <?php
                            /*foreach ($prizeTotal as $key => $value) {
                              echo '<option value="'.$value['prize_name'].'">';
                            }*/
                          ?>
                        </datalist>
                      </div>
                    </div>

                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s4 input-field">
                        <input id="ticket_price" name="ticket_price" type="text" class="validate" value="<?php echo $competition[0]['ticket_price']; ?>" data-error=".errorTxt" placeholder="" onkeypress="return AllowOnlyNumbers(event);" disabled="true">

                        <label for="ticket_price">Ticket Price</label>
                      </div>
                    </div>

                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s4 input-field">
                        <input id="available_ticket" name="available_ticket" type="text" class="validate" value="<?php echo $competition[0]['availabl_tickets']; ?>" data-error=".errorTxt" placeholder="" disabled="true">

                        <label for="available_ticket">Available Tickets</label>
                      </div>
                    </div>                    

                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s4 input-field">
                        <input id="closed_date" name="closed_date" type="text" class="datepickerCompetition validate" value="<?php echo $competition[0]['closed_date']; ?>" data-error=".errorTxt" placeholder="YYYY/MM/DD" disabled="true">

                        <label for="closed_date">Close Date</label>
                      </div>
                    </div>
                    <!-- <div class="row" style="padding: 0 1rem;">
                      <div class="col s7 input-field">
                        <h2 for="question" style="color:#9e9e9e; font-size: 1rem; margin: 0rem 0 1.424rem 0;">Challenge</h2>
                        <input list="challengeList" id="Challenge" name="Challenge" class="validate" value="<?php echo date('d/m/Y', strtotime($competition[0]['question'])); ?>" disabled="true"/>
                        <datalist id="challengeList">
                          <?php
                            /*foreach ($questionTotal as $key => $value) {
                              echo '<option value="'.$value['question'].'">';
                            }*/
                          ?>
                        </datalist>
                      </div>
                    </div> -->

                    @if($competition[0]['status'] == 1)
                    <form id="competitionAddForm" action="{{route('competitions.updateState', $competition['0']['competition_id'])}}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('put') }}
                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s4 input-field">
                        <?php 
                        $state = $competition[0]['status']; 
                        $activeChk = '';
                        if($state == 1){
                          $activeChk = 'selected="selected"';
                        }

                        $closeChk = '';
                        if($state == 2){
                          $closeChk = 'selected="selected"';
                        }

                        if(!$activeChk && $closeChk){
                          $closeChk = 'selected="selected"';
                        }
                        ?>
                        <select id="state" name="state" class="validate" data-error=".errorTxt">
                          <option value="1" <?= $activeChk; ?>>Active</option>
                          <option value="2" <?= $closeChk; ?> >Closed</option>
                        </select>

                        <label for="state">State</label>
                      </div>
                    </div>
                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s4 input-field">
                        
                        <input type="text" id="description" name="description" class="validate" data-error=".errorTxt" value="{{$competition[0]['description']}}">

                        <label for="description">Note</label>
                      </div>
                    </div>
                    <div class="col s12 display-flex mt-3">
                      <button class="g-recaptcha btn indigo" data-sitekey="6Lc1F_oUAAAAABXCu0MULxBbxalEQKCoHboXW9YQ" data-callback='onSubmit' data-action='submit'>Save</button>
                    </div>
                  </form>
                  @elseif($competition[0]['status'] == 2)
                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s4 input-field">
                      <input id="closed_date" name="closed_date" type="text" class="datepickerCompetition validate" value="<?php echo $competition[0]['sold_ticket']?$competition[0]['sold_ticket']:0; ?>" data-error=".errorTxt" disabled="true">

                      <label for="closed_date">Total tickets sold</label>
                    </div>
                  </div>
                  <form id="competitionAddForm" action="{{route('competitions.updateState', $competition['0']['competition_id'])}}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('put') }}
                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s4 input-field">
                        <?php 
                        $state = $competition[0]['status']; 
                        $activeChk = '';
                        if($state == 3){
                          $activeChk = 'selected="selected"';
                        }

                        $closeChk = '';
                        if($state == 2){
                          $closeChk = 'selected="selected"';
                        }

                        if(!$activeChk && $closeChk){
                          $closeChk = 'selected="selected"';
                        }
                        ?>
                        <select id="state" name="state" class="validate" data-error=".errorTxt">
                          <option value="3" <?= $activeChk; ?>>Drawn</option>
                          <option value="2" <?= $closeChk; ?> >Closed</option>
                        </select>

                        <label for="state">State</label>
                      </div>
                    </div>
                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s4 input-field">

                        <input type="text" id="description" name="description" class="validate" data-error=".errorTxt" value="{{$competition[0]['description']}}">

                        <label for="description">Note</label>
                      </div>
                    </div>
                    <div class="col s12 display-flex mt-3">
                      <button class="g-recaptcha btn indigo" data-sitekey="6Lc1F_oUAAAAABXCu0MULxBbxalEQKCoHboXW9YQ" data-callback='onSubmit' data-action='submit'>Save</button>
                    </div>
                  </form>
                  @endif
                </div>
              </div>
              </div>
            </div>
        </div>
      </div>
      <!-- </div> -->
    </div>
  </div>
</div>
@endsection

{{-- page script --}}
@section('page-script')
<script src="{{asset('js/scripts/page-users.js')}}"></script>
@endsection