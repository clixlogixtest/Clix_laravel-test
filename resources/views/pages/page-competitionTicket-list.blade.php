{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title', "$prizeName Ticket Management")

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
<a href="{{route('competitions.addFreeTicket')}}" class="btn waves-effect waves-light mb-1 add-kanban-btn"><i class="material-icons left">add</i><span>Add Free Entry Ticket</span></a>


<!-- question list start -->
<section class="users-list-wrapper section">
    <div class="users-list-filter">
    <div class="card-panel">
      <div class="row">
        <form action="{{route('competitions.ticketList', $competition_id)}}" method="post">
          {{ csrf_field() }}
            {{ method_field('get') }}
          <div class="col s12 m6 l3">
            <label for="users-list-verified"> Result</label>
            <div class="input-field">
              <select class="form-control" name="result" id="users-list-verified">
                <option value="">All</option>
                <option value="Pass" <?= $result == 'Pass' ? 'selected' : ''; ?>>Pass</option>
                <option value="Fail" <?= $result == 'Fail' ? 'selected' : ''; ?>>Fail</option>
              </select>
            </div>
          </div>
          <!-- <div class="col s12 m6 l3">
            <label for="users-list-role">Role</label>
            <div class="input-field">
              <select class="form-control" id="users-list-role">
                <option value="">Any</option>
                <option value="User">User</option>
                <option value="Staff">Staff</option>
              </select>
            </div>
          </div>
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
            /*$ticketList = json_encode($ticketList);
            $ticketList = json_decode($ticketList, true);*/

            $ticketList = $ticketList;
            $ticketList = json_encode($ticketList);
            $ticketList = json_decode($ticketList, true);
            $ticket_paginate = $ticketList;
            //echo '<pre>'; print_r($ticketList); echo '</pre>';
            $ticketList = $ticketList['data'];
            if($ticketList){
            
            
            ?>
            <!-- <table id="multi-select" class="display table"> -->
              <table id="page-question" class="display">
              <thead>
                <tr>
                  <th>TID</th>
                  <th>Entry Date</th>
                  <th>Player Name</th>
                  <th>Result</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($ticketList as $ticket)

                <?php
                  $color = '';
                  if($ticket['answer_status'] == 'Pass'){
                    $color = 'blue';
                  }
                  if($ticket['answer_status'] == 'Fail'){
                    $color = 'red';
                    
                  }
                      ?>
                
                  
                  <tr>
                    <td>{{$ticket['ticket_id']}}</td>
                    <td>{{$ticket['created_at']}}</td>
                    <td><a href="{{route('users.editUser', $ticket['player_id'])}}#view">{{$ticket['first_name'].' '.$ticket['surname']}}</a></td>
                    <td><span class="chip {{$color}} lighten-5">
                        <span class="{{$color}}-text">{{$ticket['answer_status']}}</span>
                      </span></td>
                    <td><a href="{{route('competition.ticket.edit', $ticket['ticket_id'])}}#edit"><i class="material-icons">edit</i></a>
                    </td>
                  </tr>
                
                @endforeach
              </tbody>
            </table>
            <?php
            }else{
              echo "<p style='text-align: center;'>No data found!</p>";
            }
            if($ticket_paginate['last_page']>1){ ?>
            <ul class="pagination">
              <li class="<?= $ticket_paginate['current_page'] == 1 ? 'disabled' : ''; ?>"><a href="{{$ticket_paginate['path']}}?page={{$ticket_paginate['current_page']-1}}"><i class="material-icons">navigate_before</i></a></li>
              <?php
              for ($i=1; $i <= $ticket_paginate['last_page']; $i++) {
                $act = 'waves-effect';
                if($i == $ticket_paginate['current_page']){
                  $act = 'active';

                }
              ?>
                <li class="{{$act}}"><a href="{{$ticket_paginate['path']}}?page={{$i}}">{{$i}}</a></li>
              <?php
              }
              ?>
              <li class="<?= $ticket_paginate['current_page'] == $ticket_paginate['last_page'] ? 'disabled' : ''; ?>"><a href="{{$ticket_paginate['path']}}?page={{$ticket_paginate['current_page']+1}}"><i class="material-icons">navigate_next</i></a></li>
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
<script type="text/javascript" src="{{asset('js/scripts/jquery.redirect.js')}}"></script>
<!-- <script src="{{asset('js/scripts/app-email.js')}}"></script> -->
<script type="text/javascript">
$(document).ready(function(){
  $('.exportTOCSV').on('click', function(e){
    e.preventDefault();
    var result = $('select[name=result]').find(":selected").val();
    //var search = $('input[name=Search]').val(); //alert(result);
    //$.get("{{route('competitions.getAllCompetitionInCSV')}}", { result: result }, function (data) {});
    $.redirect("{{route('competitions.getAllTicketInCSV', $competition_id)}}", { "result": result }, "GET", "_blank"); 
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