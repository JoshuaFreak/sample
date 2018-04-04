$(document).ready(function(){
        $('#dropstudentmodal').on('show.bs.modal', function(e) {   

            var button =  $(e.relatedTarget)
            var Id = button.data('id')
            var SectionName = button.data('section_name')
            var Code = button.data('code')

            var modal = $(this)
            $('.drop_student').text('Drop Student - Section : ' + SectionName+' - '+Code)
            $('#dropstudent_id').val(Id)

            dropstudenttable.fnDraw();
        });

        dropstudenttable = $('#dropstudenttable').dataTable( {
            "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
            "sPaginationType": "bootstrap",
            "bProcessing": true,
            "bServerSide": true,
            "bStateSave": true,
            "sAjaxSource": "../../teachers_portal/te_class/drop_student_data",
            "fnDrawCallback": function ( oSettings ) {
            },
            "fnServerParams": function(aoData){
                aoData.push(
                    { "name":"dropstudent_id", "value": $("#dropstudent_id").val() }
                );
            }
        });

		
});