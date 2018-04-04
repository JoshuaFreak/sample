$(document).ready(function () {

    $('#cscdmodal').on('show.bs.modal', function(e) { 

        var button =  $(e.relatedTarget)
        var Id = button.data('id')
        var classId = button.data('class_id')
        var gradingPeriod = button.data('grading_period_name')

        var modal = $(this)
        $('.cscd_modal').text('Class Standing Detail : ' + gradingPeriod)
        $('#cscd_class_id').val(classId)
        $('#cscd_grading_period_id').val(Id)

        cscdtable.fnDraw();

    });

    cscdtable = $('#cscdtable').dataTable({
        "sDom" : "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
        "sPaginationType" : "bootstrap",
        "bProcessing" : true,
        "bServerSide" : true,
        "bStateSave": true,
        "sAjaxSource" : "../../teachers_portal/cscd_data",
        "fnDrawCallback": function ( oSettings ) {
        },
        "fnServerParams": function(aoData){
            aoData.push(
                { "name":"class_id", "value": $("#cscd_class_id").val() },
                { "name":"grading_period_id", "value": $("#cscd_grading_period_id").val() }
            );
        },
            columns: [
                {
                    "className":      'details-control',
                    "orderable":      false,
                    "data":           null,
                    "defaultContent": ''
                },
                {data: 'class_component_category_name', name: 'class_component_category_name'},
                {data: 'component_weight', name: 'component_weight'}
            ],

            order: [[1, 'asc']]


        });

        var cscdtableChild = $('#cscdtable').DataTable();

        // Add event listener for opening and closing details
        $('#cscdtable tbody').on('click', 'td.details-control', function () {

            // alert();
            var tr = $(this).closest('tr');
            var row = cscdtableChild.row(tr);


            if (row.child.isShown()) {

                // This row is already open - close it
                // $('div.slider', row.child()).slideUp( function () {
                row.child.hide();
                tr.removeClass('shown');
            // });
            } 
            else {
                // Open this row
                row.child(format(row.data()),'no-padding').show();
                tr.addClass('shown');
                // $('div.slider', row.child()).slideDown();
            }
        });



        function format ( d ) {

            var data_html = '<div class="slider">'+
                            '<div class="pull-right">'+
                                '<button class="btn btn-sm btn-primary active" data-id='+d.id+' data-ccc_name='+d.class_component_category_name+' data-class_id='+d.class_id+' data-toggle="modal" data-target="#newdetailmodal">'+
                                    '<span class="glyphicon glyphicon-plus-sign"></span> New Detail'+
                                '</button>&nbsp;'+
                                '<button id="#" type="button" class="btn btn-sm btn-info" data-id='+d.id+' data-ccc_name='+d.class_component_category_name+' data-class_id='+d.class_id+' data-grading_period='+d.grading_period_name+' data-toggle="modal" data-target="#viewallcscdmodal">View All</button>'+
                            '</div></br></br></br>'+
                            '<div>'+
                                '<table class="table table-striped table-hover">'+
                                    '<th>Date</th>'+
                                    '<th>Description</th>'+
                                    '<th>Perfect Score</th>'+
                                    '<th>Action</th>'+
                                    '<tbody>'+
                                        '<tr>';


            $.ajax({
              url: "../../class_standing_component_detail/dataJson",
              data: {
                        'class_standing_component_id': d.id
                    },
              async:false,
            }).done(function(class_standing_component_detail_list) {
                if(class_standing_component_detail_list.length > 0)
                {
                    //iterate list here
                    //alert(JSON.stringify(class_standing_component_detail_list));
                    /*$.each($.parseJSON(class_standing_component_detail_list), function(key,value){
                        alert(value);
                    });*/

                    $.each( class_standing_component_detail_list, function( key, container ) {
                        cId = container.id;
                        cDescription = container.description;
                        cDate = container.date;
                        cPerfectScore = container.perfect_score;
                        CCCname = container.class_component_category_name;
                        data_html = data_html + 
                                    '<td>'+container["date"]+'</td>'+
                                    '<td>'+container["description"]+'</td>'+
                                    '<td>'+container["perfect_score"]+'</td>'+
                                    '<td><button class="btn btn-sm btn-primary active" data-id='+cId+' data-name='+CCCname+' data-description='+cDescription+' data-date='+cDate+' data-perfect_score='+cPerfectScore+' data-toggle="modal" data-target="#scoreentrymodal"><span class="glyphicon glyphicon-list-alt"></span> Score Entry</button>'+
                                    '&nbsp;&nbsp;<button class="btn btn-sm btn-success active" data-id='+cId+' data-cscd='+cDescription+' data-toggle="modal" data-target="#cscdeditmodal"><span class="glyphicon glyphicon-pencil"></span> Edit</a></button>'+    
                                    '&nbsp;&nbsp;<button class="btn btn-sm btn-danger active" data-id='+cId+' data-cscd='+cDescription+' data-toggle="modal" data-target="#cscddeletemodal"><span class="glyphicon glyphicon-pencil"></span> Delete</a></button>'+    
                                '</tr>';

                    });
                }
            
            });

            data_html = data_html 
                            '</tbody>'+
                            '</table>'+
                        '</div>'+
                    '</div>';

            return data_html;

        }

            
});