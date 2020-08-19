{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Users Management')

{{-- vendors styles --}}
@section('vendor-style')
<!-- <link rel="stylesheet" type="text/css" href="{{asset('vendors/flag-icon/css/flag-icon.min.css')}}"> -->
<link rel="stylesheet" type="text/css" href="{{asset('vendors/data-tables/css/jquery.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css"
  href="{{asset('vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css')}}">
<!-- <link rel="stylesheet" type="text/css" href="{{asset('vendors/data-tables/css/select.dataTables.min.css')}}"> -->
@endsection

{{-- page styles --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-users.css')}}">
<!-- <link rel="stylesheet" type="text/css" href="{{asset('css/pages/data-tables.css')}}"> -->
<style type="text/css">
  .searchField .select-wrapper{
      margin-top: 0;
  }
</style>
@endsection

{{-- page content --}}
@section('content')

<!-- Add user button -->
<a href="{{route('users.create')}}" class="btn waves-effect waves-light mb-1 add-kanban-btn"><i class="material-icons left">add</i><span>Add User</span></a>

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





<!-- Player and Administrator list button -->
<div class="mb-2 row" style="padding: 0px 18px;display: flex;color: #692929;opacity: 1;background-color: #fff;height: 48px;">
  
    <a class="display-flex align-items-center active" href="{{route('users.playerList')}}" style="width: 10%;">
      <span style="color: #644ba1;">Player</span>
    </a>
 
    <a class="display-flex align-items-center" href="{{route('users.administratorList')}}" style="width: 15%;">
      <span style="color: #644ba1;">Administrator</span>
    </a>
</div>
<!-- <div class="divider mb-3"></div> -->

<section class="users-list-wrapper section">
  <div class="users-list-filter">
    <div class="card-panel">
      <div class="row">
        <form action="{{route('users.playerList')}}" method="post">
          {{ csrf_field() }}
            {{ method_field('get') }}
          <div class="col s12 m6 l3">
            <label for="users-list-verified">Search Field</label>
            <div class="input-field searchField">
               <select class="form-control" id="search_field" name="search_field" required>
                <option value="">Any</option>
                <option value="first_name" <?php echo $search_field == 'first_name' ? 'selected' : ''; ?>>First Name</option>
                <option value="surname" <?php echo $search_field == 'surname' ? 'selected' : ''; ?>>Surname</option>
                <option value="town" <?php echo $search_field == 'town' ? 'selected' : ''; ?>>Town</option>
                <option value="email" <?php echo $search_field == 'email' ? 'selected' : ''; ?>>Email Address</option>
              </select>
            </div>
          </div>
          <div class="col s12 m6 l3">
            <span for="users-list-role">Search</span>
            <div class="input-field">
              <input type="text" class="form-control" id="search" name="search" value="{{$search}}">
            </div>
            
          </div>
          <div class="col s12 m6 l3 display-flex align-items-center show-btn">
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
            /*$userTotal = $userTotal;
            $userTotal = json_encode($userTotal);
            $userTotal = json_decode($userTotal, true);*/

            $userTotal = $userTotal;
            $userTotal = json_encode($userTotal);
            $userTotal = json_decode($userTotal, true);
            $user_paginate = $userTotal;
            //echo '<pre>'; print_r($prizeTotal); echo '</pre>';
            $userTotal = $userTotal['data'];

            if($userTotal){
            ?>
            <!-- <table id="multi-select" class="display table"> -->
              <table id="page-player" class="display">
              <thead>
                <tr>
                  <!-- <th></th> -->
                  <th>Name</th>
                  <th>Email Address</th>
                  <th>Coins</th>
                  <!-- <th>Organisation</th> -->
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($userTotal as $user)
                  @if($user['role'] == 'player')
                  <tr>
                    <!-- <td></td> -->
                    <td><a href="{{route('users.editUser', $user['id'])}}#view">{{$user['first_name'].' '.$user['surname']}}</a></td>
                    <td>{{$user['email']}}</td>
                    <td>{{$user['total_coin']}}</td>
                    <!-- <td></td> -->
                    <td style="display: flex;"><a href="{{route('users.editUser', $user['id'])}}#edit"><i class="material-icons">edit</i></a><form id="prizeDestroy{{$user['id']}}" onsubmit="return confirm('Do you really want to delete user?');" method="post" action="{{route('users.destroy', $user['id'])}}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                         <a href="javascript:{}" data-id="prizeDestroy{{$user['id']}}" id="prizeDestroyButton"><i class="material-icons">delete</i></a>
                      </form></td>
                  </tr>
                  @endif
                @endforeach
              </tbody>
            </table>
            <?php
            }else{
               echo "<p style='text-align: center;'>No data found!</p>";
            } 

            if($user_paginate['last_page']>1){ ?>
            <ul class="pagination">
              <li class="<?= $user_paginate['current_page'] == 1 ? 'disabled' : ''; ?>"><a href="{{$user_paginate['path']}}?page={{$user_paginate['current_page']-1}}"><i class="material-icons">navigate_before</i></a></li>
              <?php
              for ($i=1; $i <= $user_paginate['last_page']; $i++) {
                $act = 'waves-effect';
                if($i == $user_paginate['current_page']){
                  $act = 'active';

                }
              ?>
                <li class="{{$act}}"><a href="{{$user_paginate['path']}}?page={{$i}}">{{$i}}</a></li>
              <?php
              }
              ?>
              <li class="<?= $user_paginate['current_page'] == $user_paginate['last_page'] ? 'disabled' : ''; ?>"><a href="{{$user_paginate['path']}}?page={{$user_paginate['current_page']+1}}"><i class="material-icons">navigate_next</i></a></li>
            </ul>
            <?php } ?>
            <a href="" class="btn waves-effect waves-light mb-1 add-kanban-btn exportTOCSV" style="margin-top: 30px;"><i class="material-icons left">file_download</i><span>Export to CSV</span></a>
          </div>
        </div>
        <!-- datatable ends -->
      </div>
    </div>
  </div>
</section>
<!-- users list ends -->
@endsection

{{-- vendor scripts --}}
@section('vendor-script')
<!-- <script src="{{asset('vendors/data-tables/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js')}}"></script> -->
<!-- <script src="{{asset('vendors/data-tables/js/dataTables.select.min.js')}}"></script> -->
@endsection

{{-- page script --}}
@section('page-script')
<script src="{{asset('js/scripts/page-users.js')}}"></script>
<script src="{{asset('js/scripts/data-tables.js')}}"></script>
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
    $.redirect("{{route('users.getAllUserInCSV')}}", { "result": "player" }, "GET", "_blank"); 
  });
  });
  // Page Length Option Table

 /* $('#page-player').DataTable({
    "responsive": true,
    "lengthMenu": [
      [10, 25, 50, -1],
      [10, 25, 50, "All"]
    ]
  });
  // Page Length Option Table

  $('#page-administrator').DataTable({
    "responsive": true,
    "lengthMenu": [
      [10, 25, 50, -1],
      [10, 25, 50, "All"]
    ]
  });*/
</script>
<style type="text/css">
  select[name="page-player_length"]{
    display: none !important;
  }
  select[name="page-administrator_length"]{
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