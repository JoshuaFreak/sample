$(document).ready(function(){
    $('#editstudentremarksmodal').on('show.bs.modal', function(e) {   

        var button =  $(e.relatedTarget)
        var StudentRemarksId = button.data('id')
        var LastName = button.data('last_name')
        var FirstName = button.data('first_name')
        var MiddleName = button.data('middle_name')
        var StudentRemarksClassificationId = button.data('classification_id')
        var StudentRemarksClassificationLevelId = button.data('classification_level_id')

        var modal = $(this)
        $('.edit_student_remarks_modal').text('Edit Student Remarks')
        $('#edit_student_remarks_id').val(StudentRemarksId)
        $('#edit_student_remarks_name').text(LastName+', '+FirstName+' '+MiddleName)
        $('#edit_student_remarks_classification_id').val(StudentRemarksClassificationId)
        $('#edit_student_remarks_classification_level_id').val(StudentRemarksClassificationLevelId)

        $.ajax({
              url:"../../teachers_portal/student_remarks/editdataJson",
              type:'get',
              data:
                  {  
                    'id' : StudentRemarksId
                  },
              dataType: "json",
              async:false

        }).done(function(data) {
            
            if(data.length > 0)
            {
                $.each( data, function( key, item ) {

                    $("#edit_remarks").val(item.remarks);
                    $("#edit_remarks_grading_period_id").val(item.grading_period_id);
                });
            }
        });

     var grading_period_id = $("#edit_remarks_grading_period_id").val();
        $.ajax({
            url:"../../grading_period/dataJson",
            type:'GET',
            data:
                {  
                    'classification_id': $("#edit_student_remarks_classification_id").val(),
                },
            dataType: "json",
            async:false,
            success: function (data) 
            {    
                
                $("#edit_remarks_grading_period_id").empty();
                $("#edit_remarks_grading_period_id").append('<option value=""></option>');
                $.map(data, function (item) 
                {       
                        grading_period_name = item.text;

                        $("#edit_remarks_grading_period_id").append('<option value="'+item.value+'">'+grading_period_name+'</option>');
                });
            }  
        });
        $("#edit_remarks_grading_period_id [value='"+grading_period_id+"']").attr("selected","selected");
    });

    $(".EditStudentRemarks").click(function () {
        $.ajax({
            url:"../../teachers_portal/student_remarks/postEdit",
            type:'post',
            data:{ 
                    'id': $("#edit_student_remarks_id").val(),
                    'remarks': $("#edit_remarks").val(),
                    'grading_period_id': $("#edit_remarks_grading_period_id").val(),
                    '_token': $('input[name=_token]').val(),
                },
            async:false
        });
        $("#editstudentremarksmodal").modal('hide');
        $("#conductremarksmodal").modal('hide');
        swal("Edited!", "Successfully Edited Student Remarks", "success"); 
    });

});