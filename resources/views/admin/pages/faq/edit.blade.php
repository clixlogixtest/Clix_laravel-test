@extends('layouts.admin')

@section('title') {{ __('Edit FAQ') }} @endsection

@section('css')
<!-- summernote -->
<link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
@endsection

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
		<div class="col-sm-6">
			<h1 class="m-0">{{ __('FAQs') }}</h1>
		</div>
		<div class="col-sm-6">
			<ol class="breadcrumb float-sm-right">
				<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
				<li class="breadcrumb-item active">{{ __('Edit FAQ') }}</li>
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
  		<div class="col-md-12">

  			@include('includes.message')

  			<div class="card card-default">
		      	<div class="card-header">
		        	<h3 class="card-title">{{ __('Edit FAQ') }}</h3>

			        <div class="card-tools">
			          <a href="{{ route('admin.faqs.index') }}" class="btn btn-sm btn-primary">
			            <i class="fas fa-arrow-left"></i>
			            {{ __('Back') }}
			          </a>
			        </div>
		      	</div>
		      	<!-- /.card-header -->
		      	<form action="{{ route('admin.faqs.update',$faq->id) }}" method="POST" enctype="multipart/form-data">
			    	@csrf
			    	@method('PUT')
			      	<div class="card-body">
						<div class="row">
							<div class="col-md-12">
						        <div class="form-group">
						        	<label>Title</label>
			                    	<input type="text" class="form-control" name="title" id="title" value="{{ old('title', $faq->title) }}" placeholder="Enter Title">
						        </div>

						        <div class="form-group">
					                <label>Content</label>
				                	<textarea class="form-control" name="content" id="summernote">{{ old('content', $faq->content) }}</textarea>
			                  	</div>

			                  	<div class="form-group">
									<label>Status</label>
									<select name="status" id="status" class="form-control" style="width: 100%;">
										<option value="active" {{ (old('status',$faq->status) == 'active') ? 'selected' : '' }}>Active</option>
										<option value="inactive" {{ (old('status',$faq->status) == 'inactive') ? 'selected' : '' }}>Inactive</option>
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
<!-- Summernote -->
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
<script>
	$(function () {
  		bsCustomFileInput.init();
  		// Summernote
    	$('#summernote').summernote({
    		placeholder: 'Enter Content ...',
    		height: 300,
    	});
	});
</script>
@endsection