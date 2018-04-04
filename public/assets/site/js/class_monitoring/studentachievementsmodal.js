$(document).ready(function(){
         $('#studentachievementsmodal').on('show.bs.modal', function(e) {   

            var button =  $(e.relatedTarget)
            var Id = button.data('id')
            var StudentNo = button.data('student_no')
            var LastName = button.data('last_name')
            var FirstName = button.data('first_name')
            var MiddleName = button.data('middle_name')
            var StudentId = button.data('student_id')
            var ClassId = button.data('class_id')
            var TermId = button.data('term_id')
            var ClassificationId = button.data('classification_id')
            var ClassificationLevelId = button.data('classification_level_id')

            var modal = $(this)
            $('.student_achievements').text('Student Achievements / Awards')
            $('.achievements').text(LastName+', '+FirstName+' '+MiddleName+' '+StudentNo)
            $('#achievements_student_id').val(StudentId)
            $('#student_term_id').val(TermId)
            $('#student_classification_id').val(ClassificationId)
            $('#student_classification_level_id').val(ClassificationLevelId)
            $('#achievements_class_id').val(ClassId)
            $("#achievements_button").attr("data-id",StudentId)
            $("#achievements_button").attr("data-class_id",ClassId)
            $("#achievements_button").attr("data-student_name",LastName+', '+FirstName+' '+MiddleName+' '+StudentNo)
            $("#achievements_button").attr("data-classification_id",ClassificationId)
            $("#achievements_button").attr("data-classification_level_id",ClassificationLevelId)
            $("#achievements_button").attr("data-term_id",TermId)
            studentachievementstable.fnDraw();
        });

        studentachievementstable = $('#studentachievementstable').dataTable( {
            "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
            "sPaginationType": "bootstrap",
            "bProcessing": true,
            "bServerSide": true,
            "bStateSave": true,
            "sAjaxSource": "../../teachers_portal/student_achievements_data",
            "fnDrawCallback": function ( oSettings ) {
            },
            "fnServerParams": function(aoData){
                aoData.push(
                    { "name":"term_id", "value": $("#student_term_id").val() },
                    { "name":"student_id", "value": $("#achievements_student_id").val() },
                    { "name":"classification_id", "value": $("#student_classification_id").val() },
                    { "name":"classification_level_id", "value": $("#student_classification_level_id").val() }
                );
            }
        });
    
});