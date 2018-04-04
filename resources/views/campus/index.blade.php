@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("campus.campus_list") }}} :: @parent
@stop
{{-- Content --}}
@section('content')

 <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
           
        
<!-- Side Bar -->
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            
            @include('scheduler_sidebar')
            
	    </ul>
    </div>
</div>
<div id="page-wrapper">
    <div class="row">
<!-- Content Header -->
        <div class="page-header"><br>
            <h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left">
				{{{ Lang::get("campus.campus_list") }}}
				<div class="pull-right" style="margin-right: 10px !important">
					<a href="{{{ URL::to('campus/create') }}}" class="btn btn-sm  btn-primary"><span class="glyphicon glyphicon-plus-sign"></span>  {{ Lang::get('campus.create_new_campus') }}</a>
				</div>
            </h3>
        </div>
        <br/>
        <br/>

        
<!-- Table Section -->
    <table id="BuildingFilter" class="table table-striped table-hover">
        <thead>
            <tr>
					<th> {{ Lang::get("campus.campus_name") }}</th>
					<th>{{ Lang::get("form.action") }}</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    {{-- Scripts --}}
    @section('scripts')
        <script type="text/javascript">
          $(":submit").closest("form").submit(function(){
                $(':submit').attr('disabled', 'disabled');
            });
           // $(document).ready(function() {
           //  oTable = $('#BuildingFilter').dataTable( {
           //      "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
           //      "sPaginationType": "bootstrap",
           //      "stateSave": true,
           //      "bProcessing": true,
           //      "bServerSide": true,
           //      "sAjaxSource": "{{ URL::to('employee/employee_list/data/') }}",
           //      "fnDrawCallback": function ( oSettings ) {
                    
           //      },
           //      "fnServerParams": function(aoData){
           //              aoData.push(
           //                  { "name":"classification_id", "value": $("#classification_id").val() },
           //                  { "name":"course_coc_coe_id", "value": $("#course_coc_coe_id").val() }
           //              );
           //          },
           //  });

            $(document).ready(function() {

                BuildingFilter = $('#BuildingFilter').dataTable( {
                    "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
                    "sPaginationType": "bootstrap",
                    "bProcessing": true,
                    "bServerSide": true,
                    "bStateSave": true,
                    "sAjaxSource": "{{ URL::to('campus/data/') }}",
                    "fnDrawCallback": function ( oSettings ) {
                    },

                    "fnServerParams": function(aoData){
                        aoData.push(
                            { "name":"classification_id", "value": $("#classification_id").val() }
                        );
                    }
                });

              

                $("#classification_id").change(function(){
                   BuildingFilter.fnDraw();
                });

                // $("#course_coc_coe_id").change(function(){
                //    BuildingFilter.fnDraw();
                // });


            
           /* var oTable = $('#table').DataTable();
                $('#table tbody').on('click', 'td.details-control', function () {
                    var tr = $(this).closest('tr');
                    var row = oTable.row(tr);

                    if (row.child.isShown()) {
                        // This row is already open - close it
                        $('div.slider', row.child()).slideUp( function () {
                        row.child.hide();
                        tr.removeClass('shown');
                    });
                    } else {
                        // Open this row
                        row.child(format(row.data())).show();
                        tr.addClass('shown');
                        $('div.slider', row.child()).slideDown();
                    }
                });  */
                                
            });
        </script>
        
        <script>


        /*$(document).ready(function(){
            myTable = $("#myTable").dataTable({
                "sAjaxSource":"{{ URL::to('employee/classification') }}",
                bServerSide:true

            });

        });*/
          /*  $("#classification_id").change(function(){
                oTable = $('#myTable').dataTable();
                var classification_id = $("#classification_id").val();
                
                $.ajax({
                    url: "{{ URL::to('employee/classification') }}",
                    data: {
                                'classification_id': classification_id,
                            },
                    async:false,
                    }).done(function(data) {

                            $.each( data, function( key, classification ) {
                                alert(classification.employee_id);
                            });
                            
                    });
                // oTable.fnDraw(true);
                // oTable.fnClearTable(0);

                // oTable.fnReloadAjax('employee/employee_list/data?id=');
            
                    $('#classification_id').change(function(){
                          alert("im here!");
                          oTable.fnFilter($(this).val());
                          oTable.fnDraw();
                    });
            

            });
            // $(document).ready(function() {
            //     var oTable = $('#table').DataTable();
                 
            //     // Event listener to the two range filtering inputs to redraw on input
            //     $('#classification_id').change( function() {
            //         oTable.fnDraw();
            //     } );
            // } );*/


            
            // function BuildingFilterRedraw()
            // {

            //     $('#BuildingFilter').dataTable( {
            //         "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
            //         "sPaginationType": "bootstrap",
            //         "bProcessing": true,
            //         "bServerSide": true,
            //         "bStateSave": true,
            //         "sAjaxSource": "{{ URL::to('employee/employee_list/data/') }}",
            //         "fnDrawCallback": function ( oSettings ) {
            //         },

            //         "fnServerParams": function(aoData){
            //             aoData.push(
            //                 { "name":"classification_id", "value": $("#classification_id").val() },
            //                 { "name":"course_coc_coe_id", "value": $("#course_coc_coe_id").val() }
            //             );
            //         },
            //          columns: [
            //             {
            //                 "className":      'details-control',
            //                 "orderable":      false,
            //                 "data":           null,
            //                 "defaultContent": ''
            //             },
            //             {data: 'employee_no', name: 'employee_no'},
            //             {data: 'first_name', name: 'first_name'},
            //             {data: 'is_active', name: 'is_active'},
            //         ],
            //         order: [[1, 'asc']]
    
            //     });

            // }


        </script>
        @stop
        </div>
    </div>
</div>
@stop