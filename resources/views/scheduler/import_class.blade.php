@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("scheduler.import_class_schedule") }}} :: @parent
@stop
{{-- Content --}}
@section('content')
 <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>

  <link href="{{asset('assets/its/css/heading-design.css')}}" rel="stylesheet">
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            
            @include('scheduler_sidebar')
            
      </ul>
    </div>
</div>


<div id="page-wrapper">
  <div class="row">
            <br/>
    <div class="page-header" style="margin-left: 3%;margin-right: 3%;"><br>
      <h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left">
      {{{ Lang::get("scheduler.import_class_schedule") }}}            
      </h3>
    </div>
            <div class="col-md-6">
              <div class="secure">Please Select a Excel File</div>
              {!! Form::open(array('url'=> URL::to('scheduler/import_class'),'method'=>'POST', 'files'=>true)) !!}
              <div class="control-group">
                <div class="controls">
                  {!! Form::file('import_students_file', array('onclick'=>'myFunction()')) !!}
                </div>
              </div><br>
              <div id="success"> </div>
              {!! Form::submit('Upload', array('class'=>'send-btn btn-primary','onclick'=>'move()','id'=>'myBtn' ,'disabled')) !!}
              {!! Form::close() !!}

            <br>
            <div class="progress">
              <div id="myBar" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
               <div id="demo">0%</div>
              </div>
            </div>
          </div>

            @if(Session::has('error'))
              <div class="col-md-offset-5 col-md-7">
                <p class="errors">{!! Session::get('error') !!}</p>
              </div>
            @else
              <div class="col-md-offset-5 col-md-7">
                <p class="success">{!! Session::get('success') !!}</p>
              </div>
            @endif
  </div>
</div>
@stop
@section('scripts')
<script type="text/javascript">
function move() {
  var elem = document.getElementById("myBar");
  var width = 20;
  var id = setInterval(frame, 150);
  function frame() {
    if (width >= 100) {
      clearInterval(id);
    } else {
      width++;
      elem.style.width = width + '%';
      document.getElementById("demo").innerHTML = width * 1  + '%';
    }
  }
}
function myFunction() {
     document.getElementById("myBtn").disabled = false;
}

</script>

@stop
