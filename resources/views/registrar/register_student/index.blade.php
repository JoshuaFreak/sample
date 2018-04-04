@extends('site/layouts/default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("registrar.registered_list") }}} :: @parent
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
        <div class="col-md-12 page-header">
            <h2 class="col-md-8">{{ Lang::get("registrar.registered_student")}}</h2>
        </div>

        <ul class="nav nav-tabs">
                <li class="active"><a href="#all_data_tab" data-toggle="tab">All Students <i class="sf"></i></a></li>
          @foreach($classification_list as $classification)
              @if($classification->id == '0')
              @else 
                <li><a href="#tab_classification_{{{$classification->id}}}" data-toggle="tab">{{{$classification->classification_name}}}<i class="sf"></i></a></li>
            @endif
          @endforeach
        </ul>

        <form id="accountForm" method="post" class="form-horizontal">
            <input type="hidden" name="_token" id="_token" value="{{{ csrf_token() }}}" />
            <div class="tab-content">
                    <div class="tab-pane active" id="all_data_tab"><br>
                        <table id="table" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th> {{ Lang::get("student.school_no") }}</th>
                                    <th> {{ Lang::get("registrar.id_number") }}</th>
                                    <th> {{ Lang::get("registrar.name") }}</th>
                                    <th> {{ Lang::get("student.status") }}</th>
                                    <th>{{ Lang::get("form.action") }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                @foreach($classification_list as $classification)
                    @if($classification->classification_name == 'Pre-School')
                    <div class="tab-pane" id="tab_classification_{{{$classification->id}}}"><br>
                    @else
                        <div class="tab-pane" id="tab_classification_{{{$classification->id}}}"><br>
                    @endif
                            <table id="table_classification_{{{$classification->id}}}" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th> {{ Lang::get("student.school_no") }}</th>
                                        <th> {{ Lang::get("registrar.id_number") }}</th>
                                        <th> {{ Lang::get("registrar.name") }}</th>
                                        <th> {{ Lang::get("student.status") }}</th>
                                        <th>{{ Lang::get("form.action") }}</th>
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
   
    $(":submit").closest("form").submit(function(){
           $(':submit').attr('disabled', 'disabled');
       });
    
        var oTable;
        $(document).ready(function() {
            @foreach($classification_list as $classification)
            oTable = $('#table_classification_{{{$classification->id}}}').DataTable( {
                "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
                "sPaginationType": "bootstrap",
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "{{ URL::to('registrar/data?classification_name=') }}{{$classification->classification_name}}",
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
           //  var startPosition;
           // var endPosition;

           // $("#table_classification_{{{$classification->id}}} tbody").sortable({
           
           //  cursor : "move",
           //      start : function(event, ui) {
           //          startPosition = ui.item.prevAll().length + 1;
           //     },
           //     update : function(event, ui) {
           //          endPosition = ui.item.prevAll().length + 1;
           //          var navigationList = "";
           //          $('#table_classification_{{{$classification->id}}} #row').each(function(i) {
           //              navigationList = navigationList + ',' + $(this).val();
           //          });
           //          $.getJSON("{{ URL::to('registrar/reorder') }}", {
           //              list : navigationList
           //          }, function(data) {
           //          });
           //      }
           //  });

           @endforeach
        });

            /* for all*/
            table = $('#table').DataTable( {
                "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
                "sPaginationType": "bootstrap",

                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "{{ URL::to('registrar/dataAll/') }}",
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
    
          //   $(document).ready(function() {
          //     $('#table').DataTable( {
          //         responsive: {
          //             details: {
          //                 type: 'column',
          //                 target: 'tr'
          //             }
          //         },
          //         columnDefs: [ {
          //             className: 'control',
          //             orderable: false,
          //             targets:   0
          //         } ],
          //         order: [ 1, 'asc' ]
          //     } );
          // } );
</script>
@stop
