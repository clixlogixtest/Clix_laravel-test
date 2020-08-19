{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Add a Challenge')

{{-- vendor styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2-materialize.css')}}">
@endsection

{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-users.css')}}">
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
<!-- question add start -->
<div class="section users-edit">
  <div class="card">
    <div class="card-content">
      <div class="row">
        
        <div class="col s12">
          <!-- question add form start -->
          <form id="challengeAddForm" action="{{ route('challenges.store') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
              <div class="col s12">
                <div class="row">
                  @if($errors->has('error'))
                    <div class="error">{{ $errors->first('error') }}</div>
                  @endif
                  
                  <div class="row" style="padding: 0 1rem;" >
                    
                    <div class="col s7 input-field">
                       
                      <input id="question" name="question" type="text"  class="validate" value="<?php echo Request::old('question'); ?>" autocomplete="off"/>
                      
                      <label for="question">Question*</label>
                      <?php if ($errors->has("question")){  ?>
                        <small class="errorTxt"> <?php echo $errors->first("question"); ?></small>
                      <?php } ?>
                    </div>
                  </div>

                  <input id="answer" name="answerCount" type="hidden" class="validate" value="<?php echo Request::old('answerCount') ? Request::old('answerCount') : 2 ; ?>" data-error=".errorTxt1" autocomplete="off">
                  <?php
                    $ans = Request::old('answerCount') ? Request::old('answerCount') : 2 ;
                    for($i=0;$i<$ans;$i++){
                      $oldans = Request::old('answer.'.$i);
                      $answer_correct = Request::old('answer_correct');
                      /*$chek = '';
                      if($answer_correct == $i){

                        $chek = 'checked="checked"';

                      }*/
                  ?>
                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s7 input-field">
                        <input id="answer{{$i}}" name="answer[]" type="text" class="validate" value="{{$oldans}}" data-error=".errorTxt{{$i}}" placeholder="Answer" autocomplete="off">
                        @if($i==0)
                        <label for="answer{{$i}}">Answer*</label>
                        @endif
                        <?php if ($errors->has("answer.".$i)){  ?>
                          <small class="errorTxt{{$i}}"> <?php echo $errors->first("answer.".$i); ?></small>
                        <?php } ?>
                      </div>
                      <div class="col s4 input-field">
                        <label>
                          <input id="answer_correct<?php echo $i; ?>" name="answer_correct" type="radio" class="validate" value="{{$i+1}}" <?= Request::old('answer_correct') == $i+1 ? 'checked' : ''; ?>><span>Correct Answer</span>
                        </label>
                      </div>
                    </div>
                  <?php
                    }
                  ?>

                  <div class="answerContainer3"></div>
                  <div class="answerContainer4"></div>
                  <div class="answerContainer5"></div>
                  <div class="answerContainer6"></div>
                  
                  <div class="input-field" style="padding: 0 1rem;">
                    <button class="btn invoice-repeat-btn" data-repeater-create type="button">
                      <i class="material-icons left">add</i>
                      <span>Add Answer</span>
                    </button>
                    <button class="btn invoice-delete-btn" data-repeater-create type="button">
                      <i class="material-icons left">delete</i>
                      <span>Remove Answer</span>
                    </button>
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
          <!-- question add form ends -->
        </div>
      </div>
      <!-- </div> -->
    </div>
  </div>
</div>
<!-- question add ends -->
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
    var answer = $('input[name=answerCount]').val();
      if(answer <= 2){
        $(".invoice-delete-btn").css('display', 'none');
      }
      if(answer == 6){
        $(".invoice-repeat-btn").css('display', 'none');
      }

    $(".invoice-repeat-btn").on('click', function(){
      
      var answer = $('input[name=answerCount]').val(); 
      var char = String.fromCharCode(65+parseInt(answer));
      answer++;
      if(answer <= 6){
       $(".answerContainer"+answer+"").append('<div class="row" style="padding: 0 1rem;">'
                    +'<div class="col s7 input-field">'
                      +'<input id="answer'+answer+'" name="answer[]" type="text" class="validate" value="" data-error=".errorTxt1" placeholder="Answer" autocomplete="off">'
                    +'</div>'
                    +'<div class="col s4 input-field">'
                    +'<label>'
                      +'<input id="answer_correct'+answer+'" name="answer_correct" type="radio" class="validate" value="'+answer+'" data-error=".errorTxt1" autocomplete="off"><span>Correct Answer</span>'
                      +'</label>'
                    +'</div>'
                  +'</div>');
       $('input[name=answerCount]').val(answer);
      }

      if(answer == 6){

        $(this).css('display', 'none');

      }

      if(answer > 2){

        $(".invoice-delete-btn").css('display', '');

      }
    });

    $(".invoice-delete-btn").on('click', function(){
      var answer = $('input[name=answerCount]').val(); 
      var char = String.fromCharCode(65+parseInt(answer));
      
      if(answer <= 6){
       $(".answerContainer"+answer+"").html('');
       answer--;
       $('input[name=answerCount]').val(answer);
      }
      
      if(answer < 6){

        $(".invoice-repeat-btn").css('display', '');

      }

      if(answer <= 2){

        $(this).css('display', 'none');

      }

    });

  });
</script>
@endsection