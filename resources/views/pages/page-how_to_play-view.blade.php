{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','How to Play')

{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-users.css')}}">
 <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
 <script src="https://cdn.ckeditor.com/4.5.11/full-all/ckeditor.js"></script>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

@endsection

{{-- page content  --}}
@section('content')

@if($errors->any())
  <div class="card-alert card red lighten-5">
    <div class="card-content red-text">
      <p><strong>Error!</strong> {{$errors->first()}} </p>
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

<!-- setting add start -->
<div class="section users-edit">
  <div class="card">
    <div class="card-content">

      <div class="row">
        <div class="col s12">
         
          <!-- setting add account form start -->
          <form id="settingsAddForm" action="{{ route('how_to_play.store') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
              <div class="col s12">
                <div class="row">

                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s10 input-field">
                      <h2 for="description" style="color:#9e9e9e; font-size: 1rem; margin: 0rem 0 1.424rem 0;">Content*</h2>
                      <textarea id="content" name="content" type="text" class="validate" data-error=".errorTxt2" style="width: 100%;height: 150px;"><?php echo Request::old('content') ? Request::old('content') : @$play['0']['content']; ?></textarea>
                      <script>
                       CKEDITOR.replace( 'content' );
                      </script>
                      
                      @if ($errors->has('content'))
                        <small class="errorTxt1">{{ $errors->first('content') }}</small>
                      @endif                     
                    </div>
                  </div>
                  
                  
                  
                </div>
              </div>
              <div class="col s12 display-flex mt-3">
                <button class="g-recaptcha btn indigo" 
                              data-sitekey="6Lc1F_oUAAAAABXCu0MULxBbxalEQKCoHboXW9YQ" 
                              data-callback='onSubmit' 
                              data-action='submit'>
                  Save</button>
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
<!-- setting add ends -->
@endsection

{{-- page script --}}
@section('page-script')

<script src="{{asset('js/scripts/page-users.js')}}"></script>
<script src="{{asset('js/scripts/ui-alerts.js')}}"></script>
@endsection