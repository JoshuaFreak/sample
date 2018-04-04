@extends('site.layouts.default_portal')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("students_portal.grade") }}} :: @parent
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
      <h2>{{{ Lang::get("students_portal.grade") }}}</h2>
    </div>
    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
    <div class="col-md-12">
      <div class="col-md-5">
        <div class="form-group {{{ $errors->has('classification_level_id') ? 'has-error' : '' }}}">
          <label for="classification_level_id">Select {{ Lang::get('registrar_report.classification_level') }}</label>
            <select class="form-control" name="classification_level_id" id="classification_level_id">
                <option type="text" name="0" id="" value=""></option>
                @foreach($classification_level_list as $classification_level)                                
                  <option type="text" name="classification_level_id" id="classification_level_id" value="{{{$classification_level->id}}}" data-section="{{{$classification_level->section_id}}}" data-term="{{{$classification_level->term_id}}}" data-classification="{{{$classification_level->classification_id}}}" data-curriculum="{{{$classification_level->curriculum_id}}}" data-student="{{{$classification_level->student_id}}}">{{$classification_level->level}}</option>                                                           
                @endforeach
            </select>
        </div>
      </div>
      <div class="col-md-3"><br/>
        <button type="button" class="btn btn-sm btn-primary" id="loadGrade">
          <span class="glyphicon glyphicon-ok-circle"></span> Filter
        </button>
      </div>
    </div>
    <br/><br/><br/><br/><br/><br/>
    <div class="col-md-12" id="no_data" style="background-color: #B6CADC;" align="center"><br>No data available<br/><br/></div>
    <div class="col-md-8" id="CorContainer"></div>
  </div>
</div>
@stop

{{-- Scripts --}}
@section('scripts')
<script type="text/javascript">

    $("#classification_level_id").change(function(){
        selectListChange('semester_level_id','{{{URL::to("semester_level/dataJson")}}}',  { 'is_active':1 , 'classification_level_id': $(this).val() } ,'Please select a Classification Level')
    });

    $("#no_data").show();
    $("#loadGrade").click(function()
    {
        loadStudentGrade($("#classification_level_id").find(':selected').data('student'),
          $("#classification_level_id").find(':selected').data('classification'),
          $("#classification_level_id").val(), 
          $("#classification_level_id").find(':selected').data('term'), 
          $("#classification_level_id").find(':selected').data('section'), 
          $("#classification_level_id").find(':selected').data('curriculum'));
        $("#no_data").hide();
    });

    function loadStudentGrade(studentId, ClassificationId, ClassificationLevelId, TermId, SectionId ,CurriculumId)
    {
      $.ajax(
          {
            url: "{{{ URL::to('students_portal/grade/detail') }}}",
            data: { 
              'student_id': studentId, 
              'section_id': SectionId, 
              'classification_id': ClassificationId, 
              'classification_level_id': ClassificationLevelId, 
              'term_id': TermId, 
              'curriculum_id': CurriculumId
            },
          }
        ).done(function(student_detail_html){
          $("#CorContainer").html(student_detail_html);
        });
    }

</script>
@stop