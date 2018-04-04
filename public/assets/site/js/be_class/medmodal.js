var hot;

$(document).ready(function () {
    MajorExamScoreEntryHandsonTable();
});

function MajorExamScoreEntryHandsonTable() {

    $('#medmodal').on('shown.bs.modal', function (e) {

        var button =  $(e.relatedTarget)
        var GradingPeriodId = button.data('id')
        var ClassId = button.data('class_id')
        var GradingPeriodName = button.data('grading_period_name')

        var modal = $(this)
        $('.med_modal').text('Major Exam Detail : ' + GradingPeriodName)
        $('#med_class_id').val(ClassId)
        $('#med_grading_period_id').val(GradingPeriodId)
        $(this).find('#view_all').attr('data-id',ClassId);

        $("#med_id").val("");
        $("#med_perfect_score").val("");
        $("#med_date").val("");

        $.ajax({
            url:"../../major_exam_detail/EditdataJson",
            type:'get',
            data:{ 
                    'class_id' : ClassId,
                    'grading_period_id' : GradingPeriodId
                },
            dataType: "json",
            async:false 
        }).done(function(data) {
            if(data.length > 0){
                $.each( data, function( key, item ) {
                    $("#med_id").val(item.id);
                    $("#med_perfect_score").val(item.perfect_score);
                    $("#med_date").val(item.date);
                });
            }
        });

        $.ajax({
            url:"../../major_exam_score_entry/dataJson",
            type:'get',
            data:{  
                    'major_exam_detail_id' : $("#med_id").val()
                },
            dataType: "json",
            async:false,
            success: function (data){ 
                studentJsonArray = data;
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

        var data = studentJsonArray;
        var container = document.getElementById("major_exam_detail_excel");
        hot = new Handsontable(container, {
            data: data,
            beforeChange: function (changes, source) {

                //you need to have the perfect score
                //compare the perfect to the new cell value
                // alert(JSON.stringify(changes[0][1]));
                var perfect_score = parseInt($("#med_perfect_score").val());
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
                    url: "../../major_exam_score_entry/post_update",
                    data: {
                        'data': data, //returns all cells' data,
                        '_token': $('input[name=_token]').val(),
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
                data: 'score',
                type: 'numeric'
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
                var selection = hot.getSelected(); //call a function that will check if the e.keyCode corresponds to numeric value
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
    });
}

$(".btnSaveMED").click(function () {
    $.ajax({
        url:"../../major_exam_detail/postUpdate",
        type:'post',
        data:{ 
                'id': $("#med_id").val(),
                'perfect_score': $("#med_perfect_score").val(),
                'date': $("#med_date").val(),
                'class_id': $("#med_class_id").val(),
                'grading_period_id': $("#med_grading_period_id").val(),
                '_token': $('input[name=_token]').val(),
            },
        async:false
    });
    $("#medmodal").modal('hide');
    swal("Saved!", "Successfully Updated Major Exam Detail", "success"); 
});