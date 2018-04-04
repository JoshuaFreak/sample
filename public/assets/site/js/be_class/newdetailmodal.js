$(document).ready(function(){
		$('#newdetailmodal').on('show.bs.modal', function(e) { 

            var button =  $(e.relatedTarget)
            var ClassStandingComponentId = button.data('id')
            var ClassComponentCategoryName = button.data('ccc_name')
            var classId = button.data('class_id')

            var modal = $(this)
            $('.new_detail').text('Create New Detail : ' + ClassComponentCategoryName)
            $('#new_detail_class_standing_component_id').val(ClassStandingComponentId)
            $('#new_detail_class_id').val(classId)

        });

        $(".SaveNewDetail").click(function () {
            $.ajax({
                url:"../../class_standing_component_detail/create",
                type:'post',
                data: 
                    { 
                        'class_standing_component_id': $("#new_detail_class_standing_component_id").val(),
                        'class_id': $("#new_detail_class_id").val(),
                        'description': $("#new_detail_description").val(),
                        'perfect_score': $("#new_detail_perfect_score").val(),
                        'date': $("#new_detail_date").val(),
                        '_token': $('input[name=_token]').val(),
                                                      
                    },
                async:false
            });
            $("#newdetailmodal").modal('hide');
            swal("Saved!", "New Class Standing Component Detail Saved", "success"); 
        });
});