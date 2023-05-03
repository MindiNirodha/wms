@extends("la.layouts.app")

@section("contentheader_title", "Ministries/State Ministries")
@section("contentheader_description","")
@section("section", "Reports")
@section("sub_section", "")
@section("htmlheader_title", "Reports")

@section("headerElems")
@la_access("Reports", "create")
	<!-- <button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal">Add Report</button> -->
@endla_access
@endsection

@section("main-content")

<?php if(isset($param) && $param=='rep'){echo '<h3>Replied- Ministries/State Ministries</h3>';}else{echo '<h3>Not Replied - Ministries/State Ministries</h3>';}?>
<div class="box-body">
	<table id="example1" class="table table-bordered">
	<thead>
	<tr class="success">
		<th>Ministry/State Ministry</th>
		<th>Contact Person</th>
		<th>Designation</th>
		<th>Phone Number</th>
		<th>Status</th>
		<th>Count(Data)</th>
	</tr>
	</thead>
	<tbody>	
	<?php if(sizeof($data)>0){?>
	<?php foreach ($data as $key => $value) { ?>
	<tr>
		<td>{{$value->dept}}</td>
		<td>{{$value->name}}</td>
		<td>{{$value->designation}}</td>
		<td>{{$value->mobile}}</td>
		<td>{{$value->st}}</td>
		<td>{{$value->tc}}</td>
	</tr>
	<?php }}else{?>
	<tr><td colspan="4" align="center">No Data to Display</td></tr>	
	<?php } ?>	
	</tbody>
	</table>
</div>

@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script>
$(function () {
	var table = $("#example1").DataTable({"pageLength": 100});
    table.order( [ 4, 'desc' ] ).draw();
})
</script>
@endpush
