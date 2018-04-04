$(document).ready(function() {
	$('#gradingperiodmodal').on('show.bs.modal', function(e) {   
  
	    var button =  $(e.relatedTarget)
	    var classId = button.data('id')
	    var SectionName = button.data('section_name')
        var Code = button.data('code')
	    var TermId = button.data('term_id')

	    var modal = $(this)
	    $('.grading_period').text('Grading Period - Section : ' + SectionName+' - '+Code)
        $('#class_id').val(classId)
	    $('#term_id').val(TermId)

	    gradingperiodtable.fnDraw();
	});

    gradingperiodtable = $('#gradingperiodtable').dataTable( {
        "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
        "sPaginationType": "bootstrap",
        "bProcessing": true,
        "bServerSide": true,
        "bStateSave": true,
        "sAjaxSource": "../../teachers_portal/te_class/grading_period_data",
        "fnDrawCallback": function ( oSettings ) {
        },
        "fnServerParams": function(aoData){
            aoData.push(
                { "name":"class_id", "value": $("#class_id").val() },
                { "name":"term_id", "value": $("#term_id").val() }
            );
        }
    });
});