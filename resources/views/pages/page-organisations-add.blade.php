{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Add Organisation')

{{-- vendor styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2-materialize.css')}}">
@endsection

{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-users.css')}}">
<style type="text/css">
  .getfileInput{
    width: 0;
    height: 0;
    overflow: hidden;
  }
</style>
@endsection

{{-- page content --}}
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


<!-- users edit start -->
<div class="section users-edit">
  <div class="card">
    <div class="card-content">

      <div class="row">
        <div class="col s12">
         
          <!-- users add account form start -->
          <form id="organisationAddForm" action="{{ route('organisations.store') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
              <div class="col s12">
                <div class="row">

                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s7 input-field m5">
                      <input id="organisation_name" name="organisation_name" type="text" class="validate" value="<?php echo Request::old('organisation_name'); ?>"
                        data-error=".errorTxt1">
                      
                      <label for="organisation_name">Organisation Name*</label>
                      @if($errors->has('organisation_name'))
                        <small class="errorTxt1">{{ $errors->first('organisation_name') }}</small>
                      @endif
                      
                    </div>
                    <div class="col s7 input-field m5">
                      <input id="uk_company_registration_number" name="uk_company_registration_number" type="text" class="validate" data-error=".errorTxt2" value="<?php echo Request::old('uk_company_registration_number'); ?>">
                      <label for="uk_company_registration_number">UK Company Registration Number*</label>
                      @if ($errors->has('uk_company_registration_number'))
                        <small class="errorTxt1">{{ $errors->first('uk_company_registration_number') }}</small>
                      @endif
                    </div>
                  </div>

                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s7 input-field m5">
                      
                      <input id="address" name="address" type="text" class="validate"
                        data-error=".errorTxt1" value="<?php echo Request::old('address'); ?>">
                        <label for="address">Address*</label>
                      @if ($errors->has('address'))
                        <small class="errorTxt1">{{ $errors->first('address') }}</small>
                      @endif
                      
                    </div>
                    <div class="col s7 input-field m5">
                      
                      <input id="postcode" name="postcode" type="text" class="validate" data-error=".errorTxt2" value="<?php echo Request::old('postcode'); ?>">
                      <label for="postcode">Postcode*</label>
                      @if ($errors->has('postcode'))
                        <small class="errorTxt1">{{ $errors->first('postcode') }}</small>
                      @endif
                    </div> 
                    <div class="col s7 input-field m5"> 
                      <input id="phone" name="phone" type="text" class="validate" data-error=".errorTxt2" value="<?php echo Request::old('phone'); ?>" pattern="[1-9]{1}[0-9]{9}" maxlength="10" minlength="10">
                      <label for="phone">Phone*</label>
                      @if ($errors->has('phone'))
                        <small class="errorTxt1">{{ $errors->first('phone') }}</small>
                      @endif
                    </div>
                    <div class="col s7 input-field m5">
                      
                      <input id="competition_website_url" name="competition_website_url" type="text" class="validate" data-error=".errorTxt2" value="<?php echo Request::old('competition_website_url'); ?>">
                      <label for="competition_website_url">Competition Website URL*</label>
                      @if ($errors->has('competition_website_url'))
                        <small class="errorTxt1">{{ $errors->first('competition_website_url') }}</small>
                      @endif                     
                    </div>
                  </div>

                  <div class="row" style="padding: 0 1rem;">
                   <!--  <div class="col s7 input-field m5">
                      <h2 for="description" style="color:#9e9e9e; font-size: 1rem; margin: 0rem 0 1.424rem 0;">Organisation Logo*</h2>
                      <button id="add_file" class="add-file-btn btn btn-block waves-effect waves-light mb-10" style="width: 50%;">
                        <i class="material-icons">file_upload</i>
                        <span>File Upload</span>
                      </button>
                      @if ($errors->has('organisation_logo')) 
                        <small class="errorTxt1">{{ $errors->first('organisation_logo') }}</small>
                      @endif-->
                      <!-- file input  -->
                     <!-- <div class="getfileInput">
                        <input type="file" id="organisation_logo" name="organisation_logo" multiple="multiple">
                      </div>
                      <div class="row" id="image_preview" style="display: flex;"></div>
                      
                    </div> -->
                    <div class="col s12 file-field input-field m10">
                      
                        <div class="btn float-right">
		                  <span>Organisation Logo*</span>
		                  <input type="file" id="organisation_logo" name="organisation_logo">
		                </div>
		                <div class="file-path-wrapper" style="padding-left: 0px;">
		                  <input class="file-path validate" type="text">
		                </div>
                      <!-- <label for="competition_website_url">Competition Website URL*</label> -->
                      @if ($errors->has('organisation_logo'))
                        <small class="errorTxt1">{{ $errors->first('organisation_logo') }}</small>
                      @endif                     
                    </div>
                  </div>
                  <div class="divider mb-3"></div>
                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s7 input-field m5">
                      <input id="player_start_wallet_balance" name="player_start_wallet_balance" type="number" class="validate" value="<?php echo Request::old('player_start_wallet_balance'); ?>"
                        data-error=".errorTxt1" min="0">
                      
                      <label for="player_start_wallet_balance">Player Start Wallet Balance*</label>
                      @if ($errors->has('player_start_wallet_balance'))
                        <small class="errorTxt1">{{ $errors->first('player_start_wallet_balance') }}</small>
                      @endif
                      
                    </div>
                    <div class="col s7 input-field m5">
                      <input id="placeholder_draw_video" name="placeholder_draw_video" type="text" class="validate" value="<?php echo Request::old('placeholder_draw_video'); ?>"
                        data-error=".errorTxt1">
                      
                      <label for="placeholder_draw_video">Placeholder draw video*</label>
                      @if ($errors->has('placeholder_draw_video'))
                        <small class="errorTxt1">{{ $errors->first('placeholder_draw_video') }}</small>
                      @endif
                      
                    </div>
                  </div>
                  <!-- <div class="row" style="padding: 0 1rem;">
                    <div class="col s7 input-field">
                      <input id="placeholder_draw_video" name="placeholder_draw_video" type="text" class="validate" value="<?php echo Request::old('placeholder_draw_video'); ?>"
                        data-error=".errorTxt1">
                      
                      <label for="placeholder_draw_video">Placeholder draw video*</label>
                      @if ($errors->has('placeholder_draw_video'))
                        <small class="errorTxt1">{{ $errors->first('placeholder_draw_video') }}</small>
                      @endif
                      
                    </div>
                  </div> -->

                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s7 input-field m5">
                      <input id="paypal_api_credentials" name="paypal_api_credentials" type="text" class="validate" value="<?php echo Request::old('paypal_api_credentials'); ?>"
                        data-error=".errorTxt1">
                      
                      <label for="paypal_api_credentials">Paypal API Key*</label>
                      @if ($errors->has('paypal_api_credentials'))
                        <small class="errorTxt1">{{ $errors->first('paypal_api_credentials') }}</small>
                      @endif
                      
                    </div>
                    <div class="col s7 input-field m5">
                      <input id="payment_gateway_secret_key" name="payment_gateway_secret_key" type="text" class="validate" value="<?php echo Request::old('payment_gateway_secret_key'); ?>"
                        data-error=".errorTxt1">
                      
                      <label for="payment_gateway_secret_key">Paypal API Secret Key*</label>
                      @if ($errors->has('payment_gateway_secret_key'))
                        <small class="errorTxt1">{{ $errors->first('payment_gateway_secret_key') }}</small>
                      @endif
                      
                    </div>
                  </div>

                  <!-- <div class="row" style="padding: 0 1rem;">
                    <div class="col s7 input-field">
                      <input id="payment_gateway_secret_key" name="payment_gateway_secret_key" type="text" class="validate" value="<?php echo Request::old('payment_gateway_secret_key'); ?>"
                        data-error=".errorTxt1">
                      
                      <label for="payment_gateway_secret_key">Paypal API Secret Key*</label>
                      @if ($errors->has('payment_gateway_secret_key'))
                        <small class="errorTxt1">{{ $errors->first('payment_gateway_secret_key') }}</small>
                      @endif
                      
                    </div>
                  </div> -->

                  <div class="row" style="padding: 0 1rem;">
                    <!-- <div class="col s7 input-field">
                        
                        <h2 for="description" style="color:#9e9e9e; font-size: 1rem; margin: 0rem 0 1.424rem 0;">Terms and Conditions*</h2>
		                <button id="add_terms_file" class="add-file-btn btn btn-block waves-effect waves-light mb-10" style="width: 40%;">
		                  <i class="material-icons">file_upload</i>
		                  <span>File Upload</span>
		                </button>
		                <small class="errorTxt1 terms_and_conditionsText"></small>
		                @if ($errors->has('terms_and_conditions')) 
		                  <small class="errorTxt1">{{ $errors->first('terms_and_conditions') }}</small>
		                @endif
		                
		                <div class="getfileInput">
		                  <input type="file" id="terms_and_conditions" name="terms_and_conditions" multiple="multiple">
		                </div>
                      
                    </div> -->
                    <div class="col s12 file-field input-field m10">
                      
                        <div class="btn float-right">
		                  <span>Terms and Conditions*</span>
		                  <input type="file" id="terms_and_conditions" name="terms_and_conditions">
		                </div>
		                <div class="file-path-wrapper" style="padding-left: 0px;">
		                  <input class="file-path validate" type="text">
		                </div>
                      <!-- <label for="competition_website_url">Competition Website URL*</label> -->
                      @if ($errors->has('terms_and_conditions'))
                        <small class="errorTxt1">{{ $errors->first('terms_and_conditions') }}</small>
                      @endif                     
                    </div>
                  </div>

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
<script type="text/javascript">
  $(document).ready(function(){

    $("#add_file").on("click", function (e) {
      e.preventDefault();
      $(".getfileInput #organisation_logo").click();
    });

    $("#add_terms_file").on("click", function (e) {
      e.preventDefault();
      $(".getfileInput #terms_and_conditions").click();
    });

    $("#phone").on("blur", function(){
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

    $("#organisation_logo").on("change", function(e) {
      var files = e.target.files,
      filesLength = files.length; 
      
        $("#prizeUploadImageForm").submit();

        for (var i = 0; i < filesLength; i++) {
          var f = files[i]
          var fileReader = new FileReader();

          fileReader.onload = (function(e) {
            var file = e.target;
            $('#image_preview').append("<div class='col-md-3 pip' id='" + i + "' style='margin: 10px;'>" +
              '<img width="140" height="150" class="imageThumb img-responsive" src="' + e.target.result + '" title="' + file.name + '"/>' +
              //"<br/><span class=\"remove\">Remove image</span>" +
              "</div>");
            /*$(".remove").click(function(){
              
              $(this).parent(".pip").remove();
            });*/
            
            // Old code here
            /*$("<img></img>", {
              class: "imageThumb",
              src: e.target.result,
              title: file.name + " | Click to remove"
            }).insertAfter("#files").click(function(){$(this).remove();});*/
            
          });
          fileReader.readAsDataURL(f);
        }

      
    });

    $("#terms_and_conditions").on("change", function(e) {
      var terms_and_conditions =  $(this).val();
      $('.terms_and_conditionsText').html(terms_and_conditions);

    });

  });
</script>
@endsection