@extends('layouts.admin')

@section('title') {{ __('Package List') }} @endsection

@section('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection

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
				<li class="breadcrumb-item active">{{ __('Package List') }}</li>
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
		<div class="col-12">

			@include('includes.message')

			<div class="card">
              <div class="card-header">
                <h3 class="card-title">{{ __('Package List') }}</h3>

                <div class="card-tools">
		          <a href="{{ route('admin.packages.create') }}" class="btn btn-sm btn-primary">
		            <i class="fas fa-plus"></i>
		            {{ __('Add Package') }}
		          </a>
		        </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>S.No.</th>
							<th>Image</th>
							<th>Title</th>
							<th>Description</th>
							<th>Price</th>
							<th>Period</th>
							<th>Status</th>
							<th width="60px">Action</th>
						</tr>
					</thead>
					<tbody>
						@forelse($packages as $key => $package)
						<tr>
							<td>{{ $loop->iteration }}</td>
							<td>
								@if(!empty($package->image))
					        	<img src="{{ asset('uploads/packages/'.$package->image) }}" alt="" style="width: 100px;height: 80px;"/>
					        	@endif
							</td>
							<td>{{ $package->title }}</td>
							<td>{{ $package->description }}</td>
							<td>{{ $package->price }}</td>
							<td>{{ $package->period_value .' '. ucfirst($package->period_type) }}</td>
							<td>{{ ucfirst($package->status) }}</td>
							<td>
								<a class="btn btn-sm btn-primary" href="{{ route('admin.packages.edit',$package) }}" title="Edit"><i class="fas fa-edit"></i></a>

								<form action="{{ route('admin.packages.destroy',$package) }}" method="POST" style="display:inline-block;">
				                    @csrf
				                    @method('DELETE')
				                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete ?');" title="Delete"><i class="fas fa-trash"></i></button>
				                </form>
							</td>
						@empty
							<tr>
								<td colspan="8">{{ __('No data found.') }}</td>
							</tr>
						</tr>
						@endforelse
					</tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
		</div>
	</div>
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection

@section('js')
<!-- DataTables  & Plugins -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

<script>
  	$(function () {
	    $("#example1").DataTable({
	      "responsive": true, 
	      "lengthChange": true, 
	      "autoWidth": false,
	    });

	    $('#example2').DataTable({
	      "paging": true,
	      "lengthChange": false,
	      "searching": false,
	      "ordering": true,
	      "info": true,
	      "autoWidth": false,
	      "responsive": true,
	    });
  	});
</script>
@endsection