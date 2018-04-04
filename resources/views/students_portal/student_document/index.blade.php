@extends('site.layouts.default_portal')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("students_portal.student_document") }}} :: @parent
@stop
{{-- Content --}}
@section('content')
<div class="navbar-default sidebar" role="navigation">
  <div class="sidebar-nav navbar-collapse">    
      <ul class="nav" id="side-menu"> 
        @include('student_sidebar')
      </ul>
  </div>
</div>
<div id="page-wrapper">
  <div class="row">
    <div class="page-header"><br>
      <h2>{{{ Lang::get("students_portal.document_management") }}}</h2>
    </div>
    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
    <div class="col-md-12">
      <div class="col-md-5">
        <div class="form-group {{{ $errors->has('classification_level_id') ? 'has-error' : '' }}}">
          <label  for="classification_level_id">Select {{ Lang::get('classification_level.classification_level') }}</label>
          <select class="form-control" name="classification_level_id" id="classification_level_id">
            <option type="text" name="0" id="" value=""></option>
            @foreach($classification_level_list as $classification_level)                                
              <option type="text" name="classification_level_id" id="classification_level_id" value="{{{$classification_level->classification_level_id}}}" data-student="{{{$classification_level->student_id}}}">{{$classification_level->level}}</option>                                                           
            @endforeach
          </select>
        </div>
      </div>
    </div>
    <br><br><br><br>
    <table id="table" class="table table-striped table-hover">
      <thead>
        <tr>
          <th>{{ Lang::get("students_portal.student") }}</th>
          <th> {{ Lang::get("students_portal.supervisor") }}</th>
          <th> {{ Lang::get("students_portal.document_management") }}</th>
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
      function format ( d ) {
              // `d` is the original data object for the row
       var data_html = '<div class="slider">'+
                 '<div class="form-group">'+
                    '<div class="col-md-4" id="folder_'+d.id+'"></div>'+
                    '<div class="col-md-12" id="student_document_'+d.student_id+'"></div>'+
                 '</div>';

      data_html = data_html + '</div>';

      return data_html;
          }


      oTable = $('#table').dataTable( {
        "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r><'row'<'col-md-6'i><'col-md-6'p>>",
        "sPaginationType": "bootstrap",
        "bSort" : false,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "{{ URL::to('students_portal/student_document/StudentDocumentdata?') }}"+"student_id="+$("#classification_level_id").find(':selected').data('student'),
        "fnDrawCallback": function ( oSettings ) {
          },
        "fnServerParams": function(aoData){
            aoData.push(
                { "name":"student_id", "value": $("#classification_level_id").find(':selected').data('student') },
                { "name":"classification_level_id", "value": $("#classification_level_id").val() }
            );
        },
        columns: [
                {data: 'student_last_name', name: 'student_last_name'},
                {data: 'teacher_last_name', name: 'teacher_last_name'},
                {data: 'document_management', name: 'document_management'}
            ],
            order: [[1, 'asc']],
      });

      // Add event listener for opening and closing details
        var documentChild = $('#table').DataTable();

          // Add event listener for opening and closing details
          $('#table tbody').on('click', 'p.details-control', function () {

                var tr = $(this).closest('tr');
                var row = documentChild.row(tr);
                var data = row.data();

              if (row.child.isShown()) {

                      // This row is already open - close it
                      $('div.slider', row.child()).slideUp( function () {
                      row.child.hide();
                      tr.removeClass('shown');

                  });
              } 
              else 
              {
                  // Open this row
                  row.child(format(row.data()),'no-padding').show();
                  tr.addClass('shown');
                  $('div.slider', row.child()).slideDown();

                          $.ajax({
                          url: "../../students_portal/student_document/CreateFolderdataJson",
                          data: {
                                  'folder_id': 1,
                                  'student_id': data.student_id,
                                },
                          async:false,
                              }).done(function(folder_list) {
                                if(folder_list.length > 0)
                                {

                                $.each( folder_list, function( key, folder ) {
                                        $("#folder_"+data.student_id).append('<div class="col-md-12"><b>'+folder["folder_name"]+'</b></div>');
                                    });
                              }
                          });

                          $.ajax({
                          url: "../../students_portal/student_document/StudentDocumentJson",
                          data: {
                                  'student_id': data.student_id,
                                },
                          async:false,
                              }).done(function(folder_list) {
                                if(folder_list.length > 0)
                                {

                                $.each( folder_list, function( key, folder ) {
                                        $("#student_document_"+data.student_id).append('<div class="col-md-2" id="student_file_'+folder.id+'"><b>'+folder["folder_name"]+'</b></div>');
                                        $.ajax({
                                        url: "../../students_portal/student_document/fileDataJson",
                                        data: {
                                                'student_id': data.student_id,
                                                'folder_id': folder.id,
                                              },
                                        async:false,
                                            }).done(function(file_list) {
                                              if(file_list.length > 0)
                                              {

                                              $.each( file_list, function( key, file ) {
                                                      $("#student_file_"+folder.id).append('<div class="col-md-12"><a href="../../students_portal/'+file["id"]+'/downloadFile" target="_blank">'+file["file_name"]+'</a></div>');
                                                  });
                                            }
                                        });
                                    });
                              }
                          });
              

              } 
        });

      $("#classification_level_id").change(function(){
         oTable.fnDraw();
      });
  });
</script>
@stop