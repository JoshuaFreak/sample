$(document).ready(function(){
    $('#editinterventionmodal').on('show.bs.modal', function(e) {   

        var button =  $(e.relatedTarget)
        var InterventionId = button.data('id')

        var modal = $(this)
        $('.edit_intervention_modal').text('Edit Intervention')
        $('#edit_intervention_id').val(InterventionId)

        $.ajax({
              url:"../../teachers_portal/student_intervention/editdataJson",
              type:'get',
              data:
                  {  
                    'id' : InterventionId
                  },
              dataType: "json",
              async:false

        }).done(function(data) {
            
            if(data.length > 0)
            {
                $.each( data, function( key, item ) {

                    $("#edit_teacher_comment").val(item.teacher_comment);
                    $("#edit_action_taken_id").val(item.edit_action_taken_id);
                    $("#edit_itervention_grading_period_id").val(item.edit_itervention_grading_period_id);

                });


            }
        
        });

    });


    $(".SaveIntervention").click(function () {
        $.ajax({
            url:"../../teachers_portal/student_intervention/post",
            type:'post',
            data:{ 
                    'teacher_comment': $("#teacher_comment").val(),
                    'action_taken_id': $("#action_taken_id").val(),
                    'grading_period_id': $("#itervention_grading_period_id").val(),
                    'student_id': $("#student_id").val(),
                    'class_id': $("#class_id").val(),
                    '_token': $('input[name=_token]').val(),
                },
            async:false
        });
        $("#newinterventionmodal").modal('hide');
        $("#studentinterventionmodal").modal('hide');
        swal("Saved!", "Successfully Save Intervention", "success"); 
    });
});