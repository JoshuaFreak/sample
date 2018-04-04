  $(document).ready(function(){



     $('#uploadDocumentModal').on('show.bs.modal', function(e) { 

            $("#student_document_container").empty();

            var button =  $(e.relatedTarget)
            var Id = button.data('id')
            var ClassificationId = button.data('classification_id')
            var StudentNo = button.data('student_no')
            var LastName = button.data('last_name')
            var FirstName = button.data('first_name')
            var MiddleName = button.data('middle_name')
            var StudentId = button.data('student_id')
            var ClassId = button.data('class_id')
            var TermId = button.data('term_id')
            var ClassificationLevelId = button.data('classification_level_id')

            var modal = $(this)
            $('.upload_document').text('Upload Document : ' + LastName+', '+FirstName+' '+MiddleName)
            $('#student_id').val(Id)
            $('#student_document_term_id').val(TermId)
            $('#student_document_classification_level_id').val(ClassificationLevelId)
       

            $("#folder_name").val("");
            $("#folder_id").val("");
            $("#parent_folder_id").val("");

            $.ajax({
                url:"../../teachers_portal/class_advisory/CreateFolderdataJson",
                type:'get',
                data:{ 
                    'student_id' : Id,
                    'folder_id' : 1,
                    },
                dataType: "json",
                async:false 
            }).done(function(data) {
                if(data.length > 0){
                    $.each( data, function( key, item ) {
                        $("#folder_name").val(item.folder_name);
                        $("#folder_id").val(item.id);
                        $("#parent_folder_id").val(item.parent_folder_id);
                    });
                }
            });

            var folder_id = $("#folder_id").val();
            $("#student_document_db_container").empty();
            if(folder_id != "")
            {

                $.ajax({
                url:"../../teachers_portal/class_advisory/studentFolderDataJson",
                type:'get',
                data:{ 
                    'folder_id' : folder_id,
                    },
                dataType: "json",
                async:false 
                }).done(function(data) {
                    if(data.length > 0){

                        $.each( data, function( key, item ) {
                            $("#student_document_db_container").append('<div class="form-group">'
                                    +'<div class="col-md-4">○ <label>'+item.folder_name+'</label></div>'
                                    +'<div class="col-md-4"><input type="file" id="item_'+item.id+'" name="filefield"></input></div>'
                                    +'<div class="col-md-4"><button type="button" class="upload" data-folderid="'+item.id+'">Upload</button></div>'
                                +'<div>');
                        });
                    }
                });
            }

            $(".upload").click(function(){

                folder_id = $(this).data('folderid');
                main_folder_id = $("#folder_id").val();
                // file = $("#item_"+folder_id)[0].files[0];
                file = $("#item_"+folder_id).prop("files")[0];
                var form_data = new FormData();
                form_data.append("file",file);
                form_data.append("folder_id",folder_id);
                form_data.append("main_folder_id",main_folder_id);
                form_data.append("_token",$('input[name=_token]').val());

                $.ajax({
                    url:"../../teachers_portal/class_advisory/upload_file",
                    type:'post',
                    datatype:'script',
                    contentType:false,
                    processData:false,
                    data:form_data,
                    async:false
                });

            $("#uploadDocumentModal").modal('hide');
            swal("Good!", "Successfully Uploaded File", "success");
            });


        });

});

        $(".btnSaveFolder").click(function () {
            $.ajax({
                url:"../../teachers_portal/class_advisory/postUpdateFolder",
                type:'post',
                data:{ 
                        'folder_name': $("#folder_name").val(),
                        'id': $("#folder_id").val(),
                        'student_id': $("#student_id").val(),
                        'teacher_id': $("#teacher_id").val(),
                        'term_id': $("#student_document_term_id").val(),
                        'classification_level_id': $("#student_document_classification_level_id").val(),
                        '_token': $('input[name=_token]').val(),
                    },
                async:false
            }).done(function(data){
                    $("#folder_id").val(data.id);

            });
            // $("#uploadDocumentModal").modal('hide');
            alert("Successfully Created Folder"); 
        });

        $("#save_student_document").hide();
        $("#student_document_form").submit(function (event)
        {

        event.preventDefault();
        
        if($("#student_document_count").val()==0 || $("#student_document_count").val()=='')
        {
            alert("Please Specify Student Document Count");
        }
        else
        {   
            $("#create_student_document").hide();
            $.ajax(
                {
                  url:"../../teachers_portal/class_advisory/CreateFolderdataJson",
                  data:{ 
                        'student_id': $("#student_id").val(),
                   },
                  async:false
                }
            ).done(function( data ) 
                {
                 generateStudentDocumentForm();

                $("#save_student_document").show();
            });
        }

    })

    $("#save_student_document").click(function(){    

        $("#student_document .student_document_form").each(function(){

            value = $(this).find('input[name="student_document_name"]').val();
            $("#student_document_container").append('<div class="form-group">'
                +'<div class="col-md-4">○ <label>'+value+'</label></div>'
                +'<div class="col-md-8" id="'+value+'"></div>'
            +'<div>');

        });

        for(var i=1; i<= $("#student_document_count").val(); i++)
        {
            if($("#student_document_form_"+i).html().length > 0)
            {
                $.ajax({
                        url:"../../teachers_portal/class_advisory/studentDocumentpostUpdate",
                        type:'post',
                        data: 
                            { 
                                'parent_folder_id': $("#folder_id").val(),
                                'folder_name': $("#student_document_"+i).val(),
                                'student_id': $("#student_id").val(),
                                'teacher_id': $("#teacher_id").val(),
                                'term_id': $("#student_document_term_id").val(),
                                'classification_level_id': $("#student_document_classification_level_id").val(),
                                '_token': $('input[name=_token]').val(),
                            },
                        async:false,
                        success:function(data)
                        {
                            $("#"+data.folder_name).append('<div class="form-group">'
                                    +'<div class="col-md-4"><input type="file"></input></div>'
                                    +'<div class="col-md-2"></div>'
                                    +'<div class="col-md-4"><button type="button" class="upload" data-folderid="'+data.id+'">Upload</button></div>'
                                +'<div>');
                        }
                }).done(function( data ) {
                    
                    $("#student_document_form_"+i).remove();
                    $("#message").html('Student Document'+i+' was successfully saved.');
                    $("#create_student_document").show();
                    $("#save_student_document").hide();
                });
            }
        }

        // $('#student_document_form')[0].reset();
        $('#student_document_count').val("");
        $("#message").html('');
        $("#create_teacher_detail").show();

    });

    $("#cancel_student_document").click(function(){
        $("#student_document").html('');
        $("#create_student_document").show();
        $("#save_student_document").hide();

    });

    function generateStudentDocumentForm()
    {
        $("#student_document").html('');
        for(var i=1; i<= $("#student_document_count").val(); i++)
        {
            var template = $("#subject-material-form-template").clone().html();
            var html = template
                        .replace('<<student_document_form_id>>','student_document_form_'+i)
                        .replace('<<student_document>>', 'student_document_'+i)
                        .replace('<<student_id>>', 'student_id_'+i)
                        .replace('<<data>>', i)

        $("#student_document").append(html); 
        $("#create_student_document").hide();
        }
    }