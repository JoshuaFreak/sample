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
			<h3> {{{ Lang::get("gen_user.create_user") }}}
				<div class="pull-right">
					<div class="pull-right">
			            <a href="{{{ URL::to('gen_user') }}}" class="btn btn-sm  btn-primary iframe"><span class="glyphicon glyphicon-list"></span> {{ Lang::get("gen_user.user_list") }}</a>
			        </div>
				</div>
			</h3>
		</div>
		<form class="form-horizontal" method="post" action="{{ URL::to('gen_user/create') }}" autocomplete="off">
			@include('gen_user.form')
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
	$(function() {
		$("#roles").select2()
	});
</script>
@stop
