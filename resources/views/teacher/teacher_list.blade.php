@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("teacher.teacher_list") }}} :: @parent
@stop
{{-- Content --}}
@section('content')
<div class="navbar-default sidebar" role="navigation">
	<div class="sidebar-nav navbar-collapse">    
	    <ul class="nav" id="side-menu"> 
	      @include('hrms_sidebar')
	    </ul>
	</div>
</div>
<div id="page-wrapper">
    <div class="row">
        <div class="page-header"><br>
            <h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left">
                {{{ Lang::get("teacher.teacher_list") }}}
                <div class="pull-right" style="margin-right: 10px !important">
                    <a href="{{{ URL::to('teacher/create') }}}" class="btn btn-sm  btn-primary"><span class="glyphicon glyphicon-plus-sign"></span>  {{ Lang::get('teacher.create_new_teacher') }}</a>
                </div>
            </h3>
        </div> 
        <div class="col-md-12">
            <div class="col-md-3">
                <div class="form-group {{{ $errors->has('classification_id') ? 'has-error' : '' }}}">
                    <label for="classification_id">Search by {{ Lang::get('teacher.classification') }}</label>
                    <select class="form-control" name="classification_id" id="classification_id">
                        <option type="text" name="0" id="" value=""></option>
                            @foreach($classification_list as $classification)                                
                                    <option type="text" name="classification_id" id="classification_id" value="{{{$classification->id}}}">{{$classification->classification_name}}</option>                                                           
                            @endforeach
                    </select>
                </div>
            </div>
            <div id="program_container"></div>
            <div class="col-md-3">
                <div class="form-group {{{ $errors->has('classification_level_id') ? 'has-error' : '' }}}">
                    <label for="classification_level_id">Search by {{ Lang::get('teacher.classification_level') }}</label>
                    <select class="form-control" name="classification_level_id" id="classification_level_id">
                        <option type="text" name="0" id="" value=""></option>
                            @foreach($classification_level_list as $classification_level)                                
                                    <option type="text" name="classification_level_id" id="classification_level_id" value="{{{$classification_level->id}}}">{{$classification_level->level}}</option>                                                           
                            @endforeach
                    </select>
                </div>
            </div>  
   
        </div>
		<table id="TeacherFilter" class="table table-striped table-hover">
			<thead>
				<tr>
					<th> {{ Lang::get("teacher.name") }}</th>
                    <th> {{ Lang::get("teacher.classification") }}</th>
                    <!-- <th> {{ Lang::get("teacher.program") }}</th> -->
                    <th> {{ Lang::get("teacher.classification_level") }}</th>
                    <th> {{ Lang::get("teacher.subject") }}</th>
					<!-- <th> {{ Lang::get("form.action") }}</th> -->
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>
@stop
{{-- Scripts --}}
@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {

            $("#classification_id").change(function(){
                  selectListChange('classification_level_id','{{{URL::to("classification_level/dataJson")}}}',  { 'is_active':1 , 'classification_id': $(this).val() } ,'Please select a Classification Level')
            });

            $("#classification_id").change(function(){
                  selectListChange('subject_id','{{{URL::to("subject/dataJson")}}}',  { 'is_active':1 , 'classification_id': $(this).val() } ,'Please select a Subject')
            });

            TeacherFilter = $('#TeacherFilter').dataTable( {
                "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
                "sPaginationType": "bootstrap",
                "bProcessing": true,
                "bServerSide": true,
                "bStateSave": true,
                "sAjaxSource": "{{ URL::to('teacher/getTeacherData/') }}",
                "fnDrawCallback": function ( oSettings ) {
                },

                "fnServerParams": function(aoData){
                    aoData.push(
                        { "name":"employee_id", "value": $("#employee_id").val() },
                        { "name":"classification_id", "value": $("#classification_id").val() },
                        // { "name":"program_id", "value": $("#program_id").val() },
                        { "name":"classification_level_id", "value": $("#classification_level_id").val() },
                        { "name":"subject_id", "value": $("#subject_id").val() }
                    );
                }
            });

            $("#classification_id").change(function(){
               TeacherFilter.fnDraw();
            });
            $("#classification_level_id").change(function(){
               TeacherFilter.fnDraw();
            });
            $("#subject_id").change(function(){
               TeacherFilter.fnDraw();
            });
    });
    // $(function() {   
        
    //     $("#classification_id").change(function(){
            
    //         var condition = $("#classification_id option:selected").text();

    //         $("#program_container").empty();

    //         if(condition == "College")
    //         {
    //             $("#program_container").append(''
    //                 +'<div class="col-md-3">'
    //                     +'<div class="form-group {{{ $errors->has('program_id') ? 'has-error' : '' }}}">'
    //                         +'<label for="program_id">{{ Lang::get('teacher.program') }}</label>'
    //                         +'<select class="form-control" name="program_id" id="program_id">'
    //                             +'<option type="text" name="0" id="" value=""></option>'
    //                                 +'@foreach($program_list as $program)'                                
    //                                         +'<option type="text" name="program_id" id="program_id" value="{{{$program->id}}}">{{$program->program_code}}</option>'                                                           
    //                                 +'@endforeach'
    //                         +'</select>'
    //                     +'</div>'
    //                 +'</div>'
    //                 +'');
    //         }
    //         $("#program_id").change(function(){
    //            TeacherFilter.fnDraw();
    //         });
    //     });
    // });
</script>
@stop