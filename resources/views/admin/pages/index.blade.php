@extends('layouts.admin')

@section('title') {{ __('Page List') }} @endsection

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
				<h1 class="m-0">{{ __('Pages') }}</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
					<li class="breadcrumb-item active">{{ __('Page List') }}</li>
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
                <h3 class="card-title">{{ __('Page List') }}</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>S.No.</th>
							<th>Title</th>
							<th>Content</th>
							<th>Template</th>
							<th>Status</th>
							<th width="60px">Action</th>
						</tr>
					</thead>
					<tbody>
						@forelse($pages as $key => $page)
						<tr>
							<td>{{ $loop->iteration }}</td>
							<td>{{ $page->title }}</td>
							<td>{!! $page->content !!}</td>
							<td>{{ $page->template->name ?? '' }}</td>
							<td>{{ ucfirst($page->status) }}</td>
							<td>
								<a class="btn btn-sm btn-primary" href="{{ route('admin.pages.edit',$page) }}" title="Edit"><i class="fas fa-edit"></i></a>
							</td>
						@empty
							<tr>
								<td colspan="5">{{ __('No data found.') }}</td>
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