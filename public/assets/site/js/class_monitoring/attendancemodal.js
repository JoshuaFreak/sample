var hot;

$(document).ready(function () {
    AttendanceHandsonTable();
});

function modifyAttendance() {
    $("#edit_delete").click(function(){

        var section_id = $(this).data('section_id');
        var term_id = $(this).data('term_id');
        
        $("#section_id_modify").val(section_id);
        $("#attendance_date").empty();

        $('#datepicker').datepicker({
            format: "MM d, yyyy",
            orientation: "top left",
            autoclose: true,
            startView: 1,
            todayHighlight: true,
            todayBtn: "linked",
        });

        $("#load_attendance").click(function(){
            $.ajax({
                url:"../../teachers_portal/class_monitoring/modifyDataJsonStudentAttendance",
                type:'get',
                data:{  
                        'term_id' : term_id,
                        'section_id' : section_id,
                        'date_start' : $("#date_start").val(),
                        'date_end' : $("#date_end").val(),
                    },
                dataType: "json",
                async:false,
                success: function (data) 
                {

                    $("#attendance_date").empty(); 

                    $.map(data, function(item)
                    {

                        $("#attendance_date").append('<div class="col-md-4">'
                                +'<div class="col-md-9" >'
                                        +'<input class="form-control" type="date" id="date_'+item.id+'" data-id="'+item.id+'" name="date_data" value="'+item.date+'"/>'
                                +'</div>'
                                +'<div class="col-md-3" >'
                                        +'<button class="btn btn-danger remove_btn" data-popout="true" data-title="Remove this?" onConfirm="sample()" singleton="true" data-toggle="confirmation" type="button" data-id="'+item.id+'" id="date_'+item.id+'" value="'+item.date+'"><span class="glyphicon glyphicon-remove-circle"></span></button>'
                                +'</div>'
                            +'</div>');
                        
                        $("[data-toggle=confirmation]").confirmation({container:"body",btnOkClass:"btn btn-sm btn-success",btnCancelClass:"btn btn-sm btn-danger",
                                onConfirm:function(event, element) {
                                 var id = $(this).data('id');

                                    $.ajax({
                                        url:"../../teachers_portal/class_monitoring/removeStudentAttendance",
                                        type:'post',
                                        data:{  
                                                'id' : id,
                                                '_token': $('input[name=_token]').val(),
                                            },
                                        dataType: "json",
                                        async:false,
                                        success: function (data) 
                                        {
                                            swal("Successfully deleted!");
                                            location.reload();
                                        } 
                                    });
                                }

                        });


                    });
                } 
            });

        });

        $("#save_changes").click(function(){

            $("input[name='date_data']").each(function(){

                var value = $(this).val()
                var id = $(this).data('id')
                        $.ajax({
                            url:"../../teachers_portal/class_monitoring/updateStudentAttendanceDate",
                            type:'post',
                            data:{  
                                    'id' : id,
                                    'value' : value,
                                    '_token': $('input[name=_token]').val(),
                                },
                            dataType: "json",
                            async:false,
                            success: function (data) 
                            {
                                
                            } 
                        });
            });

            swal("Changes saved Successfully!");
            location.reload();
        });


        
    });
}

function AttendanceHandsonTable() {

    $('#attendancemodal').on('shown.bs.modal', function (e) {
        $(".htCore tbody tr").remove();

        var button =  $(e.relatedTarget)
        var ClassificationId = button.data('classification_id')
        var ClassificationLevelId = button.data('classification_level_id')
        var TermId = button.data('term_id')
        var SectionId = button.data('section_id')
        var Level = button.data('level')
        var SectionName = button.data('section_name')
        var TermName = button.data('term_name')

        $("#edit_delete").attr('data-section_id',SectionId);
        $("#edit_delete").attr('data-term_id',TermId);
        modifyAttendance();

        var modal = $(this)
        $('.attendance_modal').text('Attendance')
        $('.attendance_level').text(Level+' '+SectionName)
        $('.attendance_term').text(TermName)
        $('#attendance_classification_id').val(ClassificationId)
        $('#attendance_classification_level_id').val(ClassificationLevelId)
        $('#attendance_term_id').val(TermId)
        $('#attendance_section_id').val(SectionId)

        $.ajax({
            url:"../../teachers_portal/class_monitoring/dataJsonStudentAttendance",
            type:'get',
            data:{  
                    'term_id' : TermId,
                    'section_id' : SectionId,
                    'classification_level_id' : ClassificationLevelId,
                },
            dataType: "json",
            async:false,
            success: function (data) { 
                StudentAttendanceArray = data;
            } 
        });

        $.ajax({
            url:"../../attendance_remark/dataJson",
            type:'get',
            data:{ },
            dataType: "json",
            async:false,
            success: function (data) { 
                attendance_remark = data;
            }
        });

        function firstRowRenderer(instance, td, row, col, prop, value, cellProperties) {
            Handsontable.renderers.TextRenderer.apply(this, arguments);
            td.style.background = '#E6E6E6';
        }

        var data = StudentAttendanceArray[0];
        var student_attendance_id = StudentAttendanceArray[1];

        var container = document.getElementById("attendance_excel");
        hot = new Handsontable(container, {
            data: data,
            afterChange: function (changes, source) {

                
                if(changes != null){
                    var rating_row = changes[0][0];
                    var rating_column = changes[0][1];
                    var student_id = student_attendance_id[rating_row][rating_column];
                    var rating = changes[0][3];
                    $.ajax({
                        url: "../../teachers_portal/class_monitoring/postStudentAttendance",
                        data: {
                            'id': student_id,
                            'rating': rating,
                            '_token': $('input[name=_token]').val(),
                        }, 
                        dataType: 'json',
                        type: 'POST',
                        async:false
                    });
                }
            },
            className: "htCenter htMiddle",
            height: 300,
            currentRowClassName: 'currentRow',
            currentColClassName: 'currentCol',
            fixedRowsTop: 1,
            fixedColumnsLeft: 3,
            cells : function(row, col, prop, td) {
                var cellProperties = {};
                // if (col == 0 || col == 1) {
                //     cellProperties.editor = false;
                //     cellProperties.renderer = firstRowRenderer;
                    
                // }

                if(col >= 3 && row >= 1 ){
                    return {
                        type: 'handsontable',
                        handsontable: {
                            colHeaders: false,
                            data: attendance_remark,
                            columns:[{data:'text'}]
                        }
                    }
                }

                if (row == 0 || col == 0) {
                    cellProperties.editor = false;
                    cellProperties.renderer = firstRowRenderer;
                    
                }else if (col == 1 || col == 2 || col == 3 || col == 4){
                    cellProperties.readOnly = true;
                }
                return cellProperties;
            }
        });   
    });
}

$(".btnSave").click(function () {
    var date = $("#date").val();
    if(date != "")
    {
        $.ajax({
            url:"../../teachers_portal/class_monitoring/postAttendance",
            type:'post',
            data:{ 
                    'date': $("#date").val(),
                    'term_id': $("#attendance_term_id").val(),
                    'classification_level_id': $("#attendance_classification_level_id").val(),
                    'section_id': $("#attendance_section_id").val(),
                    '_token': $('input[name=_token]').val(),
                },
            async:false
        });
        $("#attendancemodal").modal('hide');
        swal("Saved!", "Successfully Save", "success"); 
    }
    else
    {
        swal("Please Select Date to add another attendance.");
    }
});