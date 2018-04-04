$(document).ready(function(){
    $('#deletestudentachievementsmodal').on('show.bs.modal', function(e) {   

        var button =  $(e.relatedTarget)
        var StudentAchievementsId = button.data('id')
        var LastName = button.data('last_name')
        var FirstName = button.data('first_name')
        var MiddleName = button.data('middle_name')
        var StudentAchievementsClassificationId = button.data('classification_id')

        var modal = $(this)
        $('.delete_student_achievements_modal').text('Delete Achievements / Awards')
        $('#delete_student_achievements_id').val(StudentAchievementsId)
        $('#delete_student_achievements_name').text(LastName+', '+FirstName+' '+MiddleName)
        $('#delete_classification_id').val(StudentAchievementsClassificationId)

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

                    $("#delete_achievements").val(item.achievements);
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

    $(".deleteStudentAchievements").click(function () {
        $.ajax({
            url:"../../teachers_portal/student_achievements/postdelete",
            type:'post',
            data:{ 
                    'id': $("#delete_student_achievements_id").val(),
                    '_token': $('input[name=_token]').val(),
                },
            async:false
        });
        $("#deletestudentachievementsmodal").modal('hide');
        $("#studentachievementsmodal").modal('hide');
        swal("Deleted!", "Successfully Delete Student Achievements", "success"); 
    });
});