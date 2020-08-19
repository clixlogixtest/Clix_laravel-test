{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Edit a Challenge')

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
<!-- question edit start -->
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
      <?php
        $question = json_encode($question);
        $question = json_decode($question, true);
        $Challenge_answers = json_encode($Challenge_answers);
        $Challenge_answers = json_decode($Challenge_answers, true);
      ?>
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

                $challenge1 = $log_before_changes['challenge'];
                $answer1 = $log_before_changes['answer'];

                $challenge2 = $log_details['challenge'];
                $answer2 = $log_details['answer'];
                //echo '<pre>'; print_r($log_before_changes); print_r($log_details); echo '</pre>'; die();

                

                if($description == 'A challenge is created' || $description == 'A challenge is deleted'){
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
                        
                        <p class="card-text mt-1"> Question : <?= $challenge2['question']; ?>
                        </p>
                        @foreach($answer2 as $val) 
                        
                        <p class="card-text mt-1"> Answer : <?= @$val['answer']; ?>
                        </p>
                        @endforeach
                      </div>
                      
                    </div>
                  </div>
                </li>
                 <?php
                }elseif($description == 'A challenge is updated'){
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
                        
                          <?php
                          if($challenge1['question'] != $challenge2['question']){
                          ?>
                          <p class="card-text mt-1"> Prize Name : <?= $challenge2['question']; ?>
                          </p>
                          <?php
                          }
                          if($answer1 != $answer2){ 
                            foreach ($answer2 as $key => $value) {
                          ?>
                          <p class="card-text mt-1"> Answer : <?= $value['answer']; ?>
                          </p>
                          <?php
                            }
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
          
          <!-- question edit form start -->
          <!-- <form id="challengeAddForm" action="{{route('challenges.update', $question['0']['question_id'])}}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            {{ method_field('put') }} -->
            <div class="row">
              <div class="col s12">
                <div class="row">
                  @if($errors->has('error'))
                    <div class="error">{{ $errors->first('error') }}</div>
                  @endif
                  
                  <div class="row" style="padding: 0 1rem;" >
                    <div class="col s7 input-field">
                      <h2 for="question" style="color:#9e9e9e; font-size: 1rem; margin: 0rem 0 1.424rem 0;">Question</h2>
                      <textarea id="question" name="question" class="birthdate-picker" data-error=".errorTxt3" disabled="true"><?php echo Request::old('question') ? Request::old('question') : $question['0']['question']; ?></textarea>
                      
                      @if ($errors->has('question'))
                        <small class="errorTxt1">{{ $errors->first('question') }}</small>
                      @endif
                    </div>
                  </div>

                  
                  <?php 
                    @$ans = count($Challenge_answers);
                    for($i=0;$i<$ans;$i++){
                      $oldans = $Challenge_answers[$i]['answer'];
                      $corAns = $Challenge_answers[$i]['correct_answer'];
                      $corAnsChecked = $corAns == 1 ? 'checked="checked"' : '';
                  ?>
                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s7 input-field">
                        <input id="answer{{$i}}" name="answer[]" type="text" class="validate" value="{{$oldans}}" data-error=".errorTxt{{$i}}" placeholder="Answer" disabled="true">
                        @if($i==0)
                        <label for="answer{{$i}}">Answer</label>
                        @endif
                        <?php if ($errors->has("answer.".$i)){  ?>
                          <small class="errorTxt{{$i}}"> <?php echo $errors->first("answer.".$i); ?></small>
                        <?php } ?>
                      </div>
                      <div class="col s4 input-field">
                        <label>
                          <input id="answer_correct<?php echo $i; ?>" name="answer_correct" type="radio" class="validate" value="{{$i}}" {{ $corAnsChecked }} disabled="true"><span>Correct Answer</span>
                        </label>
                      </div>
                      
                    </div>
                  <?php
                    }
                  ?>

                  
                  
                </div>
              </div>
              <!-- <div class="col s12 display-flex mt-3">
                <button class="g-recaptcha btn indigo" 
                              data-sitekey="6Lc1F_oUAAAAABXCu0MULxBbxalEQKCoHboXW9YQ" 
                              data-callback='onSubmit' 
                              data-action='submit'>
                  Save</button>
              </div> -->
            </div>
          <!-- </form> -->
          <!-- question edit form ends -->
        </div>
        <div class="col s12" id="edit">
          <!-- question edit form start -->
          <form id="challengeAddForm" action="{{route('challenges.update', $question['0']['question_id'])}}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            {{ method_field('put') }}
            <div class="row">
              <div class="col s12">
                <div class="row">
                  @if($errors->has('error'))
                    <div class="error">{{ $errors->first('error') }}</div>
                  @endif
                  
                  <div class="row" style="padding: 0 1rem;" >
                    <!-- <div class="col s7 input-field">
                      <h2 for="question" style="color:#9e9e9e; font-size: 1rem; margin: 0rem 0 1.424rem 0;">Question*</h2>
                      <textarea id="question" name="question" class="birthdate-picker" data-error=".errorTxt3"><?php echo Request::old('question') ? Request::old('question') : $question['0']['question']; ?></textarea>
                      
                      @if ($errors->has('question'))
                        <small class="errorTxt1">{{ $errors->first('question') }}</small>
                      @endif
                    </div> -->
                    <div class="col s7 input-field">
                       
                      <input id="question" name="question" type="text"  class="validate" value="<?php echo Request::old('question') ? Request::old('question') : $question['0']['question']; ?>" autocomplete="off"/>
                      
                      <label for="question">Question*</label>
                      <?php if ($errors->has("question")){  ?>
                        <small class="errorTxt"> <?php echo $errors->first("question"); ?></small>
                      <?php } ?>
                    </div>
                  </div>
                  <?php $answerCount = @Request::old('answerCount') ? @Request::old('answerCount') : count(@$Challenge_answers); ?>
                  <input id="answer" name="answerCount" type="hidden" class="validate" value="<?php  echo $answerCount; ?>" data-error=".errorTxt1">
                  <?php
                    @$ans = $answerCount; 
                    @$diff = 6-@$ans;
                    for($i=0;$i<$ans;$i++){ 
                      $oldans = @$Challenge_answers[@$i]['answer'];

                      $corAns = @$Challenge_answers[@$i]['correct_answer'];
                      $corAnsChecked = $corAns == 1 ? 'checked="checked"' : ''; 
                  ?>
                    <div class="answerContainer{{@$i+1}}">
                    <div class="row" style="padding: 0 1rem;">
                      <div class="col s7 input-field">
                        <input id="answer{{@$i}}" name="answer[]" type="text" class="validate" value="{{$oldans}}" data-error=".errorTxt{{@$i}}" placeholder="Answer" autocomplete="off">
                        @if($i==0)
                        <label for="answer{{@$i}}">Answer*</label>
                        @endif
                        <?php if ($errors->has("answer.".$i)){  ?>
                          <small class="errorTxt{{@$i}}"> <?php echo $errors->first("answer.".@$i); ?></small>
                        <?php } ?>
                      </div>
                      <div class="col s4 input-field">
                        <label>
                          <input id="answer_correct<?php echo @$i; ?>" name="answer_correct" type="radio" class="validate" value="{{@$i+1}}" {{ $corAnsChecked }} autocomplete="off"><span>Correct Answer</span>
                        </label>
                      </div>
                      
                    </div>
                    </div>
                  <?php
                    } 
                    for ($j=$ans; $j < 6; $j++) { 
                      ?>
                      <div class="answerContainer{{$j+1}}"></div>
                      <?php
                    } //die();
                  ?>
                  
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
          <!-- question edit form ends -->
        </div>

      </div>
      <!-- </div> -->
    </div>
  </div>
</div>
<!-- question edit ends -->
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
                      +'<input id="answer_correct'+answer+'" name="answer_correct" type="radio" class="validate" value="'+answer+'" data-error=".errorTxt1"><span>Correct Answer</span>'
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

<script type="text/javascript">
  $(document).ready(function(){
    
    /*$(".removeAns").on('click', function(){

    });

    $(".invoice-repeat-btn").on('click', function(){
      var answer = $('input[name=answerCount]').val(); 
      var char = String.fromCharCode(65+parseInt(answer));
      answer++
      if(answer <= 5){

        $(".answerContainer").append('<div class="row" style="padding: 0 1rem;">'
                    +'<div class="col s7 input-field">'
                      +'<input id="answer'+answer+'" name="answer[]" type="text" class="validate" value="" data-error=".errorTxt1" placeholder="Answer">'
                    +'</div>'
                    +'<div class="col s4 input-field">'
                    +'<label>'
                      +'<input id="answer_correct'+answer+'" name="answer_correct[]" type="checkbox" class="validate" value="1" data-error=".errorTxt1"><span>Correct Answer</span>'
                      +'</label>'
                    +'</div>'
                    
                  +'</div>');
       $('input[name=answerCount]').val(answer);

      }else{
        alert("You can not add more than 5 answer.");
      }
       
    });*/
      //}
  });
</script>
@endsection