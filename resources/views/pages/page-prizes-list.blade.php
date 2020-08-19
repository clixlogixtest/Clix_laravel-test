{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Prize Management')

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
<a href="{{route('prizes.create')}}" class="btn waves-effect waves-light mb-1 add-kanban-btn"><i class="material-icons left">add</i><span>Add Prize</span></a>

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

<!-- users list start -->
<section class="users-list-wrapper section">
  <div class="users-list-filter">
    <div class="card-panel">
      <div class="row">
        <form action="{{route('prizes.index')}}" method="post">
          {{ csrf_field() }}
            {{ method_field('get') }}
          <div class="col s12 m6 l3" style="margin-top: -2px;">
            
            <label for="users-list-verified"> Available to win</label>
            <div class="input-field">
              <select class="form-control" name="available_to_win" id="users-list-verified">
                <option value="">Any</option>
                <option value="1" <?= @$available_to_win == 1 ? 'selected' : ''; ?>>Yes</option>
                <option value="0" <?= @$available_to_win == 0 ? 'selected' : ''; ?>>No</option>
              </select>
            </div>
          </div>
          <div class="col s12 m6 l3">
            <label for="users-list-verified"> Category </label>
            <div class="input-field">
              <select class="form-control" name="category" id="users-list-verified">
                <option value="">Any</option>
                @php
                  $prize_categories = json_encode($prize_categories);
                  $prize_categories = json_decode($prize_categories, true);
                  foreach($prize_categories as $key=>$val){
                @endphp
                <option value="{{$val['prize_category_id']}}" <?= @$category == $val['prize_category_id'] ? 'selected' : ''; ?>>{{$val['category_name']}}</option>
                @php
                  }
                @endphp              
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
            $prizeTotal = $prizeTotal;
            $prizeTotal = json_encode($prizeTotal);
            $prizeTotal = json_decode($prizeTotal, true);
            $prize_paginate = $prizeTotal;
            //echo '<pre>'; print_r($prizeTotal); echo '</pre>';
            $prizeTotal = $prizeTotal['data'];
            if($prizeTotal){
            
            ?>
            <!-- <table id="multi-select" class="display table"> -->
              <table id="page-prize" class="display">
              <thead>
                <tr>
                  <!-- <th></th> -->
                  <th>Product</th>
                  <th>Category</th>
                  <th>Value</th>
                  <th>Available</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($prizeTotal as $user)

                


                  
                  <tr>
                    <!-- <td></td> -->
                    <td><a href="{{route('prize.editPrize', $user['prize_id'])}}#view">{{$user['prize_name']}}</a></td>
                    <td>{{@$user['category_name']}}</td>
                    <td>{{$user['cash_value']}} {{$user['currency']}}</td>
                    @if($user['available_to_win'] == 1)
                    <td>Available</td>
                    @else
                    <td>Not Available</td>
                    @endif
                    <td style="display: flex;"><a href="{{route('prize.editPrize', $user['prize_id'])}}#edit"><i class="material-icons">edit</i></a>

                      <form id="prizeDestroy{{$user['prize_id']}}" onsubmit="return confirm('Do you really want to delete prize?');" method="post" action="{{route('prizes.destroy', $user['prize_id'])}}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                         <a href="javascript:{}" data-id="prizeDestroy{{$user['prize_id']}}" id="prizeDestroyButton"><i class="material-icons">delete</i></a>
                      </form>
                      <!-- <a href="{{route('prizes.destroy', $user['prize_id'])}}"><i class="material-icons">delete</i></a> -->
                    </td>
                  </tr>
                  
                @endforeach
              </tbody>
            </table>
            <?php 
            }else{
              echo "<p style='text-align: center;'>No data found!</p>";
            }
            if($prize_paginate['last_page']>1){ ?>
            <ul class="pagination">
              <li class="<?= $prize_paginate['current_page'] == 1 ? 'disabled' : ''; ?>"><a href="{{$prize_paginate['path']}}?page={{$prize_paginate['current_page']-1}}"><i class="material-icons">navigate_before</i></a></li>
              <?php
              for ($i=1; $i <= $prize_paginate['last_page']; $i++) {
                $act = 'waves-effect';
                if($i == $prize_paginate['current_page']){
                  $act = 'active';

                }
              ?>
                <li class="{{$act}}"><a href="{{$prize_paginate['path']}}?page={{$i}}">{{$i}}</a></li>
              <?php
              }
              ?>
              <li class="<?= $prize_paginate['current_page'] == $prize_paginate['last_page'] ? 'disabled' : ''; ?>"><a href="{{$prize_paginate['path']}}?page={{$prize_paginate['current_page']+1}}"><i class="material-icons">navigate_next</i></a></li>
            </ul>
          <?php } ?>
          </div>
        </div>
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
@endsection

{{-- page script --}}
@section('page-script')
<script src="{{asset('js/scripts/page-users.js')}}"></script>
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
  /*$('#page-prize').DataTable({
    "responsive": true,
    "lengthMenu": [
      [10, 25, 50, -1],
      [10, 25, 50, "All"]
    ]
  });*/
</script>
<style type="text/css">
  select[name="page-prize_length"]{
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