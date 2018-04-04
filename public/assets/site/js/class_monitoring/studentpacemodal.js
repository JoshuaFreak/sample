$(document).ready(function(){
         $('#studentpacemodal').on('show.bs.modal', function(e) {   

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

            // $.ajax({
            // url:"../../teachers_portal/student_academic_projection",
            // type:'GET',
            // data:{ 
            //         'student_id': StudentId,
            //         'classification_id': ClassificationId,
            //         'classification_level_id': ClassificationLevelId,
            //         'term_id': TermId,                
            //     },
            // async:false
            // }); 

            var modal = $(this)

            $('.student_pace').text('Student pace / Awards');
            $('.student_name').text(LastName+', '+FirstName+' '+MiddleName+' '+StudentNo);
            $('#pace_student_id').val(StudentId);
            $('#new_pace_student_id').val(StudentId);
            $('#student_pace_term_id').val(TermId);
            $('#new_pace_student_term_id').val(TermId);
            $('#student_pace_classification_id').val(ClassificationId);
            $('#new_student_pace_classification_id').val(ClassificationId);
            $('#student_pace_classification_level_id').val(ClassificationLevelId);
            $('#new_student_classification_level_id').val(ClassificationLevelId);
            $('#pace_class_id').val(ClassId);
            $("#pace_button").attr("data-id",StudentId);
            $("#pace_button").attr("data-class_id",ClassId);
            $("#pace_button").attr("data-student_name",LastName+', '+FirstName+' '+MiddleName+' '+StudentNo);
            $("#pace_button").attr("data-classification_id",ClassificationId);
            $("#pace_button").attr("data-classification_level_id",ClassificationLevelId);
            $("#pace_button").attr("data-term_id",TermId);

            studentpacetable.fnDraw();
            // selectListChange('new_student_pace_classification_id','{{{URL::to("term/dataJson")}}}',  { 'is_active':1 , 'classification_id': $("#classification_id").val() } ,'Please select a Student No')
        });




        studentpacetable = $('#studentpacetable').dataTable( {
            "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
            "sPaginationType": "bootstrap",
            "bProcessing": true,
            "bServerSide": true,
            "bStateSave": true,
            "sAjaxSource": "../../teachers_portal/student_academic_projection",
            "fnDrawCallback": function ( oSettings ) {
            },
            "fnServerParams": function(aoData){
                aoData.push(
                    { "name":"term_id", "value": $("#student_pace_term_id").val() },
                    { "name":"student_id", "value": $("#pace_student_id").val() },
                    { "name":"classification_id", "value": $("#student_classification_id").val() },
                    { "name":"classification_level_id", "value": $("#student_pace_classification_level_id").val() }
                );
            }
        });
    
});