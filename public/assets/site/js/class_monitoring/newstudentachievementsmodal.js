$(document).ready(function(){
    $('#newstudentachievementsmodal').on('show.bs.modal', function(e) {   

        var button =  $(e.relatedTarget)
        var StudentId = button.data('id')
        var ClassId = button.data('class_id')            
        var StudentName = button.data('student_name')
        var ClassificationId = button.data('classification_id')
        var ClassificationLevelId = button.data('classification_level_id')
        var TermId = button.data('term_id')

        var modal = $(this)
        $('.student_achievements').text('Assign Achievements / Awards')
        $('#achievements_student_id').val(StudentId)
        $('#achievements_class_id').val(ClassId)
        $('#student_name').text(StudentName)
        $('#student_classification_id').val(ClassificationId)
        $('#student_classification_level_id').val(ClassificationLevelId)
        $('#student_term_id').val(TermId)

        $.ajax({
            url:"../../grading_period/dataJson",
            type:'GET',
            data:
                {  
                    'classification_id': $("#student_classification_id").val(),
                },
            dataType: "json",
            async:false,
            success: function (data) 
            {    
                
                $("#student_achievements_grading_period_id").empty();
                $("#student_achievements_grading_period_id").append('<option value=""></option>');
                $.map(data, function (item) 
                {      
                        $("#student_achievements_grading_period_id").append('<option value="'+item.value+'">'+item.text+'</option>');
                });
            }  

        });

    });

    $(".SaveAchievements").click(function () {
        $.ajax({
            url:"../../teachers_portal/student_achievements/postCreate",
            type:'post',
            data:{ 
                    'achievements': $("#achievements").val(),
                    'grading_period_id': $("#student_achievements_grading_period_id").val(),
                    'student_id': $("#achievements_student_id").val(),
                    'class_id': $("#achievements_class_id").val(),
                    'classification_id': $("#student_classification_id").val(),
                    'classification_level_id': $("#student_classification_level_id").val(),
                    'term_id': $("#student_term_id").val(),
                    '_token': $('input[name=_token]').val(),
                },
            async:false
        });
        $("#newstudentachievementsmodal").modal('hide');
        $("#studentachievementsmodal").modal('hide');
        swal("Saved!", "Successfully Assigned Awards", "success"); 
    });
});