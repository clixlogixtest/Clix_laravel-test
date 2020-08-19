{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Change Password')

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
          <form id="userEditForm" action="{{route('users.changePasswordUpdate', $user['0']['id'])}}" method="post" onsubmit="return checkForm(this);">
            {{ csrf_field() }}
            {{ method_field('put') }}
            <div class="row">
              <div class="col s12">
                  
                  <!--First Name-->
                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s12 input-field m6">
                      <input id="current_password" name="current_password" type="password" class="validate" value="<?php echo Request::old('current_password') ? Request::old('current_password') : $user['0']['current_password']; ?>"
                        data-error=".errorTxt1">
                      <label for="current_password">Current Password*</label>
                      <p><small class="errorTxt1 currentpasserror"></small></p>
                      @if ($errors->has('current_password'))
                        <small class="errorTxt1">{{ $errors->first('current_password') }}</small>
                      @endif
                      
                    </div>
                  </div>

                  <!--Surname-->
                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s12 input-field m6">
                      <input id="new_password" name="new_password" type="password" class="validate" value="<?php echo Request::old('new_password') ? Request::old('new_password') : $user['0']['new_password']; ?>" data-error=".errorTxt2" onchange="validatePassword($(this).val(), $(this))">
                      <label for="new_password">New Password*</label>
                      <p><small class="errorTxt1 passerror"></small></p>
                      @if ($errors->has('new_password'))
                        <small class="errorTxt1">{{ $errors->first('new_password') }}</small>
                      @endif
                    </div>
                  </div>

                  <!--Surname-->
                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s12 input-field m6">
                      <input id="confirm_new_password" name="confirm_new_password" type="password" class="validate" value="<?php echo Request::old('confirm_new_password') ? Request::old('confirm_new_password') : $user['0']['confirm_new_password']; ?>" data-error=".errorTxt2" onchange="validateConfirmPassword($(this).val(), $(this))">
                      <label for="confirm_new_password">Confirm New Password*</label>
                      <p><small class="errorTxt1 confirmpasserror"></small></p>
                      @if ($errors->has('confirm_new_password'))
                        <small class="errorTxt1">{{ $errors->first('confirm_new_password') }}</small>
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
<!-- <script src="{{asset('js/scripts/page-users.js')}}"></script> -->
<script type="text/javascript">

  function validatePassword(pass, idies){

    if(pass != "") {
      if(pass < 8) {
        //alert("Error: Password must contain at least six characters!");
        $(".passerror").text("Password must contain at least eight characters!");
        $("input[name=new_password]").css('border-bottom-color', '#ff4081');
        idies.focus();
        return false;
      }
      re = /[0-9]/;
      if(!re.test(pass)) {
        //alert("Error: password must contain at least one number (0-9)!");
        $(".passerror").text("Password must contain at least one number (0-9)!");
        $("input[name=new_password]").css('border-bottom-color', '#ff4081');
        idies.focus();
        return false;
      }
      re = /[a-z]/;
      if(!re.test(pass)) {
        //alert("Error: password must contain at least one lowercase letter (a-z)!");
        $(".passerror").text("Password must contain at least one lowercase letter (a-z)!");
        $("input[name=new_password]").css('border-bottom-color', '#ff4081');
        idies.focus();
        return false;
      }
      re = /[A-Z]/;
      if(!re.test(pass)) {
        //alert("Error: password must contain at least one uppercase letter (A-Z)!");
        $(".passerror").text("Password must contain at least one uppercase letter (A-Z)!");
        $("input[name=new_password]").css('border-bottom-color', '#ff4081');
        idies.focus();
        return false;
      }
      re = /[!@#\$%\^&\*]/;
      if(!re.test(pass)) {
        //alert("Error: password must contain at least one special character!");
        $(".passerror").text("Password must contain at least one special character!");
        $("input[name=new_password]").css('border-bottom-color', '#ff4081');
        idies.focus();
        return false;
      }
    } else {
      //alert("Error: Please check that you've entered and confirmed your password!");
      $(".passerror").text("Please check that you've entered and confirmed your password!");
      $("input[name=new_password]").css('border-bottom-color', '#ff4081');
      idies.focus();
      return false;
    }

    $(".passerror").text('');
    $("input[name=new_password]").css('border-bottom-color', '');

    //alert("You entered a valid password!");

    return true;

  } 

  function validateConfirmPassword(pass, idies){

    if(pass != "") {
      if(pass < 8) {
        //alert("Error: Password must contain at least six characters!");
        $(".confirmpasserror").text("Password must contain at least eight characters!");
        $("input[name=current_password]").css('border-bottom-color', '#ff4081');
        idies.focus();
        return false;
      }
      re = /[0-9]/;
      if(!re.test(pass)) {
        //alert("Error: password must contain at least one number (0-9)!");
        $(".confirmpasserror").text("Password must contain at least one number (0-9)!");
        $("input[name=confirm_new_password]").css('border-bottom-color', '#ff4081');
        idies.focus();
        return false;
      }
      re = /[a-z]/;
      if(!re.test(pass)) {
        //alert("Error: password must contain at least one lowercase letter (a-z)!");
        $(".confirmpasserror").text("Password must contain at least one lowercase letter (a-z)!");
        $("input[name=confirm_new_password]").css('border-bottom-color', '#ff4081');
        idies.focus();
        return false;
      }
      re = /[A-Z]/;
      if(!re.test(pass)) {
        //alert("Error: password must contain at least one uppercase letter (A-Z)!");
        $(".confirmpasserror").text("Password must contain at least one uppercase letter (A-Z)!");
        $("input[name=confirm_new_password]").css('border-bottom-color', '#ff4081');
        idies.focus();
        return false;
      }
      re = /[!@#\$%\^&\*]/;
      if(!re.test(pass)) {
        //alert("Error: password must contain at least one special character!");
        $(".confirmpasserror").text("Password must contain at least one special character!");
        $("input[name=confirm_new_password]").css('border-bottom-color', '#ff4081');
        idies.focus();
        return false;
      }
    } else {
      //alert("Error: Please check that you've entered and confirmed your password!");
      $(".confirmpasserror").text("Please check that you've entered and confirmed your password!");
      $("input[name=confirm_new_password]").css('border-bottom-color', '#ff4081');
      idies.focus();
      return false;
    }

    $(".confirmpasserror").text('');
    $("input[name=confirm_new_passwordconfirm_new_password]").css('border-bottom-color', '');

    //alert("You entered a valid password!");

    return true;

  } 

  function checkForm(form)
  {
    if(!form.current_password.value){
      $(".currentpasserror").text("Current password is  required!");
        $("input[name=current_password]").css('border-bottom-color', '#ff4081');
        form.current_password.focus();
        return false;
    }

    if(form.new_password.value && form.confirm_new_password.value && form.new_password.value == form.confirm_new_password.value) {
      if(form.new_password.value.length < 8) {
        $(".passerror").text("Password must contain at least eight characters!");
        $("input[name=new_password]").css('border-bottom-color', '#ff4081');
        form.new_password.focus();
        return false;
      }
      re = /[0-9]/;
      if(!re.test(form.new_password.value)) {
        $(".passerror").text("Password must contain at least one number (0-9)!");
        $("input[name=new_password]").css('border-bottom-color', '#ff4081');
        form.new_password.focus();
        return false;
      }
      re = /[a-z]/;
      if(!re.test(form.new_password.value)) {
        $(".passerror").text("Password must contain at least one lowercase letter (a-z)!");
        $("input[name=new_password]").css('border-bottom-color', '#ff4081');
        form.new_password.focus();
        return false;
      }
      re = /[A-Z]/;
      if(!re.test(form.new_password.value)) {
       $(".passerror").text("Password must contain at least one uppercase letter (A-Z)!");
        $("input[name=new_password]").css('border-bottom-color', '#ff4081');
        form.new_password.focus();
        return false;
      }

      re = /[!@#\$%\^&\*]/;
      if(!re.test(form.new_password.value)) {
        //alert("Error: password must contain at least one special character!");
        $(".passerror").text("Password must contain at least one special character!");
        $("input[name=new_password]").css('border-bottom-color', '#ff4081');
        form.new_password.focus();
        return false;
      }

    } else {
      $(".passerror").text("Please check that you've entered and confirmed your password!");
      $("input[name=new_password]").css('border-bottom-color', '#ff4081');
      form.new_password.focus();
      return false;
    }

    $(".passerror").text('');
    $("input[name=new_password]").css('border-bottom-color', '');
    return true;
  }

</script>
@endsection