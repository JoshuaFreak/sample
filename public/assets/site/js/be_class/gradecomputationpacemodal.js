var hot;

$(document).ready(function () {
    gradeComputationpaceHandsonTable();
});

function gradeComputationpaceHandsonTable() {

    $('#gradecomputationpacemodal').on('shown.bs.modal', function (e) {

        var button =  $(e.relatedTarget)
        var Id = button.data('id')
        var Level = button.data('level')
        var SectionName = button.data('section_name')
        var Name = button.data('name')
        var ClassificationId = button.data('classification_id')
        var TermId = button.data('term_id')
        var SubjectId = button.data('subject_id')

        var modal = $(this)
        $('.grade_computation_pace').text('Computation of Grade - ' + Level +' '+SectionName+' ('+Name+')')
        $('#pace_class_id').val(Id)
        $('#pace_classification_id').val(ClassificationId)
        $('#pace_term_id').val(TermId)

        $.ajax({
            url:"../../teachers_portal/be_class/dataJsonGradeComputationPace",
            type:'get',
            data:
                {  
                    'class_id' : Id,
                    'classification_id' : ClassificationId,
                    'term_id' : TermId,
                    'subject_id' : SubjectId
                },
            dataType: "json",
            async:false,
            success: function (data) 
            { 
                paceArray = data;
            } 

        });

        function firstRowRenderer(instance, td, row, col, prop, value, cellProperties) {
            Handsontable.renderers.TextRenderer.apply(this, arguments);
            td.style.background = '#E6E6E6';
        }

        var data = paceArray[0];
        var grade_id_arr = paceArray[1];

        var container = document.getElementById("grade_computation_pace_excel");
        hot = new Handsontable(container, {
            data: data,
            className: "htCenter htMiddle",
            height: 300,
            currentRowClassName: 'currentRow',
            currentColClassName: 'currentCol',
            fixedRowsTop: 1,
            cells : function(row, col, prop, td){
                var cellProperties = {};
                if(row != 0 || row === 0) {
                    cellProperties.editor = false;
                }
                if (row === 0 || col === 0) {
                    cellProperties.renderer = firstRowRenderer; // uses function directly
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