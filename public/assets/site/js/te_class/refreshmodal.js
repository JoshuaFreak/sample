$(document).ready(function(){

        $('#refreshstudentmodal').on('show.bs.modal', function(e) {   
            var button =  $(e.relatedTarget)
            var Id = button.data('id')
            var Code = button.data('code')

            var modal = $(this)
        	$('.refresh_modal').text('Refresh Student List')
            $('#course_code').text(Code)

        });
        
});