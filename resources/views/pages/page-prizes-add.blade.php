{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Add a Prize')

{{-- vendor styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2-materialize.css')}}">
@endsection

{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-users.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-file-manager.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/widget-timeline.css')}}">
<style type="text/css">
  .getfileInput{
    width: 0;
    height: 0;
    overflow: hidden;
  }
</style>
<script src="https://www.google.com/recaptcha/api.js"></script>
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

<!-- users edit start -->
<div class="section users-edit">
  <div class="card">
    <div class="card-content">
      
      <div class="row">
        <div class="col s12">
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
          <!-- users add account form start -->
          <form id="prizeAddForm" action="{{ route('prizes.store') }}" method="post" enctype="multipart/form-data">
            <?php 
               $file = Request::old('images'); //print_r($file);
               //$file = json_decode($file, true);
               
             ?>
            <div class="row" id="image_preview" style="display: flex; margin-left: 0px;">
              <?php 
              if($file){

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

              }
              

              ?>
            </div>
            <input type="hidden" name="imagesPosition" value="">
            {{ csrf_field() }}
            <div class="row">
              <div class="col s12">
                <div class="row">
                  
                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s7 input-field">
                      <input id="prize_name" name="prize_name" type="text"  class="validate" value="<?php echo Request::old('prize_name'); ?>"
                        data-error=".errorTxt1">
                      
                      <label for="prize_name">Prize Name*</label>
                      @if ($errors->has('prize_name'))
                        <small class="errorTxt1">{{ $errors->first('prize_name') }}</small>
                      @endif
                      
                    </div>
                  </div>
                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s7 input-field m3">
                      <input id="cash_value" name="cash_value" type="number" onChange="if(this.value.length>999999.99) return false;"  class="validate" value="<?php echo Request::old('cash_value'); ?>"
                        data-error=".errorTxt1" min="0" max="999999.99" step="0.01">
                      
                      <label for="cash_value">Cash Value*</label>
                      @if ($errors->has('cash_value'))
                        <small class="errorTxt1">{{ $errors->first('cash_value') }}</small>
                      @endif
                      
                    </div>
                    <div class="col s7 input-field m2">
                      <select id="currency" name="currency"  class="validate" data-error=".errorTxt2">
                        <option selected="selected">GBP</option>
                      </select>
                      <label for="currency">Currency*</label>
                      @if ($errors->has('currency'))
                        <small class="errorTxt1">{{ $errors->first('currency') }}</small>
                      @endif
                    </div>
                  </div>
                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s7 input-field m5">
                      <select id="category" name="category"  class="validate" data-error=".errorTxt2">
                        <option value="">None</option>
                        @php
                        $prize_categories = json_encode($prize_categories);
                        $prize_categories = json_decode($prize_categories, true);
                        foreach($prize_categories as $key=>$value){
                        @endphp
                        <option value="{{$value['prize_category_id']}}" <?php echo Request::old('category') == $value['prize_category_id'] ? 'selected="selected"' : ''; ?>>{{$value['category_name']}}</option>
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
                  <div class="row" style="padding: 0 1rem;" >
                    <div class="col s7 input-field">
                      <!-- <h2 for="description" style="color:#9e9e9e; font-size: 1rem; margin: 0rem 0 1.424rem 0;">Description of the prize*</h2> -->
                      <textarea id="description" name="description" class="validate materialize-textarea birthdate-picker" data-error=".errorTxt3" style="height: 233px;"><?php echo Request::old('description'); ?></textarea>
                      <label for="description">Description of the prize*</label>
                      
                      @if ($errors->has('description'))
                        <small class="errorTxt1">{{ $errors->first('description') }}</small>
                      @endif
                    </div>
                  </div>
                  
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
                    if ($errors->isEmpty()) {
                      
                      if(Request::old('available')){
                        $selected = 'checked"';
                      }
                    }
                  ?>
                  <div class="row" style="padding: 0 1rem;" >
                  <div class="col s12 input-field m6" style="padding-bottom: 30px;">
                      <label>
                      <input id="available" name="available" type="checkbox"  class="validate" value="1" data-error=".errorTxt1" <?= $selected; ?>> <span>Available</span>
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
<script src="{{asset('js/scripts/app-file-manager.js')}}"></script>
<script type="text/javascript" src="http://www.expertphp.in/js/jquery.form.js"></script>
<script src="{{asset('js/scripts/ui-alerts.js')}}"></script>
<script>

  function isUrlValid(url) {
    return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
}

  $(document).on('submit','form#prizeAddForm',function(){
    var getFile = $("#getFile").val();
    var fil = "{{$file[0]}}";

    
      if (!getFile && !isUrlValid(fil)){
        window.location.href = '#breadcrumbs-wrapper';
        alert("Please upload images.");
        return false;
      }else{
        

    
        document.getElementById("prizeAddForm").submit();
        //return false;
      }
  });

  /* function onSubmit(token) {
    
    var image_preview = $("#image_preview").html(); alert(image_preview);
    var $fileUpload = $(".getfileInput input[type='file']");
      if (!image_preview){
        alert("You can only upload a maximum of 6 files");
      }else{
        

    
        document.getElementById("prizeAddForm").submit();
      }
     
   }*/
</script>
<script type="text/javascript">
  $(document).ready(function(){
  
  /*var image_preview = $("#image_preview").html();
  $('select[required]').css({
    position: 'absolute',
    display: 'inline',
    height: 0,
    padding: 0,
    border: '1px solid rgba(255,255,255,0)',
    width: 0
  }); 

  $("#prizeAddForm").validate({
    rules: {
      prize_name: {
        required: true,
      },
      cash_value: {
        required: true,
      },
      currency: {
        required: true,
      },
      category: {
        required: true,
      },
      description: {
        required: true,
      },
      },
      //For custom messages
      messages: {
      prize_name:{
        required: "Enter a Prize Name"
      },
      cash_value:{
        required: "Enter a Cash Value"
      },
      currency:{
        required: "Select a Currency"
      },
      category:{
        required: "Select a Category"
      },
      description:{
        required: "Enter a Description"
      },
      
      },
      errorElement : 'div',
      errorPlacement: function(error, element) {
        var placement = $(element).data('error');
        if (placement) {
          $(placement).append(error)
        } else {
      error.insertAfter(element);
      }
    }
  });*/

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
      }else{
        $("#prizeUploadImageForm").submit();

        /*for (var i = 0; i < filesLength; i++) {
          var f = files[i]
          var fileReader = new FileReader();

          fileReader.onload = (function(e) {
            var file = e.target;
            $('#image_preview').append("<div class='col-md-3 pip' id='" + i + "' style='margin: 10px;'>" +
              '<img width="140" height="150" class="imageThumb img-responsive" src="' + e.target.result + '" title="' + file.name + '"/>' +
              "<br/><span class=\"remove\">Remove image</span>" +
              "</div>");
            $(".remove").click(function(){
              
              $(this).parent(".pip").remove();
            });*/
            
            // Old code here
            /*$("<img></img>", {
              class: "imageThumb",
              src: e.target.result,
              title: file.name + " | Click to remove"
            }).insertAfter("#files").click(function(){$(this).remove();});*/
            
         /* });
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
    event.preventDefault();
    $.ajax({
     url:"{{ route('prizes.uploadImages') }}",
     method:"POST",
     data:new FormData(this),
     dataType:'JSON',
     contentType: false,
     cache: false,
     processData: false,
     success:function(data)
     {  
      
      var i = 0;
      $.each(data, function( key, val ) {
        //items.push( "<li id='" + key + "'>" + val + "</li>" );

        $('#image_preview').append("<div class='col-md-3 pip' style='margin: 10px;'>" +
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

  

  });
  
</script>
@endsection