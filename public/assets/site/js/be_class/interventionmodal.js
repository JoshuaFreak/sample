$(document).ready(function(){
        $('#interventionmodal').on('show.bs.modal', function(e) {   

            var button =  $(e.relatedTarget)
            var Id = button.data('id')
            var Level = button.data('level')
            var SectionName = button.data('section_name')
            var Name = button.data('name')

            var modal = $(this)
            $('.intervention').text('Intervention - ' + Level +' '+SectionName+' ('+Name+')')
            $('#intervention_class_id').val(Id)

            interventiontable.fnDraw();
        });

        interventiontable = $('#interventiontable').dataTable( {
                "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
                "sPaginationType": "bootstrap",
                "bProcessing": true,
                "bServerSide": true,
                "bStateSave": true,
                "sAjaxSource": "../../teachers_portal/intervention_data",
                "fnDrawCallback": function ( oSettings ) {
                },
                "fnServerParams": function(aoData){
                    aoData.push(
                        { "name":"class_id", "value": $("#intervention_class_id").val() }
                    );
                }
            });
	
		
});