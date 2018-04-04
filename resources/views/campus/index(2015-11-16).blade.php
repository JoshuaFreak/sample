@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("building.building_list") }}} :: @parent
@stop
{{-- Content --}}
@section('content')
<div class="navbar-default sidebar" role="navigation">
	<div class="sidebar-nav navbar-collapse">    
	    <ul class="nav" id="side-menu"> 
	      @include('scheduler_sidebar')
	    </ul>
	</div>
</div>
<div id="page-wrapper">
    <div class="row">
		<div class="page-header">
			<h2>
				{{{ Lang::get("building.building_list") }}}
				<div class="pull-right">
					<a href="{{{ URL::to('building/create') }}}" class="btn btn-sm  btn-primary"><span class="glyphicon glyphicon-plus-sign"></span>  {{ Lang::get('building.create_new_building') }}</a>
				</div>
			</h2>
		</div>
		<table id="table" class="table table-striped table-hover">
			<thead>
				<tr>
					<th> {{ Lang::get("building.classification") }}</th>
					<th> {{ Lang::get("building.building_name") }}</th>
					<th>{{ Lang::get("form.action") }}</th>
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
		        "sAjaxSource": "{{ URL::to('building/data/') }}",
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
			var startPosition;
           var endPosition;
           $("#table tbody").sortable({
            cursor : "move",
                start : function(event, ui) {
                    startPosition = ui.item.prevAll().length + 1;
               },
               update : function(event, ui) {
                    endPosition = ui.item.prevAll().length + 1;
                    var navigationList = "";
                    $('#table #row').each(function(i) {
                        navigationList = navigationList + ',' + $(this).val();
                    });
                    $.getJSON("{{ URL::to('building/reorder') }}", {
                        list : navigationList
                    }, function(data) {
                    });
                }
            });
		});
	</script>
@stop