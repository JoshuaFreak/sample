var hot;


$(document).ready(function () {
    PACEHandsonTable();
});

function PACEHandsonTable() {

    $('#academicprojectionmodal').on('shown.bs.modal', function (e) {
        $(".htCore tbody tr").remove();
        
        var button =  $(e.relatedTarget)
        var StudentId = button.data('student_id')
        var StudentNo = button.data('student_no')
        var LastName = button.data('last_name')
        var FirstName = button.data('first_name')
        var MiddleName = button.data('middle_name')
        var BirthDate = button.data('birthdate')
        var Level = button.data('level')
        var SectionName = button.data('section_name')
        var TermName = button.data('term_name')
        var TermId = button.data('term_id')
        var ClassificationId = button.data('classification_id')
        var ClassificationName = button.data('classification_name')
        var ClassificationLevelId = button.data('classification_level_id')

        var modal = $(this)
        //i just changed the name Academic Projection to SPC
        $('.pace_modal').text('Supervisor Progress Chart')
        $('.ap_student_no').text(StudentNo)
        $('.ap_student_name').text(LastName+', '+FirstName+' '+MiddleName)
        $('.ap_birthdate').text(BirthDate)
        $('#ap_level').text(Level+' '+SectionName)
        $('#ap_term').text(TermName)
        $('#ap_student_id').val(StudentId)
        $('#ap_term_id').val(TermId)
        $('#ap_classification_id').val(ClassificationId)
        $('#ap_classification_name').text(ClassificationName)

        $.ajax({
            url:"../../teachers_portal/academic_projection/dataJsonStudent",
            type:'get',
            data:{  
                    'term_id' : TermId,
                    'student_id' : StudentId,
                    'classification_level_id' : ClassificationLevelId
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

        function yellowRenderer(instance, td, row, col, prop, value, cellProperties) {
            Handsontable.renderers.TextRenderer.apply(this, arguments);
            td.style.background = '#FEDF62';
        }

        function redRenderer(instance, td, row, col, prop, value, cellProperties) {
            Handsontable.renderers.TextRenderer.apply(this, arguments);
            td.style.background = '#FF7E79';
        }

        function greenRenderer(instance, td, row, col, prop, value, cellProperties) {
            Handsontable.renderers.TextRenderer.apply(this, arguments);
            td.style.background = '#9CE05A';
        }

        function blueRenderer(instance, td, row, col, prop, value, cellProperties) {
            Handsontable.renderers.TextRenderer.apply(this, arguments);
            td.style.background = '#64B3DE';
        }

        function lavenderRenderer(instance, td, row, col, prop, value, cellProperties) {
            Handsontable.renderers.TextRenderer.apply(this, arguments);
            td.style.background = '#D783FF';
        }

        var data = paceArray[0];
        var grade_id_arr = paceArray[1];
        var detail_arr = paceArray[2];
        var str = '<tr id="header-grouping">'+'<th colspan="2"></th>'+'<th colspan="6">First</th>' + 
          '<th colspan="6">Second</th>'+ '<th colspan="6">Third</th>' + '<th colspan="6">Fourth</th>'+'</tr>';  
        
        var container = document.getElementById("academic_projection_excel");
        hot = new Handsontable(container, {
            data: data,
            afterRender  : function () {$('.htCore > thead > tr').before(str);},
            // colHeaders: true,
            afterChange: function (changes, source) {

                if(changes != null){
                    var score_entry_row = changes[0][0];
                    var score_entry_column = changes[0][1];
                    var score_entry_id = grade_id_arr[score_entry_row][score_entry_column];
                    var detail = detail_arr[score_entry_row][score_entry_column];
                    var new_data = changes[0][3];
                    // alert(score_entry_id);
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
            height: 350,
            // currentRowClassName: 'currentRow',
            // currentColClassName: 'currentCol',
            // fixedRowsTop: 1,
            fixedColumnsLeft: 2,
            cells : function(row, col, prop, td) {
                var cellProperties = {};
                // alert(data[row][0]);
                if (col == 0 || col == 1) {
                    cellProperties.editor = false;
                    cellProperties.renderer = firstRowRenderer;
                    
                }


                if(data[row][1] == "DATE RELEASED" || data[row][1] == "DATE TAKEN" ) { 
                    if(col >= 2 ){
                        cellProperties.placeholder = "mm/dd"; 
                    } 
                } 

                if (data[row][0] == "Mathematics" || data[row][0] == "Algebra I" ||  data[row][0] == "Algebra II" ||  data[row][0] == "Geometry" ||  data[row][0] == "General Math") {
                    cellProperties.renderer = yellowRenderer;
                    
                }else if (data[row][0] == "English" || data[row][0] == "English and Literature" || data[row][0] == "English 1") {
                    cellProperties.renderer = redRenderer;
                    
                }else if (data[row][0] == "Social Studies" || data[row][0] == "Phil. History & Geography" ||  data[row][0] == "Asian History" ||  data[row][0] == "Philippine Economics" ||  data[row][0] == "World History" ||  data[row][0] == "Basic History and Civilization" ||  data[row][0] == "Elective: Rizal") {
                    cellProperties.renderer = greenRenderer;
                    
                }else if (data[row][0] == "Science" || data[row][0] == "Biology" || data[row][0] == "Physical Science" || data[row][0] == "Chemistry" || data[row][0] == "Physics") {
                    cellProperties.renderer = blueRenderer;
                    
                }else if (data[row][0] == "Word Building" || data[row][0] == "Etymology" || data[row][0] == "Oral Communication") {
                    cellProperties.renderer = lavenderRenderer;
                    
                }
                return cellProperties;
            },
            beforeKeyDown: function (e) {
                var selection = hot.getSelected();
                if (!((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105) || (e.keyCode >= 37 && e.keyCode <= 40) || e.keyCode === 13 || e.keyCode === 111 || e.keyCode === 191 || e.keyCode === 8)){
                    Handsontable.dom.stopImmediatePropagation(e); // remove data at cell, shift up
                    e.preventDefault();
                }
                    // alert(e.keyCode);
            }
        });   
    });
}