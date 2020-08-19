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
<ul class="tabs mb-2 row">
  <li class="tab">
    <a class="display-flex align-items-center active" id="account-tab" href="#player">
      <span>Player</span>
    </a>
  </li>
  <li class="tab">
    <a class="display-flex align-items-center" id="information-tab" href="#administrator">
      <span>Administrator</span>
    </a>
  </li>
</ul>
<!-- <div class="divider mb-3"></div> -->

<section class="users-list-wrapper section">
  <!-- <div class="users-list-filter">
    <div class="card-panel">
      <div class="row">
        <form>
          <div class="col s12 m6 l3">
            <label for="users-list-verified">Verified</label>
            <div class="input-field">
              <select class="form-control" id="users-list-verified">
                <option value="">Any</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </div>
          <div class="col s12 m6 l3">
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
          </div>
          <div class="col s12 m6 l3 display-flex align-items-center show-btn">
            <button type="submit" class="btn btn-block indigo waves-effect waves-light">Show</button>
          </div>
        </form>
      </div>
    </div>
  </div> -->

  

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
                    <td>{{$user['first_name'].' '.$user['surname']}}</td>
                    <td>{{$user['email']}}</td>
                    <td>{{$user['total_coin']}}</td>
                    <!-- <td></td> -->
                    <td><a href="{{route('users.edit', $user['id'])}}"><i class="material-icons">edit</i></a></td>
                  </tr>
                  @endif
                @endforeach
              </tbody>
            </table>
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
          </div>
        </div>
        <div class="" id="administrator">
          <div class="responsive-table">
            @if($errors->any())
                    
              <div class="error">{{$errors->first()}}</div>
            @endif

            @if(session('message'))
              <div class="success">{{session('message')}}</div>              
            @endif
            <?php 
            $userTotal = $userTotal;
            $userTotal = json_encode($userTotal);
            $userTotal = json_decode($userTotal, true);

            
            
            ?>
            <!-- <table id="multi-select" class="display table"> -->
              <table id="page-administrator" class="display">
              <thead>
                <tr>
                  <!-- <th></th> -->
                  <th>Name</th>
                  <th>Email Address</th>
                  <th>Organisation</th>
                  <th>Role</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($userTotal as $user)
                @if($user['role'] != 'player')
                <?php 
                     $role = $user['role'];
            $role = explode(',', $role);
            $roles = '';
            if(in_array("competition_administrator", $role)){

                if($roles){
                  $roles .= ', '; 
                }
                
                $roles .= 'Competition Admin'; 
                
            }

            if( in_array("user_administrator", $role)){
              
              if($roles){
                $roles .= ', '; 
              }
               
              $roles .= 'User Admin';  
                
            }

            if(in_array("organisation_administrator", $role)){
                if($roles){
                  $roles .= ', '; 
                }  
                $roles .= 'Organisation Admin';   
                
            }

            if(in_array("prize_administrator", $role)){
                
                if($roles){
                  $roles .= ', '; 
                }
                $roles .= 'Prize Admin';  
                
            }

            if(in_array("player", $role)){

                if($roles){
                  $roles .= ', '; 
                }
                $roles .= 'Player';
                
            }
                ?>
                <tr>
                  <!-- <td></td> -->
                  <td>{{$user['first_name'].' '.$user['surname']}}</td>
                  <td>{{$user['email']}}</td>
                  <td></td>
                  <td>{{$roles}}</td>
                  <td style="display: flex;"><a href="{{route('users.edit', $user['id'])}}#information"><i class="material-icons">edit</i></a><form id="prizeDestroy{{$user['id']}}" onsubmit="return confirm('Do you really want to delete user?');" method="post" action="{{route('users.destroy', $user['id'])}}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                         <a href="javascript:{}" data-id="prizeDestroy{{$user['id']}}" id="prizeDestroyButton"><i class="material-icons">delete</i></a>
                      </form></td>
                </tr>
                @endif
                @endforeach
                
              </tbody>
            </table>
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
<script src="{{asset('vendors/data-tables/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js')}}"></script>
<!-- <script src="{{asset('vendors/data-tables/js/dataTables.select.min.js')}}"></script> -->
@endsection

{{-- page script --}}
@section('page-script')
<script src="{{asset('js/scripts/page-users.js')}}"></script>
<script src="{{asset('js/scripts/data-tables.js')}}"></script>
<script src="{{asset('js/scripts/ui-alerts.js')}}"></script>
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
  // Page Length Option Table

  $('#page-player').DataTable({
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
  });
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