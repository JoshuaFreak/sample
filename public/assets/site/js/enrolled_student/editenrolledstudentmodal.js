$(document).ready(function(){
    $('#editEnrolledStudent').on('show.bs.modal', function(e) {   

        var button =  $(e.relatedTarget)
        var EnrollmentId = button.data('enrollment_id')
        var StudentId = button.data('student_id')
        var LastName = button.data('last_name')
        var FirstName = button.data('first_name')
        var MiddleName = button.data('middle_name')
        var PaymentSchemeId = button.data('payment_scheme_id')

        var modal = $(this)
        $('.edit_enrolled_student_modal').text('Edit Student Payment Scheme')
        $('#student_id').val(StudentId)
        $('#enrollment_id').val(EnrollmentId)
        $('#edit_enrolled_student_name').text(LastName+', '+FirstName+' '+MiddleName)
        $('#payment_scheme_id').val(PaymentSchemeId)

        // $.ajax({
        //       url:"enroll_student/editdataJson",
        //       type:'get',
        //       data:
        //           {  
        //             'student_id' : StudentId
        //           },
        //       dataType: "json",
        //       async:false

        // }).done(function(data) {
            
        //     if(data.length > 0)
        //     {
        //         $.each( data, function( key, item ) {

        //             $("#edit_enrolled_student_payment_scheme").val(item.payment_scheme_id);
        //         });
        //     }
        // });

     // var payment_scheme_id = $("#edit_enrolled_student_payment_scheme").val();
     //    $.ajax({
     //        url:"payment_scheme/dataJson",
     //        type:'GET',
     //        data:
     //            {  
     //                'id': $("#payment_scheme_id").val(),
     //            },
     //        dataType: "json",
     //        async:false,
     //        success: function (data) 
     //        {    
                
     //            $("#edit_enrolled_student_payment_scheme").empty();
     //            $("#edit_enrolled_student_payment_scheme").append('<option value=""></option>');
     //            $.map(data, function (item) 
     //            {       
     //                    payment_scheme_name = item.text;

     //                    $("#edit_enrolled_student_payment_scheme").append('<option value="'+item.value+'">'+payment_scheme_name+'</option>');
     //            });
     //        }  
     //    });

        
    });

        $(".EditEnrolledStudent").click(function () {
            $.ajax({
                url:"enroll_student/postEditdataJson",
                type:'post',
                data:{ 
                        'id': $("#enrollment_id").val(),
                        'payment_scheme_id': $("#edit_enrolled_student_payment_scheme").val(),
                        '_token': $('input[name=_token]').val(),
                    },
                async:false
            });
            $("#editEnrolledStudent").modal('hide');
            swal("Edited!", "Successfully Edited Enrolled Student", "success"); 
            // location.reload();
        });

    });
// });