$(document).ready(function(){
        $('#masterlistmodal').on('show.bs.modal', function(e) {   

            var button =  $(e.relatedTarget)
            var Id = button.data('id')
            var SectionName = button.data('section_name')
            var Code = button.data('code')

            var modal = $(this)
            $('.modal-title').text('Master List - Section : ' + SectionName+' - '+Code)
            $('#masterlist_id').val(Id)

            masterlisttable.fnDraw();
        });

        masterlisttable = $('#masterlisttable').dataTable( {
            "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
            "sPaginationType": "bootstrap",
            "bProcessing": true,
            "bServerSide": true,
            "bStateSave": true,
            "sAjaxSource": "../../teachers_portal/te_class/master_list_data",
            "fnDrawCallback": function ( oSettings ) {
            },
            "fnServerParams": function(aoData){
                aoData.push(
                    { "name":"masterlist_id", "value": $("#masterlist_id").val() }
                );
            }
        });
	
		
});