{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Category Management')

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
<a href="{{route('category.create')}}" class="btn waves-effect waves-light mb-1 add-kanban-btn"><i class="material-icons left">add</i><span>Add Category</span></a>

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

<!-- Faqs list start -->
<section class="users-list-wrapper section">
  <div class="users-list-table">
    <div class="card">
      <div class="card-content">
  <!-- datatable start -->
        <div class="" id="player">
          <div class="responsive-table">
            
            <?php 
            /*$faqList = $faqList;
            $faqList = json_encode($faqList);
            $faqList = json_decode($faqList, true);*/
            $faqList = $faqList;
            $faqList = json_encode($faqList);
            $faqList = json_decode($faqList, true);
            $user_paginate = $faqList;
            //echo '<pre>'; print_r($prizeTotal); echo '</pre>';
            $faqList = $faqList['data'];
            if($faqList){
            ?>
            <!-- <table id="multi-select" class="display table"> -->
              <table id="page-faq" class="display">
              <thead>
                <tr>
                  <!-- <th></th> -->
                  <th>Category Name</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($faqList as $question)
                  
                  <tr>
                    <!-- <td></td> -->
                    <td><a href="{{route('categories.editCategories', $question['prize_category_id'])}}#view">{{$question['category_name']}}</a></td>
                    <td style="display: flex;"><a href="{{route('categories.editCategories', $question['prize_category_id'])}}#edit"><i class="material-icons">edit</i></a>

                      <form id="prizeDestroy{{$question['prize_category_id']}}" onsubmit="return confirm('Do you really want to delete this Category?');" method="post" action="{{route('category.destroy', $question['prize_category_id'])}}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                         <a  href="javascript:{}" data-id="prizeDestroy{{$question['prize_category_id']}}" id="prizeDestroyButton"><i class="material-icons">delete</i></a>
                      </form>
                    </td>
                  </tr>
                  
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
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Faqs list ends -->
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
  /*$('#page-faq').DataTable({
    "responsive": true,
    "lengthMenu": [
      [10, 25, 50, -1],
      [10, 25, 50, "All"]
    ]
  });*/
</script>
<style type="text/css">
  select[name="page-faq_length"]{
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