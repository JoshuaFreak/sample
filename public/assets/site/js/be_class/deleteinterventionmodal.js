$(document).ready(function(){
    $('#deleteinterventionmodal').on('show.bs.modal', function(e) {   

        var button =  $(e.relatedTarget)
        var InterventionId = button.data('id')
        var SubjectCode = button.data('code')
        var SubjectName = button.data('name')
        var Level = button.data('level')
        var SectionName = button.data('section_name')
        var LastName = button.data('last_name')
        var FirstName = button.data('first_name')
        var MiddleName = button.data('middle_name')
        var InterventionClassificationId = button.data('classification_id')

        var modal = $(this)
        $('.delete_intervention_modal').text('Edit Intervention - ' + Level +' '+SectionName+' ('+SubjectName+')')
        $('#delete_intervention_id').val(InterventionId)
        $('#delete_student_name').text(LastName+', '+FirstName+' '+MiddleName)
        $('#delete_classification_id').val(InterventionClassificationId)

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

                    $("#delete_teacher_comment").val(item.teacher_comment);
                    $("#delete_action_taken_id").val(item.action_taken_id);
                    $("#delete_grading_period_id").val(item.grading_period_id);
                });
            }
        });

        var action_taken_id = $("#delete_action_taken_id").val();
        $.ajax({
            url:"../../action_taken/dataJson",
            type:'GET',
            data:
                {  
                    // 'id': $("#action_taken_id").val(),
                },
            dataType: "json",
            async:false,
            success: function (data) 
            {    
                
                $("#delete_action_taken_id").empty();
                $("#delete_action_taken_id").append('<option value=""></option>');
                $.map(data, function (item) 
                {       
                        action_taken_name = item.text;

                        $("#delete_action_taken_id").append('<option value="'+item.value+'">'+action_taken_name+'</option>');
                });
            }  
        });
        $("#delete_action_taken_id [value='"+action_taken_id+"']").attr("selected","selected");

        var grading_period_id = $("#delete_grading_period_id").val();
        $.ajax({
            url:"../../grading_period/dataJson",
            type:'GET',
            data:
                {  
                    'classification_id': $("#delete_classification_id").val(),
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

    $(".deleteIntervention").click(function () {
        $.ajax({
            url:"../../teachers_portal/student_intervention/postdelete",
            type:'post',
            data:{ 
                    'id': $("#delete_intervention_id").val(),
                    '_token': $('input[name=_token]').val(),
                },
            async:false
        });
        $("#deleteinterventionmodal").modal('hide');
        $("#studentinterventionmodal").modal('hide');
        swal("Deleted!", "Successfully Delete Intervention", "success"); 
    });
});