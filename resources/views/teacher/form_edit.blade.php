<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="form-group {{{ $errors->has('employee_name') ? 'has-error' : '' }}}">
<label class="col-md-3 control-label" for="employee_name">{{ Lang::get('teacher.teacher_search') }}</label>
<div class="col-md-3">
   <input type="text" class="form-control" id="employee_name" name="employee_name" value="{{{ Input::old('teacher_name', isset($teacher_name) ? $teacher_name : null) }}}">
   {!! $errors->first('employee_name', '<label class="control-label" for="employee_name">:message</label>')!!}
   <input type="hidden" id="employee_id" name="employee_id" value="{{{ Input::old('employee_id', isset($employee) ? $employee->employee_id : null) }}}">
</div>
</div>

<div class="form-group {{{ $errors->has('default_program_id') ? 'has-error' : '' }}}">
   <label class="control-label col-md-3">{{ Lang::get('teacher.default_program') }}</label>
   <div class="col-md-3">
      <select class="form-control" id="default_program_id" name="default_program_id">
         <option class="form-control">{{ $default_program -> program_name }}</option>
         <option disabled>-----</option>
         @foreach($program_list as $program)
            <option class="form-control" id="default_program_id" value="{{ $program -> id }}">{{ $program -> program_name }}</option>
         @endforeach
      </select>   
   </div>
</div>

<div class="form-group {{{ $errors->has('program_id') ? 'has-error' : '' }}}">
   <label class="control-label col-md-3">{{ Lang::get('program.program') }}</label>
   <div class="col-md-3">
      <select name="program_id[]" id="program_id" multiple style="width:  100%;">
         <option></option>
         @foreach($program_list as $program)
            <option id="program_id" value="{{ $program -> id }}">{{ $program -> program_name }}</option>
         @endforeach
      </select>
   </div>
</div>

<div class="form-group {{{ $errors->has('program_id') ? 'has-error' : '' }}}">
   <label class="control-label col-md-3">{{ Lang::get('program.program') }}</label>
   <div class="col-md-6">
      @foreach($teacher_program_list as $teacher_program)
         <div class="col-md-4">
            <label class="control-label">{{ $teacher_program -> program_name }}</label>
         </div>
         <div class="col-md-2">
            <button type="button" id="btn_delete" class="btn btn-sm btn-danger" data-id="{{ $teacher_program -> id }}">X</button>
         </div>
      @endforeach
   </div>
</div>

@section('scripts')
<script type="text/javascript">
   $("#program_id").select2();
   $('#btn_delete[data-id]').click(function(){
      var id = $(this).attr('data-id');

      swal({
         title: "Are you sure?",
         type: "warning",
         showCancelButton: true,
         confirmButtonColor: "#DD6B55",
         confirmButtonText: "Yes, delete it!",
         closeOnConfirm: false
      },
      function(){
        $.ajax({
            url: "{{ URL::to('teacher/deleteTeacherProgram') }}",
            type: "POST",
            data:
            {
               'id': id,
               '_token': $("input[name=_token]").val(),
            },
            async: false
         }).done(function(){
            swal('Successfull!','','success')
            window.location.replace("{{ URL::to('teacher/') }}");
         });
      }); 
   });
   var id = $(this).attr('data-id');
   $.ajax({
      url:'{{{ URL::to("teacher/teacherprogramdataJson") }}}',
      type:'GET',
      data:
         {  
            'teacher_id': id,
         },
         dataType: "json",
         async:false,
         success: function (data) 
         {   
            $.each(data, function(key, value) {
               $("#program_id > option[value='"+value.program_id+"']" ).addClass('optdisabled');
               $("#program_id > option[value='"+value.program_id+"']" ).attr('disabled','disabled');
            });
         }
   });
</script>
@endsection