var hot;

$(document).ready(function () {
    paceHandsonTable();
});

function paceHandsonTable() {

    $('#pacemodal').on('shown.bs.modal', function (e) {

        var button =  $(e.relatedTarget)
        var Id = button.data('id')
        var Name = button.data('grading_period_name')
        var TermId = button.data('term_id')
        var SubjectId = button.data('subject_id')
        var SectionId = button.data('section_id')

        var modal = $(this)
        $('.pace_modal').text('Academic Projection - ' +Name)
        $('#pace_term_id').val(TermId)
        $('#pace_subject_id').val(SubjectId)

        $.ajax({
            url:"../../teachers_portal/academic_projection/dataJson",
            type:'get',
            data:{  
                    'term_id' : TermId,
                    'subject_id' : SubjectId,
                    'grading_period_id' : Id,
                    'section_id' : SectionId
                },
            dataType: "json",
            async:false,
            success: function (data) { 
                paceArray = data;
            } 
        });

        function firstRowRenderer(instance, td, row, col, prop, value, cellProperties) {
            Handsontable.renderers.TextRenderer.apply(this, arguments);
            td.style.background = '#E6E6E6';
        }

        var data = paceArray[0];
        var grade_id_arr = paceArray[1];
        var detail_arr = paceArray[2];

        var container = document.getElementById("pace_excel");
        hot = new Handsontable(container, {
            data: data,
            afterChange: function (changes, source) {
                if(changes != null){
                    var score_entry_row = changes[0][0];
                    var score_entry_column = changes[0][1];
                    var score_entry_id = grade_id_arr[score_entry_row][score_entry_column];
                    var detail = detail_arr[score_entry_row][score_entry_column];
                    var new_data = changes[0][3];
                    // alert(detail);
                    $.ajax({
                        url: "../../teachers_portal/academic_projection/postUpdatePace",
                        data: {
                            'id': score_entry_id, 
                            'detail': detail, 
                            'data': new_data,
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
                if (!((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105) || (e.keyCode >= 37 && e.keyCode <= 40) || e.keyCode === 13 || e.keyCode === 8 || e.keyCode === 111 || e.keyCode === 191  || e.keyCode === 51   || e.keyCode === 117 )){
                    Handsontable.dom.stopImmediatePropagation(e); // remove data at cell, shift up
                    e.preventDefault();
                }
                    // alert(e.keyCode);
            }
        }); 
    });
}