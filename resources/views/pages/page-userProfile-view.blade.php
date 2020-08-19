{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Users Profile')

{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-users.css')}}">
@endsection

{{-- page content  --}}
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

@if(session('message'))
  <div class="card-alert card green lighten-5">
    <div class="card-content green-text">
      <p><strong>Success!</strong> {{session('message')}} </p>
    </div>
    <button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">×</span>
    </button>
  </div>          
@endif
<!-- users view start -->
<div class="section users-edit">
  <div class="card">
    <div class="card-content">
      <div class="row">
        <div class="col s12" id="edit">
  <!-- users view media object ends -->
  

  <!-- users view card details start -->
  <!-- users add account form start -->
          <form id="userEditForm" action="{{route('users.userProfileUpdate', $user['0']['id'])}}" method="post">
            {{ csrf_field() }}
            {{ method_field('put') }}
            <div class="row">
              <div class="col s12">
                  
                  <!--First Name-->
                  <div class="col s12 input-field m6">
                    <input id="first_name" name="first_name" type="text" class="validate" value="<?php echo Request::old('first_name') ? Request::old('first_name') : $user['0']['first_name']; ?>"
                      data-error=".errorTxt1">
                    <label for="first_name">Firstname*</label>
                    @if ($errors->has('first_name'))
                      <small class="errorTxt1">{{ $errors->first('first_name') }}</small>
                    @endif
                    
                  </div>

                  <!--Surname-->
                  <div class="col s12 input-field m6">
                    <input id="surname" name="surname" type="text" class="validate" value="<?php echo Request::old('surname') ? Request::old('surname') : $user['0']['surname']; ?>" data-error=".errorTxt2">
                    <label for="surname">Surname*</label>
                    @if ($errors->has('surname'))
                      <small class="errorTxt1">{{ $errors->first('surname') }}</small>
                    @endif
                  </div>

                  <!--Emil Address-->
                  <div class="col s12 input-field">
                    <input id="email" name="email" type="email" class="validate" value="<?php echo Request::old('email') ? Request::old('email') : $user['0']['email']; ?>" data-error=".errorTxt3">
                    <label for="email">Email Address*</label>
                    @if ($errors->has('email'))
                      <small class="errorTxt1">{{ $errors->first('email') }}</small>
                    @endif
                  </div>
                  
                  <!-- <div class="col s12 input-field m6">
                    <input id="password" name="password" type="password" class="validate" value="<?php echo Request::old('surname'); ?>" data-error=".errorTxt2">
                    <label for="password">Password*</label>
                    @if ($errors->has('password'))
                      <small class="errorTxt1">{{ $errors->first('password') }}</small>
                    @endif
                  </div> -->

                    
                  

                  

                  <!--Contact Phone Number-->
                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s12 input-field m12">
                      <input id="contact_number" name="contact_number" type="text" minlength="10" class="validate" value="<?php echo Request::old('contact_number') ? Request::old('contact_number') : $user['0']['contact_number']; ?>" data-error=".errorTxt3" pattern="[1-9]{1}[0-9]{9}" maxlength="10">
                      <label for="contact_number">Contact Phone Number*</label>
                      <small class="errorTxt1 phoneError"></small>
                      @if ($errors->has('contact_number'))
                        <small class="errorTxt1">{{ $errors->first('contact_number') }}</small>
                      @endif
                    </div>
                  </div>

                  <!--Acceptd terms and conditions-->
                  <div class="row" style="padding: 0 1rem;">
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

              <!--Save button and google captcha-->
              
            </div>
          </form>
          <!-- users add account form ends -->
  <!-- users view card details ends -->
</div>
</div>
</div>
</div>

</div>
<!-- users view ends -->
@endsection

{{-- page script --}}
@section('page-script')
<script src="{{asset('js/scripts/page-users.js')}}"></script>
<script type="text/javascript">
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
      document.getElementById("userEditForm").submit();
    }
     
   }

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
  });
</script>
@endsection