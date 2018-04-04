$(document).ready(function () {

    $('#studentlistmodal').on('show.bs.modal', function(e) { 

        var button =  $(e.relatedTarget)
        // var ClassificationId = button.data('classification_id')
        var ClassificationLevelId = button.data('classification_level_id')
        var TermId = button.data('term_id')
        var SectionId = button.data('section_id')
        var Level = button.data('level')
        var SectionName = button.data('section_name')
        var TermName = button.data('term_name')

        var modal = $(this)
        $('.student_list_modal').text('Student List : ' + Level+' '+SectionName+' - '+TermName)
        // $('#student_list_classification_id').val(ClassificationId)
        $('#student_list_classification_level_id').val(ClassificationLevelId)
        $('#student_list_section_id').val(SectionId)
        $('#student_list_term_id').val(TermId)

        studentlisttable.fnDraw();

    });

    studentlisttable = $('#studentlisttable').dataTable({
        "sDom" : "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
        "sPaginationType" : "bootstrap",
        "bProcessing" : true,
        "bServerSide" : true,
        "bStateSave": true,
        "sAjaxSource" : "../../teachers_portal/class_advisory/student_list_data",
        "fnDrawCallback": function ( oSettings ) {
        },
        "fnServerParams": function(aoData){
            aoData.push(
                // { "name":"classification_id", "value": $("#student_list_classification_id").val() },
                { "name":"classification_level_id", "value": $("#student_list_classification_level_id").val() },
                { "name":"term_id", "value": $("#student_list_term_id").val() },
                { "name":"section_id", "value": $("#student_list_section_id").val() }
            );
        }
    });
});