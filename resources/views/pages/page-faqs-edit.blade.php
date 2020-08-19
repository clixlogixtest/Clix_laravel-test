{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Edit a FAQ')

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
<!-- FAQs edit start -->
<div class="section users-edit">
  <div class="card">
    <div class="card-content">
      <div class="row">
        
        <div class="col s12">
          <!-- FAQs edit form start -->
          <form id="faqEditForm" action="{{route('faqs.update', $faq['0']['id'])}}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            {{ method_field('put') }}
            <div class="row">
              <div class="col s12">
                <div class="row">
                  
                  <div class="row" style="padding: 0 1rem;" >
                    <div class="col s7 input-field">
                      <h2 for="question" style="color:#9e9e9e; font-size: 1rem; margin: 0rem 0 1.424rem 0;">FAQ Question*</h2>
                      <textarea id="question" name="question" class="birthdate-picker" data-error=".errorTxt3"><?php echo Request::old('question') ? Request::old('question') : $faq['0']['question']; ?></textarea>

                      
                      @if ($errors->has('question'))
                        <small class="errorTxt1">{{ $errors->first('question') }}</small>
                      @endif
                    </div>
                  </div>

                  <div class="row" style="padding: 0 1rem;" >
                    <div class="col s7 input-field">
                      <h2 for="answer" style="color:#9e9e9e; font-size: 1rem; margin: 0rem 0 1.424rem 0;">FAQ Answer*</h2>
                      <textarea id="answer" name="faq_answer" class="birthdate-picker" data-error=".errorTxt3"><?php echo Request::old('faq_answer') ? Request::old('faq_answer') : $faq['0']['answer']; ?></textarea>
                      <script>
                       CKEDITOR.replace( 'answer' );
                      </script>
                      
                      @if ($errors->has('faq_answer'))
                        <small class="errorTxt1">{{ $errors->first('faq_answer') }}</small>
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
          <!-- FAQs edit form ends -->
        </div>
      </div>
      <!-- </div> -->
    </div>
  </div>
</div>
<!-- FAQs edit ends -->
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