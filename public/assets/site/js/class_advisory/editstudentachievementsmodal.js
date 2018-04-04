$(document).ready(function(){
    $('#editstudentachievementsmodal').on('show.bs.modal', function(e) {   

        var button =  $(e.relatedTarget)
        var StudentAchievementsId = button.data('id')
        var LastName = button.data('last_name')
        var FirstName = button.data('first_name')
        var MiddleName = button.data('middle_name')
        var StudentAchievementsClassificationId = button.data('classification_id')
        var StudentAchievementsClassificationLevelId = button.data('classification_level_id')

        var modal = $(this)
        $('.edit_student_achievements_modal').text('Edit Achievements / Awards')
        $('#edit_student_achievements_id').val(StudentAchievementsId)
        $('#edit_student_achievements_name').text(LastName+', '+FirstName+' '+MiddleName)
        $('#edit_student_classification_id').val(StudentAchievementsClassificationId)
        $('#edit_student_classification_level_id').val(StudentAchievementsClassificationLevelId)

        $.ajax({
              url:"../../teachers_portal/student_achievements/editdataJson",
              type:'get',
              data:
                  {  
                    'id' : StudentAchievementsId
                  },
              dataType: "json",
              async:false

        }).done(function(data) {
            
            if(data.length > 0)
            {
                $.each( data, function( key, item ) {

                    $("#edit_achievements").val(item.achievements);
                    $("#edit_achievements_grading_period_id").val(item.grading_period_id);
                });
            }
        });

     var grading_period_id = $("#edit_achievements_grading_period_id").val();
        $.ajax({
            url:"../../grading_period/dataJson",
            type:'GET',
            data:
                {  
                    'classification_id': $("#edit_student_classification_id").val(),
                },
            dataType: "json",
            async:false,
            success: function (data) 
            {    
                
                $("#edit_achievements_grading_period_id").empty();
                $("#edit_achievements_grading_period_id").append('<option value=""></option>');
                $.map(data, function (item) 
                {       
                        grading_period_name = item.text;

                        $("#edit_achievements_grading_period_id").append('<option value="'+item.value+'">'+grading_period_name+'</option>');
                });
            }  
        });
        $("#edit_achievements_grading_period_id [value='"+grading_period_id+"']").attr("selected","selected");
    });

    $(".EditStudentAchievements").click(function () {
        $.ajax({
            url:"../../teachers_portal/student_achievements/postEdit",
            type:'post',
            data:{ 
                    'id': $("#edit_student_achievements_id").val(),
                    'achievements': $("#edit_achievements").val(),
                    'grading_period_id': $("#edit_achievements_grading_period_id").val(),
                    '_token': $('input[name=_token]').val(),
                },
            async:false
        });
        $("#editstudentachievementsmodal").modal('hide');
        $("#studentachievementsmodal").modal('hide');
        swal("Edited!", "Successfully Edit Student Achievements", "success"); 
    });

});