{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Edit a User')

{{-- vendor styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2-materialize.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-timeline.css')}}">
@endsection

{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-users.css')}}">
@endsection
<script src="https://www.google.com/recaptcha/api.js"></script>

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

<!-- users edit start -->
<div class="section users-edit">
  <div class="card">
    <div class="card-content">
      <!-- <div class="card-body"> -->
      <ul class="tabs mb-2 row">
        <li class="tab">
          <a class="display-flex align-items-center active" id="account-tab" href="#view">
            <i class="material-icons mr-1">remove_red_eye</i><span>View</span>
          </a>
        </li>
        <li class="tab">
          <a class="display-flex align-items-center" id="information-tab" href="#edit">
            <i class="material-icons mr-2">edit</i><span>Edit</span>
          </a>
        </li>
        <li class="tab">
          <a class="display-flex align-items-center" id="information-tab" href="#revision">
            <i class="material-icons mr-2">timeline</i><span>Revision</span>
          </a>
        </li>
      </ul>
      <div class="divider mb-3"></div>
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

                if($description == 'A user account is created'){
                  ?>
                  <li class="<?= $clas; ?>">
                  <div class="timeline-badge cyan">
                    <a class="tooltipped" data-position="top" data-tooltip="<?= date('M d Y', strtotime($logs['timestamp'])); ?>"><i
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
                        
                        <p class="card-text mt-1"> Roles : <?= $log_details['role']; ?>
                        </p>
                        <p class="card-text mt-1"> Firstname : <?= $log_details['first_name']; ?>
                        </p>
                        <p class="card-text mt-1"> Surname : <?= $log_details['surname']; ?>
                        </p>
                        <?php 
                          $date_of_birth = $log_details['date_of_birth']; 
                          $date_of_birth = strtotime($date_of_birth); 
                          $date_of_birth = date('d/m/Y', $date_of_birth);
                        ?>
                        <p class="card-text mt-1"> Date of Birth : <?= $date_of_birth; ?>
                        </p>
                        <p class="card-text mt-1"> Address : <?= $log_details['address']; ?>
                        </p>
                        <p class="card-text mt-1"> Town : <?= $log_details['town']; ?>
                        </p>
                        <p class="card-text mt-1"> Contact Phone Number : <?= $log_details['contact_number']; ?>
                        </p>
                      </div>
                      
                    </div>
                  </div>
                </li>
                 <?php
                }elseif($description == 'A user account is updated' ){
                  ?>
                   <li class="<?= $clas; ?>">
                  <div class="timeline-badge cyan">
                    <a class="tooltipped" data-position="top" data-tooltip="<?= date('M d Y', strtotime($logs['timestamp'])); ?>"><i
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
                          if($log_before_changes['role'] != $log_details['role']){
                          ?>
                          <p class="card-text mt-1"> Prize Name : <?= $log_details['role']; ?>
                          </p>
                          <?php
                          }
                          if($log_before_changes['first_name'] != $log_details['first_name']){
                          ?>
                          <p class="card-text mt-1"> Category : <?= $log_details['first_name']; ?>
                          </p>
                          <?php
                          }
                          if($log_before_changes['surname'] != $log_details['surname']){
                          ?>
                          <p class="card-text mt-1"> Ticket Price : <?= $log_details['surname']; ?>
                          </p>
                          <?php
                          }
                          if($log_before_changes['email'] != $log_details['email']){
                          ?>
                          <p class="card-text mt-1"> Ticket Price : <?= $log_details['email']; ?>
                          </p>
                          <?php
                          }

                          $date_of_birth =  $log_details['date_of_birth']; 
                          $date_of_birth = strtotime($date_of_birth); 
                          $date_of_birth = date('d/m/Y', $date_of_birth);

                          if($log_before_changes['date_of_birth'] != $log_details['date_of_birth']){
                          ?>
                          <p class="card-text mt-1"> Availabl Tickets : <?= $date_of_birth; ?>
                          </p>
                          <?php
                          }
                          if($log_before_changes['address'] != $log_details['address']){
                          ?>
                          <p class="card-text mt-1"> Closed Date : <?= $log_details['address']; ?>
                          </p>
                          <?php
                          }
                          if($log_before_changes['town'] != $log_details['town']){
                          ?>
                          <p class="card-text mt-1"> Closed Date : <?= $log_details['town']; ?>
                          </p>
                          <?php
                          }
                          if($log_before_changes['contact_number'] != $log_details['contact_number']){
                          ?>
                          <p class="card-text mt-1"> Closed Date : <?= $log_details['contact_number']; ?>
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
        <div class="col s12" id="view">
          <div>
            <?php
            $user = json_encode($user);
            $user = json_decode($user, true);
            ?>
            <!-- Name -->
            <div class="col s12 place mt-4 p-0">
              <div class="col s2 m2 l3"><i class="material-icons"> perm_identity </i> Name</div>
              <div class="col s10 m10 l7">
                <p class="m-0">{{$user['0']['first_name'].' '.$user['0']['surname']}}</p>
              </div>
            </div>
            <!-- Email -->
            <div class="col s12 place mt-4 p-0">
              <div class="col s2 m2 l3"><i class="material-icons"> mail_outline </i> Email</div>
              <div class="col s10 m10 l7">
                <p class="m-0">{{$user['0']['email']}}</p>
              </div>
            </div>
            <!-- Role -->
            <div class="col s12 place mt-4 p-0">
              <div class="col s2 m2 l3"><i class="material-icons"> group </i> Role</div>
              <div class="col s10 m10 l7">
                <p class="m-0">{{$user['0']['role']}}</p>
              </div>
            </div>
            
            <!-- Phone -->
            <div class="col s12 phone mt-4 p-0">
              <div class="col s2 m2 l3"><i class="material-icons"> call </i> Phone Number</div>
              <div class="col s10 m10 l7">
                <p class="m-0">{{$user['0']['contact_number']}}</p>
              </div>
            </div>
            
          </div>
        </div>
        <div class="col s12" id="edit">
          <!-- users add account form start -->
          <form id="userEditForm" action="{{route('org_admins.update', $user['0']['id'])}}" method="post">
            {{ csrf_field() }}
            {{ method_field('put') }}
            <div class="row">
              <div class="col s12">
                  <input name="role" type="hidden" value="organisation_administrator">

                  <div class="row" style="padding: 0 1rem;">
	                  <div class="col s12 input-field">
	                    <?php 
	                    $first_name = '';
	                    if($errors->has('first_name')){
	                    $first_name = "parsley-error";
	                    }
	                    ?>
	                    <select id="organisation" name="organisation" class="validate" data-error=".errorTxt1">
	                      <?php
	                          $organisationList = json_encode($organisationList);
	                          $organisationList = json_decode($organisationList, true);
	                          $orgID = Request::old('organisation') ? Request::old('organisation') : $user['0']['organisation_id'];
	                          foreach ($organisationList as $key => $value) {
	                            ?>
	                              <option value="{{$value['organisation_id']}}" <?= $orgID == $value['organisation_id'] ? 'selected' : ''; ?>>{{$value['organisation_name']}}</option>
	                            <?php
	                          }
	                      ?>

	                    </select>
	                    
	                    <label for="organisation">Organisation*</label>
	                    @if ($errors->has('organisation'))
	                      <small class="errorTxt1">{{ $errors->first('organisation') }}</small>
	                    @endif
	                    
	                  </div>
                  </div>
                  <!--First Name-->
                  <div class="col s12 input-field m6">
                    <input id="first_name" name="first_name" type="text" class="validate" value="<?php echo Request::old('first_name') ? Request::old('first_name') : $user['0']['first_name']; ?>"
                      data-error=".errorTxt1">
                    <label for="first_name">Firstname*</label>
                    @if ($errors->has('first_name'))
                      <small class="errorTxt1">{{ $errors->first('first_name') }}</small>
                    @endif
                    
                  </div>

                  <!--Surname-->
                  <div class="col s12 input-field m6">
                    <input id="surname" name="surname" type="text" class="validate" value="<?php echo Request::old('surname') ? Request::old('surname') : $user['0']['surname']; ?>" data-error=".errorTxt2">
                    <label for="surname">Surname*</label>
                    @if ($errors->has('surname'))
                      <small class="errorTxt1">{{ $errors->first('surname') }}</small>
                    @endif
                  </div>

                  <!--Emil Address-->
                  <div class="col s12 input-field">
                    <input id="email" name="email" type="email" class="validate" value="<?php echo Request::old('email') ? Request::old('email') : $user['0']['email']; ?>" data-error=".errorTxt3">
                    <label for="email">Email Address*</label>
                    @if ($errors->has('email'))
                      <small class="errorTxt1">{{ $errors->first('email') }}</small>
                    @endif
                  </div>
                  <?php 
                    $date_of_birth = Request::old('date_of_birth') ? Request::old('date_of_birth') : $user['0']['date_of_birth']; 
                    $date_of_birth = strtotime($date_of_birth); 
                    $date_of_birth = date('d/m/Y', $date_of_birth);
                  ?>
                    
                  <!--Date of Birth-->
                  <div class="row" style="padding: 0 1rem;" >
                    <div class="col s12 input-field m6">
                      <input id="date_of_birth" name="date_of_birth" type="text" class="birthdate-picker datepickeruser" value="{{$date_of_birth}}" data-error=".errorTxt3">
                      <label for="date_of_birth">Date of Birth*</label>
                      @if ($errors->has('date_of_birth'))
                        <small class="errorTxt1">{{ $errors->first('date_of_birth') }}</small>
                      @endif
                    </div>
                  </div>

                  <!--Address-->
                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s12 input-field m6">
                      <input id="address" name="address" type="text" class="validate" value="<?php echo Request::old('address') ? Request::old('address') : $user['0']['address']; ?>" data-error=".errorTxt3">
                      <label for="address">Address*</label>
                      @if ($errors->has('address'))
                        <small class="errorTxt1">{{ $errors->first('address') }}</small>
                      @endif
                    </div>
                  </div>

                  <!--Town-->
                  <div class="col s12 input-field m6">
                    <input id="town" name="town" type="text" class="validate" value="<?php echo Request::old('town')  ? Request::old('town') : $user['0']['town']; ?>" data-error=".errorTxt3">
                    <label for="town">Town*</label>
                    @if ($errors->has('town'))
                      <small class="errorTxt1">{{ $errors->first('town') }}</small>
                    @endif
                  </div>

                  <!--Postcode-->
                  <div class="col s12 input-field m6">
                    <input id="post_code" name="post_code" type="text" class="validate" value="<?php echo Request::old('post_code') ? Request::old('post_code') : $user['0']['post_code']; ?>" data-error=".errorTxt3">
                    <label for="post_code">Postcode*</label>
                    @if ($errors->has('post_code'))
                      <small class="errorTxt1">{{ $errors->first('post_code') }}</small>
                    @endif
                  </div>

                  <!--Contact Phone Number-->
                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s12 input-field m6">
                      <input id="contact_number" name="contact_number" type="text" minlength="10" class="validate" value="<?php echo Request::old('contact_number') ? Request::old('contact_number') : $user['0']['contact_number']; ?>" data-error=".errorTxt3" pattern="[1-9]{1}[0-9]{9}" maxlength="10">
                      <label for="contact_number">Contact Phone Number*</label>
                      <small class="errorTxt1 phoneError"></small>
                      @if ($errors->has('contact_number'))
                        <small class="errorTxt1">{{ $errors->first('contact_number') }}</small>
                      @endif
                    </div>
                  </div>

                  <!--Acceptd terms and conditions-->
                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s12 input-field m6">
                        <label>
                          <?php $satus = Request::old('status') ? Request::old('status') : $user['0']['status']; 
                           $chk = '';
                            if($satus){
                              $chk = 'checked="checked"';
                            }
                          ?>
                        <input id="status" name="status" type="checkbox" class="validate" value="1" data-error=".errorTxt1" {{$chk}}> <span>Accept terms and conditions</span>
                      </label>
                        @if ($errors->has('status'))
                          <small class="errorTxt1">{{ $errors->first('status') }}</small>
                        @endif
                    </div>
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
<script>
   function onSubmit(token) {
    var mobNum = $("#contact_number").val();
    var filter = /^\d*(?:\.\d{1,2})?$/;

    if (filter.test(mobNum)) {
      if(mobNum.length==10){

        $(".phoneError").text('');
      } else {
        $("phoneError").text('');
        $("phoneError").text('Please put 10  digit phone number');
        $("#contact_number").focus();
        return false;
      }
    }else {
      $("phoneError").text('');
      $("phoneError").text('Not a valid number');
      $("#contact_number").focus();
      return false;
    }

    var competition_admin  = $( "input[id=competition_admin]:checked" ).val();
    var user_admin         = $( "input[id=user_admin]:checked" ).val();
    var organisation_admin = $( "input[id=organisation_admin]:checked" ).val();
    var prize_admin        = $( "input[id=prize_admin]:checked" ).val();
    var player             = $( "input[id=player]:checked" ).val();
    if(player && (competition_admin || user_admin  || organisation_admin || prize_admin)){
      alert("Please select either Player or other roles. You can not select player with other roles.");
    }else{
      document.getElementById("userEditForm").submit();
    }
     
   }
</script>
<script type="text/javascript">
  $(document).ready(function(){

    $(document).on('click', '.tabs .tab a', function(){
      
        var href = $(this).attr("href");
        window.location.hash = href;
      
    });

    $("#contact_number").on("blur", function(){
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

    $('.datepickeruser').datepicker({
      autoClose: true,
      format: 'dd/mm/yyyy',
      container: 'body',
      onDraw: function onDraw() {
        // materialize select dropdown not proper working on mobile and tablets so we make it browser default select
        $('.datepicker-container').find('.datepicker-select').addClass('browser-default');
        $(".datepicker-container .select-dropdown.dropdown-trigger").remove();
      }
    });
    
    $( "input[id=competition_admin]" ).on('click', function(e){
      var competition_admin  = $( "input[id=competition_admin]:checked" ).val();
      var user_admin         = $( "input[id=user_admin]:checked" ).val();
      var organisation_admin = $( "input[id=organisation_admin]:checked" ).val();
      var prize_admin        = $( "input[id=prize_admin]:checked" ).val();
      var player             = $( "input[id=player]:checked" ).val();
      if(player && (competition_admin || user_admin  || organisation_admin || prize_admin)){
        $(this).prop("checked", false);
        alert("Please select either Player or other roles. You can not select player with other roles.");
      }
    });
    $( "input[id=user_admin]" ).on('click', function(e){
      var competition_admin  = $( "input[id=competition_admin]:checked" ).val();
      var user_admin         = $( "input[id=user_admin]:checked" ).val(); 
      var organisation_admin = $( "input[id=organisation_admin]:checked" ).val(); 
      var prize_admin        = $( "input[id=prize_admin]:checked" ).val();
      var player             = $( "input[id=player]:checked" ).val();
      if(player && (competition_admin || user_admin  || organisation_admin || prize_admin)){
        $(this).prop("checked", false);
        alert("Please select either Player or other roles. You can not select player with other roles.");
      }
    });
    $( "input[id=organisation_admin]" ).on('click', function(e){
      var competition_admin  = $( "input[id=competition_admin]:checked" ).val();
      var user_admin         = $( "input[id=user_admin]:checked" ).val();
      var organisation_admin = $( "input[id=organisation_admin]:checked" ).val();
      var prize_admin        = $( "input[id=prize_admin]:checked" ).val();
      var player             = $( "input[id=player]:checked" ).val();
      if(player && (competition_admin || user_admin  || organisation_admin || prize_admin)){
        $(this).prop("checked", false);
        alert("Please select either Player or other roles. You can not select player with other roles.");
      }
    });
    $( "input[id=prize_admin]" ).on('click', function(e){
      var competition_admin  = $( "input[id=competition_admin]:checked" ).val();
      var user_admin         = $( "input[id=user_admin]:checked" ).val();
      var organisation_admin = $( "input[id=organisation_admin]:checked" ).val();
      var prize_admin        = $( "input[id=prize_admin]:checked" ).val();
      var player             = $( "input[id=player]:checked" ).val();
      if(player && (competition_admin || user_admin  || organisation_admin || prize_admin)){
        $(this).prop("checked", false);
        alert("Please select either Player or other roles. You can not select player with other roles.");
      }
    });
    $( "input[id=player]" ).on('click', function(e){
      var competition_admin  = $( "input[id=competition_admin]:checked" ).val();
      var user_admin         = $( "input[id=user_admin]:checked" ).val();
      var organisation_admin = $( "input[id=organisation_admin]:checked" ).val();
      var prize_admin        = $( "input[id=prize_admin]:checked" ).val();
      var player             = $( "input[id=player]:checked" ).val();
      if(player && (competition_admin || user_admin  || organisation_admin || prize_admin)){
        $( "input[id=competition_admin]:checked" ).prop("checked", false);
        $( "input[id=user_admin]:checked" ).prop("checked", false);
        $( "input[id=organisation_admin]:checked" ).prop("checked", false);
        $( "input[id=prize_admin]:checked" ).prop("checked", false);
        //$(this).prop("checked", false);
        //alert("Please select either Player or other roles. You can not select player with other roles.");
      }
    });
  });
</script>
@endsection