@extends('site.layouts.default')

{{-- Title --}}
@section('title')
Percentage Level
@endsection

{{-- Content --}}
@section('content')
<div class="navbar-default sidebar" role="navigation">
	<div class="sidebar-nav navbar-collapse">    
	    <ul class="nav" id="side-menu"> 
	      @include('registrar_sidebar')
	    </ul>
	</div>
</div>
<div id="page-wrapper">
   <div class="row">
		<div class="page-header"><br>
			<h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left">
				Percentage Level
			<!-- 	<div class="pull-right">
					<a href="{{{ URL::to('percentage_level/create') }}}" class="btn btn-sm  btn-primary"><span class="glyphicon glyphicon-plus-sign"></span>  New Percentage Level</a>
				</div> -->
				@foreach($percentage_level_list as $percentage_level)
					<img src="{{ $percentage_level -> thumbnail }}"> 
				@endforeach
			</h3>
		</div>
		<table id="percentageLevelTable" class="table table-striped table-hover">
			<thead>
				<tr>
					<th>Level</th>
					<th>Code</th>
					<th colspan="2">Percentage</th>
					<th>Old Level</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		var percentage_level;
		percentage_level = $('#percentageLevelTable').dataTable( {
			"sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
			"sPaginationType": "bootstrap",
			"bProcessing": true,
			"bServerSide": true,
			"bStateSave": true,
			"sAjaxSource": "{{ URL::to('percentage_level/data') }}",
			"fnDrawCallback": function ( oSettings ) {
			}
      });
	});
</script>
@endsection