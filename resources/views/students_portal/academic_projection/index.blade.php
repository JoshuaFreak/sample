@extends('site.layouts.default_portal')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("students_portal.academic_projection") }}} :: @parent
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
      <h2>{{{ Lang::get("students_portal.academic_projection") }}}</h2>
    </div>
    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
    <div class="col-md-12">
        <div class="col-md-5">
            <input type="hidden" name="term_id" id="term_id" value="{{$enrollment->term_id}}" />
            <div class="form-group {{{ $errors->has('classification_level_id') ? 'has-error' : '' }}}">
                <label for="classification_level_id">Select {{ Lang::get('registrar.classification_level') }}</label>
                <select class="form-control" name="classification_level_id" id="classification_level_id" tabindex="4">
                    <option name="" value="" selected></option>
                    @foreach($classification_level_list as $classification_level)
                        <option name="classification_level_id" value="{{{ $classification_level->id }}}" data-student="{{{$classification_level->student_id}}}">{{{ $classification_level->level }}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3"><br/>
            <button type="button" class="btn btn-sm btn-primary" id="LoadSubject">&nbsp;Filter&nbsp;</button>
        </div> 
    </div> 
    <br><br><br><br>
    <div class="col-md-12" id="no_data" style="background-color: #B6CADC;" align="center"><br>No data available<br/><br/></div>
    <div class="col-md-8 handsontable" id="academic_projection_excel"></div>
  </div>
</div>
@stop

{{-- Scripts --}}
@section('scripts')
<script type="text/javascript">

    $("#no_data").show();
    $("#LoadSubject").click(function(){
        loadStudentSubject($("#classification_level_id").find(':selected').data('student'),$("#term_id").val(),$("#classification_level_id").val());
        $("#no_data").hide();
    });

    function loadStudentSubject(StudentId, TermId, ClassificationLevelId)
    {

        $.ajax({
            url:"{{{ URL::to('students_portal/academic_projection/detail') }}}",
            type:'get',
            data:{  
                    'classification_level_id' : ClassificationLevelId,
                    'student_id' : StudentId,
                    'term_id' : TermId
                },
            dataType: "json",
            async:false,
            success: function (data) { 
                paceArray = data;
            } 
        });

 
       function firstRowRenderer(instance, td, row, col, prop, value, cellProperties) {
            Handsontable.renderers.TextRenderer.apply(this, arguments);
            td.style.background = '#E6E6E6';
        }

        function yellowRenderer(instance, td, row, col, prop, value, cellProperties) {
            Handsontable.renderers.TextRenderer.apply(this, arguments);
            td.style.background = '#FEDF62';
        }

        function redRenderer(instance, td, row, col, prop, value, cellProperties) {
            Handsontable.renderers.TextRenderer.apply(this, arguments);
            td.style.background = '#FF7E79';
        }

        function greenRenderer(instance, td, row, col, prop, value, cellProperties) {
            Handsontable.renderers.TextRenderer.apply(this, arguments);
            td.style.background = '#9CE05A';
        }

        function blueRenderer(instance, td, row, col, prop, value, cellProperties) {
            Handsontable.renderers.TextRenderer.apply(this, arguments);
            td.style.background = '#64B3DE';
        }

        function lavenderRenderer(instance, td, row, col, prop, value, cellProperties) {
            Handsontable.renderers.TextRenderer.apply(this, arguments);
            td.style.background = '#D783FF';
        }

        var data = paceArray[0];
        var grade_id_arr = paceArray[1];
        var detail_arr = paceArray[2];
        var str = '<tr id="header-grouping">'+'<th colspan="2"></th>'+'<th colspan="6">First</th>' + 
          '<th colspan="6">Second</th>'+ '<th colspan="6">Third</th>' + '<th colspan="6">Fourth</th>'+'</tr>';  
        
        var container = document.getElementById("academic_projection_excel");
        hot = new Handsontable(container, {
            data: data,
            afterRender  : function () {$('.htCore > thead > tr').before(str);},
            // colHeaders: true,
            afterChange: function (changes, source) {

                if(changes != null){
                    var score_entry_row = changes[0][0];
                    var score_entry_column = changes[0][1];
                    var score_entry_id = grade_id_arr[score_entry_row][score_entry_column];
                    var detail = detail_arr[score_entry_row][score_entry_column];
                    var new_data = changes[0][3];
                    // alert(score_entry_id);
                    $.ajax({
                        url: "../../teachers_portal/academic_projection/postUpdatePace",
                        data: {
                            'id': score_entry_id, 
                            'detail': detail, 
                            'data': new_data,
                            '_token': $('input[name=_token]').val(),
                        }, 
                        dataType: 'json',
                        type: 'POST',
                        async:false
                    });
                }
            },
            className: "htCenter htMiddle",
            height: 600,
            colWidths: [200, 200, 60, 60, 60, 60, 60, 60, 60, 60, 60, 60, 60, 60, 60, 60, 60, 60, 60, 60, 60, 60, 60, 60, 60, 150],
            autoRowSize: true,
            // currentRowClassName: 'currentRow',
            // currentColClassName: 'currentCol',
            // fixedRowsTop: 1,
            fixedColumnsLeft: 2,
            cells : function(row, col, prop, td) {
                var cellProperties = {};
                // alert(data[row][0]);
                if (col >= 0) {
                    cellProperties.editor = false;
                    cellProperties.renderer = firstRowRenderer;                   
                }
                if(data[row][1] == "DATE RELEASED" || data[row][1] == "DATE TAKEN" ) { 
                    if(col >= 2 ){
                        cellProperties.placeholder = "mm/dd"; 
                    } 
                }
                if (data[row][0] == "Mathematics" || data[row][0] == "Algebra I" ||  data[row][0] == "Algebra II" ||  data[row][0] == "Geometry" ||  data[row][0] == "General Math") {
                    cellProperties.renderer = yellowRenderer;
                    
                }else if (data[row][0] == "English" || data[row][0] == "English and Literature" || data[row][0] == "English 1") {
                    cellProperties.renderer = redRenderer;
                    
                }else if (data[row][0] == "Social Studies" || data[row][0] == "Phil. History & Geography" ||  data[row][0] == "Asian History" ||  data[row][0] == "Philippine Economics" ||  data[row][0] == "World History") {
                    cellProperties.renderer = greenRenderer;
                    
                }else if (data[row][0] == "Science" || data[row][0] == "Biology" || data[row][0] == "Physical Science" || data[row][0] == "Chemistry" || data[row][0] == "Physics") {
                    cellProperties.renderer = blueRenderer;
                    
                }else if (data[row][0] == "Word Building" || data[row][0] == "Etymology" || data[row][0] == "Oral Communication") {
                    cellProperties.renderer = lavenderRenderer;
                    
                }
                return cellProperties;
            },
            beforeKeyDown: function (e) {
                var selection = hot.getSelected();
                if (!((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105) || (e.keyCode >= 37 && e.keyCode <= 40) || e.keyCode === 13 || e.keyCode === 111 || e.keyCode === 191 || e.keyCode === 8)){
                    Handsontable.dom.stopImmediatePropagation(e); // remove data at cell, shift up
                    e.preventDefault();
                }
                    // alert(e.keyCode);
            }
        });  
}
</script>
@stop