{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Add a Category')

{{-- vendor styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2-materialize.css')}}">
@endsection

{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-users.css')}}">
<script src="https://cdn.ckeditor.com/4.5.11/full-all/ckeditor.js"></script>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
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

<!-- FAQs add start -->
<div class="section users-edit">
  <div class="card">
    <div class="card-content">
      <div class="row">
        
        <div class="col s12">
          <!-- FAQs add form start -->
          <form id="categoryAddForm" action="{{ route('category.store') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
              <div class="col s12">
                <div class="row">
                  
                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s7 input-field">
                      <input id="category_name" name="category_name" type="text"  class="validate" value="<?php echo Request::old('category_name'); ?>"
                        data-error=".errorTxt1">
                      
                      <label for="category_name">Category Name*</label>
                      @if ($errors->has('category_name'))
                        <small class="errorTxt1">{{ $errors->first('category_name') }}</small>
                      @endif
                      
                    </div>
                  </div>

                  <div class="row" style="padding: 0 1rem;" >
                    <div class="col s7 input-field">
                      <!-- <h2 for="description" style="color:#9e9e9e; font-size: 1rem; margin: 0rem 0 1.424rem 0;">Description of the prize*</h2> -->
                      <textarea id="category_description" name="category_description" class="validate materialize-textarea birthdate-picker" data-error=".errorTxt3" style="height: 233px;"><?php echo Request::old('category_description'); ?></textarea>
                      <label for="category_description">Category Description*</label>
                      
                      @if ($errors->has('category_description'))
                        <small class="errorTxt1">{{ $errors->first('category_description') }}</small>
                      @endif
                    </div>
                  </div>

                  <div class="row" style="padding: 0 1rem;" >
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
          <!-- FAQs add form ends -->
        </div>
      </div>
      <!-- </div> -->
    </div>
  </div>
</div>
<!-- FAQs add ends -->
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
@endsection