{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Add a Free Ticket')

{{-- vendor styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2-materialize.css')}}">
@endsection

{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-users.css')}}">
<meta name="csrf-token" content="{{ csrf_token() }}">
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
      <div class="row">
        <div class="col s12">
          <!-- Competition add form start -->
          <form id="competitionAddForm" action="{{ route('competitions.storeFreeTicket') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
              <div class="col s12">
                <div class="row">
                  

                  <!-- <div class="row" style="padding: 0 1rem;" >
                    <div class="col s7 input-field">
                      <h2 for="question" style="color:#9e9e9e; font-size: 1rem; margin: 0rem 0 1.424rem 0;">Question</h2>
                      <textarea id="question" name="question" class="birthdate-picker" data-error=".errorTxt3"><?php echo Request::old('question'); ?></textarea>
                      
                      @if ($errors->has('question'))
                        <small class="errorTxt1">{{ $errors->first('question') }}</small>
                      @endif
                    </div>
                  </div> -->

                   
                    <?php 
                      $competitionList = json_encode($competitionList);
                      $competitionList = json_decode($competitionList,'true');
                      //print_r($competitionList); //die(); 
                    ?>
                    

                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s7 input-field">
                       
                        <input list="competitionList" id="competition" name="competition" type="text"  class="validate" value="<?php echo Request::old('competition'); ?>" autocomplete="off"/>
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
                        <label for="Challenge">Challenge*</label>
                      </div>
                    </div>

                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s7 input-field">
                       
                        <input id="player" name="player" type="text"  class="validate" value="<?php echo Request::old('player'); ?>" autocomplete="off"/>
                        
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
                        <label for="challenge_answer">Challenge Answer*</label>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
<script type="text/javascript">
  /*var path = "{{ route('competitions.autocompleteCompetition') }}";
    $('input#competition').typeahead({
        source:  function (query, process) {
        return $.get(path, { query: query }, function (data) {
                return process(data);
            });
        }
    });*/

    var path1 = "{{ route('competitions.autocompleteUser') }}";
    $('input#player').typeahead({
        source:  function (query, process) {
        return $.get(path1, { query: query }, function (data) {
                return process(data);
            });
        }
    });
  $(document).ready(function(){

    $('input[name=ticket_price]').on('change', function(){
      var ticket_price = $(this).val(); 
      var prize = $('input[name=prize]').val(); 
      var prizeTotal = '';
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
      var challenge_answer_old = "<?= Request::old('challenge_answer'); ?>";
      $.ajaxSetup({
        headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      jQuery.ajax({
        url: "<?php echo App::make('url')->to('/getCompetitionChallengeList/'); ?>"+"/"+encodeURIComponent(competitionVal),
        method: 'get',
        dataType: 'json',
        success: function(result){
          $("input[name=Challenge]").val(result.challengesName);
          var opt = '<option value="">--</option>';
          var k = 97;
          var Cap = 65;
          $.each(result.challengesAns, function( key, val ) { 
              var checked = '';
              var str =String.fromCharCode(k); 
              var strCap =String.fromCharCode(Cap); 
              if(challenge_answer_old == val.answer){
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