var hot;

$(document).ready(function () {
    ViewAllMEDHandsonTable();
});

function ViewAllMEDHandsonTable() {

    $('#viewallmedmodal').on('shown.bs.modal', function (e) {

        var button =  $(e.relatedTarget)
        var classId = button.data('id')

        var modal = $(this)
        $('.view_all_med').text('View All Entries')

        $.ajax({
            url:"../../major_exam_score_entry/dataJsonViewAll",
            type:'get',
            data:{  
                    'class_id' : classId
                },
            dataType: "json",
            async:false,
            success: function (data){ 
                ScoreEntryJson = data;
            }
        });

        function firstRowRenderer(instance, td, row, col, prop, value, cellProperties) {
            Handsontable.renderers.TextRenderer.apply(this, arguments);
            td.style.background = '#E6E6E6';
        }

        var data = ScoreEntryJson[0];
        var score_entry_id_arr = ScoreEntryJson[1];
        var perfect_score_arr = ScoreEntryJson[2];

        var container = document.getElementById("view_all_entries_modal");
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
                    $.ajax({
                        url: "../../major_exam_score_entry/postUpdateScoreEntry",
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
            cells : function(row, col, prop, td){
                var cellProperties = {};
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
                if (!((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105) || (e.keyCode >= 37 && e.keyCode <= 40) || e.keyCode === 13)){
                    Handsontable.dom.stopImmediatePropagation(e); // remove data at cell, shift up
                    e.preventDefault();
                }
            }
        });   
    });
}