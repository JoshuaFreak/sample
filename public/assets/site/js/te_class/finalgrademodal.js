var hot;

$(document).ready(function () {
    FinalGradeHandsonTable();
});

function FinalGradeHandsonTable() {

    $('#finalgrademodal').on('shown.bs.modal', function (e) {

        var button =  $(e.relatedTarget)
        var Id = button.data('id')
        var SectionName = button.data('section_name')
        var Code = button.data('code')

        var modal = $(this)
        $('.final_grade').text('Final Grade - Section : ' + SectionName+' - '+Code)
        $('#class_id').val(Id)

        $.ajax({
            url:"../../teachers_portal/te_class/dataJson",
            type:'get',
            data:
                {  
                    'class_id' : Id
                },
            dataType: "json",
            async:false,
            success: function (data) 
            { 
                studentJsonArray = data;
            } 

        });
        var str = '<tr id="header-grouping">'+'<th colspan="6"></th>'+'<th colspan="4">Prelim</th>' + 
          '<th colspan="4">Midterm</th>' + '<th colspan="5">Final</th>'+'<th colspan="1"></th>'+'</tr>';  
        var data = studentJsonArray;
        var container = document.getElementById("final_grade_excel");
        hot = new Handsontable(container, {
            data: data,
            afterRender  : function () {$('.htCore > thead > tr').before(str);},
            colHeaders: ["ID","Student Id", "Last Name", "First Name", "Middle Name", "CS", "ME", "PG", "CG", "CS", "ME", "PG", "CG", "CS", "ME", "PG", "CG", "FG", "Remark"],
            rowHeaders: true,
            height: 300,
            fixedColumnsLeft: 5,
            colWidths:[50,80,100,100,100,50,50,50,50,50,50,50,50,50,50,50,50,50,80],
            columns: [
            {   
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
                data: 'cs',
                readOnly: true  
            },
            { 
                data: 'me',
                readOnly: true  
            },
            {
                data: 'pg',
                readOnly: true
            },
            { 
                data: 'cg',
                readOnly: true  
            },
            { 
                data: 'cs',
                readOnly: true  
            },
            { 
                data: 'me',
                readOnly: true  
            },
            {
                data: 'pg',
                readOnly: true
            },
            { 
                data: 'cg',
                readOnly: true  
            },
            { 
                data: 'cs',
                readOnly: true  
            },
            { 
                data: 'me',
                readOnly: true  
            },
            {
                data: 'pg',
                readOnly: true
            },
            { 
                data: 'cg',
                readOnly: true  
            },
            { 
                data: 'fg',
                readOnly: true  
            },
            { 
                data: 'remark',
                readOnly: true  
            }
            ]
        });
    });
    $('#finalgrademodal').on('hidden.bs.modal', function () {
        window.location.reload(true);
    });
}