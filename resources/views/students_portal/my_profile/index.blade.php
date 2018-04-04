@extends('site.layouts.default_portal')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("students_portal.my_profile") }}} :: @parent
@stop
{{-- Content --}}
@section('content')
<style type="text/css">
  b {
    color:#008cba;
  }
</style>
<div class="navbar-default sidebar" role="navigation">
  <div class="sidebar-nav navbar-collapse">    
      <ul class="nav" id="side-menu"> 
        @include('student_sidebar')
      </ul>
  </div>
</div>
<div id="page-wrapper">
  <div class="row">
    @include('notifications')
    <div class="page-header">
      <h2>{{{ Lang::get("students_portal.my_profile") }}}</h2>
    </div>
    <div class="form-horizontal" action="{{ URL::to('students_portal/' . $enrollment->id . '/my_profile') }}">
      @include('students_portal/my_profile.form')
    </div>
  </div>
</div>
@stop