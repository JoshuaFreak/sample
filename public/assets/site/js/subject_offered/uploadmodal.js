  $(document).ready(function(){



     $('#uploadModal').on('show.bs.modal', function(e) { 

            $("#subject_material_container").empty();
            var button =  $(e.relatedTarget);
            var Id = button.data('id')
            var Name = button.data('name')
            var Program = button.data('program_name')

            var modal = $(this)
            $('.subject_offered').text('Program : ' + Program+' - ('+Name+')')
            $('#subject_offered_id').val(Id)
            $('#subject_offered_name').val(Name)
       

            $("#folder_name").val("");
            $("#folder_id").val("");
            $("#parent_folder_id").val("");

            $.ajax({
                url:"subject_offered/CreateFolderdataJson",
                type:'get',
                data:{ 
                    'subject_offered_id' : Id,
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
            $("#subject_material_db_container").empty();
            if(folder_id != "")
            {

                $.ajax({
                url:"subject_offered/subjectFolderDataJson",
                type:'get',
                data:{ 
                    'folder_id' : folder_id,
                    },
                dataType: "json",
                async:false 
                }).done(function(data) {
                    if(data.length > 0){

                        $.each( data, function( key, item ) {
                            $("#subject_material_db_container").append('<div class="form-group">'
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

                $.ajax({
                    url:"subject_offered/upload_file",
                    type:'post',
                    datatype:'script',
                    contentType:false,
                    processData:false,
                    data:form_data,
                    async:false
                });

            $("#uploadModal").modal('hide');
            swal("Good!", "Successfully Uploaded File", "success");
            });


        });

});

        $(".btnSaveFolder").click(function () {
            $.ajax({
                url:"subject_offered/postUpdate",
                type:'post',
                data:{ 
                        'folder_name': $("#folder_name").val(),
                        'id': $("#folder_id").val(),
                        'subject_offered_id': $("#subject_offered_id").val(),
                        '_token': $('input[name=_token]').val(),
                    },
                async:false
            }).done(function(data){
                    $("#folder_id").val(data.id);

            });
            // $("#uploadModal").modal('hide');
            alert("Successfully Created Folder"); 
        });

        $("#save_subject_material").hide();
        $("#subject_material_form").submit(function (event)
        {

        event.preventDefault();
        
        if($("#subject_material_count").val()==0 || $("#subject_material_count").val()=='')
        {
            alert("Please Specify Subject Material Count");
        }
        else
        {   
            $("#create_subject_material").hide();
            $.ajax(
                {
                  url:"subject_offered/CreateFolderdataJson",
                  data:{ 
                        'subject_offered_id': $("#subject_offered_id").val(),
                   },
                  async:false
                }
            ).done(function( data ) 
                {
                 generateSubjectMaterialForm();

                $("#save_subject_material").show();
            });
        }

    })

    $("#save_subject_material").click(function(){    

        $("#subject_material .subject_material_form").each(function(){

            value = $(this).find('input[name="subject_material_name"]').val();
            $("#subject_material_container").append('<div class="form-group">'
                +'<div class="col-md-4">○ <label>'+value+'</label></div>'
                +'<div class="col-md-8" id="'+value+'"></div>'
            +'<div>');

        });

        for(var i=1; i<= $("#subject_material_count").val(); i++)
        {
            if($("#subject_material_form_"+i).html().length > 0)
            {
                $.ajax({
                        url:"subject_offered/subjectMaterialpostUpdate",
                        type:'post',
                        data: 
                            { 
                                'parent_folder_id': $("#folder_id").val(),
                                'folder_name': $("#subject_material_"+i).val(),
                                'subject_offered_id': $("#subject_offered_id").val(),
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
                    
                    $("#subject_material_form_"+i).remove();
                    $("#message").html('Subject Material'+i+' was successfully saved.');
                    $("#create_subject_material").show();
                    $("#save_subject_material").hide();
                });
            }
        }

        // $('#subject_material_form')[0].reset();
        $('#subject_material_count').val("");
        $("#message").html('');
        $("#create_teacher_detail").show();

    });

    $("#cancel_subject_material").click(function(){
        $("#subject_material").html('');
        $("#create_subject_material").show();
        $("#save_subject_material").hide();

    });

    function generateSubjectMaterialForm()
    {
        $("#subject_material").html('');
        for(var i=1; i<= $("#subject_material_count").val(); i++)
        {
            var template = $("#subject-material-form-template").clone().html();
            var html = template
                        .replace('<<subject_material_form_id>>','subject_material_form_'+i)
                        .replace('<<subject_material>>', 'subject_material_'+i)
                        .replace('<<subject_offered_id>>', 'subject_offered_id_'+i)
                        .replace('<<data>>', i)

        $("#subject_material").append(html); 
        $("#create_subject_material").hide();
        }
    }