$(document).ready(function(){
    $('#neweditpacemodal').on('show.bs.modal', function(e) {   

        var button =  $(e.relatedTarget)
        var StudentEditPaceId = button.data('id')
        var StudentEditPaceStudentId = button.data('student_id')
        var StudentEditPaceClassificationLevelId = button.data('classification_level_id')
        var StudentEditPaceGradingPeriodId = button.data('grading_period_id')
        var StudentEditPaceSubjectId = button.data('subject_id')
        var StudentEditPaceSubjectName = button.data('name')
        var StudentEditPaceRequiredPace = button.data('required_pace_name')
        var StudentEditTermId = button.data('term_id')

        var modal = $(this)
        $('.edit_pace').text('Edit Pace');
        $('#new_edit_pace_id').val(StudentEditPaceId);
        $('#new_edit_pace_student_id').val(StudentEditPaceClassificationLevelId);
        $('#new_edit_student_classification_level_id').val(StudentEditPaceClassificationLevelId);
        $('#new_edit_pace_student_term_id').val(StudentEditTermId);
        $('#pace_name').val(StudentEditPaceRequiredPace);
        $("#subject_name").val(StudentEditPaceSubjectName);
        $("#new_student_pace_grading_period_id [value='"+StudentEditPaceGradingPeriodId+"']").attr("selected","selected");
        

    });



});
    $("#save_edit_pace").click(function () {

        $.ajax({
            url:"../../teachers_portal/student_academic_projection/postEdit",
            type:'post',
            data:{ 
                    'id': $("#new_edit_pace_id").val(),
                    'grading_period_id': $("#new_student_pace_grading_period_id").val(),
                    '_token': $('input[name=_token]').val(),
                },
            async:false
        });
        $("#neweditpacemodal").modal('hide');
        swal("Edited!", "Successfully Edit Pace", "success"); 
        location.reload();
    });
