{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Edit a Category')

{{-- vendor styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2-materialize.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-timeline.css')}}">
@endsection

{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-users.css')}}">
<script src="https://cdn.ckeditor.com/4.5.11/full-all/ckeditor.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<!-- <script src="https://www.google.com/recaptcha/api.js"></script> -->
@endsection

{{-- page content --}}
@section('content')
<!-- FAQs edit start -->
<div class="section users-edit">
  <div class="card">
    <div class="card-content">
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

      <div class="row">
        
        <div class="col s12" id="view">
          <!-- FAQs edit form start -->
          <form action="" method="post" enctype="multipart/form-data">
            
            <div class="row">
              <div class="col s12">
                <div class="row">
                  @php
                   $faq = json_encode($faq);
                   $faq = json_decode($faq, true);

                  @endphp
                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s7 input-field">
                      <input id="category_name" name="category_name" type="text"  class="validate" value="<?php echo $faq['0']['category_name'] ; ?>"
                        data-error=".errorTxt1" disabled="true">
                      
                      <label for="category_name">Category Name*</label>
                     
                      
                    </div>
                  </div>

                  <div class="row" style="padding: 0 1rem;" >
                    <div class="col s7 input-field">
                      <!-- <h2 for="description" style="color:#9e9e9e; font-size: 1rem; margin: 0rem 0 1.424rem 0;">Description of the prize*</h2> -->
                      <textarea id="category_description" name="category_description" class="validate materialize-textarea birthdate-picker" data-error=".errorTxt3" style="height: 233px;" disabled="true"><?php echo $faq['0']['category_description']; ?></textarea>
                      <label for="category_description">Category Description*</label>
                      
                      @if ($errors->has('category_description'))
                        <small class="errorTxt1">{{ $errors->first('category_description') }}</small>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
              
            </div>
          </form>
          <!-- FAQs edit form ends -->
        </div>
        <div class="col s12" id="edit">
          <!-- FAQs edit form start -->
          <form id="faqEditForm" action="{{route('category.update', $faq['0']['prize_category_id'])}}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            {{ method_field('put') }}
            <div class="row">
              <div class="col s12">
                <div class="row">
                  @php
                   $faq = json_encode($faq);
                   $faq = json_decode($faq, true);
                  @endphp
                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s7 input-field">
                      <input id="category_name" name="category_name" type="text"  class="validate" value="<?php echo Request::old('category_name') ? Request::old('category_name') : $faq['0']['category_name'] ; ?>"
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
                      <textarea id="category_description" name="category_description" class="validate materialize-textarea birthdate-picker" data-error=".errorTxt3" style="height: 233px;"><?php echo Request::old('category_description') ? Request::old('category_description') : $faq['0']['category_description']; ?></textarea>
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
          <!-- FAQs edit form ends -->
        </div>
        <div class="col s12" id="revision">
          <!-- FAQs edit form start -->
          <?php
            //$log = json_encode($log);
            //$log = json_decode($logs, true);
            
            $i = 0;
          ?>
          <div class="row">
            <!-- datatable start -->
        <div class="" id="player">
          <div class="responsive-table">
            <ul class="timeline">
              <?php foreach($logs as $k=>$user){
                $clas = ''; 
                if($i%2==0){
                  $clas = ''; 
                }else{
                  $clas = 'timeline-inverted'; 
                }
                $log_before_changes = $user['log_before_changes'];
                $log_before_changes = json_decode($log_before_changes, true);
                $log_details = $user['log_details'];
                $log_details = json_decode($log_details, true);
                $description = $user['description'];
                if($description == 'A prize category is created' || $description == 'A prize category is deleted'){
                  ?>
                  <li class="<?= $clas; ?>">
                  <div class="timeline-badge cyan">
                    <a class="tooltipped" data-position="top" data-tooltip="<?= date('M d Y', strtotime($user['timestamp'])); ?>"><i
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
                            <h6 class="m-0"><?= $user['users_name']; ?></h6>
                            <small><?= $user['description']; ?> at <?= $user['timestamp']; ?></small>
                          </div>
                        </div>
                        <div class="divider"></div>
                        <p class="card-text mt-1"> Category Name : <?= $log_details['category_name']; ?>
                        </p>
                        <p class="card-text mt-1"> Category Description : <?= $log_details['category_description']; ?>
                        </p>
                      </div>
                      
                    </div>
                  </div>
                </li>
                 <?php
                }elseif($description == 'A prize category is updated'){
                  ?>
                   <li class="<?= $clas; ?>">
                  <div class="timeline-badge cyan">
                    <a class="tooltipped" data-position="top" data-tooltip="<?= date('M d Y', strtotime($user['timestamp'])); ?>"><i
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
                            <h6 class="m-0"><?= $user['users_name']; ?></h6>
                            <small><?= $user['description']; ?> at <?= $user['timestamp']; ?></small>
                          </div>
                        </div>
                        <div class="divider"></div>
                        <div class="card-image waves-effect waves-block waves-light mt-1">
                          <?php
                          

                          
                          if($log_before_changes['category_name'] != $log_details['category_name']){
                          ?>
                          <p class="card-text mt-1"> Category Name : <?= $log_details['category_name']; ?>
                          </p>
                          <?php
                          }
                          if($log_before_changes['category_description'] != $log_details['category_description']){
                          ?>
                          <p class="card-text mt-1"> Category Description : <?= $log_details['category_description']; ?>
                          </p>
                          <?php
                          }
                          ?>
                          

                        </div>
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
<script type="text/javascript">
  $(document).ready(function(){
    $(document).on('click', '.tabs .tab a', function(){
      
        var href = $(this).attr("href");
        window.location.hash = href;
      
    });
  });
</script>
@endsection