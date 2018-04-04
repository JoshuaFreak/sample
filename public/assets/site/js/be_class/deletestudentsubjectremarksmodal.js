$(document).ready(function(){
    $('#deletestudentsubjectremarksmodal').on('show.bs.modal', function(e) {   

        var button =  $(e.relatedTarget)
        var StudentRemarksId = button.data('id')
        var LastName = button.data('last_name')
        var FirstName = button.data('first_name')
        var MiddleName = button.data('middle_name')
        var StudentRemarksClassificationId = button.data('classification_id')

        var modal = $(this)
        $('.delete_student_remarks_modal').text('Delete Student Remarks')
        $('#delete_student_remarks_id').val(StudentRemarksId)
        $('#delete_student_remarks_name').text(LastName+', '+FirstName+' '+MiddleName)
        $('#delete_student_remarks_classification_id').val(StudentRemarksClassificationId)

        $.ajax({
              url:"../../teachers_portal/student_subject_remarks/editdataJson",
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

                    $("#delete_remarks").val(item.remarks);
                    $("#delete_grading_period_id").val(item.grading_period_id);
                });
            }
        });

        var grading_period_id = $("#delete_grading_period_id").val();
        $.ajax({
            url:"../../grading_period/dataJson",
            type:'GET',
            data:
                {  
                    'classification_id': $("#delete_student_remarks_classification_id").val(),
                },
            dataType: "json",
            async:false,
            success: function (data) 
            {    
                
                $("#delete_grading_period_id").empty();
                $("#delete_grading_period_id").append('<option value=""></option>');
                $.map(data, function (item) 
                {       
                        grading_period_name = item.text;

                        $("#delete_grading_period_id").append('<option value="'+item.value+'">'+grading_period_name+'</option>');
                });
            }  
        });
        $("#delete_grading_period_id [value='"+grading_period_id+"']").attr("selected","selected");

    });

    $(".deleteStudentSubjectRemarks").click(function () {
        $.ajax({
            url:"../../teachers_portal/student_subject_remarks/postdelete",
            type:'post',
            data:{ 
                    'id': $("#delete_student_remarks_id").val(),
                    '_token': $('input[name=_token]').val(),
                },
            async:false
        });
        $("#deletestudentsubjectremarksmodal").modal('hide');
        $("#studentsubjectremarksmodal").modal('hide');
        swal("Deleted!", "Successfully Deleted Student Remarks", "success"); 
    });
});