@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("room.delete_room") }}} :: @parent
@stop

{{-- Content --}}
@section('content')
<div class="navbar-default sidebar" role="navigation">
  <div class="sidebar-nav navbar-collapse">    
      <ul class="nav" id="side-menu"> 
      @include('scheduler_sidebar')
    </ul>
  </div>

</div>
<div id="page-wrapper">
    <div class="row">
        <div class="page-header"><br>
            <h3> {{{ Lang::get("room.delete_room") }}}  
                <div class="pull-right">
                    <div class="pull-right">
                        <a href="{{{ URL::to('room') }}}" class="btn btn-sm  btn-primary iframe"><span class="glyphicon glyphicon-list"></span> {{ Lang::get("room.room_list") }}</a>
                    </div>
                </div>
            </h3>
        </div>
        <div>
            {{ Lang::get("room.delete_message") }}
        </div>
        <form class="form-horizontal" method="post" action="{{ URL::to('room/' . $room->id . '/delete') }}" autocomplete="off">
            <input type="hidden" name="id" value="{{ $room->id }}" />   
            @include('room.form')

         <div class="col-md-12">
                <div class="form-group">
                    <label class="col-md-3 control-label" for="actions">&nbsp;</label>
                    <div class="col-md-9">  
                        <a href="{{{ URL::to('room') }}}" class="btn btn-sm btn-warning close_popup">
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