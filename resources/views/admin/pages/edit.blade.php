@extends('layouts.admin')

@section('title') {{ __('Edit Page') }} @endsection

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
			<h1 class="m-0">{{ __('Pages') }}</h1>
		</div>
		<div class="col-sm-6">
			<ol class="breadcrumb float-sm-right">
				<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
				<li class="breadcrumb-item active">{{ __('Edit Page') }}</li>
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
		        	<h3 class="card-title">{{ __('Edit Page') }}</h3>

			        <div class="card-tools">
			          <a href="{{ route('admin.pages.index') }}" class="btn btn-sm btn-primary">
			            <i class="fas fa-arrow-left"></i>
			            {{ __('Back') }}
			          </a>
			        </div>
		      	</div>
		      	<!-- /.card-header -->
		      	<form action="{{ route('admin.pages.update',$page->id) }}" method="POST" enctype="multipart/form-data">
			    	@csrf
			    	@method('PUT')
			      	<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Templates</label>
									<select name="template_id" id="template_id" class="form-control" style="width: 100%;">
										<option value="">Select Template</option>
										@foreach($templates as $key => $template)
										<option value="{{ $template->id }}" {{ (old('template_id',$page->template_id) == $template->id) ? 'selected' : '' }}>{{ $template->name }}</option>
										@endforeach
									</select>
				                </div>

						        <div class="form-group">
						        	<label>Title</label>
			                    	<input type="text" class="form-control" name="title" id="title" value="{{ old('title', $page->title) }}" placeholder="Enter Title">
						        </div>

						        <div class="form-group">
					                <label>Content</label>
				                	<textarea class="form-control" name="content" id="summernote">{{ old('content', $page->content) }}</textarea>
			                  	</div>

			                  	@if($page->slug=='about-us')

			                  	<div class="form-group">
				                    <label for="exampleInputFile">Image</label>
				                    <div class="input-group">
										<div class="custom-file">
											<input type="file" class="custom-file-input" name="image" id="image">
											<label class="custom-file-label" for="image">Choose Image</label>
										</div>
				                    </div>
				                    @if(!empty($page->image))
				                	<br>
				                	<img src="{{ asset('uploads/pages/'.$page->image) }}" alt="" style="width: 100px;height: 80px;">
				                	@endif
			                  	</div>

			                  	@endif

			                  	<div class="form-group">
									<label>Status</label>
									<select name="status" id="status" class="form-control" style="width: 100%;">
										<option value="active" {{ (old('status',$page->status) == 'active') ? 'selected' : '' }}>Active</option>
										<option value="inactive" {{ (old('status',$page->status) == 'inactive') ? 'selected' : '' }}>Inactive</option>
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