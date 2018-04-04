$(document).ready(function(){
		
		$('#cscddeletemodal').on('show.bs.modal', function(e) { 

            var button =  $(e.relatedTarget)
            var ClassStandingComponentDetailId = button.data('id')
            var ClassStandingComponentDetailDescription = button.data('cscd')

            var modal = $(this)
            $('.cscd_delete').text('Delete Class Standing Component Detail : ' + ClassStandingComponentDetailDescription)

            $.ajax({
                  url:"../../class_standing_component_detail/editdataJson",
                  type:'get',
                  data:
                      {  
                        'id' : ClassStandingComponentDetailId
                      },
                  dataType: "json",
                  async:false

            }).done(function(data) {
                if(data.length > 0)
                {
                    $.each( data, function( key, item ) {

                        $("#delete_class_standing_component_detail_id").val(item.id);
                        $("#delete_description").val(item.description);
                        $("#delete_perfect_score").val(item.perfect_score);
                        $("#delete_date").val(item.date);

                    });


                }
            
            });

        });
		
		$(".DeleteDetail").click(function () {
            $.ajax({
                url:"../../class_standing_component_detail/delete",
                type:'post',
                data: 
                    { 
                        'id': $("#delete_class_standing_component_detail_id").val(),
                        'description': $("#delete_description").val(),
                        'perfect_score': $("#delete_perfect_score").val(),
                        'date': $("#delete_date").val(),
                        '_token': $('input[name=_token]').val(),
                                                      
                    },
                async:false
            });
            $("#cscddeletemodal").modal('hide');
            swal("Deleted!", "Successfully Deleted Class Standing Component Detail", "success"); 
        });

});