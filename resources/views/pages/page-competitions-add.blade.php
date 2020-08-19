{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Add a Competition')

{{-- vendor styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2-materialize.css')}}">
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
      <div class="row">
        <div class="col s12">
          <!-- Competition add form start -->
          <form id="competitionAddForm" action="{{ route('competitions.store') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
              <div class="col s12">
                <div class="row">
                  

                    <!-- <div class="row" style="padding: 0 1rem;">
                      <div class="col s7 input-field">
                       
                        <input id="competition_title" name="competition_title" type="text"  class="validate" value="<?php echo Request::old('competition_title'); ?>" autocomplete="off"/>
                        
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
                       
                        <input id="prize" name="prize" type="text"  class="validate" value="<?php echo Request::old('prize'); ?>" autocomplete="off"/>
                        
                        <label for="prize">Prize*</label>
                        <?php if ($errors->has("prize")){  ?>
                          <small class="errorTxt"> <?php echo $errors->first("prize"); ?></small>
                        <?php } ?>
                      </div>
                    </div>

                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s4 input-field">
                        <input id="ticket_price" name="ticket_price" type="text"  class="validate" value="<?php echo Request::old('ticket_price'); ?>" onkeypress="return AllowOnlyNumbers(event);">

                        <label for="ticket_price">Ticket Price Coin(s)*</label>

                        <?php if ($errors->has("ticket_price")){  ?>
                          <small class="errorTxt"> <?php echo $errors->first("ticket_price"); ?></small>
                        <?php } ?>
                      </div>
                    </div>

                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s4 input-field">
                        <input id="available_ticket" name="available_ticket" type="number" class="validate" value="<?php echo Request::old('available_ticket'); ?>" min="0" max="9999">
                        <input type="hidden" name="calculatedAvailableTicket" value="">

                        <label for="available_ticket">Available Tickets*</label>

                        <?php if ($errors->has("available_ticket")){  ?>
                          <small class="errorTxt"> <?php echo $errors->first("available_ticket"); ?></small>
                        <?php } ?>
                      </div>
                    </div>                    

                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s4 input-field">
                        <input id="closed_date" name="closed_date" type="text" class="datepickerCompetition validate"  class="validate" value="<?php echo Request::old('closed_date'); ?>" placeholder="dd/mm/yyyy">

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
                        
                        <input id="Challenge" name="Challenge" class="materialize-textarea validate" value="<?php //echo Request::old('Challenge'); ?>" autocomplete="off"/>
                        <label for="Challenge">Challenge Question*</label>
                        

                        <?php if ($errors->has("Challenge")){  ?>
                          <small class="errorTxt"> <?php echo $errors->first("Challenge"); ?></small>
                        <?php } ?>
                      </div>
                    </div> -->

                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s7 input-field">
                       
                        <input id="Challenge" name="Challenge" type="text"  class="validate" value="<?php echo Request::old('Challenge'); ?>" autocomplete="off"/>
                        
                        <label for="Challenge">Challenge Question*</label>
                        <?php if ($errors->has("Challenge")){  ?>
                          <small class="errorTxt"> <?php echo $errors->first("Challenge"); ?></small>
                        <?php } ?>
                      </div>
                    </div>

                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s4 input-field">
                        <?php 
                        $state = Request::old('state'); 
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
                        <select id="state" name="state"  class="validate">
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
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script> -->
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
            ///alert('Prize is not available in Prize list.');
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

    var dateToday = new Date();
    $('.datepickerCompetition').datepicker({
      autoClose: true,
      format: 'dd/mm/yyyy',
      container: 'body',
      defaultDate: "+1w",
    changeMonth: true,
    numberOfMonths: 3,
    minDate: dateToday,
    onSelect: function(selectedDate) {
        var option = this.id == "from" ? "minDate" : "maxDate",
            instance = $(this).data("datepicker"),
            date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
        dates.not(this).datepicker("option", option, date);
    },
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