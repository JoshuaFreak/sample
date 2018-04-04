$(document).ready(function(){
        $('#cscdeditmodal').on('show.bs.modal', function(e) { 

            var button =  $(e.relatedTarget)
            var ClassStandingComponentDetailId = button.data('id')
            var ClassStandingComponentDetailDescription = button.data('cscd')

            var modal = $(this)
            $('.cscd_edit').text('Edit Class Standing Component Detail : ' + ClassStandingComponentDetailDescription)

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

                        $("#edit_class_standing_component_detail_id").val(item.id);
                        $("#edit_description").val(item.description);
                        $("#edit_perfect_score").val(item.perfect_score);
                        $("#edit_date").val(item.date);

                    });


                }
            
            });

        });

        $(".SaveChanges").click(function () {
            $.ajax({
                url:"../../class_standing_component_detail/edit",
                type:'post',
                data: 
                    { 
                        'id': $("#edit_class_standing_component_detail_id").val(),
                        'description': $("#edit_description").val(),
                        'perfect_score': $("#edit_perfect_score").val(),
                        'date': $("#edit_date").val(),
                        '_token': $('input[name=_token]').val(),
                                                      
                    },
                async:false
            });
            $("#cscdeditmodal").modal('hide');
            swal("Edited!", "Successfully Edited Class Standing Component Detail", "success"); 
        });

});