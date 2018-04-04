@extends('site.layouts.default_full_page')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("registrar.register_new_student") }}} :: @parent
@stop
{{-- Content --}}
@section('content')
<link rel="stylesheet" href="{{asset('assets/site/datepicker/ui/1.11.4/themes/smoothness/jquery-ui.css')}}">
<script src="{{asset('assets/site/datepicker/jquery-1.10.2.js')}}"></script>
<script src="{{asset('assets/site/datepicker/ui/1.11.4/jquery-ui.js')}}"></script>
<script>
//     $(function() {
//     $( "#birthdate" ).datepicker({
//         dateFormat: "yy-mm-dd",
//         changeMonth: true,
//         changeYear: true,
//         yearRange: '2000:2030',
//     });
// });
 </script>

<div id="full-page-wrapper">
    <div class="row">
        @include('notifications')
        <div class="page-header"><br>
            <h3> {{{ Lang::get("registrar.register_student") }}}
                <div class="pull-right">
                    <div class="pull-right">
                        <a href="{{{ URL::to('registrar/register_student') }}}" class="btn btn-sm  btn-primary iframe"><span class="glyphicon glyphicon-list"></span> {{ Lang::get("registrar.registered_student") }}</a>
                    </div>
                </div>
            </h3>
        </div>

        <form class="form-horizontal" method="post" action="{{ URL::to('registrar/register_student/create') }}" autocomplete="off">
            @include('registrar/register_student.form')
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-md-3 control-label" for="actions">&nbsp;</label>
                    <div class="col-md-9">  
                        <button type="submit" class="btn btn-sm btn-success">
                            <span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.register") }}
                        </button>   
                        <button type="reset" class="btn btn-sm btn-default">
                            <span class="glyphicon glyphicon-remove-circle"></span>  {{ Lang::get("form.reset") }}
                        </button>   
                        <a href="{{{ URL::to('registrar/register_student') }}}" class="btn btn-sm btn-warning close_popup">
                            <span class="glyphicon glyphicon-ban-circle"></span>  {{ Lang::get("form.cancel") }}
                        </a>
                    </div>
                </div>
            </div>  
        </form><br>
    </div>
</div>  
@stop

