{{-- layout --}}
@extends('layouts.fullLayoutMaster')

{{-- page title --}}
@section('title','User Forgot Password')

{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/forgot.css')}}">
@endsection

{{-- page content --}}
@section('content')
<div id="forgot-password" class="row">
  <div class="col s12 m6 l4 z-depth-4 offset-m4 card-panel border-radius-6 forgot-card bg-opacity-8">
    <form class="login-form" action="{{ route('admin.reset') }}" method="post" onsubmit="return checkForm(this);">
      <div class="row">
        <div class="input-field col s12">
          <h5 class="ml-4">Forgot Password</h5>
          <p class="ml-4">You can reset your password</p>
          {{ csrf_field() }}
          

          @if($errors->has('error'))
            <div class="error">{{ $errors->first('error') }}</div>
          @endif
          
          @if(session('message'))
            <div class="success">{{session('message')}}</div>              
          @endif

          <input type="hidden" class="form-control" name="email" value="{{ $_GET['email'] }}"/>
          @if($errors->has('email'))
            <ul class="parsley-errors-list filled" id="parsley-id-5" style="text-align: left;    margin-top: -20px;">
              <li class="parsley-required">{{ $errors->first('email') }}</li>
            </ul>  
          @endif

          <input type="hidden" class="form-control" name="token" value="{{ $_GET['token'] }}"/>
          @if($errors->has('token'))
            <ul class="parsley-errors-list filled" id="parsley-id-5" style="text-align: left;    margin-top: -20px;">
              <li class="parsley-required">{{ $errors->first('token') }}</li>
            </ul>  
          @endif

        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <i class="material-icons prefix pt-2">lock_outline</i>
          <?php 
            $password = '';
            if($errors->has('password')){
              $password = "parsley-error";
            }
          ?>
          <input type="password" id="password" class="form-control <?= $password; ?>" name="password" value="<?php echo Request::old('password'); ?>" onchange="validatePassword($(this).val(), $(this))"/>
          <label for="password" class="center-align">Enter new password</label>
          <p><small class="errorTxt1 passerror"></small></p>
          @if($errors->has('password'))
            <ul class="parsley-errors-list filled" id="parsley-id-5" style="text-align: left;    margin-top: -20px;">
              <li class="parsley-required">{{ $errors->first('password') }}</li>
            </ul>  
          @endif
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <i class="material-icons prefix pt-2">lock_outline</i>
          <?php 
            $password_confirmation = '';
            if($errors->has('password_confirmation')){
              $password_confirmation = "parsley-error";
            }
          ?>
          <input type="password" class="form-control <?= $password_confirmation; ?>" name="password_confirmation" value="<?php echo Request::old('password_confirmation'); ?>" onchange="validateConfirmPassword($(this).val(), $(this))"/>
          <p><small class="errorTxt1 confirmpasserror"></small></p>
          <label for="password" class="center-align">Enter confirm password</label>
          @if($errors->has('password_confirmation'))
            <ul class="parsley-errors-list filled" id="parsley-id-5" style="text-align: left;    margin-top: -20px;">
              <li class="parsley-required">{{ $errors->first('password_confirmation') }}</li>
            </ul>  
          @endif
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <!-- <a href="{{asset('/')}}"
            class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12 mb-1">Reset
            Password</a> -->
            <input type="submit" class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12 mb-1" id="fontColor" name="submit" value="SEND RESET" style="color: #fff;line-height: 35px;">
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

<script type="text/javascript">

  function validatePassword(pass, idies){

    if(pass != "") {
      if(pass < 8) {
        //alert("Error: Password must contain at least six characters!");
        $(".passerror").text("Password must contain at least eight characters!");
        $("input[name=password]").css('border-bottom-color', '#ff4081');
        idies.focus();
        return false;
      }
      re = /[0-9]/;
      if(!re.test(pass)) {
        //alert("Error: password must contain at least one number (0-9)!");
        $(".passerror").text("Password must contain at least one number (0-9)!");
        $("input[name=password]").css('border-bottom-color', '#ff4081');
        idies.focus();
        return false;
      }
      re = /[a-z]/;
      if(!re.test(pass)) {
        //alert("Error: password must contain at least one lowercase letter (a-z)!");
        $(".passerror").text("Password must contain at least one lowercase letter (a-z)!");
        $("input[name=password]").css('border-bottom-color', '#ff4081');
        idies.focus();
        return false;
      }
      re = /[A-Z]/;
      if(!re.test(pass)) {
        //alert("Error: password must contain at least one uppercase letter (A-Z)!");
        $(".passerror").text("Password must contain at least one uppercase letter (A-Z)!");
        $("input[name=password]").css('border-bottom-color', '#ff4081');
        idies.focus();
        return false;
      }
      re = /[!@#\$%\^&\*]/;
      if(!re.test(pass)) {
        //alert("Error: password must contain at least one special character!");
        $(".passerror").text("Password must contain at least one special character!");
        $("input[name=password]").css('border-bottom-color', '#ff4081');
        idies.focus();
        return false;
      }
    } else {
      //alert("Error: Please check that you've entered and confirmed your password!");
      $(".passerror").text("Please check that you've entered and confirmed your password!");
      $("input[name=password]").css('border-bottom-color', '#ff4081');
      idies.focus();
      return false;
    }

    $(".passerror").text('');
    $("input[name=password]").css('border-bottom-color', '');

    //alert("You entered a valid password!");

    return true;

  } 

  function validateConfirmPassword(pass, idies){

    if(pass != "") {
      if(pass < 8) {
        //alert("Error: Password must contain at least six characters!");
        $(".confirmpasserror").text("Password must contain at least eight characters!");
        $("input[name=password_confirmation]").css('border-bottom-color', '#ff4081');
        idies.focus();
        return false;
      }
      re = /[0-9]/;
      if(!re.test(pass)) {
        //alert("Error: password must contain at least one number (0-9)!");
        $(".confirmpasserror").text("Password must contain at least one number (0-9)!");
        $("input[name=password_confirmation]").css('border-bottom-color', '#ff4081');
        idies.focus();
        return false;
      }
      re = /[a-z]/;
      if(!re.test(pass)) {
        //alert("Error: password must contain at least one lowercase letter (a-z)!");
        $(".confirmpasserror").text("Password must contain at least one lowercase letter (a-z)!");
        $("input[name=password_confirmation]").css('border-bottom-color', '#ff4081');
        idies.focus();
        return false;
      }
      re = /[A-Z]/;
      if(!re.test(pass)) {
        //alert("Error: password must contain at least one uppercase letter (A-Z)!");
        $(".confirmpasserror").text("Password must contain at least one uppercase letter (A-Z)!");
        $("input[name=password_confirmation]").css('border-bottom-color', '#ff4081');
        idies.focus();
        return false;
      }
      re = /[!@#\$%\^&\*]/;
      if(!re.test(pass)) {
        //alert("Error: password must contain at least one special character!");
        $(".confirmpasserror").text("Password must contain at least one special character!");
        $("input[name=password_confirmation]").css('border-bottom-color', '#ff4081');
        idies.focus();
        return false;
      }
    } else {
      //alert("Error: Please check that you've entered and confirmed your password!");
      $(".confirmpasserror").text("Please check that you've entered and confirmed your password!");
      $("input[name=password_confirmation]").css('border-bottom-color', '#ff4081');
      idies.focus();
      return false;
    }

    $(".confirmpasserror").text('');
    $("input[name=password_confirmation]").css('border-bottom-color', '');

    //alert("You entered a valid password!");

    return true;

  } 

  function checkForm(form)
  {


    if(form.password.value != "" && form.password.value == form.password_confirmation.value) {
      if(form.password.value.length < 8) {
        $(".passerror").text("Password must contain at least eight characters!");
        $("input[name=password]").css('border-bottom-color', '#ff4081');
        form.pwd1.focus();
        return false;
      }
      re = /[0-9]/;
      if(!re.test(form.password.value)) {
        $(".passerror").text("Password must contain at least one number (0-9)!");
        $("input[name=password]").css('border-bottom-color', '#ff4081');
        form.pwd1.focus();
        return false;
      }
      re = /[a-z]/;
      if(!re.test(form.password.value)) {
        $(".passerror").text("Password must contain at least one lowercase letter (a-z)!");
        $("input[name=password]").css('border-bottom-color', '#ff4081');
        form.pwd1.focus();
        return false;
      }
      re = /[A-Z]/;
      if(!re.test(form.password.value)) {
       $(".passerror").text("Password must contain at least one uppercase letter (A-Z)!");
        $("input[name=password]").css('border-bottom-color', '#ff4081');
        form.pwd1.focus();
        return false;
      }

      re = /[!@#\$%\^&\*]/;
      if(!re.test(form.password.value)) {
        //alert("Error: password must contain at least one special character!");
        $(".passerror").text("Password must contain at least one special character!");
        $("input[name=password]").css('border-bottom-color', '#ff4081');
        idies.focus();
        return false;
      }

    } else {
      $(".passerror").text("Please check that you've entered and confirmed your password!");
      $("input[name=password]").css('border-bottom-color', '#ff4081');
      form.password.focus();
      return false;
    }

    $(".passerror").text('');
    $("input[name=password]").css('border-bottom-color', '');
    return true;
  }

</script>
