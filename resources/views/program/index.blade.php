@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("program.program_list") }}} :: @parent
@stop
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
				{{{ Lang::get("program.program_list") }}}
				<div class="pull-right" style="margin-right: 10px !important">
					<a href="{{{ URL::to('program/create') }}}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-plus-sign"></span>  {{ Lang::get('program.create_new_program') }}</a>
				</div>
			</h3>
		</div>
		<table id="table" class="table table-striped table-hover">
			<thead>
				<tr>
					<th> {{ Lang::get("program.program_name") }}</th>
					<th> Color</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>
@stop
    
{{-- Scripts --}}
@section('scripts')
	<script type="text/javascript">
		var oTable;
		$(document).ready(function() {
			oTable = $('#table').dataTable( {
				"sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
				"sPaginationType": "bootstrap",
				"bProcessing": true,
		        "bServerSide": true,
		        "sAjaxSource": "{{ URL::to('program/data/') }}",
		        "fnDrawCallback": function ( oSettings ) {
	     		}
			});
		});
	</script>
@stop