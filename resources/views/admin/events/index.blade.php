@extends('site.layouts.default') {{-- Web site Title --}} @section('title') Events :: @parent @stop {{-- Content --}} @section('content') @section('styles')
<style>
.panel-footer {
    color: inherit;
    background-color: #f5f5f5;
}

.huge {
    font-size: 30px;
}
</style>
@endsection
<link href="{{asset('assets/site/css/glyphicon.design.css')}}" rel="stylesheet">
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            @include('admin_sidebar')
        </ul>
    </div>
</div>
<div id="page-wrapper" style="min-height: 524px;">
    <div class="row">
        <div class="page-header">
        <br>
        <h2> Events
            <div class="pull-right">
                <div class="pull-right">
                    <a href="{{URL::to('admin/events/create')}}" class="btn btn-sm  btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> Create New Event</a>
                </div>
            </div>
        </h2>
        </div>
        <div id="table_wrapper" class="dataTables_wrapper form-horizontal no-footer">
            <table id="table" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>{{{ Lang::get("Date Created") }}}</th>
                        <th>{{{ Lang::get("Event Name") }}}</th>
                        <th>{{{ Lang::get("Description") }}}</th>
                        <th>{{{ Lang::get("Location") }}}</th>
                        <th>{{{ Lang::get("Active") }}}</th>
                        <th>{{ Lang::get("admin.action") }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
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
            "sAjaxSource" : "{{ URL::to('admin/events/data') }}",
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
