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
        $('.student_pace').text('Assign pace')
        $('#new_pace_student_id').val(StudentId)
        $('#new_pace_class_id').val(ClassId)
        $('#student_pace_name').text(StudentName)
        $('#student_pace_classification_id').val(ClassificationId)
        $('#student_pace_classification_level_id').val(ClassificationLevelId)
        $('#student_pace_term_id').val(TermId)
        // $("#pace").select2()
        $(function() {
        $('select.form-select').select2();
        });

        // $.ajax({
        //         url:"../../pace/dataJson",
        //         type:'GET',
        //         data:
        //             {  
        //                 'classification_id': $("#new_student_pace_classification_id").val(),
        //                 'classification_level_id':$("#new_student_classification_level_id").val(),
        //                 'grading_period_id': $("#student_pace_grading_period_id").val(),
        //                 'subject_id': $("#student_pace_subject_id").val()
        //             },
        //         dataType: "json",
        //         async:false,
        //         success: function (data) 
        //         {    
                    
        //             $("#pace").empty();
        //             $("#pace").append('<option value=""></option>');
        //             $.map(data, function (item) 
        //             {      
        //                     $("#pace").append('<option value="'+item.text+'">'+item.text+'</option>');
        //             });
        //         }  

        // });


    
    });
});
    $("#save_pace").click(function () {
        
        $.ajax({
            url:"../../teachers_portal/student_academic_projection/postCreate",
            type:'post',
            data:{ 
                    'pace': $("#pace").val(),
                    'grading_period_id': $("#student_pace_grading_period_id").val(),
                    'student_id': $("#new_pace_student_id").val(),
                    'classification_id': $("#new_student_pace_classification_id").val(),
                    'classification_level_id': $("#new_student_classification_level_id").val(),
                    'term_id': $("#new_pace_student_term_id").val(),
                    'subject_id': $("#student_pace_subject_id").val(),
                    '_token': $('input[name=_token]').val(),
                },
            async:false
        });
        $("#newpacesmodal").modal('hide');
        swal("Saved!", "Successfully Add Pace/s", "success"); 
        location.reload();
    });