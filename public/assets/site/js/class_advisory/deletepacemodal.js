$(document).ready(function(){
    $('#newdeletepacemodal').on('show.bs.modal', function(e) {   

        var button =  $(e.relatedTarget)
        var StudentDeletePaceId = button.data('id')
        var StudentDeletePaceStudentId = button.data('student_id')
        var StudentDeletePaceClassificationLevelId = button.data('classification_level_id')
        var StudentDeletePaceGradingPeriodId = button.data('grading_period_id')
        var StudentDeletePaceSubjectId = button.data('subject_id')
        var StudentDeletePaceSubjectName = button.data('name')
        var StudentDeletePaceRequiredPace = button.data('required_pace_name')
        var StudentDeleteTermId = button.data('term_id')

        var modal = $(this)
        $('.delete_pace').text('Delete Pace');
        $('#new_delete_pace_id').val(StudentDeletePaceId);
        $('#new_delete_pace_student_id').val(StudentDeletePaceClassificationLevelId);
        $('#new_delete_student_classification_level_id').val(StudentDeletePaceClassificationLevelId);
        $('#new_delete_pace_student_term_id').val(StudentDeleteTermId);
        $('#delete_pace_name').val(StudentDeletePaceRequiredPace);
        $("#delete_subject_name").val(StudentDeletePaceSubjectName);
        $("#new_student_pace_grading_period_id [value='"+StudentDeletePaceGradingPeriodId+"']").attr("selected","selected");
        

    });



});
    $("#save_delete_pace").click(function () {

        $.ajax({
            url:"../../teachers_portal/student_academic_projection/postDelete",
            type:'post',
            data:{ 
                    'id': $("#new_delete_pace_id").val(),
                    '_token': $('input[name=_token]').val(),
                },
            async:false
        });
        $("#newdeletepacemodal").modal('hide');
        swal("Deleted!", "Successfully Delete Pace", "success"); 
        // location.reload();
    });
