{{-- layout --}}
@extends('layouts.fullLayoutMaster')

{{-- page title --}}
@section('title','User Login')

{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/login.css')}}">
<script src="https://www.google.com/recaptcha/api.js"></script>

@endsection

{{-- page content --}}
@section('content')

@if(session('message'))
<div class="card-alert card green lighten-5">
  <div class="card-content green-text">
    <p>{{session('message')}}</p>
  </div>
  <button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">×</span>
  </button>
</div>
@endif
@if($errors->has('error'))
<div class="card-alert card red lighten-5">
  <div class="card-content red-text">
    <p>{{ $errors->first('error') }}</p>
  </div>
  <button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">×</span>
  </button>
</div>
@endif

<div id="login-page" class="row">
  <div class="col s12 m6 l4 z-depth-4 card-panel border-radius-6 login-card bg-opacity-8">
    <form action="{{ route('admin.login.submit') }}" method="post" class="login-form" id="login-form" onsubmit="return checkForm(this);">
      {{ csrf_field() }}
      
      <div class="row">
        <div class="input-field col s12" style="padding: 0 30px;">
          <img src="{{ URL::to('/') }}/images/logo/Huxley_Logo_Final.svg" >
          
        </div>
      </div>

      <div class="row">
        <div class="input-field col s12">
          <h5 class="ml-4">Sign in</h5>
          
        </div>
      </div>
      <div class="col s12 input-field">
        <i class="material-icons prefix pt-2">person_outline</i>
        <input id="email" name="email" type="text" class="validate" value="<?php echo Request::old('email'); ?>" data-error=".errorTxt1">
        <label for="email">Email Address</label>
        @if ($errors->has('email'))
          <small class="errorTxt1">{{ $errors->first('email') }}</small>
        @endif
        
      </div>
      
      
        <div class="col s12 input-field">
          <i class="material-icons prefix pt-2">lock_outline</i>
          <input id="password" type="password" class="validate" name="password" value="<?php echo Request::old('password'); ?>"/>
          <label for="password">Password</label>
          <p><small class="errorTxt1 passerror"></small></p>
          @if($errors->has('password'))
          <small class="errorTxt1 passerror">{{ $errors->first('password') }}</small>
            <!-- <ul class="parsley-errors-list filled" id="parsley-id-5" style="text-align: left;    margin-top: -20px;">
              <li class="parsley-required">{{ $errors->first('password') }}</li>
            </ul>   -->
          @endif
        </div>

        <!-- <div class="col s12 input-field m6"> -->
            <label style="padding: 0 1.5rem;">
              <?php $satus = Request::old('status'); 
               $chk = '';
                if($satus){
                  $chk = 'checked="checked"';
                }
              ?>
            <input id="remember" name="remember" type="checkbox" class="validate" value="on" data-error=".errorTxt1" {{$chk}}> <span>Remember Me</span>
          </label>
            @if ($errors->has('remember'))
              <small class="errorTxt1">{{ $errors->first('remember') }}</small>
            @endif
        <!-- </div>  -->
      <!-- <div class="row">
        <div class="col s12 m12 l12 ml-2 mt-1">
          <p>
            
            <label>
              <input type="checkbox" />
              <span>Remember Me</span>
            </label>
            
          </p>
        </div>
      </div> -->
      <div class="row">
        <div class="input-field col s12">
          <!-- <a href="{{asset('/')}}"
            class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12">Login</a> -->

            

            <!-- <input type="submit" class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12" name="submit" value="Login"> -->
            <button class="g-recaptcha btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12 mb-1" 
        data-sitekey="6Lc1F_oUAAAAABXCu0MULxBbxalEQKCoHboXW9YQ" 
        data-callback='onSubmit' 
        data-action='submit'>Submit</button>
             
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6 m6 l6">
          <!-- <p class="margin medium-small"><a href="{{asset('user-register')}}">Register Now!</a></p> -->
        </div>
        <div class="input-field col s6 m6 l6">
          <p class="margin right-align medium-small"><a href="{{ route('admin.resetPassword') }}">Forgot password ?</a>
          </p>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

<script>
  /*function validatePassword(pass, idies){

    if(pass != "") {
      if(pass < 8) {
        //alert("Error: Password must contain at least six characters!");
        $(".passerror").text("Password must contain at least eight characters!");
        $("input[name=password]").css('border-bottom-color', '#ff4081');
        idies.focus();
        return false;
      }
      if(pass == $('#email').val()){
        //alert("Error: Password must be different from Username!");
        $(".passerror").text("Password must be different from Username!");
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

  } */


  function onSubmit(token) {
    /*var pass = $('#password').val();
    if(pass != "") {
      if(pass < 8) {
        //alert("Error: Password must contain at least six characters!");
        $(".passerror").text("Password must contain at least eight characters!");
        $("input[name=password]").css('border-bottom-color', '#ff4081');
        idies.focus();
        return false;
      }
      if(pass == $('#email').val()){
        //alert("Error: Password must be different from Username!");
        $(".passerror").text("Password must be different from Username!");
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

    //alert("You entered a valid password!");
    $(".passerror").text('');
    $("input[name=password]").css('border-bottom-color', '');*/

    document.getElementById("login-form").submit();
    return true;
  }
</script>