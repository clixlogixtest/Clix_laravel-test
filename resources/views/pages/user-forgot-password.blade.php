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
    <form class="login-form" action="{{ route('admin.resetMail') }}" method="post">
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
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <i class="material-icons prefix pt-2">person_outline</i>
          <?php 
            $email = '';
            if($errors->has('email')){
              $email = "parsley-error";
            }
          ?>
          <input id="email" type="email" class="form-control <?= $email; ?>" name="email" value="<?php echo Request::old('email'); ?>">
          <label for="email" class="center-align">Email</label>
          @if($errors->has('email'))
          <small class="errorTxt1">{{ $errors->first('email') }}</small>
          @endif
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <!-- <a href="{{asset('/')}}"
            class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12 mb-1">Reset
            Password</a> -->
            <input type="submit" class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12 mb-1" id="fontColor" name="submit" value="Reset Password">
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6 m6 l6">
          <p class="margin medium-small"><a href="{{route( 'admin.login' )}}">Login</a></p>
        </div>
        <div class="input-field col s6 m6 l6">
          <!-- <p class="margin right-align medium-small"><a href="{{asset('user-register')}}">Register</a></p> -->
        </div>
      </div>
    </form>
  </div>
</div>
@endsection