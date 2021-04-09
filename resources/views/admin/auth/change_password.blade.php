@extends('layouts.admin')

@section('title') {{ __('Change Password') }} @endsection

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
				<li class="breadcrumb-item active">{{ __('Change Password') }}</li>
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
		        	<h3 class="card-title">{{ __('Change Password') }}</h3>
		      	</div>
		      	<!-- /.card-header -->

		      	<form action="{{ route('admin.change_password') }}" method="POST" enctype="multipart/form-data">
			    	@csrf
			      	<div class="card-body">
						<div class="row">
							<div class="col-md-12">
						        <div class="form-group">
						        	<label>Old Password</label>
			                    	<input type="password" class="form-control" name="old_password" id="old_password" value="{{ old('old_password') }}" placeholder="Enter Old Password">
						        </div>

						        <div class="form-group">
						        	<label>New Password</label>
			                    	<input type="password" class="form-control" name="password" id="password" value="{{ old('password') }}" placeholder="Enter New Password">
						        </div>
								
			                  	<div class="form-group">
						        	<label>Confirm Password</label>
			                    	<input type="password" class="form-control" name="password_confirmation" id="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="Enter Confirm Password">
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