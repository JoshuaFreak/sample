@extends('site/layouts/default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("student.student_list") }}} :: @parent
@stop
{{-- Content --}}
@section('content')
<div class="navbar-default sidebar" role="navigation">
  <div class="sidebar-nav navbar-collapse">    
      <ul class="nav" id="side-menu"> 
      @include('enrollment_sidebar')
    </ul>
  </div>

</div>
<div id="page-wrapper">
    <div class="row">
        <div class="col-md-12 page-header">
            <h2 class="col-md-8">{{ Lang::get("student.student_list")}}</h2>
        </div>
        <div class="col-md-12">
          <div class="col-md-4">
              <div class="form-group {{{ $errors->has('student_name') ? 'has-error' : '' }}}">
                  <label for="student_name">{{ Lang::get('student.term_id') }}</label>
                  <select class="form-control term_id" name="term_id" id="term_id">
                  <option name="term_id" value="0"></option>
                  @foreach($term_list as $term)

                      @if($term->id == '0')
                     
                      @else

                      <option name="term_id" value="{{{ $term->id }}}">{{{ $term->term_name}}}</option>
                      @endif
                  @endforeach    
                  </select>
                  
              </div>
          </div>
       </div>
        <input type="hidden" id="payment_scheme_id" value=""/>
        <ul class="nav nav-tabs">
              <li class="active"><a href="#all_data_tab" data-toggle="tab">All Students <i class="sf"></i></a></li>
                @foreach($classification_level_list as $classification_level)
                    @if($classification_level->id == '0')
                    @else
                        <li><a href="#tab_classification_level_{{{$classification_level->id}}}" data-toggle="tab">{{{$classification_level->level}}}<i class="sf"></i></a></li>
                    @endif
                @endforeach
        </ul>

        <form id="accountForm" method="post" class="form-horizontal">
            <input type="hidden" name="_token" id="_token" value="{{{ csrf_token() }}}" />
            <div class="tab-content">
                    <div class="tab-pane active" id="all_data_tab"><br>
                        <table id="table" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>{{ Lang::get("student.student_no") }}</th>
                                    <th>{{ Lang::get("student.name") }}</th>
                                    <th>{{ Lang::get("student.gender") }}</th>
                                    <th>{{ Lang::get("student.school_year") }}</th>
                                    <th>{{ Lang::get("student.year_level") }}</th>
                                    <th>{{ Lang::get("student.status") }}</th>
                                    <th>{{ Lang::get("form.action") }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                @foreach($classification_level_list as $classification_level)
                    @if($classification_level->level == '1st Grade')
                      <div class="tab-pane" id="tab_classification_level_{{{$classification_level->id}}}"><br>
                    @else
                      <div class="tab-pane" id="tab_classification_level_{{{$classification_level->id}}}"><br>
                    @endif
                    <table id="table_classification_level_{{{$classification_level->id}}}" class="table table-striped table-hover">
                        <thead>
                            <tr>
                              <th>{{ Lang::get("student.student_no") }}</th>
                              <th>{{ Lang::get("student.name") }}</th>
                              <th>{{ Lang::get("student.gender") }}</th>
                              <th>{{ Lang::get("student.school_year") }}</th>
                              <th>{{ Lang::get("student.year_level") }}</th>
                              <th>{{ Lang::get("student.status") }}</th>
                              <th>{{ Lang::get("form.action") }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>                
                @endforeach
            </div>
        </form>
    
    </div>
</div>
@stop


@include('enroll_student.edit_enrolled_student_modal')


{{-- Scripts --}}
@section('scripts')

<script src="{{{asset('assets/site/js/enrolled_student/editenrolledstudentmodal.js')}}}"></script>
<script type="text/javascript">

</script>
<script type="text/javascript">
   
    $(":submit").closest("form").submit(function(){
           $(':submit').attr('disabled', 'disabled');
       });
        var array = [];
        
        var oTable;
        $(document).ready(function() {
       
            @foreach($classification_level_list as $classification_level)
            array.push({{$classification_level -> id}});
            oTable = $('#table_classification_level_{{{$classification_level->id}}}').DataTable( {
                "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
                "sPaginationType": "bootstrap",
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "{{ URL::to('enroll_student/data?level=') }}{{$classification_level->level}}",
                "fnDrawCallback": function ( oSettings ) {
                    $(".iframe").colorbox({
                        iframe : true,
                        width : "80%",
                        height : "80%",
                        onClosed : function() {
                            window.location.reload();
                        }
                    });


                  $(".edit_scheme").click(function()
                  {
                      var payment_scheme_id = $(this).data('payment_scheme_id');  

                      // alert(payment_scheme_id);

                      $("#edit_enrolled_student_payment_scheme [value='"+payment_scheme_id+"']").attr("selected","selected");     
                  });
                }
            });
  

           @endforeach
           $("#term_id").change(function() { 
                  array.forEach(myFunction);

                  myAllFunction();
            });

        });
          
          function myFunction(item, index) {
            // demoP.innerHTML = demoP.innerHTML + "index[" + index + "]: " + item + "<br />"; 
            // $('#table_classification_'+item).dataTable().fnDraw();
            $('#table_classification_level_'+item).dataTable().fnReloadAjax("enroll_student/data?level="+item+"&term_id="+$("#term_id").val());
            // $('#table_classification_'+item).dataTable()._fnAjaxUpdate();
          }

          var table;

            /* for all*/
            $(document).ready(function() {
              table = $('#table').DataTable({
                  "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
                  "sPaginationType": "bootstrap",
                  "bProcessing": true,
                  "bServerSide": true,
                  "sAjaxSource": "{{ URL::to('enroll_student/dataAll?term_id=') }}"+$("#term_id").val(),
                  "fnDrawCallback": function ( oSettings ) {
                      $(".iframe").colorbox({
                          iframe : true,
                          width : "80%",
                          height : "80%",
                          onClosed : function() {
                              window.location.reload();
                          }
                      });
                  }
              });
            });
          function myAllFunction(item) {
            $('#table').dataTable().fnReloadAjax("enroll_student/dataAll?term_id="+$("#term_id").val());
          }

</script>
@stop
