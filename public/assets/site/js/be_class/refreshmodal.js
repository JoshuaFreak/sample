$(document).ready(function(){

        $('#refreshstudentmodal').on('show.bs.modal', function(e) {   
            var button =  $(e.relatedTarget)
            var Id = button.data('id')
            var Level = button.data('level')
	        var SectionName = button.data('section_name')
	        var Name = button.data('name')

            var modal = $(this)
        	$('.refresh_modal').text('Refresh - ' + Level +' '+SectionName+' ('+Name+')')
            $('#course_code').text(Name)

        });
        
});