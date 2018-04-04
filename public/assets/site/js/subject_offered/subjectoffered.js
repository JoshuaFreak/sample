        /* Formatting function for row details - modify as you need */
        function format ( d ) {
                // `d` is the original data object for the row
         var data_html = '<div class="slider">'+
                   '<div class="form-group">'+
                      '<div class="col-md-4" id="folder_'+d.id+'"></div>'+
                      '<div class="col-md-12" id="subject_material_'+d.id+'"></div>'+
                   '</div>';

        data_html = data_html + '</div>';

        return data_html;
            }

        var oTable;
         $(document).ready(function() {
          oTable = $('#SubjectOfferedTable').dataTable({
          "sDom" : "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
          "sPaginationType" : "bootstrap",
          "bProcessing" : true,
          "bServerSide" : true,
          "sAjaxSource" : "subject_offered/data",
                "fnDrawCallback": function ( oSettings ) {
                },
                "fnServerParams": function(aoData){
                        aoData.push(
                            { "name":"classification_id", "value": $("#classification_id").val() }
                        );
                    },
            columns: [
                        // {data: 'program_name', name: 'program_name'},
                        {data: 'term_name', name: 'term_name'},
                        {data: 'code', name: 'code'},
                        {data: 'name', name: 'name'},
                        // {data: 'document_management', name: 'document_management'},
                        {data: 'actions', name: 'actions'},
                ],

                order: [[1, 'asc']]

          });
            


            // Add event listener for opening and closing details
            var subjectChild = $('#SubjectOfferedTable').DataTable();

                // Add event listener for opening and closing details
                $('#SubjectOfferedTable tbody').on('click', 'p.details-control', function () {
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
                          url: "subject_offered/CreateFolderdataJson",
                          data: {
                                  'folder_id': 1,
                                  'subject_offered_id': data.id,
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
                          url: "subject_offered/SubjectMaterialJson",
                          data: {
                                  'subject_offered_id': data.id,
                                },
                          async:false,
                              }).done(function(folder_list) {
                                if(folder_list.length > 0)
                                {

                                $.each( folder_list, function( key, folder ) {
                                        $("#subject_material_"+data.id).append('<div class="col-md-2" id="subject_file_'+folder.id+'"><b>'+folder["folder_name"]+'</b></div>');
                                        $.ajax({
                                        url: "subject_offered/fileDataJson",
                                        data: {
                                                'subject_offered_id': data.id,
                                                'folder_id': folder.id,
                                              },
                                        async:false,
                                            }).done(function(file_list) {
                                              if(file_list.length > 0)
                                              {

                                              $.each( file_list, function( key, file ) {
                                                      $("#subject_file_"+folder.id).append('<div class="col-md-12"><a href="subject_offered/'+file["id"]+'/downloadFile" target="_blank">'+file["file_name"]+'</a></div>');
                                                  });
                                            }
                                        });
                                    });
                              }
                          });
                      }
                });
        });

        $("#classification_id").change(function(){
           oTable.fnDraw();
        });

