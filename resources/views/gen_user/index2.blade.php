@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("gen_user.users") }}} :: @parent
@stop

{{-- Content --}}
@section('content')
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">    
        <ul class="nav" id="side-menu"> 
          @include('admin_sidebar')
        </ul>
    </div>
</div>
<div id="page-wrapper">
    <div class="row">
		<div class="page-header">
			<h2> {{{ Lang::get("gen_user.users") }}}
				<div class="pull-right">
					<div class="pull-right">
			            <a href="{{{ URL::to('gen_user/create') }}}" class="btn btn-sm  btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> {{ Lang::get("gen_user.create_user") }}</a>
					</div>
				</div>
			</h2>
		</div>
		<table id="table" class="table table-striped table-hover">
			<thead>
				<tr>
					<th>{{{ Lang::get("gen_person.last_name") }}}</th>
					<th>{{{ Lang::get("gen_person.first_name") }}}</th>
					<th>{{{ Lang::get("gen_user.username") }}}</th>
					<th>{{{ Lang::get("gen_user.activate_user") }}}</th>
					<th>{{ Lang::get("admin.action") }}</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
</div>
@stop

{{-- Scripts --}}
@section('scripts')
<script type="text/javascript">
	var oTable;
	$(document).ready(function() {
		oTable = $('#table').dataTable({
			"sDom" : "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
			"sPaginationType" : "bootstrap",
			
			"bProcessing" : true,
			"bServerSide" : true,
			"sAjaxSource" : "{{ URL::to('gen_user/data/') }}",
			"fnDrawCallback" : function(oSettings) {
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
