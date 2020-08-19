{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Add a User')

{{-- vendor styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2-materialize.css')}}">
@endsection

{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-users.css')}}">
@endsection
 <script src="https://www.google.com/recaptcha/api.js"></script>
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
<!-- users edit start -->
<div class="section users-edit">
  <div class="card">
    <div class="card-content">
      <!-- <div class="card-body"> -->
      
      <div class="divider mb-3"></div>
      <div class="row">
        <div class="col s12" id="account">
          

          <!-- users add account form start -->
          <form id="userAddForm" action="{{ route('org_admins.store') }}" method="post">
            {{ csrf_field() }}
            <div class="row">
              <div class="col s12">
                
                  <input name="role" type="hidden" value="organisation_administrator"> 
                  
                  <div class="row" style="padding: 0 1rem;">
	                  <div class="col s12 input-field m6">
	                    <?php 
	                    $first_name = '';
	                    if($errors->has('first_name')){
	                    $first_name = "parsley-error";
	                    }
	                    ?>
	                    <select id="organisation" name="organisation" class="validate" data-error=".errorTxt1">
	                      <?php
	                          $organisationList = json_encode($organisationList);
	                          $organisationList = json_decode($organisationList, true);
	                          foreach ($organisationList as $key => $value) {
	                            ?>
	                              <option value="{{$value['organisation_id']}}" <?= Request::old('first_name') == $value['organisation_id'] ? 'selected' : ''; ?>>{{$value['organisation_name']}}</option>
	                            <?php
	                          }
	                      ?>

	                    </select>
	                    
	                    <label for="organisation">Organisation*</label>
	                    @if ($errors->has('organisation'))
	                      <small class="errorTxt1">{{ $errors->first('organisation') }}</small>
	                    @endif
	                    
	                  </div>
                  </div>

                  <div class="col s12 input-field m6">
                    <?php 
                    $first_name = '';
                    if($errors->has('first_name')){
                    $first_name = "parsley-error";
                    }
                    ?>
                    <input id="first_name" name="first_name" type="text" class="validate" value="<?php echo Request::old('first_name'); ?>"
                      data-error=".errorTxt1">
                    
                    <label for="first_name">Firstname*</label>
                    @if ($errors->has('first_name'))
                      <small class="errorTxt1">{{ $errors->first('first_name') }}</small>
                    @endif
                    
                  </div>
                  <div class="col s12 input-field m6">
                    <input id="surname" name="surname" type="text" class="validate" value="<?php echo Request::old('surname'); ?>" data-error=".errorTxt2">
                    <label for="surname">Surname*</label>
                    @if ($errors->has('surname'))
                      <small class="errorTxt1">{{ $errors->first('surname') }}</small>
                    @endif
                  </div>
                  <div class="col s12 input-field">
                    <input id="email" name="email" type="email" class="validate" value="<?php echo Request::old('email'); ?>" data-error=".errorTxt3">
                    <label for="email">Email Address*</label>
                    @if ($errors->has('email'))
                      <small class="errorTxt1">{{ $errors->first('email') }}</small>
                    @endif
                  </div>
                  <div class="row" style="padding: 0 1rem;" >
                    <div class="col s12 input-field m6">
                      <input id="date_of_birth" name="date_of_birth" type="text" class="birthdate-picker datepickeruser" value="<?php echo Request::old('date_of_birth'); ?>" data-error=".errorTxt3">
                      <label for="date_of_birth">Date of Birth*</label>
                      @if ($errors->has('date_of_birth'))
                        <small class="errorTxt1">{{ $errors->first('date_of_birth') }}</small>
                      @endif
                    </div>
                  </div>
                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s12 input-field m6">
                      <input id="address" name="address" type="text" class="validate" value="<?php echo Request::old('address'); ?>" data-error=".errorTxt3">
                      <label for="address">Address*</label>
                      @if ($errors->has('address'))
                        <small class="errorTxt1">{{ $errors->first('address') }}</small>
                      @endif
                    </div>
                  </div>
                  <div class="col s12 input-field m6">
                    <input id="town" name="town" type="text" class="validate" value="<?php echo Request::old('town'); ?>" data-error=".errorTxt3">
                    <label for="town">Town*</label>
                    @if ($errors->has('town'))
                      <small class="errorTxt1">{{ $errors->first('town') }}</small>
                    @endif
                  </div>
                  <div class="col s12 input-field m6">
                    <input id="post_code" name="post_code" type="text" class="validate" value="<?php echo Request::old('post_code'); ?>" data-error=".errorTxt3">
                    <label for="post_code">Postcode*</label>
                    @if ($errors->has('post_code'))
                      <small class="errorTxt1">{{ $errors->first('post_code') }}</small>
                    @endif
                  </div>
                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s12 input-field m6">
                      <input id="contact_number" name="contact_number" type="text" minlength="10" class="validate" value="<?php echo Request::old('contact_number'); ?>" data-error=".errorTxt3" pattern="[1-9]{1}[0-9]{9}" maxlength="10">
                      <label for="contact_number">Contact Phone Number*</label>
                      <small class="errorTxt1 phoneError"></small>
                      @if ($errors->has('contact_number'))
                        <small class="errorTxt1">{{ $errors->first('contact_number') }}</small>
                      @endif
                    </div>
                  </div>
                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s12 input-field m6">
                      <label>
                      <input id="status" name="status" type="checkbox" class="validate" value="1" data-error=".errorTxt1" <?php echo Request::old('status') == 1 ?  'checked="checked"' : ''; ?>> <span>Accept terms and conditions</span>
                    </label>
                      <!-- <label> <input id="status" name="status" type="checkbox" value=""
                        data-error=".errorTxt3"> <spam>Acceptd terms and conditions </spam></label> -->
                      @if ($errors->has('status'))
                        <small class="errorTxt1">{{ $errors->first('status') }}</small>
                      @endif
                    </div>
                    <div class="col s12 display-flex mt-3">
                      <button class="g-recaptcha btn indigo" 
                                    data-sitekey="6Lc1F_oUAAAAABXCu0MULxBbxalEQKCoHboXW9YQ" 
                                    data-callback='onSubmit' 
                                    data-action='submit'>
                        Save</button>
                    </div>
                  </div>
                  
                </div>
              </div>
              
            </div>
          </form>
          <!-- users add account form ends -->
          
          
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
<script>
  function onSubmit(token) {
    var mobNum = $("#contact_number").val();
    var filter = /^\d*(?:\.\d{1,2})?$/;

    if (filter.test(mobNum)) {
      if(mobNum.length==10){

        $(".phoneError").text('');
      } else {
        $("phoneError").text('');
        $("phoneError").text('Please put 10  digit phone number');
        $("#contact_number").focus();
        return false;
      }
    }else {
      $("phoneError").text('');
      $("phoneError").text('Not a valid number');
      $("#contact_number").focus();
      return false;
    }

    var competition_admin  = $( "input[id=competition_admin]:checked" ).val();
    var user_admin         = $( "input[id=user_admin]:checked" ).val();
    var organisation_admin = $( "input[id=organisation_admin]:checked" ).val();
    var prize_admin        = $( "input[id=prize_admin]:checked" ).val();
    var player             = $( "input[id=player]:checked" ).val();
    if(player && (competition_admin || user_admin  || organisation_admin || prize_admin)){
      alert("Please select either Player or other roles. You can not select player with other roles.");
    }else{
      document.getElementById("userAddForm").submit();
    }
    
  }


</script>
<script type="text/javascript">
  $(document).ready(function(){

    $("#contact_number").on("blur", function(){
      var mobNum = $(this).val();
      var filter = /^\d*(?:\.\d{1,2})?$/;

      if (filter.test(mobNum)) {
        if(mobNum.length==10){
          $("#mobile-valid").text('');
        } else {
          $("#folio-invalid").text();
          $("#mobile-valid").text("Please put 10  digit phone number");
          $("#contact_number").focus();
          return false;
        }
      }else {
        $("#folio-invalid").text("");
        $("#mobile-valid").text("Not a valid number");
        $("#contact_number").focus();
        return false;
      }
    
    });

    $('.datepickeruser').datepicker({
      autoClose: true,
      format: 'dd/mm/yyyy',
      container: 'body',
      onDraw: function onDraw() {
        // materialize select dropdown not proper working on mobile and tablets so we make it browser default select
        $('.datepicker-container').find('.datepicker-select').addClass('browser-default');
        $(".datepicker-container .select-dropdown.dropdown-trigger").remove();
      }
    });

    $( "input[id=competition_admin]" ).on('click', function(e){
      var competition_admin  = $( "input[id=competition_admin]:checked" ).val();
      var user_admin         = $( "input[id=user_admin]:checked" ).val();
      var organisation_admin = $( "input[id=organisation_admin]:checked" ).val();
      var prize_admin        = $( "input[id=prize_admin]:checked" ).val();
      var player             = $( "input[id=player]:checked" ).val();
      if(player && (competition_admin || user_admin  || organisation_admin || prize_admin)){
        $(this).prop("checked", false);
        alert("Please select either Player or other roles. You can not select player with other roles.");
      }
    });
    $( "input[id=user_admin]" ).on('click', function(e){
      var competition_admin  = $( "input[id=competition_admin]:checked" ).val();
      var user_admin         = $( "input[id=user_admin]:checked" ).val(); 
      var organisation_admin = $( "input[id=organisation_admin]:checked" ).val(); 
      var prize_admin        = $( "input[id=prize_admin]:checked" ).val();
      var player             = $( "input[id=player]:checked" ).val();
      if(player && (competition_admin || user_admin  || organisation_admin || prize_admin)){
        $(this).prop("checked", false);
        alert("Please select either Player or other roles. You can not select player with other roles.");
      }
    });
    $( "input[id=organisation_admin]" ).on('click', function(e){
      var competition_admin  = $( "input[id=competition_admin]:checked" ).val();
      var user_admin         = $( "input[id=user_admin]:checked" ).val();
      var organisation_admin = $( "input[id=organisation_admin]:checked" ).val();
      var prize_admin        = $( "input[id=prize_admin]:checked" ).val();
      var player             = $( "input[id=player]:checked" ).val();
      if(player && (competition_admin || user_admin  || organisation_admin || prize_admin)){
        $(this).prop("checked", false);
        alert("Please select either Player or other roles. You can not select player with other roles.");
      }
    });
    $( "input[id=prize_admin]" ).on('click', function(e){
      var competition_admin  = $( "input[id=competition_admin]:checked" ).val();
      var user_admin         = $( "input[id=user_admin]:checked" ).val();
      var organisation_admin = $( "input[id=organisation_admin]:checked" ).val();
      var prize_admin        = $( "input[id=prize_admin]:checked" ).val();
      var player             = $( "input[id=player]:checked" ).val();
      if(player && (competition_admin || user_admin  || organisation_admin || prize_admin)){
        $(this).prop("checked", false);
        alert("Please select either Player or other roles. You can not select player with other roles.");
      }
    });
    $( "input[id=player]" ).on('click', function(e){
      var competition_admin  = $( "input[id=competition_admin]:checked" ).val();
      var user_admin         = $( "input[id=user_admin]:checked" ).val();
      var organisation_admin = $( "input[id=organisation_admin]:checked" ).val();
      var prize_admin        = $( "input[id=prize_admin]:checked" ).val();
      var player             = $( "input[id=player]:checked" ).val();
      if(player && (competition_admin || user_admin  || organisation_admin || prize_admin)){
        $( "input[id=competition_admin]:checked" ).prop("checked", false);
        $( "input[id=user_admin]:checked" ).prop("checked", false);
        $( "input[id=organisation_admin]:checked" ).prop("checked", false);
        $( "input[id=prize_admin]:checked" ).prop("checked", false);
        //$(this).prop("checked", false);
        //alert("Please select either Player or other roles. You can not select player with other roles.");
      }
    });
  });
</script>
@endsection