var hot;

$(document).ready(function () {
    gradeComputationHandsonTable();
});

function gradeComputationHandsonTable() {

    $('#gradecomputationModal').on('shown.bs.modal', function (e) {

        var button =  $(e.relatedTarget)
        var Id = button.data('id')
        var SectionName = button.data('section_name')
        var Code = button.data('code')

        var modal = $(this)
        $('.grade_computation').text('Computation of Grade - Section : ' + SectionName+' - '+Code)
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

        var data = studentJsonArray;
        var container = document.getElementById("grade_computation_excel");
        hot = new Handsontable(container, {
            data: data,
            colHeaders: ["ID","Student Id", "Last Name", "First Name", "Middle Name", "Prelim", "Midterm", "Final", "IsEditable", "IsGraduating"],
            rowHeaders: true,
            height: 300,
                colWidths:[50,80,100,100,100,80,80,80,90,90],
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
                    data: 'prelim',
                    readOnly: true  
                },
                { 
                    data: 'midterm',
                    readOnly: true  
                },
                {
                    data: 'final'
                },
                { 
                    data: 'is_editable',
                    readOnly: true  
                },
                { 
                    data: 'is_graduating',
                    readOnly: true  
                }
                ]
        });
    });
}