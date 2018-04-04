@extends('site.layouts.default_module')

{{-- Web site Title --}}
@section('title')
Homepage :: @parent
@stop
{{-- Content --}}
@section('content')
@section('styles')
<style type="text/css">
  .col-xs-1 {
    width: 3.333333% !important;
  }
  .row {
    margin-left: 0px !important;
    margin-right: 0px !important;
  }
</style>
@show 
    <link rel="stylesheet" href="{{asset('assets/site/css/ihover.min.css')}}"/>
    <div align="center">
      <img src="images/cia_module1.jpg" width="100%" style="">
    </div>
@stop
