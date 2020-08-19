{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Edit a Free Ticket')

{{-- vendor styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2-materialize.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-timeline.css')}}">
@endsection

{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-users.css')}}">
@endsection

{{-- page content --}}
@section('content')

@if($errors->has('error'))
  <div class="card-alert card red lighten-5">
    <div class="card-content red-text">
      <p><strong>Error!</strong> {{ $errors->first('error') }} </p>
    </div>
    <button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">×</span>
    </button>
  </div>
@endif

<!-- Competition add start -->
<div class="section users-edit">
  <div class="card">
    <div class="card-content">
      <!-- <div class="card-body"> -->
        <ul class="tabs mb-2 row">
        <!-- <li class="tab">
          <a class="display-flex align-items-center active" id="account-tab" href="#organisationView">
            <i class="material-icons mr-1">remove_red_eye</i><span>View</span>
          </a>
        </li> -->
        <li class="tab">
          <a class="display-flex align-items-center"  href="#edit">
            <i class="material-icons mr-2">edit</i><span>Edit</span>
          </a>
        </li>
        <li class="tab">
          <a class="display-flex align-items-center"  href="#revision">
            <i class="material-icons mr-2">timeline</i><span>Revision</span>
          </a>
        </li>
      </ul>

      <div class="row">
        <div class="col s12" id="revision">
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
              <?php 
              foreach($log as $k=>$logs){
                $clas = ''; 
                if($i%2==0){
                  $clas = ''; 
                }else{
                  $clas = 'timeline-inverted'; 
                }
                $log_before_changes = $logs['log_before_changes'];
                $log_before_changes = json_decode($log_before_changes, true);
                $log_details = $logs['log_details'];
                $log_details = json_decode($log_details, true);
                $description = $logs['description'];

                $prize_id = $log_details['prize_id'];
                $priz = DB::table('prizes')->select('prizes.prize_name')->where('prize_id', '=', $prize_id)->get(); 
                $priz = json_encode($priz);
                $priz = json_decode($priz, true);
                $player_id = $log_details['player_id'];
                $player = DB::table('users')->select('users.first_name', 'users.surname')->where('id', '=', $player_id)->get();
                $player = json_encode($player);
                $player = json_decode($player, true);

                
                

                 //print_r($log_before_changes); print_r($log_details);
                if($description == 'A ticket is created'){
                  ?>
                  <li class="<?= $clas; ?>">
                  <div class="timeline-badge cyan">
                    <a class="tooltipped" data-position="top" data-tooltip="<?= date('M d Y', strtotime($logs['timestamp'])); ?>"><i
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
                            <h6 class="m-0"><?= $logs['users_name']; ?></h6>
                            <small><?= $logs['description']; ?> at <?= $logs['timestamp']; ?></small>
                          </div>
                        </div>
                        <div class="divider"></div>
                        
                        <p class="card-text mt-1"> Competition ID : <?= $log_details['competition_id']; ?>
                        </p>
                        @if($priz)
                        <p class="card-text mt-1"> Prize : <?= $priz['0']['prize_name']; ?>
                        </p>
                        @endif
                        @if($player)
                        <p class="card-text mt-1"> Player : <?= $player['0']['first_name'].' '.$player['0']['surname']; ?>
                        </p>
                        @endif
                        <p class="card-text mt-1"> Success : <?= $log_details['answer_status']; ?>
                        </p>
                        <p class="card-text mt-1"> Answer : <?= $log_details['answer']; ?>
                        </p>
                        <p class="card-text mt-1"> Ticket Type : <?= $log_details['ticket_type']; ?>
                        </p>
                      </div>
                      
                    </div>
                  </div>
                </li>
                 <?php
                }elseif($description == 'A ticket is updated' ){
                  $before_prize_id = @$log_before_changes['prize_id'];
                  $before_priz = DB::table('prizes')->select('prizes.prize_name')->where('prize_id', '=', $prize_id)->get();
                  $before_priz = json_encode($before_priz);
                  $before_priz = json_decode($before_priz, true);
                  $before_player_id = @$log_before_changes['player_id'];
                  $before_player = DB::table('users')->select('users.first_name', 'users.surname')->where('id', '=', $before_player_id)->get();
                  $before_player = json_encode($before_player);
                  $before_player = json_decode($before_player, true); 

                  ?>
                   <li class="<?= $clas; ?>">
                  <div class="timeline-badge cyan">
                    <a class="tooltipped" data-position="top" data-tooltip="<?= date('M d Y', strtotime($logs['timestamp'])); ?>"><i
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
                            <h6 class="m-0"><?= $logs['users_name']; ?></h6>
                            <small><?= $logs['description']; ?> at <?= $logs['timestamp']; ?></small>
                          </div>
                        </div>
                        <div class="divider"></div>
                        
                          <?php
                          if($log_before_changes['competition_id'] != $log_details['competition_id']){
                          ?>
                          <p class="card-text mt-1"> Competition ID : <?= $log_details['competition_id']; ?>
                          </p>
                          <?php
                          }
                          if($log_before_changes['prize_id'] != $log_details['prize_id']){
                          ?>
                          <p class="card-text mt-1"> Prize : <?= $priz['0']['prize_name']; ?>
                          </p>
                          <?php
                          }
                          if($log_before_changes['player_id'] != $log_details['player_id']){
                          ?>
                          <p class="card-text mt-1"> Player : <?= $player['0']['first_name'].' '.$player['0']['surname']; ?>
                          </p>
                          <?php
                          }
                          if($log_before_changes['answer_status'] != $log_details['answer_status']){
                          ?>
                          <p class="card-text mt-1"> Success : <?= $log_details['answer_status']; ?>
                          </p>
                          <?php
                          }
                          if($log_before_changes['answer'] != $log_details['answer']){
                          ?>
                          <p class="card-text mt-1"> Answer : <?= $log_details['answer']; ?>
                          </p>
                          <?php
                          }
                          if($log_before_changes['ticket_type'] != $log_details['ticket_type']){
                          ?>
                          <p class="card-text mt-1"> Ticket Type : <?= $log_details['ticket_type']; ?>
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
        <div class="col s12" id="edit">
          <!-- Competition add form start --> 
          <?php 
          $ticketList = json_encode($ticketList);
          $ticketList = json_decode($ticketList, true);
          ?>
          <form id="competitionAddForm" action="{{route('competition.ticket.update', $ticketList['0']['ticket_id'])}}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            {{ method_field('put') }}
            <div class="row">
              <div class="col s12">
                <div class="row">
                    <?php 
                      $competitionList = json_encode($competitionList);
                      $competitionList = json_decode($competitionList,'true');
                      //print_r($competitionList); //die(); 
                    ?>

                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s7 input-field">
                       
                        <input list="competitionList" id="competition" name="competition" type="text"  class="validate" value="<?php echo Request::old('competition') ? Request::old('competition') : $ticketList['0']['competition_id']." - ".$ticketList['0']['prize_name']; ?>" autocomplete="off"/>
                        <datalist id="competitionList">
                          <?php 
                            foreach($competitionList as $key => $value) {
                          ?>
                            <option value="{{$value['competition_id']}} - {{$value['prize_name']}}">     
                          <?php                         
                            }
                          ?>
                        </datalist>
                        
                        <label for="prize">Competition*</label>
                        <?php if ($errors->has("competition")){  ?>
                          <small class="errorTxt"> <?php echo $errors->first("competition"); ?></small>
                        <?php } ?>
                      </div>
                    </div>

                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s7 input-field">
                        <input id="Challenge" name="Challenge" type="text"  class="validate" value="<?php echo Request::old('Challenge'); ?>" autocomplete="off" disabled="true"/>
                        <label for="Challenge" class="active">Challenge*</label>
                      </div>
                    </div>

                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s7 input-field">
                       
                        <input id="player" name="player" type="text"  class="validate" value="<?php echo Request::old('player') ? Request::old('player') : $ticketList['0']['email']; ?>" autocomplete="off"/>
                        
                        <label for="player">Player*</label>
                        <?php if ($errors->has("player")){  ?>
                          <small class="errorTxt"> <?php echo $errors->first("player"); ?></small>
                        <?php } ?>
                      </div>
                    </div>

                    <div class="row" style="padding: 0 1rem;">
                      <div class="input-field col s7">
                        <select id="challenge_answer" name="challenge_answer" class="browser-default validate">
                          <option value="">-</option>
                        </select>
                        <label for="challenge_answer" class="active">Challenge Answer*</label>
                        <?php if ($errors->has("challenge_answer")){  ?>
                          <small class="errorTxt"> <?php echo $errors->first("challenge_answer"); ?></small>
                        <?php } ?>
                      </div>
                    </div>

                    <!-- <div class="row" style="padding: 0 1rem;">
                      <div class="col s4 input-field">
                        <h2 for="challenge_answer" style="color:#9e9e9e; font-size: 0.85rem; margin: 0rem 0 1.424rem 0;">Challenge Answer*</h2>
                        <select id="challenge_answer" name="challenge_answer" class="browser-default validate" data-error=".errorTxt"><option value="">-</option>
                          
                        </select>

                        

                        <?php if ($errors->has("challenge_answer")){  ?>
                          <small class="errorTxt"> <?php echo $errors->first("challenge_answer"); ?></small>
                        <?php } ?>
                      </div>
                    </div> -->
                </div>
              </div>
              <div class="col s12 display-flex mt-3">
                <button class="g-recaptcha btn indigo" data-sitekey="6Lc1F_oUAAAAABXCu0MULxBbxalEQKCoHboXW9YQ" data-callback='onSubmit' data-action='submit'>Save</button>
              </div>
            </div>
          </form>
          <!-- Competition add form ends -->
        </div>
      </div>
      <!-- </div> -->
    </div>
  </div>
</div>
<!-- users edit ends -->
@endsection

{{-- vendor scripts --}}
@section('vendor-script')
<script src="{{asset('vendors/select2/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/jquery-validation/jquery.validate.min.js')}}"></script>
@endsection

{{-- page scripts --}}
@section('page-script')
<script src="{{asset('js/scripts/page-users.js')}}"></script>
<script src="{{asset('js/scripts/ui-alerts.js')}}"></script>
<script type="text/javascript">
  $(document).ready(function(){

    $(document).on('click', '.tabs .tab a', function(){
      
        var href = $(this).attr("href");
        window.location.hash = href;
      
    });

    $('input[name=ticket_price]').on('change', function(){
      var ticket_price = $(this).val(); 
      var prize = $('input[name=prize]').val(); 
      var prizeTotalObj = jQuery.parseJSON( prizeTotal );
      var prizeData = prizeTotalObj.map(function (prizeArr) {
        if (prizeArr.prize_name == prize) {

          if(ticket_price !== null && ticket_price !== '' && prize !== null && prize !== '') {
            //alert(ticket_price); alert(prize);
            var availablTickets = ((prizeArr.cash_value*2)/ticket_price);
            $('input[name=available_ticket]').val(Math.round(availablTickets)); 
          }else{
            alert('Prize is not available in Prize list.');
          }
          
        } 
      }); 
    });

    $('input[name=prize]').on('change', function(){
      var ticket_price = $('input[name=ticket_price]').val(); 
      var prize = $(this).val(); 
      var prizeTotalObj = jQuery.parseJSON( prizeTotal );
      var prizeData = prizeTotalObj.map(function (prizeArr) {
        if (prizeArr.prize_name == prize) {

          if(ticket_price !== null && ticket_price !== '' && prize !== null && prize !== '') {
            //alert(ticket_price); alert(prize);
            var availablTickets = ((prizeArr.cash_value*2)/ticket_price);
            $('input[name=available_ticket]').val(Math.round(availablTickets)); 
          }else{
            ///alert('Prize is not available in Prize list.');
          }
        } 
      }); 
    });

    var competitionVal = $('input[name=competition]').val();
    if(competitionVal){
      var challenge_answer_old = "<?= Request::old('challenge_answer') ? Request::old('challenge_answer') : $ticketList['0']['answer']; ?>"; 
      $.ajaxSetup({
        headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      jQuery.ajax({
        url: "<?php echo App::make('url')->to('/getCompetitionChallengeList/'); ?>"+"/"+competitionVal,
        method: 'get',
        dataType: 'json',
        success: function(result){
          $("label[for=challenge_answer]").addClass('active');
        $("label[for=Challenge]").addClass('active');
          $("input[name=Challenge]").val(result.challengesName);
          var opt = '<option value="">--</option>';
          var k = 97;
          var Cap = 65;
          $.each(result.challengesAns, function( key, val ) { 
              var checked = '';
              var str =String.fromCharCode(k); 
              var strCap =String.fromCharCode(Cap);
              if(challenge_answer_old == str){
                checked = "selected";
              }
              opt += '<option value="'+str+'" '+checked+'>'+strCap+" - "+val.answer+'</option>';
              k++;
              Cap++;
            
          });
          $("select[name=challenge_answer]").html(opt);
           
        }
      });
    }

    $('input[name=competition]').on('change', function(event){
    
    $.ajaxSetup({
      headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    jQuery.ajax({
      url: "<?php echo App::make('url')->to('/getCompetitionChallengeList/'); ?>"+"/"+$(this).val(),
      method: 'get',
      dataType: 'json',
      success: function(result){
        $("label[for=challenge_answer]").addClass('active');
        $("label[for=Challenge]").addClass('active');
        $("input[name=Challenge]").val(result.challengesName);
        var opt = '<option value="">--</option>';
        var k = 97;
        var Cap = 65;
        $.each(result.challengesAns, function( key, val ) { 
            var str =String.fromCharCode(k); 
            var strCap =String.fromCharCode(Cap); 
            opt += '<option value="'+str+'">'+strCap+" - "+val.answer+'</option>';
            k++;
            Cap++;
          
        });
        $("select[name=challenge_answer]").html(opt);
         
      }
    });

    
   });

    $('.datepickerCompetition').datepicker({
      autoClose: true,
      format: 'dd/mm/yyyy',
      container: 'body',
      onDraw: function onDraw() {
        // materialize select dropdown not proper working on mobile and tablets so we make it browser default select
        $('.datepicker-container').find('.datepicker-select').addClass('browser-default');
        $(".datepicker-container .select-dropdown.dropdown-trigger").remove();
      }
    });
  });

  function AllowOnlyNumbers(e) {

    e = (e) ? e : window.event;
    var clipboardData = e.clipboardData ? e.clipboardData : window.clipboardData;
    var key = e.keyCode ? e.keyCode : e.which ? e.which : e.charCode;
    var str = (e.type && e.type == "paste") ? clipboardData.getData('Text') : String.fromCharCode(key);

    return (/^\d+$/.test(str));
  }
    
</script>
@endsection