$(document).ready(function(){
        $('#studentlistremarksmodal').on('show.bs.modal', function(e) {   


            var section_id = $(e.relatedTarget).data('section_id');
            var classification_level_id = $(e.relatedTarget).data('level_id');
            var subject_id = $(e.relatedTarget).data('subject_id');
            var term_id = $(e.relatedTarget).data('term_id');
            $("#master_list_section_id").val(section_id);
            $("#master_list_level_id").val(classification_level_id);
            $("#student_subject_id").val(subject_id);
            $("#student_term_id").val(term_id);
            var button =  $(e.relatedTarget)
            var Id = button.data('id')
            var Level = button.data('level')
            var SectionName = button.data('section_name')
            var Name = button.data('name')

            var modal = $(this)
            $('.master_list').text('Master List - ' + Level +' '+SectionName+' ('+Name+')')
            $('#masterlist_id').val(Id)

            studentlistremarkstable.fnDraw();
        });

        studentlistremarkstable = $('#studentlistremarkstable').dataTable( {
            "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
            "sPaginationType": "bootstrap",
            "bProcessing": true,
            "bServerSide": true,
            "bStateSave": true,
            "sAjaxSource": "../../teachers_portal/student_subject_remarks/data",
            "fnDrawCallback": function ( oSettings ) {
            },
            "fnServerParams": function(aoData){
                aoData.push(
                    { "name":"masterlist_id", "value": $("#masterlist_id").val() },
                    { "name":"section_id", "value": $("#master_list_section_id").val() },
                    { "name":"classification_level_id", "value": $("#master_list_level_id").val() },
                    { "name":"subject_id", "value": $("#student_subject_id").val() },
                    { "name":"term_id", "value": $("#student_term_id").val() }
                );
            }
        });
    
        
});
