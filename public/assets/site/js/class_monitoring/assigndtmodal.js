var hot;


$(document).ready(function () {
    DesirableTraitHandsonTable();
});

function DesirableTraitHandsonTable() {

    $('#assigndtmodal').on('shown.bs.modal', function (e) {

        var button =  $(e.relatedTarget)
        var StudentId = button.data('student_id')
        var StudentNo = button.data('student_no')
        var LastName = button.data('last_name')
        var FirstName = button.data('first_name')
        var MiddleName = button.data('middle_name')
        var Level = button.data('level')
        var SectionName = button.data('section_name')
        var TermName = button.data('term_name')
        var TermId = button.data('term_id')
        var ClassificationId = button.data('classification_id')
        var ClassificationLevelId = button.data('classification_level_id')

        var modal = $(this)
        $('.desirable_traits_modal').text('Assign Desirable Traits and Habits')
        $('.student_no').text(StudentNo)
        $('.student_name').text(LastName+', '+FirstName+' '+MiddleName)
        $('.student_level').text(Level+' '+SectionName)
        $('.student_term').text(StudentId)
        $('#dt_student_id').val(TermId)
        $('#dt_term_id').val(TermId)
        $('#dt_classification_id').val(ClassificationId)
        $('#dt_classification_level_id').val(ClassificationLevelId)

        $.ajax({
            url:"../../teachers_portal/class_monitoring/dataJsonDesirableTrait",
            type:'get',
            data:{  
                    'term_id' : TermId,
                    'student_id' : StudentId,
                    'classification_id' : ClassificationId,
                    'classification_level_id' : ClassificationLevelId
                },
            dataType: "json",
            async:false,
            success: function (data) { 
                DesirableTraitJson = data;
            } 
        });

        $.ajax({
            url:"../../desirable_trait_rating/dataJson",
            type:'get',
            data:{ },
            dataType: "json",
            async:false,
            success: function (data) { 
                desirable_trait_rating = data;
            }
        });

        function firstRowRenderer(instance, td, row, col, prop, value, cellProperties) {
            Handsontable.renderers.TextRenderer.apply(this, arguments);
            td.style.background = '#E6E6E6';
        }

        var data = DesirableTraitJson[0];
        var student_desirable_trait_id = DesirableTraitJson[1];

        var container = document.getElementById("desirable_traits_excel");
        hot = new Handsontable(container, {
            data: data,
            afterChange: function (changes, source) {

                if(changes != null){
                    var rating_row = changes[0][0];
                    var rating_column = changes[0][1];
                    var student_id = student_desirable_trait_id[rating_row][rating_column];
                    var rating = changes[0][3];
                    $.ajax({
                        url: "../../teachers_portal/class_monitoring/postUpdate",
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
            cells : function(row, col, prop, td) {
                var cellProperties = {};
                // alert(data[row][0]);
                if(col >= 1 && row >= 1 && data[row][0] != 'WORK HABITS' && data[row][0] != 'SOCIAL DEVELOPMENT' && data[row][0] != 'PERSONAL DEVELOPMENT'){
                    return {
                        type: 'handsontable',
                        handsontable: {
                            colHeaders: false,
                            data: desirable_trait_rating,
                            columns:[{data:'text'}]
                        }
                    }
                }
                if(data[row][0] != 'WORK HABITS' && data[row][0] != 'SOCIAL DEVELOPMENT' && data[row][0] != 'PERSONAL DEVELOPMENT'){
                    
                    cellProperties.editor = false;
                }

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
                if (!(e.keyCode === 69 || e.keyCode === 71 || e.keyCode === 73 || e.keyCode === 78 || e.keyCode === 83 || e.keyCode === 8)){
                    Handsontable.dom.stopImmediatePropagation(e); // remove data at cell, shift up
                    e.preventDefault();
                }
                    // alert(e.keyCode);
            }
        });   
    });
}