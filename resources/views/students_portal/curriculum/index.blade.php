@extends('site.layouts.default_portal')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("students_portal.curriculum") }}} :: @parent
@stop
{{-- Content --}}
@section('content')
<div class="navbar-default sidebar" role="navigation">
  <div class="sidebar-nav navbar-collapse">    
      <ul class="nav" id="side-menu"> 
        @include('student_sidebar')
      </ul>
  </div>
</div>
<div id="page-wrapper">
  <div class="row">
    <div class="page-header"><br>
      <h2>{{{ Lang::get("students_portal.curriculum") }}}</h2>
    </div>
    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
    <div class="col-md-12">
      <div class="col-md-5">
          <div class="form-group {{{ $errors->has('curriculum_id') ? 'has-error' : '' }}}">
              <label for="curriculum_id">Select {{ Lang::get('registrar.curriculum') }}</label>
              <select class="form-control" name="curriculum_id" id="curriculum_id" tabindex="4">
                <option name="" value="" selected></option>
                  @foreach($curriculum_subject_list as $curriculum_subject)
                    <option name="curriculum_id" value="{{{ $curriculum_subject->id }}}" data-classification="{{{$curriculum_subject->classification_id}}}">{{{ $curriculum_subject->curriculum_name }}}</option>
                  @endforeach
              </select>
          </div>
      </div>
      <div class="col-md-3"><br/>
          <button type="button" class="btn btn-sm btn-primary" id="LoadSubject">&nbsp;Filter&nbsp;</button>
      </div> 
    </div> 
    <br><br><br><br><br><br>
    <div class="col-md-12" id="no_data" style="background-color: #B6CADC;" align="center"><br>No data available<br/><br/></div>
    <div class="col-md-12" id="studentSubjectContainer"></div>
  </div>
</div>
@stop

{{-- Scripts --}}
@section('scripts')
<script type="text/javascript">

    $("#no_data").show();
    $("#LoadSubject").click(function(){
        loadStudentSubject($("#curriculum_id").find(':selected').data('classification'),$("#curriculum_id").val());
        $("#no_data").hide();
    });

    function loadStudentSubject(ClassificationId, CurriculumId)
    {
      $.ajax(
          {
            url: "{{{ URL::to('students_portal/curriculum/detail') }}}",
            data: { 
              'classification_id': ClassificationId, 
              'curriculum_id': CurriculumId
            },
          }
        ).done(function(student_detail_html){
          $("#studentSubjectContainer").html(student_detail_html);
        });
    }
</script>
@stop