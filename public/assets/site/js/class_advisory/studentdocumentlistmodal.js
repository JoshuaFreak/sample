


        /* Formatting function for row details - modify as you need */
        function format ( d ) {
                // `d` is the original data object for the row
         var data_html = '<div class="slider">'+
                   '<div class="form-group">'+
                      '<div class="col-md-4" id="folder_'+d.id+'"></div>'+
                      '<div class="col-md-12" id="student_document_'+d.id+'"></div>'+
                   '</div>';

        data_html = data_html + '</div>';

        return data_html;
            }

        var oTable;
                var count =1 ;
         $(document).ready(function() {

        if(count == 1){
        count++;

            $('#studentdocumentlistmodal').on('show.bs.modal', function(e) { 

                var button =  $(e.relatedTarget)
                var ClassificationId = button.data('classification_id')
                var ClassificationLevelId = button.data('classification_level_id')
                var TermId = button.data('term_id')
                var Level = button.data('level')
                var SectionName = button.data('section_name')
                var TermName = button.data('term_name')
                var ClassificationLevelId = button.data('classification_level_id')

                var modal = $(this)
                $('.student_document_list_modal').text('Student List : ' + Level+' '+SectionName+' - '+TermName)
                $('#student_document_list_classification_id').val(ClassificationId)
                $('#student_document_list_classification_level_id').val(ClassificationLevelId)
                $('#student_document_list_term_id').val(TermId)


                
                    oTable.fnDraw();
                
               
                
            });

          oTable = $('#studentdocumentlisttable').dataTable({
          "sDom" : "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
          "sPaginationType" : "bootstrap",
          "bProcessing" : true,
          "bServerSide" : true,
          "sAjaxSource" : "../../teachers_portal/class_advisory/student_document_list_data",
                "fnDrawCallback": function ( oSettings ) {
                },
                "fnServerParams": function(aoData){
                        aoData.push(
                            { "name":"classification_id", "value": $("#student_document_list_classification_id").val() },
                            { "name":"classification_level_id", "value": $("#student_document_list_classification_level_id").val() },
                            { "name":"term_id", "value": $("#student_document_list_term_id").val() }
                        );
                    },
            columns: [
                        {data: 'student_no', name: 'student_no'},
                        {data: 'last_name', name: 'last_name'},
                        {data: 'document_management', name: 'document_management'},
                ],

                order: [[1, 'asc']]

          });

            // Add event listener for opening and closing details
            var subjectChild = $('#studentdocumentlisttable').DataTable();

                // Add event listener for opening and closing details

                $('#studentdocumentlisttable tbody').on('click', 'p.details-control', function () {


               
                    var tr = $(this).closest('tr');
                    var row = subjectChild.row(tr);
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
                          url: "../../teachers_portal/class_advisory/CreateFolderdataJson",
                          data: {
                                  'folder_id': 1,
                                  'student_id': data.id,
                                },
                          async:false,
                              }).done(function(folder_list) {
                                if(folder_list.length > 0)
                                {

                                $.each( folder_list, function( key, folder ) {
                                        $("#folder_"+data.id).append('<div class="col-md-12"><b>'+folder["folder_name"]+'</b></div>');
                                    });
                              }
                          });

                          $.ajax({
                          url: "../../teachers_portal/class_advisory/StudentDocumentJson",
                          data: {
                                  'student_id': data.id,
                                },
                          async:false,
                              }).done(function(folder_list) {
                                if(folder_list.length > 0)
                                {

                                $.each( folder_list, function( key, folder ) {
                                      $("#student_document_"+data.id).append('<div class="col-md-3" id="student_file_'+folder.id+'"><b>'+folder["folder_name"]+'</b>'
                                           +'</div>');
                                        $.ajax({
                                        url: "../../teachers_portal/class_advisory/fileDataJson",
                                        data: {
                                                'student_id': data.id,
                                                'folder_id': folder.id,
                                              },
                                        async:false,
                                          }).done(function(file_list) {
                                            if(file_list.length > 0)
                                            {

                                            $.each( file_list, function( key, file ) {
                                                    $("#student_file_"+folder.id).append('<div class="col-md-12">'
                                                          +'<div class="col-md-8 " id="scroll"><a href="../../teachers_portal/'+file["id"]+'/downloadFile" target="_blank">'+file["file_name"]+'</a></div>'
                                                          +'<div class="col-sm-4"><div class="remove_btn" data-popout="true" data-title="Delete this file?" onConfirm="sample()" singleton="true" data-toggle="confirmation" type="button" id="'+folder.id+'"><span class="glyphicon glyphicon-remove-circle"></span></div>'
                                                        +'</div>');
                                                    
                                                    $("[data-toggle=confirmation]").confirmation({container:"body",btnOkClass:"btn btn-sm btn-success",btnCancelClass:"btn btn-sm btn-danger",
                                                            onConfirm:function(event, element) {
                                                             var id = $(this).data('id');

                                                                $.ajax({
                                                                    url:"../../teachers_portal/class_advisory/removeFile",
                                                                    type:'post',
                                                                    data:{  
                                                                            'id' : file.id,
                                                                            '_token': $('input[name=_token]').val(),
                                                                        },
                                                                    dataType: "json",
                                                                    async:false,
                                                                    success: function (data) 
                                                                    {
                                                                        swal("Successfully deleted!");
                                                                        location.reload();
                                                                    } 
                                                                });
                                                            }

                                                    });
                                                });
                                          }
                                      });
                                    });
                              }
                          });
                    }
                });
            }
        });
