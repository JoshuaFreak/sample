@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("teacher.create_new_teacher") }}} :: @parent
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
        @include('notifications')
        <div class="page-header"><br>
            <h3> {{{ Lang::get("teacher_detail.create_new_teacher") }}}
                <div class="pull-right">
                    <div class="pull-right">
                        <a href="{{{ URL::to('teacher') }}}" class="btn btn-sm  btn-primary iframe"><span class="glyphicon glyphicon-list"></span> {{ Lang::get("teacher_detail.teacher_detail_list") }}</a>
                    </div>
                </div>
            </h3>
        </div>
         <form class="form-horizontal" method="post" action="{{ URL::to('teacher/create') }}"  autocomplete="off">
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
            <input type="hidden" name="teacher_id" id="teacher_id">
            @include('teacher.form')
            <div class="col-md-12">
               <div class="form-group">
                  <label class="col-md-3 control-label" for="actions">&nbsp;</label>
                  <div class="col-md-9">  
                     <button type="submit" class="btn btn-sm btn-success">
                        <span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.create") }}
                     </button>  
                     <a href="{{{ URL::to('teacher') }}}"> 
                     <button type="button" class="btn btn-sm btn-warning">
                        <span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.cancel") }}
                     </button>   
                     </a> 
                  </div>
               </div>
            </div>  
         </form>
        <br>
        <br>
        <br>
        <br>
    </div>
</div>   


@stop
@section('scripts')

    
<!-- <script id="teacher-detail-form-template" type="application/html-template">
    <div id="<<teacher-detail_form_id>>">
        <form class="form-horizontal">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-md-2 control-label" for="default_program_id">{{ Lang::get('teacher.default_program') }}</label>
                    <div class="col-md-4">
                        <select class="form-control" name="default_program_id" id="<<default_program_id>>" class="default_program_id" data-id="<<data>>" tabindex="4">
                           <option></option>
                           @foreach($program_list as $program)
                              <option class="form-control" name="default_program_id" value="{{ $program -> id }}">{{ $program -> program_name }}</option>
                           @endforeach
                        </select>   
                    </div>
                </div>
            
               <div class="form-group">
                   <label class="col-md-2 control-label" for="program_id">{{ Lang::get('program.program') }}</label>
                   <div class="col-md-4">
                       <select name="program_id[]" id="<<program_id>>" class="program_id" multiple style="width:100%;">
                          @foreach($program_list as $program)
                              <option name="program_id" value="{{ $program -> id }}">{{ $program -> program_name }}</option>
                           @endforeach
                       </select>
                   </div>
               </div>
            </div>
            <br/>
            <div class="form-group">
            <hr/>
            </div>
        </form>
    </div>
</script>    -->
<script type="text/javascript">

   $(function() {
      var employee_list = new Bloodhound({
         datumTokenizer: function (datum) {
            return Bloodhound.tokenizers.whitespace(datum.employee_name);
         },
         queryTokenizer: Bloodhound.tokenizers.whitespace,
         limit: 10,
         remote: {
            // url points to a json file that contains an array of country names, see
            // https://github.com/twitter/typeahead.js/blob/gh-pages/data/countries.json
            //url: '../../its/student/dataJson',
            //url:'http://boc.itechrar.com/its/student/dataJson',
            url:'../employee/dataJsonTeacher?query=%QUERY',

            // the json file contains an array of strings, but the Bloodhound
            // suggestion engine expects JavaScript objects so this converts all of
            // those strings
            filter: function (employee_list) {
               // alert('this is an alert script from create');
               //console.log(employee_list); //debugging
               // Map the remote source JSON array to a JavaScript object array
               return $.map(employee_list, function (employee) {
               // console.log(employee.employee_name); //debugging

               return {
                  employee_name:  employee.last_name + ', '+employee.first_name + ' ' + employee.middle_name,
                  id: employee.teacher_id
               };
               });
            }
         }
      });

      employee_list.initialize();
      console.log(employee_list);

      $('#employee_name').typeahead(null, {
         employee_name: 'employee_list',
         displayKey: 'employee_name',
         source: employee_list.ttAdapter()
      }).bind("typeahead:selected", function(obj, employee, employee_name) {
         console.log(employee);
         $("#teacher_id").val(employee.id);
         $("#employee_name").val(employee.employee_name);
      });
    });

   $(function(){
      $('#program_id').select2();   
   });

</script> 
@stop