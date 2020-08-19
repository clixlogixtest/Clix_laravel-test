{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Report list')

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
<!-- users list start -->
<section class="users-list-wrapper section">
  
  <div class="users-list-table">
    <div class="card">
      <div class="card-content">
        <?php
            $prize = $log['0']['log_details'];
            //$prize = json_encode($prize);
            $prize = json_decode($prize, true);
            
          ?>
        
            <div class="row">
              <div class="col s12">
                <div class="row">

                  <!--Add File-->
                   <?php 
                     $file = $prize['file'];
                     $file = json_decode($file, true);
                     //print_r($prize); die();
                   ?>
                  <div class="row" style="display: flex;">
                    <?php 

                    for($i=0;$i<count($file);$i++) { 
                      
                        ?>
                        <div class='col-md-3' style='margin: 10px;'>
                        <img width="140" height="150" class="imageThumb img-responsive" src="{{@$file[$i]}}" title=""/>
                        </div>
                      <?php
                    }

                    ?>
                  </div>


                  <!--Prize Name-->
                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s7 input-field">
                      <input id="prize_name" name="prize_name" type="text" class="validate" value="<?php echo Request::old('prize_name') ? Request::old('prize_name') : $prize['prize_name']; ?>"
                        data-error=".errorTxt1" disabled="true">
                      
                      <label for="prize_name">Prize Name</label>
                    </div>
                  </div>
                  
                  <!--Cash Value and Currency-->
                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s7 input-field m3">
                      <input id="cash_value" name="cash_value" type="number" class="validate" value="<?php echo Request::old('cash_value') ? Request::old('cash_value') : $prize['cash_value']; ?>"
                        data-error=".errorTxt1" disabled="true">
                      
                      <label for="cash_value">Cash Value</label>
                    </div>

                    <div class="col s7 input-field m2">
                      <select id="currency" name="currency" class="validate" data-error=".errorTxt2" disabled="true">
                        <option selected="selected">GBP</option>
                      </select>
                      <label for="currency">Currency</label>
                    </div>
                  </div>
                  
                  <!--Category-->
                  <?php 
                    $selected = '';
                    if(Request::old('category')  == 'cat 01'){
                      $selected = 'selected="selected"';
                    }elseif($prize['prize_category']  == 'cat 01'){
                      $selected = 'selected="selected"';
                    }
                  ?>
                  <div class="row" style="padding: 0 1rem;">
                    <div class="col s7 input-field m5">
                      <select id="category" name="category" class="validate" data-error=".errorTxt2" disabled="true">
                        <option value="">None</option>
                        <option value="cat 01" <?php echo $selected; ?>>Cat 01</option>
                      </select>
                      <label for="category">Category</label>
                    </div>
                  </div>

                  <!--Description of the prize-->
                  <div class="row" style="padding: 0 1rem;" >
                    <div class="col s7 input-field">
                      <h2 for="description" style="color:#9e9e9e; font-size: 1rem; margin: 0rem 0 1.424rem 0;">Description of the prize</h2>
                      <textarea id="description" name="description" class="birthdate-picker" data-error=".errorTxt3" disabled="true"><?php echo Request::old('description') ? Request::old('description') : $prize['description']; ?></textarea>
                    </div>
                  </div>
                  
                  
                  <?php 
                    $selected = '';
                    if(Request::old('available')){
                      $selected = 'checked"';
                    }elseif($prize['available_to_win']){
                      $selected = 'checked';
                    }
                  ?>
                  <div class="col s12 input-field m6">
                    <label>
                      <input id="available" name="available" type="checkbox" class="validate" value="1" data-error=".errorTxt1" <?= $selected; ?> disabled="true"> <span>Available</span>
                    </label>
                  </div>

                </div>
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
@endsection