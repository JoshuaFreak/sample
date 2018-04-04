@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("payment.create") }}} :: @parent
@stop

{{-- Content --}}
@section('content')
<style>
.hidden
{
	display: none;
}
.show
{
	display: block;
}
</style>

    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
	<div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                @include('accounting_sidebar')
		    </ul>
	    </div>

    </div>

<div id="page-wrapper">
    <div class="row">
		<div class="page-header"><br>
			<h2> 
				{{{ Lang::get("payment.create") }}}
				<div class="pull-right">
				</div>
			</h2>
		</div>

		<form id="payment_create" class="form-horizontal" method="post" action="{{ URL::to('payment/create') }}" autocomplete="off">
		    
			@include('payment.form')
			<div class="col-md-12">
				<div class="form-group">
					<label class="col-md-3 control-label" for="actions">&nbsp;</label>
					<div class="col-md-9">	
			 			<button id="savePayment" type="button" class="btn btn-sm btn-success">
			                <span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.create") }}
			            </button>	
			            <button type="reset" class="btn btn-sm btn-default">
			                <span class="glyphicon glyphicon-remove-circle"></span>  {{ Lang::get("form.reset") }}
			            </button>	
			 			<a href="{{{ URL::to('payment') }}}" class="btn btn-sm btn-warning close_popup">
			                <span class="glyphicon glyphicon-ban-circle"></span>  {{ Lang::get("form.cancel") }}
			       		</a>
			        </div>
			    </div>
			</div>	
		</form>
	</div>
</div>

@stop

