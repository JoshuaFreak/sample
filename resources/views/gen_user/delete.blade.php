@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("gen_user.delete_user") }}} :: @parent
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
        <div class="page-header"><br>
            <h3> {{{ Lang::get("gen_user.delete_user") }}}  
                <div class="pull-right">
                    <div class="pull-right">
                        <a href="{{{ URL::to('gen_user') }}}" class="btn btn-sm  btn-primary iframe"><span class="glyphicon glyphicon-list"></span> {{ Lang::get("gen_user.user_list") }}</a>
                    </div>
                </div>
            </h3>
        </div>
        <div>
            <b>{{ Lang::get("gen_user.delete_message") }}</b>
        </div><br>
        <form class="form-horizontal" method="post" action="{{ URL::to('gen_user/' . $gen_user->id . '/delete') }}" autocomplete="off">
            <input type="hidden" name="id" value="{{ $gen_user->id }}" />   
            @include('gen_user.form_delete')
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-md-3 control-label" for="actions">&nbsp;</label>
                    <div class="col-md-9">  
                        <a href="{{{ URL::to('gen_user') }}}" class="btn btn-sm btn-warning close_popup">
                            <span class="glyphicon glyphicon-ban-circle"></span>  {{ Lang::get("form.cancel") }}
                        </a>
                        <button type="submit" class="btn btn-sm btn-danger">
                            <span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}
                        </button>   
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
