var hot;

$(document).ready(function () {
    ViewAllScoreEntryHandsonTable();
});

function ViewAllScoreEntryHandsonTable() {

    $('#viewallcscdmodal').on('shown.bs.modal', function (e) {

        var button =  $(e.relatedTarget)
        var ClassStandingComponentId = button.data('id')
        var ClassComponentCategoryName = button.data('ccc_name')
        var GradingPeriodName = button.data('grading_period')
        var classId = button.data('class_id')

        var modal = $(this)
        $('.view_all_cscd').text('Class Standing Score Entries : ' + GradingPeriodName)
        $('#class_standing_component_id').val(ClassStandingComponentId)
        $('#class_id').val(classId)
        $('.class_component_category_name').text( ClassComponentCategoryName)

        //for attendace detail only
        $.ajax({
            url:"../../class_standing_score_entry/dataJsonAttendance",
            type:'get',
            data:{  
                    'class_standing_component_id' : ClassStandingComponentId,
                    'class_id' : classId
                },
            dataType: "json",
            async:false,
            success: function (data) { 
                AttendanceJson = data;
            } 
        });

        var dataAttendance = AttendanceJson[0];    // alert(dataAttendance);
        var attendance_id_arr = AttendanceJson[1];
        //end here attendance json

        $.ajax({
            url:"../../class_standing_score_entry/dataJsonViewAll",
            type:'get',
            data:{  
                    'class_standing_component_id' : ClassStandingComponentId,
                    'class_id' : classId
                },
            dataType: "json",
            async:false,
            success: function (data) { 
                ScoreEntryJson = data;
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

        var data = ScoreEntryJson[0];
        var score_entry_id_arr = ScoreEntryJson[1];
        var perfect_score_arr = ScoreEntryJson[2];

        var container = document.getElementById("score_entries_excel");
        if(ClassComponentCategoryName == 'Attendance'){
            hot = new Handsontable(container, {
                data: dataAttendance,
                afterChange: function (changes, source) {
                    if(changes != null){
                        var attendance_row = changes[0][0];
                        var attendance_column = changes[0][1];
                        var attendance_id = attendance_id_arr[attendance_row][attendance_column];
                        var attendance = changes[0][3];
                        $.ajax({
                            url: "../../class_standing_score_entry/postUpdateAttendance",
                            data: {
                                'id': attendance_id, 
                                'attendance_remark': attendance,
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
                cells : function(row, col, prop, td) {
                    var cellProperties = {};

                    if(col >= 5 && row >= 1 ){
                        return {
                            type: 'handsontable',
                            handsontable: {
                                colHeaders: false,
                                data: attendance_remark,
                                columns:[{data:'text'}]
                            }
                        }
                    }

                    if (row == 0 || col == 0){
                        cellProperties.editor = false;
                        cellProperties.renderer = firstRowRenderer;
                    }else if (col == 1 || col == 2 || col == 3 || col == 4){
                        cellProperties.readOnly = true;
                    }
                    return cellProperties;
                },
                beforeKeyDown: function (e) {
                    var selection = hot.getSelected();
                    if (!((e.keyCode === 80 || e.keyCode === 76 || e.keyCode === 65 || (e.keyCode >= 37 && e.keyCode <= 40) || e.keyCode === 13))){
                        Handsontable.dom.stopImmediatePropagation(e); // remove data at cell, shift up
                        e.preventDefault();
                    }
                }
            });
        }else{
            hot = new Handsontable(container, {
                data: data,
                beforeChange: function (changes, source) {

                    var perfect_score_column = changes[0][1];
                    var perfect_score = parseInt(perfect_score_arr[perfect_score_column]);
                    var new_score = parseInt(changes[0][3]);
                    var field = changes[0][1];
                    if(new_score > perfect_score ){
                        sweetAlert("Oops...", "Score must be lesser than or equal to perfect score!", "error");
                        return false;
                    }
                },
                afterChange: function (changes, source) {

                    if(changes != null){
                        var score_entry_row = changes[0][0];
                        var score_entry_column = changes[0][1];
                        var score_entry_id = score_entry_id_arr[score_entry_row][score_entry_column];
                        var new_score = changes[0][3];
                        $.ajax({
                            url: "../../class_standing_score_entry/postUpdateScoreEntry",
                            data: {
                                'id': score_entry_id, 
                                'score': new_score,
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
                cells : function(row, col, prop, td) {
                    var cellProperties = {};

                    if (row == 0 || col == 0) {
                        cellProperties.editor = false;
                        cellProperties.renderer = firstRowRenderer;
                        
                    }else if (col == 1 || col == 2 || col == 3 || col == 4){
                        cellProperties.readOnly = true;
                    }
                    return cellProperties;
                },
                beforeKeyDown: function (e) {
                    var selection = hot.getSelected();

                    //call a function that will check if the e.keyCode corresponds to numeric value
                    if (!((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105) || (e.keyCode >= 37 && e.keyCode <= 40) || e.keyCode === 13)){
                        Handsontable.dom.stopImmediatePropagation(e); // remove data at cell, shift up
                        e.preventDefault();
                    }
                }
            });            
        }
    });
}