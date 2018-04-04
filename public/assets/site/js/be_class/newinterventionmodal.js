$(document).ready(function(){
    $('#newinterventionmodal').on('show.bs.modal', function(e) {   

        var button =  $(e.relatedTarget)
        var StudentId = button.data('id')
        var ClassId = button.data('class_id')
        var StudentName = button.data('student_name')
        var InterventionClassificationId = button.data('classification_id')

        var modal = $(this)
        $('.new_intervention_modal').text('Create Intervention')
        $('#intervention_student_id').val(StudentId)
        $('#intervention_class_id').val(ClassId)
        $('#student_name').text(StudentName)
        $('#intervention_classification_id').val(InterventionClassificationId)

        $.ajax({
            url:"../../grading_period/dataJson",
            type:'GET',
            data:
                {  
                    'classification_id': $("#intervention_classification_id").val(),
                },
            dataType: "json",
            async:false,
            success: function (data) 
            {    
                
                $("#itervention_grading_period_id").empty();
                $("#itervention_grading_period_id").append('<option value=""></option>');
                $.map(data, function (item) 
                {      
                        $("#itervention_grading_period_id").append('<option value="'+item.value+'">'+item.text+'</option>');
                });
            }  

        });

    });

    $(".SaveIntervention").click(function () {
        $.ajax({
            url:"../../teachers_portal/student_intervention/postCreate",
            type:'post',
            data:{ 
                    'teacher_comment': $("#teacher_comment").val(),
                    'action_taken_id': $("#action_taken_id").val(),
                    'grading_period_id': $("#itervention_grading_period_id").val(),
                    'student_id': $("#intervention_student_id").val(),
                    'class_id': $("#intervention_class_id").val(),
                    'classification_id': $("#intervention_classification_id").val(),
                    '_token': $('input[name=_token]').val(),
                },
            async:false
        });
        $("#newinterventionmodal").modal('hide');
        $("#studentinterventionmodal").modal('hide');
        swal("Saved!", "Successfully Save Intervention", "success"); 
    });
});