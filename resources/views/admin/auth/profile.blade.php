@extends('layouts.admin')

@section('title') {{ __('Profile') }} @endsection

@section('css')
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{ asset('plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
		<div class="col-sm-6">
			<h1 class="m-0">{{ __('User') }}</h1>
		</div>
		<div class="col-sm-6">
			<ol class="breadcrumb float-sm-right">
				<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
				<li class="breadcrumb-item active">{{ __('Profile') }}</li>
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
		        	<h3 class="card-title">{{ __('Profile') }}</h3>
		      	</div>
		      	<!-- /.card-header -->
				
		      	<form action="{{ route('admin.profile') }}" method="POST" enctype="multipart/form-data">
			    	@csrf
			      	<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
						        	<label>Email</label>
			                    	<input type="email" class="form-control" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" placeholder="Email">
						        </div>
						        
						        <div class="form-group">
						        	<label>First Name</label>
			                    	<input type="text" class="form-control" name="first_name" id="first_name" value="{{ old('first_name', $user_meta['first_name']) }}" placeholder="First Name">
						        </div>

						        <div class="form-group">
						        	<label>Last Name</label>
			                    	<input type="text" class="form-control" name="last_name" id="last_name" value="{{ old('last_name', $user_meta['last_name']) }}" placeholder="Last Name">
						        </div>

						        <div class="form-group">
						        	<label>Job Title</label>
			                    	<input type="text" class="form-control" name="job_title" id="job_title" value="{{ old('job_title', $user_meta['job_title']) }}" placeholder="Job Title">
						        </div>

						        <div class="form-group">
						        	<label>Nationality</label>
			                    	<select name="nationality" id="nationality" class="form-control" style="width: 100%;">
			                    		<option value="">Select Nationality</option>
			                    		@foreach($countries as $key => $country)
										<option value="{{ $country->nationality }}" {{ (old('nationality',$user_meta['nationality']) == $country->nationality) ? 'selected' : '' }}>{{ $country->nationality }}</option>
										@endforeach
									</select>
						        </div>

								<div class="form-group">
									<label>Date of Birth</label>
									<div class="input-group date">
										<input type="text" name="date_of_birth" class="form-control pull-right" id="datepicker" value="{{ old('date_of_birth', $user_meta['date_of_birth']) }}">
										<div class="input-group-text input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
									</div>
								</div>

								<div class="form-group">
						        	<label>Town of Birth</label>
			                    	<input type="text" class="form-control" name="town_of_birth" id="town_of_birth" value="{{ old('town_of_birth', $user_meta['town_of_birth']) }}" placeholder="Town of Birth">
						        </div>

						        <div class="form-group">
						        	<label>Gender</label>
			                    	<div class="form-check">
										<input class="form-check-input" type="radio" name="gender" value="male" {{ (old('gender',$user_meta['gender']) == 'male') ? 'checked' : '' }}>
										<label class="form-check-label mr-4">Male</label>

										<input class="form-check-input" type="radio" name="gender" value="female" {{ (old('gender',$user_meta['gender']) == 'female') ? 'checked' : '' }}>
										<label class="form-check-label">Female</label>
			                        </div>
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
<!-- bootstrap datepicker -->
<script src="{{ asset('plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script>
	$(function () {
  		bsCustomFileInput.init();

  		//Date picker
	    $('#datepicker').datepicker({
	      autoclose: true,
	      todayHighlight: true,
	      format:'yyyy-mm-dd'
	    });
	});
	
</script>
@endsection