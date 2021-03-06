$(document).ready(function(){
        $('#studentinterventionmodal').on('show.bs.modal', function(e) {   

            var button =  $(e.relatedTarget)
            var Id = button.data('id')
            var StudentNo = button.data('student_no')
            var LastName = button.data('last_name')
            var FirstName = button.data('first_name')
            var MiddleName = button.data('middle_name')
            var StudentId = button.data('student_id')
            var ClassId = button.data('class_id')

            var modal = $(this)
            $('.student_intervention').text('Student Intervention')
            $('.intervention').text('Intervention : ' + LastName+', '+FirstName+' '+MiddleName+' '+StudentNo)
            $('#student_id').val(StudentId)
            $('#student_class_id').val(ClassId)
            $("#intervention_button").attr("data-id",StudentId)
            $("#intervention_button").attr("data-class_id",ClassId)
            $("#intervention_button").attr("data-student_name",LastName+', '+FirstName+' '+MiddleName+' '+StudentNo)

            studentinterventiontable.fnDraw();
        });

        studentinterventiontable = $('#studentinterventiontable').dataTable( {
                "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
                "sPaginationType": "bootstrap",
                "bProcessing": true,
                "bServerSide": true,
                "bStateSave": true,
                "sAjaxSource": "../../teachers_portal/student_intervention_data",
                "fnDrawCallback": function ( oSettings ) {
                },
                "fnServerParams": function(aoData){
                    aoData.push(
                        { "name":"class_id", "value": $("#student_class_id").val() },
                        { "name":"student_id", "value": $("#student_id").val() }
                    );
                }
            });
	
		
});