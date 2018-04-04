@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("gen_person.users") }}} :: @parent
@stop

{{-- Content --}}
@section('content')
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">    
        <ul class="nav" id="side-menu"> 
          @include('hrms_sidebar')
        </ul>
    </div>
</div>
<div id="page-wrapper">
    <div class="row">
        <div class="page-header"><br>
            <h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left"> {{{ Lang::get("gen_person.user_account") }}}
                <div class="pull-right">
                    <!-- <div class="pull-right">
                        <a href="{{{ URL::to('gen_user/create') }}}" class="btn btn-sm  btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> {{ Lang::get("gen_person.create_user") }}</a>
                    </div> -->
                </div>
            </h2>
        </div>

        <ul class="nav nav-tabs">
        @foreach($gen_role_list as $gen_role)
            @if($gen_role->name == 'Teacher')
                <li class="active"><a href="#tab_gen_role_{{{$gen_role->id}}}" data-toggle="tab">{{{$gen_role->name}}}<i class="sf"></i></a></li>
            @else
                <li><a href="#tab_gen_role_{{{$gen_role->id}}}" data-toggle="tab">{{{$gen_role->name}}}<i class="sf"></i></a></li>
            @endif
        @endforeach
        </ul>

        <form id="accountForm" method="post" class="form-horizontal">
            <input type="hidden" name="_token" id="_token" value="{{{ csrf_token() }}}" />
            <div class="tab-content">
                @foreach($gen_role_list as $gen_role)
                    @if($gen_role->name == 'Teacher')
                        <div class="tab-pane active" id="tab_gen_role_{{{$gen_role->id}}}"><br>
                    @else
                        <div class="tab-pane" id="tab_gen_role_{{{$gen_role->id}}}"><br>
                    @endif
                        <table id="table_gen_role_{{{$gen_role->id}}}" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>{{{ Lang::get("gen_person.last_name") }}}</th>
                                    <th>{{{ Lang::get("gen_person.first_name") }}}</th>
                                    <th>{{{ Lang::get("gen_person.username") }}}</th>
                                    <th>{{{ Lang::get("gen_person.password") }}}</th>
                                    <th>{{{ Lang::get("gen_person.activate_user") }}}</th>
                                    <th>{{ Lang::get("admin.action") }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    
                @endforeach
            </div>
        </form>
    
    </div>
</div>

@stop
{{-- Scripts --}}
@section('scripts')
<script type="text/javascript">
        var oTable;
        $(document).ready(function() {
            @foreach($gen_role_list as $gen_role)
            oTable = $('#table_gen_role_{{{$gen_role->id}}}').dataTable( {
                "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
                "sPaginationType": "bootstrap",
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "{{ URL::to('gen_user/data?name=') }}{{$gen_role->name}}",
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

           // $("#table_gen_role_{{{$gen_role->id}}} tbody").sortable({
           
           //  cursor : "move",
           //      start : function(event, ui) {
           //          startPosition = ui.item.prevAll().length + 1;
           //     },
           //     update : function(event, ui) {
           //          endPosition = ui.item.prevAll().length + 1;
           //          var navigationList = "";
           //          $('#table_gen_role_{{{$gen_role->id}}} #row').each(function(i) {
           //              navigationList = navigationList + ',' + $(this).val();
           //          });
           //          $.getJSON("{{ URL::to('gen_user/reorder') }}", {
           //              list : navigationList
           //          }, function(data) {
           //          });
           //      }
           //  });

           @endforeach
        });
</script>
@stop