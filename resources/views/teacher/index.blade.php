@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("teacher.teacher_detail") }}} :: @parent
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
			<h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left">
				Teacher List
				<div class="pull-right" style="margin-right: 10px !important">
				<!-- 	<a href="{{{ URL::to('teacher/create') }}}" class="btn btn-sm  btn-primary"><span class="glyphicon glyphicon-plus-sign"></span>  {{ Lang::get('teacher_detail.create_new_teacher') }}</a> -->
				</div>
			</h3>
		</div>
	<div class="col-md-8">
      <div class="form-group {{{ $errors->has('employee_id') ? 'has-error' : '' }}}">
        <label class="col-md-4 control-label" for="teacher_name">Search Teacher's Name</label>
        <input type="hidden" name="employee_id" id="employee_id" value="0" />
        <div class="col-md-8">
            <input class="typeahead form-control" type="text" name="teacher_name" id="teacher_name" value="" />
          {!! $errors->first('employee_id', '<label class="control-label" for="teacher_name">:message</label>')!!}

        </div>
      </div>
    </div><br><br><br><br>
		<table id="TeacherFilter" class="table table-striped table-hover">
      <thead>
        <tr>
          <th> </th>
          <th> {{ Lang::get("teacher.teacher") }}</th>
          <th> {{ Lang::get("employee.nickname") }}</th>
          <th> {{ Lang::get("employee.contact_no") }}</th>
          <th> {{ Lang::get("employee.birthdate") }}</th>
          <th> {{ Lang::get("employee.address") }}</th>
          <th> {{ Lang::get("employee.civil_status") }}</th>
          <th> {{ Lang::get("employee.position") }}</th>
          <th> {{ Lang::get("employee.employment_status") }}</th>
          <th> {{ Lang::get("employee.department") }}</th>
          <th> Action</th>
          
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

            /********
                RMD 2015-03-07
                START OF teacher_name ->  typeahead
            *************************************************************************/
                var teacher_list = new Bloodhound({
                        datumTokenizer: function (datum) {
                            //return Bloodhound.tokenizers.whitespace(datum.teacher_name);
                               var tokens = [];
                                //the available string is 'name' in your datum
                                var stringSize = datum.teacher_name.length;
                                //multiple combinations for every available size
                                //(eg. dog = d, o, g, do, og, dog)
                                for (var size = 1; size <= stringSize; size++){          
                                  for (var i = 0; i+size<= stringSize; i++){
                                      tokens.push(datum.teacher_name.substr(i, size));
                                  }
                                }

                                return tokens;    


                        },
                        queryTokenizer: Bloodhound.tokenizers.whitespace,
                        limit: 10,
                       
                        remote:{
                          url:'{{{ URL::to("teacher/dataJson?query=%QUERY") }}}',
                          filter: function (teacher_list) {
                                      // alert('this is an alert script from create');
                                  // console.log(teacher_list); //debugging
                                    // Map the remote source JSON array to a JavaScript object array
                                    return $.map(teacher_list, function (teacher) {
                                        console.log(teacher); //debugging
                                        return {
                                            teacher_name: teacher.first_name + ', '+teacher.middle_name + ' ' + teacher.last_name,
                                            employee_id: teacher.employee_id,
                                            id: teacher.teacher_id
                                        };
                                    });
                             }
                        }

                });

                teacher_list.initialize();
                 console.log(teacher_list);

               // $('#teacher_typeahead .typeahead').typeahead(null, {
                  $('#teacher_name').typeahead(
                    {
                      hint: true,
                      highlight: true,
                      minLength: 1
                    }, 
                    {
                        teacher_name: 'teacher_list',
                         displayKey: 'teacher_name',
                        source: teacher_list.ttAdapter()
                        

                    }

                    ).bind("typeahead:selected", function(obj, teacher, teacher_name) {
                        console.log(teacher);
  
                       $("#id").val(teacher.id);
                       $("#employee_id").val(teacher.employee_id);
                       $("#teacher_name").val(teacher.teacher_name);

                      //reload teacher  entries
                      // oTable.fnClearTable(0);
                      oTable.fnDraw();

                      //oTable.fnReloadAjax('teacher/data?employee_id='+teacher.employee_id);

                    });

                   /********
                END OF teacher_name ->  typeahead
            *************************************************************************/



       /* Formatting function for row details - modify as you need */
    // function format ( d ) {

    //     var data_html = '<div class="slider">'+
    //                '<div class="form-group">'+
    //                   '<div class="col-md-3" id="default_program_container_'+d.id+'"><b>Teacher Default Program</b></div>'+
    //                   '<div class="col-md-3" id="teacher_program__container_'+d.id+'"><b>Teacher Programs</b></div>'+
    //                '</div>';

    //     data_html = data_html + '</div>';



    //     return data_html;

    // }

    var oTable;
  $(document).ready(function() {
    
      oTable = $('#TeacherFilter').dataTable({
      "sDom" : "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
      "sPaginationType" : "bootstrap",
      "bProcessing" : true,
      "bServerSide" : true,
      "sAjaxSource" : "{{ URL::to('teacher/data') }}",
            "fnDrawCallback": function ( oSettings ) {
            },
            "fnServerParams": function(aoData){
                    aoData.push(
                        { "name":"employee_id", "value": $("#employee_id").val() }
                    );
                },
        columns: [
                // {
                //     "className":      'details-control',
                //     "orderable":      false,
                //     "data":           null,
                //     "defaultContent": ''
                // },

                {data: 'img', name: 'img'},
                {data: 'last_name', name: 'last_name'},
                {data: 'nickname', name: 'nickname'},
                {data: 'contact_no', name: 'contact_no'},
                {data: 'birthdate', name: 'birthdate'},
                {data: 'address', name: 'address'},
                {data: 'civil_status_name', name: 'civil_status_name'},                
                {data: 'position_name', name: 'position_name'},
                {data: 'employment_status_name', name: 'employment_status_name'},
                {data: 'department_name', name: 'department_name'},
                {data: 'action', name: 'action'},
            ],

            order: [[1, 'asc']]

      });
      // Add event listener for opening and closing details
        // var cTable = $('#TeacherFilter').DataTable();

          // Add event listener for opening and closing details
          // $('#TeacherFilter tbody').on('click', 'td.details-control', function () {
          //     var tr = $(this).closest('tr');

          // //    alert(JSON.stringify(tr));
          //     var row = cTable.row(tr);

          //  //  alert(JSON.stringify(row));
          //    var data = row.data();

          //   // alert(data["id"]);


        // if ( row.child.isShown() ) {   
        //           // This row is already open - close it
        //        $('div.slider', row.child()).slideUp( function () {
        //           row.child.hide();
        //           tr.removeClass('shown');
        //           } );
        //       } 
        //       else {
        //           // Open this row
        //           row.child( format(data), 'no-padding' ).show();
        //           tr.addClass('shown');


        //             $.ajax({
        //                 url: "{{ URL::to('teacher/defaultProgramDataJson') }}",
        //                 type: 'GET',
        //                 data: {
        //                         'teacher_id': data.id,
        //                       },
        //                 async:false,
        //                     }).done(function(teacher_list) {
        //                       // if(teacher_list.length > 0)
        //                       // {
        //                       // //iterate list here
        //                       // //alert(JSON.stringify(teacher_list));
        //                       // $.each($.parseJSON(teacher_list), function(key,value){
        //                       //     alert(value);
        //                       // });
        //                       // alert(teacher_list);
        //                       $.each( teacher_list, function( key, program ) {
        //                           $("#default_program_container_"+data.id).append('<div class="col-md-12">'+program+'</div>');
        //                       });
        //                     // }
                
        //             });


        //         $.ajax({
        //         url: "{{ URL::to('teacher/programdataJson') }}",
        //         data: {
        //               'teacher_id': data.id,
        //             },
        //         async:false,
        //         }).done(function(teacher_list) {
        //             if(teacher_list.length > 0)
        //             {

        //               $.each( teacher_list, function( key, program ) {
                    
        //                     $("#teacher_program__container_"+data.id).append('<div class="col-md-12">'+program["program_name"]+'</div>');
        //                 });
        //             }
                
        //         });

        //           $('div.slider', row.child()).slideDown();
        //       }

           

        //   });


        //     $("#filter_data").change(function(){
        //        TeacherFilter.fnDraw();
        //     });

  
    });
  </script>
@stop