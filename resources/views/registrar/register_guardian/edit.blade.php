@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("registrar.edit_guardian") }}} :: @parent
@stop

{{-- Content --}}
@section('content')
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">    
        <ul class="nav" id="side-menu"> 
          @include('registrar_sidebar')
        </ul>
    </div>
</div>
<div id="page-wrapper">
    <div class="row">
            @include('notifications')
		<div class="page-header"><br>
			<h3> {{{ Lang::get("registrar.edit_guardian") }}}
				<div class="pull-right">
					<div class="pull-right">
			            <a href="{{{ URL::to('registrar/register_guardian') }}}" class="btn btn-sm  btn-primary iframe"><span class="glyphicon glyphicon-list"></span> {{ Lang::get("registrar.registered_guardian") }}</a>
			        </div>
				</div>
			</h3>
		</div>
		<form class="form-horizontal" method="post" action="{{ URL::to('registrar/register_guardian/' . $guardian->id . '/edit') }}" autocomplete="off">
			@include('registrar/register_guardian.form_edit')
			<div class="col-md-12">
				<div class="form-group">
					<label class="col-md-3 control-label" for="actions">&nbsp;</label>
					<div class="col-md-9">	
			 			<button type="submit" class="btn btn-sm btn-success">
			                <span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.save_changes") }}
			            </button>	
			 			<a href="{{{ URL::to('registrar/register_guardian') }}}" class="btn btn-sm btn-warning close_popup">
			                <span class="glyphicon glyphicon-ban-circle"></span>  {{ Lang::get("form.cancel") }}
			       		</a>
			        </div>
			    </div>
			</div>	
		</form>
    </div>
</div>	
@stop
@section('scripts')
<script type="text/javascript">
   $(":submit").closest("form").submit(function(){
           $(':submit').attr('disabled', 'disabled');
       });
	// $(function() {
	// 	$("#student").select2()
	// });

	$(function() {
		$("#student").select2()


		$('.delete_button').click(function(){

	 		var value = $(this).data('value');
	 		var student_item = $('#student_id_'+value+'').text();
	 		$('#student_item').text(student_item);
	 	});
	});

	$('#student_guardian_modal').on('show.bs.modal', function(e) {
		$(this).find('.btn-ok').attr('onclick', 'studentGuardian('+$(e.relatedTarget).data('value')+')');

    });

	function studentGuardian(rowId){


		$.ajax({
			url:'{{{ URL::to("student_guardian/delete") }}}',
			type:'post',
			data:{
				'_token': $("input[name=_token]").val(),
				'id':rowId,
			},async:false,
			success: function (data) {
                alert("Successfully Deleted");
              },
            error: function(){
            	alert("Delete Unsuccessful")
            }
		}).done(function(){
			$("#student_container_"+rowId).remove();
			$("#student_guardian_modal").modal("hide");
		});

	}

</script>
@stop
