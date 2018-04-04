$(document).ready(function(){
    $('#newpacesmodal').on('show.bs.modal', function(e) {   

        var button =  $(e.relatedTarget)
        var StudentId = button.data('id')
        var ClassId = button.data('class_id')            
        var StudentName = button.data('student_name')
        var ClassificationId = button.data('classification_id')
        var ClassificationLevelId = button.data('classification_level_id')
        var TermId = button.data('term_id')

        var modal = $(this)
        $('.student_pace').text('Assign pace / Awards')
        $('#new_pace_student_id').val(StudentId)
        $('#new_pace_class_id').val(ClassId)
        $('#student_pace_name').text(StudentName)
        $('#student_pace_classification_id').val(ClassificationId)
        $('#student_pace_classification_level_id').val(ClassificationLevelId)
        $('#student_pace_term_id').val(TermId)


        // $(".SavePace").click(function () {
        //     $.ajax({
        //         url:"../../teachers_portal/student_academic_projection/postCreate",
        //         type:'post',
        //         data:{ 
        //                 'pace': $("#pace").val(),
        //                 'grading_period_id': $("#student_pace_grading_period_id").val(),
        //                 'student_id': $("#pace_student_id").val(),
        //                 'class_id': $("#pace_class_id").val(),
        //                 'classification_id': $("#student_classification_id").val(),
        //                 'classification_level_id': $("#student_classification_level_id").val(),
        //                 'term_id': $("#student_term_id").val(),
        //                 '_token': $('input[name=_token]').val(),
        //             },
        //         async:false
        //     });
        //     $("#newstudentpacemodal").modal('hide');
        //     $("#studentpacemodal").modal('hide');
        //     swal("Saved!", "Successfully Add Pace/s", "success"); 
        // });
    $(".SavePace").click(function () {
        $.ajax({
            url:"../../teachers_portal/student_academic_projection/postCreate",
            type:'post',
            data:{ 
                    'pace': $("#pace").val(),
                    'grading_period_id': $("#student_pace_grading_period_id").val(),
                    'student_id': $("#new_pace_student_id").val(),
                    'class_id': $("#new_pace_class_id").val(),
                    'classification_id': $("#new_student_pace_classification_id").val(),
                    'classification_level_id': $("#new_student_classification_level_id").val(),
                    'term_id': $("#new_pace_student_term_id").val(),
                    'subject_id': $("#student_pace_subject_id").val(),
                    '_token': $('input[name=_token]').val(),
                },
            async:false
        });
        $("#newstudentpacemodal").modal('hide');
        $("#studentpacemodal").modal('hide');
        swal("Saved!", "Successfully Add Pace/s", "success"); 
        });
    });
});