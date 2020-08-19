{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Challenge Management')

{{-- vendors styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/data-tables/css/jquery.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css"
  href="{{asset('vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css')}}">
@endsection

{{-- page styles --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-users.css')}}">
@endsection

{{-- page content --}}
@section('content')

<!-- Add prize button -->
<a href="{{route('challenges.create')}}" class="btn waves-effect waves-light mb-1 add-kanban-btn"><i class="material-icons left">add</i><span>Add Challenge</span></a>

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


<!-- question list start -->
<section class="users-list-wrapper section">
  <div class="users-list-filter">
    <div class="card-panel">
      <div class="row">
        <form action="{{route('challenges.index')}}" method="post">
          {{ csrf_field() }}
            {{ method_field('get') }}
          <div class="col s12 m6 l3" style="margin-top: -2px;">
            
            <div class="input-field">
              <input type="text" class="form-control" id="Search" name="Search" value="<?= @$Search; ?>">
              <label for="Search">Search</label>
            </div>
          </div>
          <div class="col s12 m6 l3 display-flex align-items-center">
            <button type="submit" class="btn btn-block indigo waves-effect waves-light">Show</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="users-list-table">
    <div class="card">
      <div class="card-content">
  <!-- datatable start -->
        <div class="" id="player">
          <div class="responsive-table">
            
            <?php 
            $questionTotal = $questionTotal;
            $questionTotal = json_encode($questionTotal);
            $questionTotal = json_decode($questionTotal, true);
            $question_paginate = $questionTotal;
            //echo '<pre>'; print_r($prizeTotal); echo '</pre>';
            $questionTotal = $questionTotal['data'];
            if($questionTotal){
            ?>
            <!-- <table id="multi-select" class="display table"> -->
              <table id="page-question" class="display">
              <thead>
                <tr>
                  <!-- <th></th> -->
                  <th>Question</th>
                  <th>Answer</th>
                  <th>Last Used</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  foreach($questionTotal as $question){
                  
                    $challenge_answers  = DB::table('challenge_answers')
                                        ->where([['question_id', '=', $question['question_id']]])
                                        ->select('challenge_answers.*')
                                        ->get();
                                        $challenge_answers = json_encode($challenge_answers);
                                        $challenge_answers = json_decode($challenge_answers, true);

                                        //print_r($challenge_answers);
                  ?>
                  <tr>
                    <!-- <td></td> -->
                    <td><a href="{{route('challenges.editChallenge', $question['question_id'])}}#view">{{$question['question']}}</a></td>
                    <?php
                    foreach ($challenge_answers as $key => $value) {
                      $k = chr(65+$key);
                      if($value['correct_answer'] == 1)
                      {
                    ?>
                    <td>{{$k}} - {{$value['answer']}}</td>
                    <?php
                      }
                    }
                    ?>
                    <td>{{$question['last_used_timestamp']}}</td>
                    <td style="display: flex;"><a href="{{route('challenges.editChallenge', $question['question_id'])}}#edit"><i class="material-icons">edit</i></a>

                      <form id="prizeDestroy{{$question['question_id']}}" onsubmit="return confirm('Do you really want to delete question and its answer?');" method="post" action="{{route('challenges.destroy', $question['question_id'])}}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                         <a href="javascript:{}" data-id="prizeDestroy{{$question['question_id']}}" id="prizeDestroyButton"><i class="material-icons">delete</i></a>
                      </form>
                      <!-- <a href="{{route('prizes.destroy', $question['question_id'])}}"><i class="material-icons">delete</i></a> -->
                    </td>
                  </tr>
                <?php
                  }
                ?>
              </tbody>
            </table>
            <?php 
            }else{
              echo "<p style='text-align: center;'>No data found!</p>";
            }
            if($question_paginate['last_page']>1){ ?>
            <ul class="pagination">
              <li class="<?= $question_paginate['current_page'] == 1 ? 'disabled' : ''; ?>"><a href="{{$question_paginate['path']}}?page={{$question_paginate['current_page']-1}}"><i class="material-icons">navigate_before</i></a></li>
              <?php
              for ($i=1; $i <= $question_paginate['last_page']; $i++) {
                $act = 'waves-effect';
                if($i == $question_paginate['current_page']){
                  $act = 'active';

                }
              ?>
                <li class="{{$act}}"><a href="{{$question_paginate['path']}}?page={{$i}}">{{$i}}</a></li>
              <?php
              }
              ?>
              <li class="<?= $question_paginate['current_page'] == $question_paginate['last_page'] ? 'disabled' : ''; ?>"><a href="{{$question_paginate['path']}}?page={{$question_paginate['current_page']+1}}"><i class="material-icons">navigate_next</i></a></li>
            </ul>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- question list ends -->
@endsection

{{-- vendor scripts --}}
@section('vendor-script')
<script src="{{asset('vendors/data-tables/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js')}}"></script>
@endsection

{{-- page script --}}
@section('page-script')
<script src="{{asset('js/scripts/page-users.js')}}"></script>
<script src="{{asset('js/scripts/ui-alerts.js')}}"></script>
<!-- <script src="{{asset('js/scripts/app-email.js')}}"></script> -->
<script type="text/javascript">
  $(document).ready(function(){
    $(document).on('click', '#prizeDestroyButton', function(e){
      e.preventDefault();
      var data = $(this).attr("data-id");
      //var cof = confirm('Do you really want to delete prize?');
      //if(cof){
        $('#'+data).submit();
      //}
    });
  });
  /*$('#page-question').DataTable({
    "responsive": true,
    "lengthMenu": [
      [10, 25, 50, -1],
      [10, 25, 50, "All"]
    ]
  });*/
</script>
<style type="text/css">
  select[name="page-question_length"]{
    display: none !important;
  }
  .dataTables_length label{
    display: flex;
  }
  .select-wrapper{
    margin-top: -25px;
    margin-left: 10px;
    margin-right: 10px;
  }
</style>

@endsection