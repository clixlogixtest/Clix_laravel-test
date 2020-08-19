{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Drawn Competition')

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

<!-- prize list start -->
<section class="users-list-wrapper section">
  <div class="users-list-table">
    <div class="card">
      <div class="card-content">
  <!-- datatable start -->
        <div class="" id="player">
          <div class="responsive-table">
            
            <?php 
            /*$CompetitionList = $CompetitionList;
            $CompetitionList = json_encode($CompetitionList);
            $CompetitionList = json_decode($CompetitionList, true);*/

            $CompetitionList = $CompetitionList;
            $CompetitionList = json_encode($CompetitionList);
            $CompetitionList = json_decode($CompetitionList, true);
            $prize_paginate = $CompetitionList;
            //echo '<pre>'; print_r($prizeTotal); echo '</pre>';
            $CompetitionList = $CompetitionList['data'];
            if($CompetitionList){
            ?>
            <!-- <table id="multi-select" class="display table"> -->
              <table id="page-DrawnCompetitiion" class="display">
              <thead>
                <tr>
                  <!-- <th></th> -->
                  <th>UID</th>
                  <th>Prize</th>
                  <th>Winner</th>
                  <th>Phone</th>
                  <th>Delivery Address</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                
                <?php
                foreach($CompetitionList as $competition){
                  
                  ?>
                  <tr>
                    <td>{{$competition['competition_id']}}</td>
                    <td>{{$competition['prize_name']}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                      <a href="{{route('competitions.edit', $competition['competition_id'])}}"><i class="material-icons">Confirm</i></a>
                      
                      
                    </td>
                  </tr>
                  
                <?php } ?>
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
<script type="text/javascript">
  /*$('#page-DrawnCompetitiion').DataTable({
    "responsive": true,
    "lengthMenu": [
      [10, 25, 50, -1],
      [10, 25, 50, "All"]
    ]
  });*/
</script>
<style type="text/css">
  select[name="page-DrawnCompetitiion_length"]{
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