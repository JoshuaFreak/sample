@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("register_lang.import_student_score") }}} :: @parent
@stop
{{-- Content --}}
@section('content')
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">    
        <ul class="nav" id="side-menu"> 
          @include('registrar_sidebar')
        </ul>
    </div>
</div>
<div id="page-wrapper">
    <div class="row">
        <div class="page-header"><br>
            <h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left">
                {{{ Lang::get("register_lang.import_student_score") }}}
                <div class="pull-right" style="margin-right: 10px !important">
                    <!-- <a href="{{{ URL::to('register/create') }}}" class="btn btn-sm  btn-primary"><span class="glyphicon glyphicon-plus-sign"></span>  {{ Lang::get('register_lang.register_person') }}</a> -->
                </div>
            </h3>
        </div>

        <div class="col-md-12">
            <div class="secure">Please Upload a Excel File</div>
            {!! Form::open(array('url'=> URL::to('registrar/import_student_score'),'method'=>'POST', 'files'=>true)) !!}
            <div class="control-group col-md-3">
                  <div class="controls">
                    {!! Form::file('import_file', array('onclick'=>'myFunction()')) !!}
                  </div>
            </div>

            <div class="col-md-3">
                <select class="form-control" id="examination_id" name="examination_id">
                    @foreach($examination_list as $examination)
                        <option value="{{ $examination -> id}}">{{$examination -> examination_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-control" id="program_category_id" name="program_category_id">
                    @foreach($program_category_list as $program_category)
                        <option value="{{ $program_category -> id}}">{{$program_category -> program_category_name}}</option>
                    @endforeach
                </select>
            </div>
            <div id="success"  class="col-md-1"> </div>
            {!! Form::submit('Upload', array('class'=>'send-btn btn-primary','onclick'=>'move()','id'=>'myBtn' ,'disabled')) !!}
            {!! Form::close() !!}
            <br/>
            <div class="progress">
                <div id="myBar" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                    <div id="demo">0%</div>
                </div>
            </div>
        </div>
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
