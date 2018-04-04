@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("gen_user.edit_user") }}} :: @parent
@stop

{{-- Content --}}
@section('content')
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">    
        <ul class="nav" id="side-menu"> 
          @include('hrms_sidebar')
        </ul>
    </div>
</div>
<div id="page-wrapper">
    <div class="row">
            @include('notifications')
		<div class="page-header"><br>
			<h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left"> 
				{{{ Lang::get("gen_user.edit_user") }}}
				<div class="pull-right" style="margin-right: 10px !important">
			            <a href="{{{ URL::to('gen_user') }}}" class="btn btn-sm  btn-primary iframe"><span class="glyphicon glyphicon-list"></span> {{ Lang::get("gen_user.user_list") }}</a>
				</div>
			</h3>
		</div>
		<form class="form-horizontal" method="post" action="{{ URL::to('gen_user/' . $gen_user->id . '/edit') }}" autocomplete="off">
			@include('gen_user.form1')
			<div class="col-md-12">
				<div class="form-group">
					<label class="col-md-3 control-label" for="actions">&nbsp;</label>
					<div class="col-md-9">	
			 			<button type="submit" class="btn btn-sm btn-success">
			                <span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.save_changes") }}
			            </button>	
			 			<a href="{{{ URL::to('gen_user') }}}" class="btn btn-sm btn-warning close_popup">
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
	// 	$("#roles").select2()
	// });
		$(function() {
		$("#roles").select2()


		$('.delete_button').click(function(){

	 		var value = $(this).data('value');
	 		var gen_role_item = $('#gen_role_id_'+value+'').text();
	 		$('#gen_role_item').text(gen_role_item);
	 	});
	});

	$('#gen_user_role_modal').on('show.bs.modal', function(e) {
		$(this).find('.btn-ok').attr('onclick', 'genUserRole('+$(e.relatedTarget).data('value')+')');

    });

	function genUserRole(rowId){


		$.ajax({
			url:'{{{ URL::to("gen_user_role/delete") }}}',
			type:'post',
			data:{
				'_token': $("input[name=_token]").val(),
				'id':rowId,
			},async:false,
			success: function (data) {
                swal("Successfully Deleted");
              },
            error: function(){
            	swal("Delete Unsuccessful")
            }
		}).done(function(){
			$("#gen_role_container_"+rowId).remove();
			$("#gen_user_role_modal").modal("hide");
		});

	}
</script>
@stop
