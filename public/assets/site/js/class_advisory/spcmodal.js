var hot;


$(document).ready(function () {
    SPCHandsonTable();
});

function SPCHandsonTable() {

    $('#spcmodal').on('shown.bs.modal', function (e) {

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
        var ClassificationName = button.data('classification_name')
        var ClassificationLevelId = button.data('classification_level_id')

        var modal = $(this)
        //i just changed the name SPC to Academic Projection  
        $('.spc_modal').text('Academic Projection')
        $('.spc_student_no').text(StudentNo)
        $('.spc_student_name').text(LastName+', '+FirstName+' '+MiddleName)
        $('.spc_level').text(Level+' '+SectionName)
        $('.spc_term').text(TermName)
        $('#spc_classification_name').text(ClassificationName)
        $('#spc_student_id').val(StudentId)
        $('#spc_term_id').val(TermId)
        $('#spc_classification_id').val(ClassificationId)
        $('#spc_classification_level_id').val(ClassificationLevelId)

        $.ajax({
            url:"../../teachers_portal/academic_projection/dataJsonSPC",
            type:'get',
            data:{  
                    'classification_id' : ClassificationId,
                    'student_id' : StudentId,
                    'term_id' : TermId
                },
            dataType: "json",
            async:false,
            success: function (data) { 
                SPCArray = data;
            } 
        });

        function firstRowRenderer(instance, td, row, col, prop, value, cellProperties) {
            Handsontable.renderers.TextRenderer.apply(this, arguments);
            td.style.fontWeight = 'bold';
        }
        function requiredRenderer(instance, td, row, col, prop, value, cellProperties) {
            Handsontable.renderers.TextRenderer.apply(this, arguments);
            td.style.background = '#FEE062';
        }
        function releasedRenderer(instance, td, row, col, prop, value, cellProperties) {
            Handsontable.renderers.TextRenderer.apply(this, arguments);
            td.style.background = '#9DE15A';
        }
        function endRenderer(instance, td, row, col, prop, value, cellProperties) {
            Handsontable.renderers.TextRenderer.apply(this, arguments);
            td.style.background = '#E6E6E6';
        }

        var data = SPCArray[0];
        var pace_arr = SPCArray[1];

        var str = '<tr id="header-grouping">'+'<th colspan="2"></th>'+'<th colspan="6">First</th>' + 
          '<th colspan="6">Second</th>'+ '<th colspan="6">Third</th>' + '<th colspan="6">Fourth</th>'+'</tr>';  
        
        var container = document.getElementById("spc_excel");
        hot = new Handsontable(container, {
            data: data,
            afterRender  : function () {$('.htCore > thead > tr').before(str);},
            // colHeaders: true,
            // afterChange: function (changes, source) {

            //     if(changes != null){
            //         var score_entry_row = changes[0][0];
            //         var score_entry_column = changes[0][1];
            //         var score_entry_id = grade_id_arr[score_entry_row][score_entry_column];
            //         var detail = detail_arr[score_entry_row][score_entry_column];
            //         var new_data = changes[0][3];
            //         // alert(score_entry_id);
            //         $.ajax({
            //             url: "../../teachers_portal/academic_projection/postUpdatePace",
            //             data: {
            //                 'id': score_entry_id, 
            //                 'detail': detail, 
            //                 'data': new_data,
            //                 '_token': $('input[name=_token]').val(),
            //             }, 
            //             dataType: 'json',
            //             type: 'POST',
            //             async:false
            //         });
            //     }
            // },
            // className: "htCenter htMiddle",
            height: 350,
            // currentRowClassName: 'currentRow',
            // currentColClassName: 'currentCol',
            // fixedRowsTop: 1,
            fixedColumnsLeft: 2,
            cells : function(row, col, prop, td) {
                var cellProperties = {};

                // alert(pace_arr[row][col]);

                if(pace_arr[row][col] == 'finished_pace'){
                    cellProperties.editor = false;
                    cellProperties.renderer = endRenderer;
                }else if(pace_arr[row][col] == 'released_pace'){
                    cellProperties.editor = false;
                    cellProperties.renderer = releasedRenderer;
                }else if(pace_arr[row][col] == 'required_pace'){
                    cellProperties.editor = false;
                    cellProperties.renderer = requiredRenderer;
                }else if(pace_arr[row][col] == 'end_pace'){
                    cellProperties.editor = false;
                    cellProperties.renderer = endRenderer;
                }

                if(col == 0){
                    cellProperties.editor = false;
                    cellProperties.renderer = firstRowRenderer;
                }else if (col >= 0 || row >= 0) {
                    cellProperties.editor = false;                    
                }
                return cellProperties;
            }
        });   
    });
}