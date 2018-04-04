@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("registrar.registered_guardian") }}} :: @parent
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
            <h2> {{{ Lang::get("registrar.registered_guardian") }}}
                <div class="pull-right">
                    <div class="pull-right">
                        <a href="{{{ URL::to('registrar/register_guardian/create') }}}" class="btn btn-sm  btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> {{ Lang::get("registrar.register_guardian") }}</a>
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
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@stop
    
{{-- Scripts --}}
@section('scripts')
    <script type="text/javascript">
       $(":submit").closest("form").submit(function(){
           $(':submit').attr('disabled', 'disabled');
       });
        var oTable;
        $(document).ready(function() {
            oTable = $('#table').dataTable( {
                "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
                "sPaginationType": "bootstrap",
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "{{ URL::to('registrar/register_guardian/data/') }}",
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
                    $.getJSON("{{ URL::to('registrar/register_guardian/reorder') }}", {
                        list : navigationList
                    }, function(data) {
                    });
                }
            });
        });
    </script>
@stop
