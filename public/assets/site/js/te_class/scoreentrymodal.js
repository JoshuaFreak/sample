var hot;


$(document).ready(function () {
    ScoreEntryHandsonTable();
});

function ScoreEntryHandsonTable() {

    $('#scoreentrymodal').on('shown.bs.modal', function (e) {

        var button =  $(e.relatedTarget)
        var ClassStandingComponentDetailId = button.data('id')
        var ClassStandingComponentDetailDescription = button.data('description')
        var ClassStandingComponentDetailDate = button.data('date')
        var ClassStandingComponentDetailPerfectScore = button.data('perfect_score')
        var classId = button.data('class_id')
        var ClassComponentCategoryName = button.data('name')

        var modal = $(this)
        $('.score_entry').text('Score Entry : ' + ClassComponentCategoryName)
        $('#description').val(ClassStandingComponentDetailDescription)
        $('#date').val(ClassStandingComponentDetailDate)
        $('#perfect_score').val(ClassStandingComponentDetailPerfectScore)
        $('#class_id').val(classId)

        $.ajax({
            url:"../../class_standing_score_entry/dataJson",
            type:'get',
            data:{  
                    'class_standing_component_detail_id' : ClassStandingComponentDetailId
                },
            dataType: "json",
            async:false,
            success: function (data) { 
                studentJsonArray = data;
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

        var data = studentJsonArray;
        var container = document.getElementById("score_entry_excel");
        if(ClassComponentCategoryName == 'Attendance'){
            hot = new Handsontable(container, {
                data: data,
                afterChange: function (change, source) {
                    $.ajax({
                        url: "../../class_standing_score_entry/post_update",
                        data: {
                            'data': data,
                            '_token': $('input[name=_token]').val(), //returns all cells' data
                        }, 
                        dataType: 'json',
                        type: 'POST',
                        async:false
                    });
                },
                colHeaders: ["ID","Student Id", "Last Name", "First Name", "Middle Name", "Attendance"],
                rowHeaders: true,
                className: "htCenter htMiddle",
                height: 300,
                currentRowClassName: 'currentRow',
                currentColClassName: 'currentCol',
                colWidths:[50,100,100,100,100,100],
                columns: [{   
                    data: 'id',
                    readOnly: true 
                },
                {   
                    data: 'student_no',
                    readOnly: true 
                },
                {   
                    data: 'last_name',
                    readOnly: true  
                },
                { 
                    data: 'first_name',
                    readOnly: true  
                },
                { 
                    data: 'middle_name',
                    readOnly: true  
                },
                {
                    data:'attendance_remarks_code',
                    type: 'handsontable',
                    handsontable: {
                        colHeaders: false,
                        data: attendance_remark,
                        columns:[{data:'text'}]
                    }
                }],
                beforeKeyDown: function (e) {
                    var selection = hot.getSelected();

                    //call a function that will check if the e.keyCode corresponds to numeric value
                    var score_entry_column = selection[1];
                    if (!((e.keyCode === 80 || e.keyCode === 76 || e.keyCode === 65 || e.keyCode === 13 || (e.keyCode >= 37 && e.keyCode <= 40)))){
                        Handsontable.dom.stopImmediatePropagation(e); // remove data at cell, shift up
                        e.preventDefault();
                    }
                }
            });
        }else{
            hot = new Handsontable(container, {
                data: data,
                beforeChange: function (changes, source) {

                    //you need to have the perfect score
                    //compare the perfect to the new cell value

                    var perfect_score = parseInt(ClassStandingComponentDetailPerfectScore);
                    var new_score = parseInt(changes[0][3]);
                    var field = changes[0][1];
                    if(field == "score"){ 
                        if(new_score > perfect_score ){

                            sweetAlert("Oops...", "Score must be lesser than or equal to perfect score!", "error");
                            return false;
                        }
                    }
                },
                afterChange: function (change, source) {
                    $.ajax({
                        url: "../../class_standing_score_entry/post_update",
                        data: {
                            'data': data,
                            '_token': $('input[name=_token]').val(), //returns all cells' data
                        }, 
                        dataType: 'json',
                        type: 'POST',
                        async:false
                    });
                },
                colHeaders: ["ID","Student Id", "Last Name", "First Name", "Middle Name", "Score", "Attendance"],
                rowHeaders: true,
                className: "htCenter htMiddle",
                height: 300,
                currentRowClassName: 'currentRow',
                currentColClassName: 'currentCol',
                colWidths:[50,100,100,100,100,80,100],
                columns: [{   
                    data: 'id',
                    readOnly: true 
                },
                {   
                    data: 'student_no',
                    readOnly: true 
                },
                {   
                    data: 'last_name',
                    readOnly: true  
                },
                { 
                    data: 'first_name',
                    readOnly: true  
                },
                { 
                    data: 'middle_name',
                    readOnly: true  
                },
                { 
                    data: 'score'
                },
                {
                    data:'attendance_remarks_code',
                    type: 'handsontable',
                    handsontable: {
                        colHeaders: false,
                        data: attendance_remark,
                        columns:[{data:'text'}]
                    }
                }],
                beforeKeyDown: function (e) {
                    var selection = hot.getSelected();

                    //call a function that will check if the e.keyCode corresponds to numeric value
                    var score_entry_column = selection[1];
                    if(score_entry_column == '6'){
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
                }
            });            
        }
    });
}