$(document).ready(function() {
    table = $('#table').dataTable( {
        "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
        "sPaginationType": "bootstrap",
        "bProcessing": true,
        "bServerSide": true,
        "bStateSave": true,
        "sAjaxSource": "../../teachers_portal/class_monitoring/data",
        "fnDrawCallback": function ( oSettings ) {
        },
        "fnServerParams": function(aoData){
            aoData.push(
                { "name":"classification_id", "value": $("#classification_id").val() },
                { "name":"classification_level_id", "value": $("#classification_level_id").val() },
                { "name":"term_id", "value": $("#term_id").val() }
            );
        }
    });

    $("#classification_id").change(function(){
        selectListChange('classification_level_id','../../classification_level/dataJson',  { 'is_active':1 , 'classification_id': $(this).val() } ,'Please select a Classification Level')
        selectListChange('term_id','../../term/dataJson',  { 'is_active':1 , 'classification_id': $(this).val() } ,'Please select a Term')
    });   
    
    $("#classification_level_id").change(function(){
       table.fnDraw();
    });  

    $("#term_id").change(function(){
       table.fnDraw();
    });  

});