@extends('site.layouts.default_portal')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("students_portal.student_ledger") }}} :: @parent
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
      <h2>{{{ Lang::get("students_portal.student_ledger") }}}</h2>
    </div>
    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
    <div class="col-md-12">
      <div class="col-md-5">
        <div class="form-group {{{ $errors->has('term_id') ? 'has-error' : '' }}}">
          <label  for="term_id">Select {{ Lang::get('term.term') }}</label>
          <select class="form-control" name="term_id" id="term_id">
            <option type="text" name="0" id="" value=""></option>
            @foreach($term_list as $term)                                
              <option type="text" name="term_id" id="term_id" value="{{{$term->term_id}}}" data-student="{{{$term->student_id}}}">{{$term->term_name}}</option>                                                           
            @endforeach
          </select>
        </div>
      </div>
    </div>
    <br><br><br><br>
    <table id="table" class="table table-striped table-hover">
      <thead>
        <tr>
          <th>{{ Lang::get("student_ledger.date") }}</th>
          <th> {{ Lang::get("student_ledger.or_no") }}</th>
          <th> {{ Lang::get("student_ledger.particulars") }}</th>
          <th> {{ Lang::get("student_ledger.debit") }}</th>
          <th> {{ Lang::get("student_ledger.credit") }}</th>
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
  var oTable;
  $(document).ready(function() {


      oTable = $('#table').dataTable( {
        "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r><'row'<'col-md-6'i><'col-md-6'p>>",
        "sPaginationType": "bootstrap",
        "bSort" : false,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "{{ URL::to('students_portal/student_ledger/data?') }}"+"student_id="+$("#term_id").find(':selected').data('student'),
        // "sAjaxSource": "{{ URL::to('student_ledger/data/') }}",
        "fnDrawCallback": function ( oSettings ) {
          },
        "fnServerParams": function(aoData){
            aoData.push(
                { "name":"student_id", "value": $("#term_id").find(':selected').data('student') },
                { "name":"term_id", "value": $("#term_id").val() }
            );
        }
      });

      $("#term_id").change(function(){
         oTable.fnDraw();
      });
  });
</script>
@stop