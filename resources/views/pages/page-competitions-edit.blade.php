{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Edit a Competition')

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

<?php
  $competition = json_encode($competition);
  $competition = json_decode($competition, true);
?>
<!-- Competition add start -->
<div class="section users-edit">
  <div class="card">
    <div class="card-content">
      <ul class="tabs mb-2 row">
        <li class="tab">
          <a class="display-flex align-items-center active" id="account-tab" href="#view">
            <i class="material-icons mr-1">remove_red_eye</i><span>View</span>
          </a>
        </li>
        <li class="tab">
          <a class="display-flex align-items-center" id="information-tab" href="#edit">
            <i class="material-icons mr-2">edit</i><span>Edit</span>
          </a>
        </li>
        <li class="tab">
          <a class="display-flex align-items-center" id="information-tab" href="#revision">
            <i class="material-icons mr-2">timeline</i><span>Revision</span>
          </a>
        </li>
      </ul>

      <!-- <div class="card-body"> -->
      <div class="row" >
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
              <?php foreach($log as $k=>$user){
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
                $priz = json_encode($priz);
                $priz = json_decode($priz, true);
                //print_r($priz);

                $challenge_id = $log_details['challenge_id'];
                $challenge = DB::table('challenges')->select('challenges.question')->where('question_id', '=', $challenge_id)->get();
                $challenge = json_encode($challenge);
                $challenge = json_decode($challenge, true);

                //print_r($challenge); die();
                

                

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
                        <div class="divider"></div>
                        
                        @if($priz)
                        <p class="card-text mt-1"> Prize Name : <?= @$priz['0']['prize_name']; ?>
                        </p>
                        @endif
                        @if($challenge)

                        <p class="card-text mt-1"> Challenge Question : <?= @$challenge['0']['question']; ?>
                        </p>
                        @endif
                        <p class="card-text mt-1"> Ticket Price Coin(s) : <?= $log_details['ticket_price']; ?>
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
                  $before_prize_id = @$log_before_changes['prize_id'];
                  $before_priz = DB::table('prizes')->select('prizes.prize_name')->where('prize_id', '=', $before_prize_id)->get();
                   $before_priz = json_encode($before_priz);
                   $before_priz = json_decode($before_priz, true);
                   $before_challenge_id = @$log_before_changes['challenge_id'];
                   $before_challenge = DB::table('challenges')->select('challenges.question')->where('question_id', '=', $before_challenge_id)->get();
                   $before_challenge = json_encode($before_challenge);
                   $before_challenge = json_decode($before_challenge, true);
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
                        <div class="divider"></div>
                          
                          
                        
                          <?php
                          
                          if($before_prize_id != $prize_id){
                            if($priz){
                          ?>
                          <p class="card-text mt-1"> Prize Name : <?= $priz['0']['prize_name']; ?>
                          </p>
                          <?php
                            }
                          }
                          if($before_challenge_id != $challenge_id){
                            if($challenge){
                          ?>
                          <p class="card-text mt-1"> Challenge Question : <?= $challenge['0']['question']; ?>
                          </p>
                          <?php
                            }
                          }
                          if($log_before_changes['ticket_price'] != $log_details['ticket_price']){
                          ?>
                          <p class="card-text mt-1"> Ticket Price Coin(s) : <?= $log_details['ticket_price']; ?>
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
        <div class="col s12" id="view">
          
            <div class="row">
              <div class="col s12">
                <div class="row">
                  
                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s7 input-field">
                        <h2 for="question" style="color:#9e9e9e; font-size: 1rem; margin: 0rem 0 1.424rem 0;">Prize</h2>
                        <input list="prizeList" class="validate" value="<?php echo Request::old('prize') ? Request::old('prize') : $competition['0']['prize_name']; ?>" disabled="true"/>
                       
                      </div>
                    </div>

                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s4 input-field">
                        <input type="text" class="validate" value="<?php echo Request::old('ticket_price') ? Request::old('ticket_price') : $competition['0']['ticket_price']; ?>" data-error=".errorTxt" placeholder="" onkeypress="return AllowOnlyNumbers(event);" disabled="true">

                        <label for="ticket_price">Ticket Price Coin(s)</label>
                      </div>
                    </div>

                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s4 input-field">
                        <input type="text" class="validate" value="<?php echo round(Request::old('available_ticket') ? Request::old('available_ticket') : $competition['0']['availabl_tickets']); ?>" data-error=".errorTxt" placeholder="" disabled="true">

                        <label for="available_ticket">Available Tickets</label>
                      </div>
                    </div>                    
                    <?php
                    $closed_date = Request::old('closed_date') ? Request::old('closed_date') : $competition['0']['closed_date'];
                    $closed_date = strtotime($closed_date);
                    $closed_date = date('d/m/Y', $closed_date);
                    ?>
                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s4 input-field">
                        <input type="text" class="datepickerCompetition validate" value="<?php echo $closed_date; ?>" data-error=".errorTxt" placeholder="dd/mm/yyyy" disabled="true">

                        <label for="closed_date">Close Date</label>
                      </div>
                    </div>

                    
                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s7 input-field">
                        <h2 for="question" style="color:#9e9e9e; font-size: 1rem; margin: 0rem 0 1.424rem 0;">Challenge Question</h2>
                        <input list="challengeList" class="validate" value="<?php echo Request::old('Challenge') ? Request::old('Challenge') : $competition['0']['question']; ?>" disabled="true"/>
                        
                      </div>
                    </div>

                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s4 input-field">
                        <?php 
                        $state = Request::old('state') ? Request::old('state') : $competition['0']['status']; 
                        $activeChk = '';
                        if($state == 1){
                          $activeChk = 'selected="selected"';
                        }

                        $closeChk = '';
                        if($state == 0){
                          $closeChk = 'selected="selected"';
                        }

                        if(!$activeChk && $closeChk){
                          $closeChk = 'selected="selected"';
                        }
                        ?>
                        <select class="validate" data-error=".errorTxt" disabled="true">
                          <option value="1" <?= $activeChk; ?>>Active</option>
                          <option value="0" <?= $closeChk; ?> >Draft</option>
                        </select>

                        <label for="state">State</label>
                      </div>
                    </div>
                  
                </div>
              </div>
              
            </div>
          
        </div>

        <div class="col s12" id="edit">
          <!-- Competition add form start -->
          <form id="competitionAddForm" action="{{route('competitions.update', $competition['0']['competition_id'])}}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            {{ method_field('put') }}
            <div class="row">
              <div class="col s12">
                <div class="row">
                  

                    <!-- <div class="row" style="padding: 0 1rem;">
                      <div class="col s7 input-field">
                       
                        <input id="competition_title" name="competition_title" type="text"  class="validate" value="<?php echo Request::old('competition_title') ? Request::old('competition_title') : $competition['0']['competition_title']; ?>" autocomplete="off"/>
                        
                        <label for="competition_title">Competition Title*</label>
                        <?php if ($errors->has("competition_title")){  ?>
                          <small class="errorTxt"> <?php echo $errors->first("competition_title"); ?></small>
                        <?php } ?>
                      </div>
                    </div> -->

                    <?php 
                    $prizeTotal = json_encode($prizeTotal);
                    $prizeTotal = json_decode($prizeTotal, true);
                    //echo '<pre>'; print_r($prizeTotal); echo '</pre>';
                    ?>
                    

                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s7 input-field">
                       
                        <input id="prize" name="prize" type="text"  class="validate" value="<?php echo Request::old('prize') ? Request::old('prize') : $competition['0']['prize_name']; ?>" autocomplete="off"/>
                        
                        <label for="prize">Prize*</label>
                        <?php if ($errors->has("prize")){  ?>
                          <small class="errorTxt"> <?php echo $errors->first("prize"); ?></small>
                        <?php } ?>
                      </div>
                    </div>

                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s4 input-field">
                        <input id="ticket_price" name="ticket_price" type="text" class="validate" value="<?php echo Request::old('ticket_price') ? Request::old('ticket_price') : $competition['0']['ticket_price']; ?>" onkeypress="return AllowOnlyNumbers(event);">

                        <label for="ticket_price">Ticket Price Coin(s)*</label>

                        <?php if ($errors->has("ticket_price")){  ?>
                          <small class="errorTxt"> <?php echo $errors->first("ticket_price"); ?></small>
                        <?php } ?>
                      </div>
                    </div>

                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s4 input-field">
                        <input id="available_ticket" name="available_ticket" type="number" class="validate" value="<?php echo round(Request::old('available_ticket') ? Request::old('available_ticket') : $competition['0']['availabl_tickets']); ?>" min="0" max="9999">

                        <input type="hidden" name="calculatedAvailableTicket" value="">

                        <label for="available_ticket">Available Tickets*</label>

                        <?php if ($errors->has("available_ticket")){  ?>
                          <small class="errorTxt"> <?php echo $errors->first("available_ticket"); ?></small>
                        <?php } ?>
                      </div>
                    </div>                    
                    <?php
                    $closed_date = Request::old('closed_date') ? Request::old('closed_date') : $competition['0']['closed_date'];
                    $closed_date = strtotime($closed_date);
                    $closed_date = date('d/m/Y', $closed_date);
                    ?>
                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s4 input-field">
                        <input id="closed_date" name="closed_date" type="text" class="datepickerCompetition validate" value="<?php echo $closed_date; ?>" placeholder="dd/mm/yyyy">

                        <label for="closed_date">Close Date*</label>

                        <?php if ($errors->has("closed_date")){  ?>
                          <small class="errorTxt"> <?php echo $errors->first("closed_date"); ?></small>
                        <?php } ?>
                      </div>
                    </div>

                    <?php 
                    $questionTotal = json_encode($questionTotal);
                    $questionTotal = json_decode($questionTotal, true);
                    ?>
                    <!-- <div class="row" style="padding: 0 1rem;">
                      <div class="col s7 input-field">
                        
                        <input id="Challenge" name="Challenge"  class="validate" value="<?php echo Request::old('Challenge') ? Request::old('Challenge') : $competition['0']['question']; ?>" autocomplete="off"/>
                        <label for="Challenge">Challenge Question*</label>

                        <?php if ($errors->has("Challenge")){  ?>
                          <small class="errorTxt"> <?php echo $errors->first("Challenge"); ?></small>
                        <?php } ?>
                      </div>
                    </div> -->

                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s7 input-field">
                       
                        <input id="Challenge" name="Challenge" type="text"  class="validate" value="<?php echo Request::old('Challenge') ? Request::old('Challenge') : $competition['0']['question']; ?>" autocomplete="off"/>
                        
                        <label for="Challenge">Challenge Question*</label>
                        <?php if ($errors->has("Challenge")){  ?>
                          <small class="errorTxt"> <?php echo $errors->first("Challenge"); ?></small>
                        <?php } ?>
                      </div>
                    </div>

                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s4 input-field">
                        <?php 
                        $state = Request::old('state') ? Request::old('state') : $competition['0']['status']; 
                        $activeChk = '';
                        if($state == 1){
                          $activeChk = 'selected="selected"';
                        }

                        $closeChk = '';
                        if($state == 0){
                          $closeChk = 'selected="selected"';
                        }

                        if(!$activeChk && $closeChk){
                          $closeChk = 'selected="selected"';
                        }
                        ?>
                        <select id="state" name="state" class="validate">
                          <option value="1" <?= $activeChk; ?>>Active</option>
                          <option value="0" <?= $closeChk; ?> >Draft</option>
                        </select>

                        <label for="state">State*</label>

                        <?php if ($errors->has("state")){  ?>
                          <small class="errorTxt"> <?php echo $errors->first("state"); ?></small>
                        <?php } ?>
                      </div>
                    </div>
                  
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
<!-- Competition add ends -->
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
<script type="text/javascript">
    var path = "{{ route('competitions.autocompletePrize') }}";
    $('input#prize').typeahead({
        source:  function (query, process) {
        return $.get(path, { query: query }, function (data) {
                return process(data);
            });
        }
    });

    var path1 = "{{ route('competitions.autocompleteChallenge') }}";
    $('input#Challenge').typeahead({
        source:  function (query, process) {
        return $.get(path1, { query: query }, function (data) {
                return process(data);
            });
        }
    });
</script>

<script type="text/javascript">
  $(document).ready(function(){

    $(document).on('click', '.tabs .tab a', function(){
      
        var href = $(this).attr("href");
        window.location.hash = href;
      
    });

    $('input[name=ticket_price]').on('change', function(){
      var ticket_price = $(this).val(); 
      var prize = $('input[name=prize]').val(); 
      var prizeTotal = '<?php echo json_encode($prizeTotal); ?>';
      var prizeTotalObj = jQuery.parseJSON( prizeTotal );
      var prizeData = prizeTotalObj.map(function (prizeArr) {
        if (prizeArr.prize_name == prize) {

          if(ticket_price !== null && ticket_price !== '' && prize !== null && prize !== '') {
            //alert(ticket_price); alert(prize);
            var availablTickets = ((prizeArr.cash_value*2)/ticket_price);

            $('input[name=available_ticket]').val(Math.round(availablTickets)); 
            $('input[name=calculatedAvailableTicket]').val(Math.round(availablTickets)); 
          }else{
            alert('Prize is not available in Prize list.');
          }
          
        } 
      }); 
    });

    $('input[name=prize]').on('change', function(){
      var ticket_price = $('input[name=ticket_price]').val(); 
      var prize = $(this).val(); 
      var prizeTotal = '<?php echo json_encode($prizeTotal); ?>';
      var prizeTotalObj = jQuery.parseJSON( prizeTotal );
      var prizeData = prizeTotalObj.map(function (prizeArr) {
        if (prizeArr.prize_name == prize) {

          if(ticket_price !== null && ticket_price !== '' && prize !== null && prize !== '') {
            //alert(ticket_price); alert(prize);
            var availablTickets = ((prizeArr.cash_value*2)/ticket_price);
            $('input[name=available_ticket]').val(Math.round(availablTickets)); 
            $('input[name=calculatedAvailableTicket]').val(Math.round(availablTickets));
          }else{
            alert('Prize is not available in Prize list.');
          }
        } 
      }); 
    });

    $('input[name=available_ticket]').on('change', function(){
      var avlTicket = $(this).val();
      var calculatedAvailableTicket = $('input[name=calculatedAvailableTicket]').val();
      if(avlTicket < calculatedAvailableTicket){
        alert("You have entered lower than the calculated amount of tickets!");
      }

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