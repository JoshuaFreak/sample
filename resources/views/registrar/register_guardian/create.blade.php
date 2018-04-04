@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("registrar.register_new_guardian") }}} :: @parent
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
		<div class="page-header">
			<h3> {{{ Lang::get("registrar.register_new_guardian") }}}
				<div class="pull-right">
					<div class="pull-right">
			            <a href="{{{ URL::to('registrar/register_guardian') }}}" class="btn btn-sm  btn-primary iframe"><span class="glyphicon glyphicon-list"></span> {{ Lang::get("registrar.guardian_list") }}</a>
			        </div>
				</div>
			</h3>
		</div>
		<form class="form-horizontal" method="post" action="{{ URL::to('registrar/register_guardian/create') }}" autocomplete="off">
			@include('registrar/register_guardian.form')
			<div class="col-md-12">
				<div class="form-group">
					<label class="col-md-3 control-label" for="actions">&nbsp;</label>
					<div class="col-md-9">	
			 			<button type="submit" class="btn btn-sm btn-success">
			                <span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.create") }}
			            </button>	
			            <button type="reset" class="btn btn-sm btn-default">
			                <span class="glyphicon glyphicon-remove-circle"></span>  {{ Lang::get("form.reset") }}
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
	$(function() {
		$("#student").select2()
	});
</script>
@stop
