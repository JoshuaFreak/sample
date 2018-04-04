$(document).ready(function() {
	$('#gradingperiodmodal').on('show.bs.modal', function(e) {   
  
	    var button =  $(e.relatedTarget)
	    var classId = button.data('id')
        var Level = button.data('level')
	    var SectionName = button.data('section_name')
        var Name = button.data('name')
        var TermId = button.data('term_id')
        var SubjectId = button.data('subject_id')
	    var ClassificationId = button.data('classification_id')

	    var modal = $(this)
	    $('.grading_period').text('Grading Period - ' + Level +' '+SectionName+' ('+Name+')')
        $('#gp_class_id').val(classId)
        $('#gp_term_id').val(TermId)
        $('#gp_subject_id').val(SubjectId)
	    $('#gp_classification_id').val(ClassificationId)

	    gradingperiodtable.fnDraw();
	});

    gradingperiodtable = $('#gradingperiodtable').dataTable( {
        "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
        "sPaginationType": "bootstrap",
        "bProcessing": true,
        "bServerSide": true,
        "bStateSave": true,
        "sAjaxSource": "../../teachers_portal/be_class/grading_period_data",
        "fnDrawCallback": function ( oSettings ) {
        },
        "fnServerParams": function(aoData){
            aoData.push(
                { "name":"class_id", "value": $("#gp_class_id").val() },
                { "name":"term_id", "value": $("#gp_term_id").val() },
                { "name":"subject_id", "value": $("#gp_subject_id").val() },
                { "name":"classification_id", "value": $("#gp_classification_id").val() }
            );
        }
    });
});