{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Edit a Organisation')

{{-- vendor styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2-materialize.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-timeline.css')}}">
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
      <!-- <div class="card-body"> -->
      
      <?php
        $organisation = json_encode($organisationList);
        $organisation = json_decode($organisation, true);
      ?>
      <div class="row">
        
        
        <div class="col s12" id="edit">
         
          <!-- users add account form start -->
          <form id="organisationAddForm" action="{{route('organisations.organisationProfileUpdate', $organisation['0']['organisation_id'])}}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            {{ method_field('put') }}
            <div class="row">
              <div class="col s12">
                <div class="row">

                  <div class="row" style="padding: 0 1rem;">
                    <!-- <div class="col s7 input-field m5">
                      <input id="organisation_name" name="organisation_name" type="text" class="validate" value="<?php echo Request::old('organisation_name') ? Request::old('organisation_name') : $organisation['0']['organisation_name']; ?>"
                        data-error=".errorTxt1">
                      
                      <label for="organisation_name">Organisation Name*</label>
                      @if($errors->has('organisation_name'))
                        <small class="errorTxt1">{{ $errors->first('organisation_name') }}</small>
                      @endif
                      
                    </div> -->
                    <div class="col s7 input-field m5">
                      <input id="uk_company_registration_number" name="uk_company_registration_number" type="text" class="validate" data-error=".errorTxt2" value="<?php echo Request::old('uk_company_registration_number') ? Request::old('uk_company_registration_number') : $organisation['0']['company_registration_number']; ?>">
                      <label for="uk_company_registration_number">UK Company Registration Number*</label>
                      @if ($errors->has('uk_company_registration_number'))
                        <small class="errorTxt1">{{ $errors->first('uk_company_registration_number') }}</small>
                      @endif
                    </div>
                  </div>

                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s7 input-field m5">
                      
                      <input id="address" name="address" type="text" class="validate"
                        data-error=".errorTxt1" value="<?php echo Request::old('address') ? Request::old('address') : $organisation['0']['address']; ?>">
                        <label for="address">Address*</label>
                      @if ($errors->has('address'))
                        <small class="errorTxt1">{{ $errors->first('address') }}</small>
                      @endif
                      
                    </div>
                    <div class="col s7 input-field m5">
                      
                      <input id="postcode" name="postcode" type="text" class="validate" data-error=".errorTxt2" value="<?php echo Request::old('postcode') ? Request::old('postcode') : $organisation['0']['post_code']; ?>">
                      <label for="postcode">Postcode*</label>
                      @if ($errors->has('postcode'))
                        <small class="errorTxt1">{{ $errors->first('postcode') }}</small>
                      @endif
                    </div> 
                    <div class="col s7 input-field m5"> 
                      <input id="phone" name="phone" type="text" class="validate" data-error=".errorTxt2" value="<?php echo Request::old('phone') ? Request::old('phone') : $organisation['0']['phone_number']; ?>" pattern="[1-9]{1}[0-9]{9}" maxlength="10" minlength="10">
                      <label for="phone">Phone*</label>
                      @if ($errors->has('phone'))
                        <small class="errorTxt1">{{ $errors->first('phone') }}</small>
                      @endif
                    </div>
                    <div class="col s7 input-field m5">
                      
                      <input id="competition_website_url" name="competition_website_url" type="text" class="validate" data-error=".errorTxt2" value="<?php echo Request::old('competition_website_url') ? Request::old('competition_website_url') : $organisation['0']['website_uri']; ?>">
                      <label for="competition_website_url">Competition Website URL*</label>
                      @if ($errors->has('competition_website_url'))
                        <small class="errorTxt1">{{ $errors->first('competition_website_url') }}</small>
                      @endif                     
                    </div>
                  </div>

                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s12 file-field input-field m6">
                      
                        <div class="btn float-right">
                      <span>Organisation Logo*</span>
                      <input type="file" id="organisation_logo" name="organisation_logo">
                    </div>
                    <div class="file-path-wrapper" style="padding-left: 0px;">
                      <input class="file-path validate" type="text" value="">
                    </div>
                      <!-- <label for="competition_website_url">Competition Website URL*</label> -->
                      @if ($errors->has('organisation_logo'))
                        <small class="errorTxt1">{{ $errors->first('organisation_logo') }}</small>
                      @endif                     
                    </div>
                    <div class="col s12 file-field input-field m4">
                      <img src="<?php echo $organisation['0']['image']; ?>" style="width: 100%;height: auto;">
                    </div>
                  </div>
                  <div class="divider mb-3"></div>
                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s7 input-field m5">
                      <input id="player_start_wallet_balance" name="player_start_wallet_balance" type="number" class="validate" value="<?php echo Request::old('player_start_wallet_balance') ? Request::old('player_start_wallet_balance') : $organisation['0']['player_wallet_balance']; ?>"
                        data-error=".errorTxt1" min="0">
                      
                      <label for="player_start_wallet_balance">Player Start Wallet Balance*</label>
                      @if ($errors->has('player_start_wallet_balance'))
                        <small class="errorTxt1">{{ $errors->first('player_start_wallet_balance') }}</small>
                      @endif
                      
                    </div>
                    <div class="col s7 input-field m5">
                      <input id="placeholder_draw_video" name="placeholder_draw_video" type="text" class="validate" value="<?php echo Request::old('placeholder_draw_video') ? Request::old('placeholder_draw_video') : $organisation['0']['placeholder_video_uri'];?>"
                        data-error=".errorTxt1">
                      
                      <label for="prize_name">Placeholder draw video*</label>
                      @if ($errors->has('placeholder_draw_video'))
                        <small class="errorTxt1">{{ $errors->first('placeholder_draw_video') }}</small>
                      @endif
                      
                    </div>
                  </div>
                  <!-- <div class="row" style="padding: 0 1rem;">
                    <div class="col s7 input-field">
                      <input id="placeholder_draw_video" name="placeholder_draw_video" type="text" class="validate" value="<?php echo Request::old('placeholder_draw_video') ? Request::old('placeholder_draw_video') : $organisation['0']['placeholder_video_uri'];?>"
                        data-error=".errorTxt1">
                      
                      <label for="prize_name">Placeholder draw video*</label>
                      @if ($errors->has('placeholder_draw_video'))
                        <small class="errorTxt1">{{ $errors->first('placeholder_draw_video') }}</small>
                      @endif
                      
                    </div>
                  </div> -->

                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s7 input-field m5">
                      <input id="paypal_api_credentials" name="paypal_api_credentials" type="text" class="validate" value="<?php echo Request::old('paypal_api_credentials') ? Request::old('paypal_api_credentials') : $organisation['0']['payment_gateway_id']; ?>"
                        data-error=".errorTxt1">
                      
                      <label for="prize_name">Paypal API Key*</label>
                      @if ($errors->has('paypal_api_credentials'))
                        <small class="errorTxt1">{{ $errors->first('paypal_api_credentials') }}</small>
                      @endif
                      
                    </div>
                    <div class="col s7 input-field m5">
                      <input id="payment_gateway_secret_key" name="payment_gateway_secret_key" type="text" class="validate" value="<?php echo Request::old('payment_gateway_secret_key') ? Request::old('payment_gateway_secret_key') : $organisation['0']['payment_gateway_secret_key']; ?>"
                        data-error=".errorTxt1">
                      
                      <label for="prize_name">Paypal API Secret Key*</label>
                      @if ($errors->has('payment_gateway_secret_key'))
                        <small class="errorTxt1">{{ $errors->first('payment_gateway_secret_key') }}</small>
                      @endif
                      
                    </div>
                  </div>

                  <!-- <div class="row" style="padding: 0 1rem;">
                    
                  </div> -->

                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s12 file-field input-field m10">
                      <div class="btn float-right">
                      <span>Terms and Conditions*</span>
                      <input type="file" id="terms_and_conditions" name="terms_and_conditions">
                    </div>
                    <div class="file-path-wrapper" style="padding-left: 0px;">
                      <input class="file-path validate" type="text" value="">

                    </div>

                    <!-- <label for="competition_website_url">Competition Website URL*</label> -->
                    @if ($errors->has('terms_and_conditions'))
                      <small class="errorTxt1">{{ $errors->first('terms_and_conditions') }}</small>
                    @endif 
                      
                    </div>
                    <a href="<?php echo $organisation['0']['terms_and_conditions_document']; ?>" target="_blank" style="padding: 0 14px;"><?php echo $organisation['0']['terms_and_conditions_document']; ?></a>
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
<script type="text/javascript">
  $(document).ready(function(){

    $(document).on('click', '.tabs .tab a', function(){
      
        var href = $(this).attr("href");
        window.location.hash = href;
      
    });

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