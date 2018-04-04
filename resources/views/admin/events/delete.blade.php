@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("gen_user.create_user") }}} :: @parent
@stop

{{-- Content --}}
@section('content')
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">    
        <ul class="nav" id="side-menu"> 
          @include('admin_sidebar')
        </ul>
    </div>
</div>
<div id="page-wrapper">
    <div class="row">
            @include('notifications')
		<div class="page-header"><br>
			<h3> {{{ Lang::get("Create New Event") }}}
				<div class="pull-right">
					<div class="pull-right">
			            <a href="{{{ URL::to('admin/events') }}}" class="btn btn-sm  btn-primary iframe"><span class="glyphicon glyphicon-list"></span> {{ Lang::get("Event Lists") }}</a>
			        </div>
				</div>
			</h3>
		</div>
		<form class="form-horizontal" method="post" action="{{ URL::to('admin/events/'.$event->id.'/delete') }}" autocomplete="off" enctype="multipart/form-data">
			@include('admin/events.form')
			<div class="col-md-12">
				<div class="form-group">
					<label class="col-md-3 control-label" for="actions">&nbsp;</label>
					<div class="col-md-9">	
			 			<button type="submit" class="btn btn-sm btn-danger">
			                <span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.delete") }}
			            </button>	
			            <button type="reset" class="btn btn-sm btn-default">
			                <span class="glyphicon glyphicon-remove-circle"></span>  {{ Lang::get("form.reset") }}
			            </button>
			 			<a href="{{{ URL::to('admin/events') }}}" class="btn btn-sm btn-warning close_popup">
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
	$(function() {
		$("#roles").select2()
	});

	$(function() {
      $('#datepicker').datepicker({
          format: "MM d, yyyy",
          orientation: "top left",
          autoclose: true,
          startView: 1,
          todayHighlight: true,
          todayBtn: "linked",
      });

      $(':input:not([type="submit"],[type="hidden"])').prop('disabled', true);
    });
</script>
@stop
