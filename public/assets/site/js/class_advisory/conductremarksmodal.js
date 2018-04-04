$(document).ready(function(){
         $('#conductremarksmodal').on('show.bs.modal', function(e) {   

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
            $('.conduct_remarks_modal').text('Conduct Remarks')
            $('.remarks').text(LastName+', '+FirstName+' '+MiddleName+' '+StudentNo)
            $('#student_remarks_id').val(StudentId)
            $('#student_remarks_list_term_id').val(TermId)
            $('#student_remarks_list_classification_id').val(ClassificationId)
            $('#student_remarks_list_classification_level_id').val(ClassificationLevelId)
            // $('#achievements_class_id').val(ClassId)
            $("#remarks_button").attr("data-id",StudentId)
            $("#remarks_button").attr("data-class_id",ClassId)
            $("#remarks_button").attr("data-student_name",LastName+', '+FirstName+' '+MiddleName+' '+StudentNo)
            $("#remarks_button").attr("data-classification_id",ClassificationId)
            $("#remarks_button").attr("data-classification_level_id",ClassificationLevelId)
            $("#remarks_button").attr("data-term_id",TermId)
            conductremarkstable.fnDraw();
        });

        conductremarkstable = $('#conductremarkstable').dataTable( {
            "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
            "sPaginationType": "bootstrap",
            "bProcessing": true,
            "bServerSide": true,
            "bStateSave": true,
            "sAjaxSource": "../../teachers_portal/student_remarks/conduct_remarks_data",
            "fnDrawCallback": function ( oSettings ) {
            },
            "fnServerParams": function(aoData){
                aoData.push(
                    { "name":"term_id", "value": $("#student_remarks_list_term_id").val() },
                    { "name":"student_id", "value": $("#student_remarks_id").val() },
                    { "name":"classification_id", "value": $("#student_remarks_list_classification_id").val() },
                    { "name":"classification_level_id", "value": $("#student_remarks_list_classification_level_id").val() }
                );
            }
        });
    
});