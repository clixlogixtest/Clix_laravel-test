{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Global Settings')

{{-- vendors styles --}}
@section('vendor-style')
  <link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2-materialize.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-timeline.css')}}">
@endsection

{{-- page styles --}}
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

<!-- setting add start -->
<div class="section users-edit">
  <div class="card">
    <div class="card-content">
      <ul class="tabs mb-2 row">
        <li class="tab">
          <a class="display-flex align-items-center" id="information-tab" href="#edit">
            <i class="material-icons mr-2">edit</i><span>Edit</span>
          </a>
        </li>
        <li class="tab">
          <a class="display-flex align-items-center" id="information-tab" href="#revision">
            <i class="material-icons mr-2">remove_red_eye</i><span>Revision</span>
          </a>
        </li>
      </ul>

      <div class="row">
        <div class="col s12" id="revision">
          <!-- users add account form start -->
          <?php
            $log = json_encode($log);
            $log = json_decode($log, true); 
            $i = 0;
          ?>
          <div class="row">
            <!-- datatable start -->
            <div class="" id="player">
              <div class="responsive-table">
                <!-- <table id="multi-select" class="display table"> -->
                  <ul class="timeline">
              <?php foreach($log as $k=>$logs){
                $clas = ''; 
                if($i%2==0){
                  $clas = ''; 
                }else{
                  $clas = 'timeline-inverted'; 
                }
                $log_before_changes = $logs['log_before_changes'];
                $log_before_changes = json_decode($log_before_changes, true);
                $log_details = $logs['log_details'];
                $log_details = json_decode($log_details, true);
                $description = $logs['description'];
                 //print_r($log_before_changes); print_r($log_details);
                if($description == 'A setting is created'){
                  ?>
                  <li class="<?= $clas; ?>">
                  <div class="timeline-badge cyan">
                    <a class="tooltipped" data-position="top" data-tooltip="Sep 18 2019"><i
                        class="material-icons white-text">language</i></a>
                  </div>
                  <div class="timeline-panel">
                    <div class="card m-0 hoverable">
                      <div class="card-content">
                        <div class="card-header pb-1">
                          <div class="avatar mr-2">
                            <img src="{{asset('images/avatar/user-profile-placeholder-image-png.jpg')}}"
                              class="responsive-img border-radius-4" width="38">
                          </div>
                          <div class="card-text">
                            <h6 class="m-0"><?= $logs['users_name']; ?></h6>
                            <small><?= $logs['description']; ?> at <?= $logs['timestamp']; ?></small>
                          </div>
                        </div>
                        <div class="divider"></div>
                        
                        <p class="card-text mt-1" style="word-break: break-all;"> Terms and Condition : <?= $log_details['terms_and_condition']; ?>
                        </p>
                        <p class="card-text mt-1" style="word-break: break-all;" > Placeholder Draw Video : <?= $log_details['placeholder_draw_video']; ?>
                        </p>
                        <p class="card-text mt-1" style="word-break: break-all;"> Alternative Cash Prize Percentage : <?= @$log_details['alternative_cash_prize_percentage']; ?>
                          </p>
                      </div>
                      
                    </div>
                  </div>
                </li>
                 <?php
                }elseif($description == 'A setting is updated' ){
                  ?>
                   <li class="<?= $clas; ?>">
                  <div class="timeline-badge cyan">
                    <a class="tooltipped" data-position="top" data-tooltip="Sep 18 2019"><i
                        class="material-icons white-text">language</i></a>
                  </div>
                  <div class="timeline-panel">
                    <div class="card m-0 hoverable">
                      <div class="card-content">
                        <div class="card-header pb-1">
                          <div class="avatar mr-2">
                            <img src="{{asset('images/avatar/user-profile-placeholder-image-png.jpg')}}" 
                              class="responsive-img border-radius-4" width="38">
                          </div>
                          <div class="card-text">
                            <h6 class="m-0"><?= $logs['users_name']; ?></h6>
                            <small><?= $logs['description']; ?> at <?= $logs['timestamp']; ?></small>
                          </div>
                        </div>
                        <div class="divider"></div>
                        
                          <?php
                          if($log_before_changes['terms_and_condition'] != $log_details['terms_and_condition']){
                          ?>
                          <p class="card-text mt-1" style="word-break: break-all;"> Terms and Condition : <?= $log_details['terms_and_condition']; ?>
                          </p>
                          <?php
                          }
                          if($log_before_changes['placeholder_draw_video'] != $log_details['placeholder_draw_video']){
                          ?>
                          <p class="card-text mt-1" style="word-break: break-all;"> Placeholder Draw Video : <?= $log_details['placeholder_draw_video']; ?>
                          </p>
                          <?php
                          }
                          if(@$log_before_changes['alternative_cash_prize_percentage'] != @$log_details['alternative_cash_prize_percentage']){
                          ?>
                          <p class="card-text mt-1" style="word-break: break-all;"> Alternative Cash Prize Percentage : <?= @$log_details['alternative_cash_prize_percentage']; ?>
                          </p>
                          <?php
                          }
                          
                          ?>
                      </div>
                      
                    </div>
                  </div>
                </li>
                  <?php
                }
                ?>
                
                
              <?php $i++; } ?>
            </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="col s12" id="edit">
         
          <!-- setting add account form start -->
          <form id="settingsAddForm" action="{{ route('settings.store') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
              <div class="col s12">
                <div class="row">
                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s7 input-field m5">
                      <h2 for="description" style="color:#9e9e9e; font-size: 1rem; margin: 0rem 0 1.424rem 0;">Update Terms and Conditions*</h2>
                      <button id="add_file" class="add-file-btn btn btn-block waves-effect waves-light mb-10" style="width: 50%;">
                        <i class="material-icons">file_upload</i>
                        <span>File Upload</span>
                      </button>
                      @if ($errors->has('terms_and_condition')) 
                        <small class="errorTxt1">{{ $errors->first('terms_and_condition') }}</small>
                      @endif
                      <small class="errorTxt1 terms"></small>
                      <!-- file input  -->
                      <div class="getfileInput">
                        <input type="file" id="terms_and_condition" name="terms_and_condition">
                      </div>
                      <div class="row" id="image_preview" style="display: flex;"></div>
                      
                    </div>
                  </div>

                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s7 input-field m5">
                      <input id="placeholder_draw_video" name="placeholder_draw_video" type="text" class="validate" data-error=".errorTxt2" value="<?php echo Request::old('placeholder_draw_video') ? Request::old('placeholder_draw_video') : $setting['0']['placeholder_draw_video']; ?>">
                      <label for="placeholder_draw_video">Placeholder Video URL*</label>
                      @if ($errors->has('placeholder_draw_video'))
                        <small class="errorTxt1">{{ $errors->first('placeholder_draw_video') }}</small>
                      @endif                     
                    </div>
                  </div>

                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s7 input-field m5">
                      <input id="alternative_cash_prize_percentage" name="alternative_cash_prize_percentage" type="number" class="validate" data-error=".errorTxt2" value="<?php echo Request::old('alternative_cash_prize_percentage') ? Request::old('alternative_cash_prize_percentage') : $setting['0']['alternative_cash_prize_percentage']; ?>" placeholder="22" min="0" max="100">
                      <label for="placeholder_draw_video">Alternative Cash Prize Percentage*</label>
                      @if ($errors->has('alternative_cash_prize_percentage'))
                        <small class="errorTxt1">{{ $errors->first('alternative_cash_prize_percentage') }}</small>
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

{{-- vendor scripts --}}
@section('vendor-script')
<script src="{{asset('vendors/data-tables/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js')}}"></script>
@endsection

{{-- page script --}}
@section('page-script')
<script src="{{asset('vendors/select2/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/scripts/page-users.js')}}"></script>
<script src="{{asset('js/scripts/ui-alerts.js')}}"></script>

<script type="text/javascript">
  $(document).ready(function(){

    $(document).on('click', '.tabs .tab a', function(){
      
        var href = $(this).attr("href");
        window.location.hash = href;
      
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function(){
    $("#add_file").on("click", function (e) {
      e.preventDefault();
      $(".getfileInput #terms_and_condition").click();
    });
    $("#terms_and_condition").on("change", function(e){
      var ext = $(this).val().split('.').pop();
      if(ext != 'html'){
        $('.terms').html('Please upload only html file.');
         alert('Please upload only html file.');
         return false;
      }   
    });
    $("form#settingsAddForm").on('submit', function(e){
      //e.preventDefault();
      var ext = $('#terms_and_condition').val().split('.').pop();
      if(ext != 'html'){
        $('.terms').html('Please upload only html file.');
         alert('Please upload only html file.');
         return false;
      } 
      return true; 

    });

  });
</script>
@endsection