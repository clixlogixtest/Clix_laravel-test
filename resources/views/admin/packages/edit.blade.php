@extends('layouts.admin')

@section('title') {{ __('Edit Package') }} @endsection

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
		<div class="col-sm-6">
			<h1 class="m-0">{{ __('Packages') }}</h1>
		</div>
		<div class="col-sm-6">
			<ol class="breadcrumb float-sm-right">
				<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
				<li class="breadcrumb-item active">{{ __('Edit Package') }}</li>
			</ol>
		</div>
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
  	<div class="row">
  		<div class="col-md-6">

  			@include('includes.message')

  			<div class="card card-default">
		      	<div class="card-header">
		        	<h3 class="card-title">{{ __('Edit Package') }}</h3>

			        <div class="card-tools">
			          <a href="{{ route('admin.packages.index') }}" class="btn btn-sm btn-primary">
			            <i class="fas fa-arrow-left"></i>
			            {{ __('Back') }}
			          </a>
			        </div>
		      	</div>
		      	<!-- /.card-header -->
		      	<form action="{{ route('admin.packages.update',$package->id) }}" method="POST" enctype="multipart/form-data">
			    	@csrf
			    	@method('PUT')
			      	<div class="card-body">
						<div class="row">
							<div class="col-md-12">
						        <div class="form-group">
						        	<label>Title</label>
			                    	<input type="text" class="form-control" name="title" id="title" value="{{ old('title', $package->title) }}" placeholder="Enter Title">
						        </div>

						        <div class="form-group">
					                <label>Description</label>
					                <textarea class="form-control" name="description" id="description" rows="2" placeholder="Enter Description ...">{{ old('description', $package->description) }}</textarea>
			                  	</div>

			                  	<div class="form-group">
					                <label>Content</label>
					                @if($package->package_details()->count() > 0) 
						                @foreach($package->package_details as $key => $package_detail)
						                	<input type="hidden" class="form-control" name="package_detail_id[]"  value="{{ $package_detail->id }}">

							                <textarea class="form-control mb-2" name="content[]" id="content_{{ $key }}" rows="4" placeholder="Enter Content ...">{{ old('content.'.$key, $package_detail->content) }}</textarea>
						                @endforeach 

					                @else
					                	@for ($i = 0; $i < 3; $i++)
							                <textarea class="form-control mb-2" name="content[]" id="content_{{ $i }}" rows="4" placeholder="Enter Content ...">{{ old('content.'.$i) }}</textarea>
						                @endfor
					                @endif
			                  	</div>

			                  	<div class="form-group">
						        	<label>Price</label>
			                    	<input type="text" class="form-control" name="price" id="price" value="{{ old('price', $package->price) }}" placeholder="Enter Price">
						        </div>

						        <div class="form-group">
				                    <label for="exampleInputFile">Image</label>
				                    <div class="input-group">
										<div class="custom-file">
											<input type="file" class="custom-file-input" name="image" id="image">
											<label class="custom-file-label" for="image">Choose Image</label>
										</div>
				                    </div>
				                    @if(!empty($package->image))
				                	<br>
				                	<img src="{{ asset('uploads/packages/'.$package->image) }}" alt="" style="width: 100px;height: 80px;">
				                	@endif
			                  	</div>

			                  	<div class="form-group">
									<label>Period Type</label>
									<select name="period_type" id="period_type" class="form-control" style="width: 100%;">
										<option value="year" {{ (old('period_type',$package->period_type) == 'year') ? 'selected' : '' }}>Yearly</option>
										<option value="month" {{ (old('period_type',$package->period_type) == 'month') ? 'selected' : '' }}>Monthly</option>
										<option value="day" {{ (old('period_type',$package->period_type) == 'day') ? 'selected' : '' }}>Daily</option>
										<option value="hour" {{ (old('period_type',$package->period_type) == 'hour') ? 'selected' : '' }}>Hourly</option>
									</select>
				                </div>

			                  	<div class="form-group">
						        	<label>Period Value</label>
			                    	<input type="number" class="form-control" name="period_value" id="period_value" value="{{ old('period_value', $package->period_value) }}" placeholder="Enter Period Value" min="0">
						        </div>

			                  	<div class="form-group">
									<label>Status</label>
									<select name="status" id="status" class="form-control" style="width: 100%;">
										<option value="active" {{ (old('status',$package->status) == 'active') ? 'selected' : '' }}>Active</option>
										<option value="inactive" {{ (old('status',$package->status) == 'inactive') ? 'selected' : '' }}>Inactive</option>
									</select>
				                </div>

					      	</div>
						</div>
					</div>
					<!-- /.card-body -->
					<div class="card-footer">
	                  <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
	                </div>
				</form>
			</div>
  		</div>
  	</div>
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection

@section('js')
<!-- bs-custom-file-input -->
<script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script>
	$(function () {
  		bsCustomFileInput.init();
	});
</script>
@endsection