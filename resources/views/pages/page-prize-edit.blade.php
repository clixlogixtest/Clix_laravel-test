{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Edit a Prize')

{{-- vendor styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2-materialize.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-timeline.css')}}">
@endsection

{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-users.css')}}">
<!-- <link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-file-manager.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/widget-timeline.css')}}"> -->
<script src="https://www.google.com/recaptcha/api.js"></script>
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

<!-- prize edit start -->
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
        <div class="col s12" id="revision">
          <!-- users add account form start -->
          <?php
            $log = json_encode($log);
            $log = json_decode($log, true);
            //print_r($log);
            $i = 0;
          ?>
          <div class="row">
            <!-- datatable start -->
        <div class="" id="player">
          <div class="responsive-table">
            <ul class="timeline">
              <?php foreach($log as $k=>$user){
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
                if($description == 'A prize is created' || $description == 'A prize is deleted'){
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
                          <?php $log_details_file =  $log_details['file'];
                          $log_details_file = json_decode($log_details_file, true); ?>
                          <img class="responsive-img " src="<?= $log_details_file['primary'] ? $log_details_file['primary'] : $log_details_file['0']; ?>">
                        </div>
                        <p class="card-text mt-1"> Prize Name : <?= $log_details['prize_name']; ?>
                        </p>
                        @php
                        $prize_category = '';
                        if($log_details['prize_category']){
                          $prize_categories = DB::table('prize_categories')->where('prize_category_id', '=', $log_details['prize_category'])->get();
                          $prize_categories = json_encode($prize_categories);
                          $prize_categories = json_decode($prize_categories, true);
                          if($prize_categories){
                            $prize_category = $prize_categories['0']['category_name'];
                          }
                          
                        }
                        @endphp
                        <p class="card-text mt-1"> Category : <?= $prize_category; ?>
                        </p>
                        <p class="card-text mt-1"> Cash Value : <?= $log_details['cash_value']; ?>
                        </p>
                        <p class="card-text mt-1"> Currency : <?= $log_details['currency']; ?>
                        </p>
                        <p class="card-text mt-1"> Description of the prize : <?= $log_details['description']; ?>
                        </p>
                      </div>
                      
                    </div>
                  </div>
                </li>
                 <?php
                }elseif($description == 'A prize is updated'){
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
                          $log_details_file =  $log_details['file'];
                          $log_details_file = json_decode($log_details_file, true);

                          if($log_before_changes != $log_details){
                            ?>
                              <img class="responsive-img " src="<?= $log_details_file['primary'] ? $log_details_file['primary'] : $log_details_file['0']; ?>">
                            <?php
                          }
                          if($log_before_changes['prize_name'] != $log_details['prize_name']){
                          ?>
                          <p class="card-text mt-1"> Prize Name : <?= $log_details['prize_name']; ?>
                          </p>
                          <?php
                          }
                          if($log_before_changes['prize_category'] != $log_details['prize_category']){
                          ?>
                          @php
                          $prize_category = '';
                          if($log_details['prize_category']){
                            $prize_categories = DB::table('prize_categories')->where('prize_category_id', '=', $log_details['prize_category'])->get();
                            $prize_categories = json_encode($prize_categories);
                            $prize_categories = json_decode($prize_categories, true);
                            if($prize_categories){
                              $prize_category = $prize_categories['0']['category_name'];
                            }
                          }
                          @endphp
                          <p class="card-text mt-1"> Category : <?= $prize_category; ?>
                          </p>
                          <?php
                          }
                          if($log_before_changes['cash_value'] != $log_details['cash_value']){
                          ?>
                          <p class="card-text mt-1"> Cash Value : <?= $log_details['cash_value']; ?>
                          </p>
                          <?php
                          }
                          if($log_before_changes['currency'] != $log_details['currency']){
                          ?>
                          <p class="card-text mt-1"> Currency : <?= $log_details['currency']; ?>
                          </p>
                          <?php
                          }
                          if($log_before_changes['description'] != $log_details['description']){
                          ?>
                          <p class="card-text mt-1"> Description of the prize : <?= $log_details['description']; ?>
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
        </div>
        <div class="col s12" id="view">
          <!-- users add account form start -->
          <?php
            $prize = json_encode($prize);
            $prize = json_decode($prize, true);
          ?>
        
            <div class="row">
              <div class="col s12">
                <div class="row">

                  <!--Add File-->
                   <?php 
                     $file = $prize['0']['file'];
                     $file = json_decode($file, true);
                   ?>
                  <div class="row" style="display: flex;">
                    <?php 

                    for($i=0;$i<count($file);$i++) { 
                      
                        ?>
                        <div class='col-md-3' style='margin: 25px;'>
                        <img width="140" height="150" class="imageThumb img-responsive" src="{{@$file[$i]}}" title=""/>
                        </div>
                      <?php
                    }

                    ?>
                  </div>


                  <!--Prize Name-->
                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s7 input-field">
                      <input id="prize_name" name="prize_name" type="text" class="validate" value="<?php echo Request::old('prize_name') ? Request::old('prize_name') : $prize['0']['prize_name']; ?>"
                        data-error=".errorTxt1" disabled="true">
                      
                      <label for="prize_name">Prize Name</label>
                    </div>
                  </div>
                  
                  <!--Cash Value and Currency-->
                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s7 input-field m3">
                      <input id="cash_value" name="cash_value" type="number" class="validate" value="<?php echo Request::old('cash_value') ? Request::old('cash_value') : $prize['0']['cash_value']; ?>"
                        data-error=".errorTxt1" disabled="true" min="0" max="999999.99" step="0.01">
                      
                      <label for="cash_value">Cash Value</label>
                    </div>

                    <div class="col s7 input-field m2">
                      <h2 for="description" style="color:#9e9e9e; font-size: 1rem; margin: 0rem 0 1.424rem 7px;">Currency</h2>
                      <select id="currency" name="currency" disabled="true">
                        <option selected="selected">GBP</option>
                      </select>
                      
                    </div>
                  </div>
                  
                  <!--Category-->
                  <?php 
                    $selected = '';
                    if(Request::old('category')  == 'cat 01'){
                      $selected = 'selected="selected"';
                    }elseif($prize['0']['prize_category']  == 'cat 01'){
                      $selected = 'selected="selected"';
                    }
                  ?>
                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s7 input-field m5">
                      <h2 for="description" style="color:#9e9e9e; font-size: 1rem; margin: 0rem 0 1.424rem 7px;">Category</h2>
                      <select id="category" name="category" disabled="true">
                        <option value="">None</option>
                        @php
                        $prize_category = '';
                        if($log_details['prize_category']){
                          $prize_categories = DB::table('prize_categories')->where('prize_category_id', '=', $log_details['prize_category'])->get();
                          $prize_categories = json_encode($prize_categories);
                          $prize_categories = json_decode($prize_categories, true);
                          if($prize_categories){
                            $prize_category = $prize_categories['0']['category_name'];
                          }
                        }
                        @endphp
                        <option value="$prize_category" selected="selected"> {{$prize_category }}</option>
                      </select>
                    </div>
                  </div>

                  <!--Description of the prize-->
                  <div class="row" style="padding: 0 2rem;" >
                    <div class="col s7 input-field">
                      <h2 for="description" style="color:#9e9e9e; font-size: 1rem; margin: 0rem 0 1.424rem 0;">Description of the prize</h2>
                      <textarea id="description" name="description" class="materialize-textarea birthdate-picker" data-error=".errorTxt3" disabled="true"><?php echo Request::old('description') ? Request::old('description') : $prize['0']['description']; ?></textarea>
                    </div>
                  </div>
                  
                  
                  <?php 
                    $selected = '';
                    if(Request::old('available')){
                      $selected = 'checked"';
                    }elseif($prize['0']['available_to_win']){
                      $selected = 'checked';
                    }
                  ?>
                  <div class="col s12 input-field m6" style="padding-bottom: 30px;">
                    <label>
                      <input id="available" name="available" type="checkbox" class="validate" value="1" data-error=".errorTxt1" <?= $selected; ?> disabled="true"> <span>Available</span>
                    </label>
                  </div>

                </div>
              </div>
            </div>
          <!-- users add account form ends -->
        </div>
        <div class="col s12" id="edit">
          <!-- users add account form start -->
          <form id="prizeUploadImageForm" action="" method="post" enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="row" style="padding: 0 1rem;" >
                <div class="col s3 input-field add-new-file mt-0">
                  <!-- Add File Button -->
                  <button id="add_file" class="add-file-btn btn btn-block waves-effect waves-light mb-10">
                    <i class="material-icons">file_upload</i>
                    <span>Upload Images</span>
                  </button>
                  @if ($errors->has('add_file.1')) 
                    <small class="errorTxt1">{{ $errors->first('add_file.1') }}</small>
                  @endif
                  @if ($errors->has('add_file.2')) 
                    <small class="errorTxt1">{{ $errors->first('add_file.2') }}</small>
                  @endif
                  @if ($errors->has('add_file.3')) 
                    <small class="errorTxt1">{{ $errors->first('add_file.3') }}</small>
                  @endif
                  @if ($errors->has('add_file.4')) 
                    <small class="errorTxt1">{{ $errors->first('add_file.4') }}</small>
                  @endif
                  @if ($errors->has('add_file.5')) 
                    <small class="errorTxt1">{{ $errors->first('add_file.5') }}</small>
                  @endif
                  @if ($errors->has('add_file.6')) 
                    <small class="errorTxt1">{{ $errors->first('add_file.6') }}</small>
                  @endif
                  <!-- file input  -->
                  <div class="getfileInput">
                    <input type="file" id="getFile" name="add_file[]" multiple="multiple">
                  </div>
                </div>
              </div>
            </form>

          <?php
            $prize = json_encode($prize);
            $prize = json_decode($prize, true);
          ?>
          <form id="prizeEditForm" action="{{route('prizes.update', $prize['0']['prize_id'])}}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            {{ method_field('put') }}
            <?php 
               $file = $prize['0']['file'];
               $file = json_decode($file, true);
               
             ?>
            <div class="row" id="image_preview" style="display: flex; margin-left: 0px;">
              <?php 

              for($i=0;$i<count($file);$i++) { 
                if(@$file[$i]){
                  ?>
                
                <div class='col-md-3 pip' style='margin: 25px;'>
                <img width="140" height="150" data-id="primary{{$i}}" class="imageThumb img-responsive" src="{{@$file[$i]}}" title="Click on image to make it primary image." style="cursor:pointer;"/>
                <input type="hidden" name="images[]" value="{{@$file[$i]}}"/>
                <input type="hidden" id="primary{{$i}}" name="primary" value=""/>
                <br/><span class="remove">Remove image</span>
                </div>

                  
                <?php
                }
              }

              ?>
            </div>
            <div class="row">
              <div class="col s12">
                <div class="row">

                  <!--Prize Name-->
                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s7 input-field">
                      <input id="prize_name" name="prize_name" type="text" class="validate" value="<?php echo Request::old('prize_name') ? Request::old('prize_name') : $prize['0']['prize_name']; ?>"
                        data-error=".errorTxt1">
                      
                      <label for="prize_name">Prize Name*</label>
                      @if ($errors->has('prize_name'))
                        <small class="errorTxt1">{{ $errors->first('prize_name') }}</small>
                      @endif
                      
                    </div>
                  </div>
                  
                  <!--Cash Value and Currency-->
                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s7 input-field m3">
                      <input id="cash_value" name="cash_value" type="number" onChange="if(this.value.length>999999.99) return false;"  class="validate" value="<?php echo Request::old('cash_value') ? Request::old('cash_value') : $prize['0']['cash_value']; ?>"
                        data-error=".errorTxt1" min="0" max="999999.99" step="0.01">
                      
                      <label for="cash_value">Cash Value*</label>
                      @if ($errors->has('cash_value'))
                        <small class="errorTxt1">{{ $errors->first('cash_value') }}</small>
                      @endif
                      
                    </div>
                    <div class="col s7 input-field m2">
                      <select id="currency" name="currency" class="validate">
                        <option selected="selected">GBP</option>
                      </select>
                      <label for="currency">Currency*</label>
                      @if ($errors->has('currency'))
                        <small class="errorTxt1">{{ $errors->first('currency') }}</small>
                      @endif
                    </div>
                  </div>
                  
                  <!--Category-->
                  <?php 
                    $selected = '';
                    if(Request::old('category')  == 'cat 01'){
                      $selected = 'selected="selected"';
                    }elseif($prize['0']['prize_category']  == 'cat 01'){
                      $selected = 'selected="selected"';
                    }
                  ?>
                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s7 input-field m5">
                      
                      <select id="category" name="category" class="validate">
                        <option value="">None</option>
                        @php
                        $category = Request::old('category') ? Request::old('category') : $prize['0']['prize_category'];
                        $prize_categories = json_encode($prize_categories);
                        $prize_categories = json_decode($prize_categories, true);
                        foreach($prize_categories as $key=>$value){
                        @endphp
                        <option value="{{$value['prize_category_id']}}" <?php echo $category == $value['prize_category_id'] ? 'selected="selected"' : ''; ?>>{{$value['category_name']}}</option>
                        @php
                        }
                        @endphp
                      </select>

                      <label for="category">Category*</label>
                      
                      @if ($errors->has('category'))
                        <small class="errorTxt1">{{ $errors->first('category') }}</small>
                      @endif
                    </div>
                  </div>

                  <!--Description of the prize-->
                  <div class="row" style="padding: 0 1rem;" >
                    <div class="col s7 input-field">
                      <!-- <h2 for="description" style="color:#9e9e9e; font-size: 1rem; margin: 0rem 0 1.424rem 7px;">Description of the prize*</h2> -->
                      <textarea id="description" name="description" class="validate materialize-textarea birthdate-picker" data-error=".errorTxt3"><?php echo Request::old('description') ? Request::old('description') : $prize['0']['description']; ?></textarea>
                      <label for="description">Description of the prize*</label>
                      
                      @if ($errors->has('description'))
                        <small class="errorTxt1">{{ $errors->first('description') }}</small>
                      @endif
                    </div>
                  </div>
                  
                  <!--Add File-->
                  <!-- <div class="row" style="padding: 0 1rem;" >
                    <div class="col s3 input-field add-new-file mt-0">
                      
                      <button id="add_file" class="add-file-btn btn btn-block waves-effect waves-light mb-10">
                        <i class="material-icons">file_upload</i>
                        <span>Upload Images</span>
                      </button> -->
                      @if ($errors->has('add_file.1')) 
                        <small class="errorTxt1">{{ $errors->first('add_file.1') }}</small>
                      @endif
                      @if ($errors->has('add_file.2')) 
                        <small class="errorTxt1">{{ $errors->first('add_file.2') }}</small>
                      @endif
                      @if ($errors->has('add_file.3')) 
                        <small class="errorTxt1">{{ $errors->first('add_file.3') }}</small>
                      @endif
                      @if ($errors->has('add_file.4')) 
                        <small class="errorTxt1">{{ $errors->first('add_file.4') }}</small>
                      @endif
                      @if ($errors->has('add_file.5')) 
                        <small class="errorTxt1">{{ $errors->first('add_file.5') }}</small>
                      @endif
                      @if ($errors->has('add_file.6')) 
                        <small class="errorTxt1">{{ $errors->first('add_file.6') }}</small>
                      @endif
                      <!-- file input  -->
                      <!-- <div class="getfileInput">
                        <input type="file" id="getFile" name="add_file[]" multiple="multiple">
                      </div>
                    </div>
                  </div> -->
                   

                  <?php 
                    $selected = '';
                    if(Request::old('available')){
                      $selected = 'checked"';
                    }elseif($prize['0']['available_to_win']){
                      $selected = 'checked';
                    }
                  ?>
                  <div class="row" style="padding: 0 1rem;" >
                    <div class="col s12 input-field m6" style="padding-bottom: 30px;">
                      <label>
                        <input id="available" name="available" type="checkbox" class="validate" value="1" data-error=".errorTxt1" <?= $selected; ?>> <span>Available</span>
                      </label>
                      <!-- <label> <input id="status" name="status" type="checkbox" value=""
                        data-error=".errorTxt3"> <spam>Acceptd terms and conditions </spam></label> -->
                      @if ($errors->has('available'))
                        <small class="errorTxt1">{{ $errors->first('available') }}</small>
                      @endif
                    </div>
                  </div>

                </div>
              </div>

              <!--Save button and google captcha-->
              <div class="col s12 display-flex mt-3">
                <button class="btn waves-effect waves-light right submit" type="submit" name="action">Save</button>
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
<!-- prize edit ends -->
<script type="text/javascript">
function onSubmit(token){
  
  document.getElementById("prizeEditForm").submit();
   
}
</script>
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
    
  
    
 

    $("#add_file").on("click", function (e) {
      e.preventDefault();
      $(".getfileInput input").click();
    });

    //$(".getfileInput input").on('change', function(){
      //if (window.File && window.FileList && window.FileReader) {
    $("#getFile").on("change", function(e) {
      var files = e.target.files,
      filesLength = files.length; 

      if (parseInt(filesLength) > 6){
        alert("You can only upload a maximum of 6 files");
        //$('#image_preview').html('');
      }else{
          $('#image_preview').html('');
          $("#prizeUploadImageForm").submit();
       /* for (var i = 0; i < filesLength; i++) {
          var f = files[i]
          var fileReader = new FileReader();
          fileReader.onload = (function(e) {
            var file = e.target;
            $('#image_preview').append("<div class='col-md-3' style='margin: 10px;'>" +
              '<img width="140" height="150" class="imageThumb img-responsive" src="' + e.target.result + '" title="' + file.name + '"/>' +
              "<br/><span class=\"remove\">Remove image</span>" +
              "</div>");*/
            /*$(".remove").click(function(){
              $(this).parent(".pip").remove();
            });*/
            
            // Old code here
            /*$("<img></img>", {
              class: "imageThumb",
              src: e.target.result,
              title: file.name + " | Click to remove"
            }).insertAfter("#files").click(function(){$(this).remove();});*/
            
          /*});
          fileReader.readAsDataURL(f);
        }*/

      }
    });
  /*} else {
    alert("Your browser doesn't support to File API")
  }*/

      /*var $fileUpload = $(".getfileInput input[type='file']"); console.log($fileUpload.get(0).files);
      if (parseInt($fileUpload.get(0).files.length) > 6){
        alert("You can only upload a maximum of 6 files");
      }else{ //alert();
        //var total_file=document.getElementById("images").files.length;
        for(var i=0;i<$fileUpload;i++)
        {
          $('#image_preview').append("<div class='col-md-3'><img class='img-responsive' src='"+URL.createObjectURL(event.target.files[i])+"'></div>");
        }
      }*/
    //});

     $('#prizeUploadImageForm').on('submit', function(event){
        event.preventDefault();  //alert();
        $.ajax({
         url:"{{ route('prizes.uploadImages') }}",
         method:"POST",
         data:new FormData(this),
         dataType:'JSON',
         contentType: false,
         cache: false,
         processData: false,
         success:function(data)
         { //console.log(data);  
          //alert();
          var i = 0;
          $.each(data, function( key, val ) { //console.log(val);
            //items.push( "<li id='" + key + "'>" + val + "</li>" );

            $('#image_preview').append("<div class='col-md-3 pip' style='margin: 25px;'>" +
                  '<img width="140" height="150" data-id="primary'+i+'" class="imageThumb img-responsive" src="' + val + '" title="Click on image to make it primary image." style="cursor:pointer;"/>' 
                  +
                  '<input type="hidden" name="images[]" value="' + val + '"/>' +
                  '<input type="hidden" id="primary'+i+'" name="primary" value=""/>' +
                  "<br/><span class=\"remove\">Remove image</span>" +
                  "</div>");
                $(".remove").click(function(){
                  
                  $(this).parent(".pip").remove();
                });
                $(".pip img").on('click', function(){ 
                  var data_id = $(this).attr('data-id');
                  var src = $(this).attr('src');
                  //alert(data_id);
                  $('input[name=primary]').val(src);
                });
                

                i++;
                
          });
         }
        })
       });

$(document).on('click', ".remove", function(){
  
  $(this).parent(".pip").remove();
});
$(document).on('click', ".pip img", function(){ 
  var data_id = $(this).attr('data-id');
  var src = $(this).attr('src');
  //alert(data_id);
  $('input[name=primary]').val(src);
});


  });
  
</script>
<script type="text/javascript">
  $('#page-prize_log').DataTable({
    "responsive": true,
    "lengthMenu": [
      [10, 25, 50, -1],
      [10, 25, 50, "All"]
    ]
  });
</script>
<style type="text/css">
  select[name="page-prize_log_length"]{
    display: none !important;
  }
  .dataTables_length label{
    display: flex;
  }
  /*.select-wrapper{
    margin-top: -25px;
    margin-left: 10px;
    margin-right: 10px;
  }*/
</style>

@endsection