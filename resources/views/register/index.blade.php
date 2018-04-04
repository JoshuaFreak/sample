@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("register_lang.register_list") }}} :: @parent
@stop
{{-- Content --}}
@section('content')
<div class="navbar-default sidebar" role="navigation">
	<div class="sidebar-nav navbar-collapse">    
	    <ul class="nav" id="side-menu"> 
	      @include('enrollment_sidebar')
	    </ul>
	</div>
</div>
<div id="page-wrapper">
    <div class="row">
		<div class="page-header"><br>
			<h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left">
				{{{ Lang::get("register_lang.register_list") }}}
				<div class="pull-right" style="margin-right: 10px !important">
					<a href="{{{ URL::to('register/create') }}}" class="btn btn-sm  btn-primary"><span class="glyphicon glyphicon-plus-sign"></span>  {{ Lang::get('register_lang.register_person') }}</a>
				</div>
			</h3>
		</div>
		<table id="table" class="table table-striped table-hover">
			<thead>
				<tr>
					<th> {{ Lang::get("register_lang.nationality_id") }}</th>
					<th> {{ Lang::get("register_lang.name") }}</th>
					<th> {{ Lang::get("register_lang.english_name") }}</th>
					<!-- <th>{{ Lang::get("form.action") }}</th> -->	
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
		        "sAjaxSource": "{{ URL::to('register/data') }}",
		        "fnDrawCallback": function ( oSettings ) {
	           		$(".iframe").colorbox({
                        iframe : true,
                        width : "80%",
                        height : "80%",
                        onClosed : function() {
                            window.location.reload();
                        }
                    });
	     		}
			});
		
		});
	</script>
@stop