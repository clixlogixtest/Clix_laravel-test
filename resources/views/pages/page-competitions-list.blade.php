{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Competition Management')

{{-- vendors styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/data-tables/css/jquery.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css"
  href="{{asset('vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css')}}">
@endsection

{{-- page styles --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-users.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-invoice.css')}}">
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

<!-- Add prize button -->
<a href="{{route('competitions.create')}}" class="btn waves-effect waves-light mb-1 add-kanban-btn"><i class="material-icons left">add</i><span>Add Competition</span></a>
<a href="{{route('competitions.addFreeTicket')}}" class="btn waves-effect waves-light mb-1 add-kanban-btn"><i class="material-icons left">add</i><span>Add Free Entry Ticket</span></a>

<!-- prize list start -->
<section class="invoice-list-wrapper section">
   <div class="users-list-filter">
    <div class="card-panel">
      <div class="row">
        <form action="{{route('competitions.index')}}" method="post">
          {{ csrf_field() }}
            {{ method_field('get') }}
          <div class="col s12 m6 l3" style="margin-top: -2px;">
            
            <div class="input-field">
              <input type="text" class="form-control" id="Search" name="Search" value="<?= $Search; ?>">
              <label for="Search">Search</label>
            </div>
          </div>
          <div class="col s12 m6 l3">
            <label for="users-list-verified"> Result</label>
            <div class="input-field">
              <select class="form-control" name="result" id="users-list-verified">
                <option value="">Any</option>
                <option value="0" <?= $result == 0 ? 'selected' : ''; ?>>Draft</option>
                <option value="1" <?= $result == 1 ? 'selected' : ''; ?>>Active</option>
                <option value="2" <?= $result == 2 ? 'selected' : ''; ?>>Closed</option>
                <option value="3" <?= $result == 3 ? 'selected' : ''; ?>>Drawn</option>
              </select>
            </div>
          </div>
          <!-- 
          <div class="col s12 m6 l3">
            <label for="users-list-status">Status</label>
            <div class="input-field">
              <select class="form-control" id="users-list-status">
                <option value="">Any</option>
                <option value="Active">Active</option>
                <option value="Close">Close</option>
                <option value="Banned">Banned</option>
              </select>
            </div>
          </div> -->
          <div class="col s12 m6 l3 display-flex align-items-center">
            <button type="submit" class="btn btn-block indigo waves-effect waves-light">Show</button>
          </div>

          <a href="" class="btn waves-effect waves-light mb-1 add-kanban-btn exportTOCSV"><i class="material-icons left">file_download</i><span>Export to CSV</span></a>

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
            /*$competitionList = $competitionList;
            $competitionList = json_encode($competitionList);
            $competitionList = json_decode($competitionList, true);*/

            $competitionList = $competitionList;
            $competitionList = json_encode($competitionList);
            $competitionList = json_decode($competitionList, true);
            $competition_paginate = $competitionList;
            //echo '<pre>'; print_r($prizeTotal); echo '</pre>';
            $competitionList = $competitionList['data'];
            if($competitionList){
            ?>
            
   
            <!-- <table id="multi-select" class="display table"> -->
              <table id="page-competition" class="display">
              <thead>
                <tr>
                  <!-- <th></th> -->
                  <th>CID</th>
                  <th>Prize</th>
                  <th>Tickets Sold</th>
                  <th>Close Date</th>
                  <th>State</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                
                <?php
                foreach($competitionList as $competition){
                  $color = '';
                  $stausText = '';
                  if($competition['status'] ==0){
                    $color = 'blue';
                    $stausText = 'Draft';
                  }
                  if($competition['status'] ==1){
                    $color = 'green';
                    $stausText = 'Active';
                  }
                  if($competition['status'] ==2){
                    $color = 'orange';
                    $stausText = 'Closed';
                  }
                  if($competition['status'] ==3){
                    $color = 'red';
                    $stausText = 'Drawn';
                  }

                  $avl = $competition['availabl_tickets']; 
                  $avlVal = $avl;
                  $avl = strlen($avl);
                  
                  if($avl<4){
                    $avdiff = 4-$avl;
                    $zero = '';
                    for ($i=0; $i < $avdiff; $i++) { 
                      $zero .= 0;
                    }
                    $avlVal = $zero.$competition['availabl_tickets'];

                  }

                  $ticketList = DB::table('tickets')
                  ->select('tickets.ticket_id')
                  ->where('tickets.competition_id', '=', $competition['competition_id'])
                  ->orderBy('tickets.created_at', 'desc')
                  ->get();

                  $TotalSoldTicket = '';
                  if($ticketList){
                    $TotalSoldTicket = count($ticketList);
                  } 

                  $sold = $TotalSoldTicket ? $TotalSoldTicket : '0000'; 
                  $soldVal = $sold;
                  $sold = strlen($sold);
                  
                  if($sold<4){
                    $avdiff = 4-$sold;
                    $zero = '';
                    for ($i=0; $i < $avdiff; $i++) { 
                      $zero .= 0;
                    }
                    $soldVal = $zero.$TotalSoldTicket;

                  }
                  
                  ?>
                  <tr>
                    <td>{{$competition['competition_id']}}</td>
                    <td>
                      @if($competition['status'] ==0)
                      <a href="{{route('competitions.editCompetition', $competition['competition_id'])}}#view">{{$competition['prize_name']}}</a>
                      @else
                      <a href="{{route('competitions.show', $competition['competition_id'])}}#view">{{$competition['prize_name']}}</a>
                      @endif</td>
                    <td><a href="{{route('competitions.ticketList', $competition['competition_id'])}}">{{$soldVal}}/{{$avlVal}}</a></td>
                    <td>{{date('d/m/Y', strtotime($competition['closed_date']))}}</td>
                    <td ><span class="chip {{$color}} lighten-5">
                        <span class="{{$color}}-text">{{$stausText}}</span>
                      </span>
                    </td>
                    <td style="display: flex;">
                      @if($competition['status'] ==0)
                      <a href="{{route('competitions.editCompetition', $competition['competition_id'])}}#edit"><i class="material-icons">edit</i></a>
                      <form id="prizeDestroy{{$competition['competition_id']}}" onsubmit="return confirm('Do you really want to delete competition?');" method="post" action="{{route('competitions.destroy', $competition['competition_id'])}}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                         <a href="javascript:{}" data-id="prizeDestroy{{$competition['competition_id']}}" id="prizeDestroyButton"><i class="material-icons">delete</i></a>
                      </form>
                      @else
                      <a href="{{route('competitions.show', $competition['competition_id'])}}#view"><i class="material-icons">remove_red_eye</i></a>
                      @endif
                      
                    </td>
                  </tr>
                  
                <?php } ?>
              </tbody>
            </table>
            <?php 
            }else{
              echo "<p style='text-align: center;'>No data found!</p>";
            }
            if($competition_paginate['last_page']>1){ ?>
            <ul class="pagination">
              <li class="<?= $competition_paginate['current_page'] == 1 ? 'disabled' : ''; ?>"><a href="{{$competition_paginate['path']}}?page={{$competition_paginate['current_page']-1}}"><i class="material-icons">navigate_before</i></a></li>
              <?php
              for ($i=1; $i <= $competition_paginate['last_page']; $i++) {
                $act = 'waves-effect';
                if($i == $competition_paginate['current_page']){
                  $act = 'active';

                }
              ?>
                <li class="{{$act}}"><a href="{{$competition_paginate['path']}}?page={{$i}}">{{$i}}</a></li>
              <?php
              }
              ?>
              <li class="<?= $competition_paginate['current_page'] == $competition_paginate['last_page'] ? 'disabled' : ''; ?>"><a href="{{$competition_paginate['path']}}?page={{$competition_paginate['current_page']+1}}"><i class="material-icons">navigate_next</i></a></li>
            </ul>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- prize list ends -->
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
<script type="text/javascript" src="{{asset('js/scripts/jquery.redirect.js')}}"></script>
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

$(document).ready(function(){
  $('.exportTOCSV').on('click', function(e){
    e.preventDefault();
    var result = $('select[name=result]').find(":selected").val();
    var search = $('input[name=Search]').val(); //alert(result);
    //$.get("{{route('competitions.getAllCompetitionInCSV')}}", { result: result }, function (data) {});
    $.redirect("{{route('competitions.getAllCompetitionInCSV')}}", { "result": result, "search": search }, "GET", "_blank"); 
  });
  });
  /*$('#page-competition').DataTable({
    "responsive": true,
    "lengthMenu": [
      [10, 25, 50, -1],
      [10, 25, 50, "All"]
    ]
  });*/
</script>
<style type="text/css">
  select[name="page-competition_length"]{
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