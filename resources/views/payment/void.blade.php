@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("payment.void_payment") }}} :: @parent
@stop

{{-- Content --}}
@section('content')

<!-- Side Bar -->
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            @include('accounting_sidebar')
        </ul>
    </div>
</div>
<div id="page-wrapper">
    <div class="row">       
        <div class="page-header">
            <h3> {{{ Lang::get("payment.void_payment") }}}  
                <div class="pull-right">
                    <div class="pull-right">
                        <a href="{{{ URL::to('payment') }}}" class="btn btn-sm  btn-primary iframe"><span class="glyphicon glyphicon-list"></span> {{ Lang::get("payment.payment_list") }}</a>
                    </div>
                </div>
            </h3>
        </div>
        <div>
            {{ Lang::get("payment.void_message") }}
        </div>
        <form class="form-horizontal" method="post" action="{{ URL::to('payment/' . $payment->id . '/void') }}" autocomplete="off">
            <input type="hidden" name="id" value="{{ $payment->id }}" />   
            @include('payment.void_form')
         <div class="col-md-12">
                <div class="form-group">
                    <label class="col-md-3 control-label" for="actions">&nbsp;</label>
                    <div class="col-md-9">  
                        <a href="{{{ URL::to('payment') }}}" class="btn btn-sm btn-warning close_popup">
                            <span class="glyphicon glyphicon-ban-circle"></span>  {{ Lang::get("form.cancel") }}
                        </a>
                        <button type="submit" class="btn btn-sm btn-danger">
                            <span class="glyphicon glyphicon-trash"></span> {{ Lang::get("payment.void") }}
                        </button>   
                    </div>
                </div>
            </div>  
        </form>
    </div>  
</div>  

@stop