var hot;

$(document).ready(function () {
    EditClassRecordHandsonTable();
});

function EditClassRecordHandsonTable() {

    $('#editclassrecordmodal').on('shown.bs.modal', function (e) {

        var button =  $(e.relatedTarget)
            var GradingPeriodId = button.data('id')
            var ClassId = button.data('class_id')
            var GradingPeriodName = button.data('grading_period_name')

            var modal = $(this)
            $('.edit_class_record').text('Edit All Class Component Record : ' + GradingPeriodName)

        $.ajax({
            url:"../../class_record/dataJsonEdit",
            type:'get',
            data:{  
                    'grading_period_id' : GradingPeriodId,
                    'class_id' : ClassId
                },
            dataType: "json",
            async:false,
            success: function (data){ 
                ScoreEntryJson = data;
            } 
        });

        $.ajax({
            url:"../../attendance_remark/dataJson",
            type:'get',
            data:{ },
            dataType: "json",
            async:false,
            success: function (data){ 
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
        var container = document.getElementById("edit_class_record_excel");
        hot = new Handsontable(container, {
            data: data,
            beforeChange: function (changes, source) {

                //you need to have the perfect score
                //compare the perfect to the new cell value
                // alert(changes);
                var perfect_score_column = changes[0][1];
                var perfect_score = parseInt(perfect_score_arr[perfect_score_column]);
                var new_score = parseInt(changes[0][3]);
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
                    var attendance_remark = changes[0][3];
                    if(data[0][score_entry_column] == 'Attendance'){
                        $.ajax({
                            url: "../../class_standing_score_entry/postUpdateAttendance",
                            data: {
                                'id': score_entry_id, 
                                'attendance_remark': attendance_remark,
                                '_token': $('input[name=_token]').val(),
                            }, 
                            dataType: 'json',
                            type: 'POST',
                            async:false
                        });
                    }else{
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
                }
            },
            className: "htCenter htMiddle",
            height: 300,
            currentRowClassName: 'currentRow',
            currentColClassName: 'currentCol',
            fixedRowsTop: 3,
            mergeCells: [
                {row: 0, col: 0, rowspan: 3, colspan: 1},
                {row: 0, col: 1, rowspan: 3, colspan: 1},
                {row: 0, col: 2, rowspan: 3, colspan: 1},
                {row: 0, col: 3, rowspan: 3, colspan: 1},
                {row: 0, col: 4, rowspan: 3, colspan: 1}
            ],
            cells: function (row, col, prop) {
              var cellProperties = {};

                if(data[0][col] == 'Attendance'){
                    if(col === col && row >= 3 ){
                        return {
                            type: 'handsontable',
                            handsontable: {
                                colHeaders: false,
                                data: attendance_remark,
                                columns:[{data:'text'}]
                            }
                        }
                    }
                }

                if(row === 0 || row === 1|| row === 2 || col === 0 || col === 1 || col === 2 || col === 3 || col === 4) {
                    cellProperties.editor = false;
                }
                if (row === 0 || row === 1|| row === 2 || col === 0) {
                    cellProperties.renderer = firstRowRenderer; // uses function directly
                }

              return cellProperties;
            },
            beforeKeyDown: function (e) {
                var selection = hot.getSelected();
                //call a function that will check if the e.keyCode corresponds to numeric value
                var score_entry_column = selection[1];
                if(data[0][score_entry_column] == 'Attendance'){
                    if (!((e.keyCode === 80 || e.keyCode === 76 || e.keyCode === 65 || e.keyCode === 13 || (e.keyCode >= 37 && e.keyCode <= 40)))){
                        Handsontable.dom.stopImmediatePropagation(e); // remove data at cell, shift up
                        e.preventDefault();
                    }
                }else{
                    if (!((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105) || (e.keyCode >= 37 && e.keyCode <= 40) || e.keyCode === 13)){
                        Handsontable.dom.stopImmediatePropagation(e); // remove data at cell, shift up
                        e.preventDefault();
                    }
                }
            },
        });
    });
    $("#classrecordmodal").draggable({
        handle: ".modal-header"
    });
}