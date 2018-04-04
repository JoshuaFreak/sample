

    $(document).ready(function() {

        // alert("{{ URL::to('../../../teachers_portal/te_class/data/') }}");
        classdt = $('#classdt').dataTable( {
            "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
            "sPaginationType": "bootstrap",
            "bProcessing": true,
            "bServerSide": true,
            "bStateSave": true,
        //    "sAjaxSource": "{{ URL::to('../../../teachers_portal/te_class/data/') }}",
           //   "sAjaxSource": "http://localhost/genericschool/public/teachers_portal/te_class/data",
             "sAjaxSource": "../../teachers_portal/te_class/data",
          
            "fnDrawCallback": function ( oSettings ) {
            },
            "fnServerParams": function(aoData){
                aoData.push(
                    { "name":"term_id", "value": $("#term_id").val() }
                );
            }
        });
        $("#term_id").change(function(){
           classdt.fnDraw();
        });              
    });