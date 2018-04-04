@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("gen_role.edit_role") }}} :: @parent
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
		<div class="page-header">
			<h3> {{{ Lang::get("gen_role.edit_role") }}}
				<div class="pull-right">
					<div class="pull-right">
			            <a href="{{{ URL::to('gen_role') }}}" class="btn btn-sm  btn-primary iframe"><span class="glyphicon glyphicon-list"></span> {{ Lang::get("gen_role.role_list") }}</a>
			        </div>
				</div>
			</h3>
		</div>
		<form class="form-horizontal" method="post" action="{{ URL::to('gen_role/' . $gen_role->id . '/edit') }}" autocomplete="off">
			<input type="hidden" name="id" value="{{ $gen_role->id }}" />
			@include('gen_role.form')
			<div class="col-md-12">
				<div class="form-group">
					<label class="col-md-3 control-label" for="actions">&nbsp;</label>
					<div class="col-md-9">	
			 			<button type="submit" class="btn btn-sm btn-success">
			                <span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.save_changes") }}
			            </button>	
			 			<a href="{{{ URL::to('gen_role') }}}" class="btn btn-sm btn-warning close_popup">
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
		$("#permission").select2()
	});
</script>
@stop
