$(document).ready(function () {

    $('#reportcardmodal').on('show.bs.modal', function(e) { 

        var button =  $(e.relatedTarget)
        var StudentId = button.data('student_id')
        var StudentNo = button.data('student_no')
        var LastName = button.data('last_name')
        var FirstName = button.data('first_name')
        var MiddleName = button.data('middle_name')
        var Level = button.data('level')
        var SectionName = button.data('section_name')
        var TermName = button.data('term_name')
        var SectionId = button.data('section_id')
        var TermId = button.data('term_id')
        var ClassificationId = button.data('classification_id')
        var ClassificationLevelId = button.data('classification_level_id')
        var CurriculumId = button.data('curriculum_id')

        var modal = $(this)
        $('.report_card_modal').text('Academic Report')
        $('.rc_student_no').text(StudentNo)
        $('.rc_student_name').text(LastName+', '+FirstName+' '+MiddleName)
        $('.rc_level').text(Level+' '+SectionName)
        $('.rc_term').text(TermName)
        $('#rc_student_id').val(StudentId)
        $('#rc_section_id').val(SectionId)
        $('#rc_term_id').val(TermId)
        $('#rc_classification_id').val(ClassificationId)
        $('#rc_classification_level_id').val(ClassificationLevelId)
        $('#rc_curriculum_id').val(CurriculumId)

         $.ajax({
            url:"../../grading_period/dataJson",
            type:'GET',
            data:
                {  
                    'classification_id': $("#rc_classification_id").val(),
                },
            dataType: "json",
            async:false,
            success: function (data) 
            {    
                
                $("#rc_grading_period_id").empty();
                $("#rc_grading_period_id").append('<option value=""></option>');
                $.map(data, function (item) 
                {      
                        $("#rc_grading_period_id").append('<option value="'+item.value+'">'+item.text+'</option>');
                });
            }  

        });


    });
});



        function callReport(reportId)
        {
          var url = $("#"+reportId).data('url');
          var student_id = $("#rc_student_id").val();
          var section_id  = $("#rc_section_id").val(); 
          var term_id = $("#rc_term_id").val();
          var classification_id  = $("#rc_classification_id").val(); 
          var classification_level_id = $("#rc_classification_level_id").val();
          var grading_period_id = $("#rc_grading_period_id").val();
          var curriculum_id = $("#rc_curriculum_id").val();

          url = url +"?student_id="+student_id+"&section_id="+section_id+"&term_id="+term_id+"&classification_id="+classification_id+"&classification_level_id="+classification_level_id+"&grading_period_id="+grading_period_id+"&curriculum_id="+curriculum_id;
          
          window.open(url);
        }