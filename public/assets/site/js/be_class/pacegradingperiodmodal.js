$(document).ready(function() {
	$('#pacegradingperiodmodal').on('show.bs.modal', function(e) {   
  
	    var button =  $(e.relatedTarget)
	    var classId = button.data('id')
        var Level = button.data('level')
        var SectionId = button.data('section_id')
	    var SectionName = button.data('section_name')
        var Name = button.data('name')
        var TermId = button.data('term_id')
        var SubjectId = button.data('subject_id')
	    var ClassificationId = button.data('classification_id')

	    var modal = $(this)
	    $('.grading_period').text('Grading Period - ' + Level +' '+SectionName+' ('+Name+')')
        $('#pgp_section_id').val(SectionId)
        $('#pgp_class_id').val(classId)
        $('#pgp_term_id').val(TermId)
        $('#pgp_subject_id').val(SubjectId)
	    $('#pgp_classification_id').val(ClassificationId)

	    pacegradingperiodtable.fnDraw();
	});

    pacegradingperiodtable = $('#pacegradingperiodtable').dataTable( {
        "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
        "sPaginationType": "bootstrap",
        "bProcessing": true,
        "bServerSide": true,
        "bStateSave": true,
        "sAjaxSource": "../../teachers_portal/be_class/pace_grading_period_data",
        "fnDrawCallback": function ( oSettings ) {
        },
        "fnServerParams": function(aoData){
            aoData.push(
                { "name":"section_id", "value": $("#pgp_section_id").val() },
                { "name":"class_id", "value": $("#pgp_class_id").val() },
                { "name":"term_id", "value": $("#pgp_term_id").val() },
                { "name":"subject_id", "value": $("#pgp_subject_id").val() },
                { "name":"classification_id", "value": $("#pgp_classification_id").val() }
            );
        }
    });
});