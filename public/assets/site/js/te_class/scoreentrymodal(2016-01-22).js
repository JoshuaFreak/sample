$(document).ready(function(){

        $('#scoreentrymodal').on('show.bs.modal', function(e) {  

            var button =  $(e.relatedTarget)
            var cId = button.data('id')
            var cDescription = button.data('description')
            var cDate = button.data('date')
            var cPerfectScore = button.data('perfect_score')
            var classId = button.data('class_id')
            var Cname = button.data('name')

            var modal = $(this)
            $('.modal-title').text('Score Entry : ' + Cname)
            $('#description').val(cDescription)
            $('#date').val(cDate)
            $('#perfect_score').val(cPerfectScore)
            $('#class_id').val(classId)



            var container = document.getElementById('score_entry_excel'),hot;

            var data = getStudentJSon();

            hot = new Handsontable(container,
            {
                
                data: data, 
                 colHeaders: ["ID","Student Id", "Last Name", "First Name", "Middle Name", "Score", "Remark"],
                rowHeaders: true,
                height: 400,
                colWidths:[100,100,100,100,100,100,100],
                columns: [
                {   
                    data: 'id',
                    readOnly: true 
                },{   
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
                        data: getAttendanceRemark(),
                        columns:[{data:'text'}]
                    }
                }]

            });




            function getStudentJSon(){
                $.ajax({
                      url:"../../class_standing_score_entry/dataJson",
                      type:'get',
                      data:
                          {  
                            'class_standing_component_detail_id' : cId
                          },
                      dataType: "json",
                      async:false,
                      success: function (data) 
                      { 
                        studentJsonArray = data;
                      } 

                });

                return studentJsonArray;
            }

            function getAttendanceRemark(){
                              

                $.ajax({
                      url:"../../attendance_remark/dataJson",
                      type:'get',
                      data:
                          {  
                          },
                      dataType: "json",
                      async:false,
                      success: function (data) 
                      { 
                        attendance_remark = data;
                      }  

                });

                return attendance_remark;
            }

            $(".btnUpdate").click(function () {
                
                $(".btnUpdate").each(function(){
                    $.ajax({
                        url: "../../class_standing_score_entry/post_update",
                        data: {
                            'data': data //returns all cells' data
                        }, 
                        dataType: 'json',
                        type: 'POST',
                        async:false
                    });
                });

                alert("Data Saved"); 
            });


        }); 

});