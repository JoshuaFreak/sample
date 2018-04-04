@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("scheduler.schedule") }}} :: @parent
@stop
{{-- Content --}}
@section('content')
<style>
th
{
  /*background-color: #F4DC5A;*/
  /*background-image: url("{{{ asset('assets/site/images/th-background.jpg') }}}");*/
}
.f_td
{
  /*background-color: #FFF4B9; */
}
</style>
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">    
        <ul class="nav" id="side-menu"> 
            @include('scheduler_sidebar')
        </ul>
    </div>
</div>
<div id="page-wrapper">
  <div class="row">
    <div class="page-header"><br>
      <h2>
        {{{ Lang::get("scheduler.scheduler_report") }}}
      </h2>
    </div>
    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
    <div class="col-md-12">
      
      <div class="col-md-3">
        <div class="form-group">
            <label for="program_id">{{ Lang::get('scheduler.term') }}</label>
            <select class="form-control" id="term_id" name="term_id">
                  <option value"0"></option>
                  @foreach($term_list as $term)
                    @if($term -> id != 0)
                      <option value="{{{$term -> id}}}"> {{{ $term -> term_name }}}</option>
                    @endif
                  @endforeach
            </select>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
            <label for="section_id">{{ Lang::get('scheduler.classification_level') }}</label>
            <select class="form-control" id="classification_level_id" name="classification_level_id">
                  <option value"0"></option>
                  @foreach($classification_level_list as $classification_level)
                      @if($classification_level -> id != 0)
                        <option value="{{{$classification_level -> id}}}"> {{{ $classification_level -> level }}}</option>
                      @endif
                  @endforeach
            </select>
        </div>
      </div>
     <!--  <div class="col-md-3">
        <div class="form-group">
          <label for="term_id">{{ Lang::get('teacher.teacher') }}</label>
          <input type="text" class="form-control" id="teacher_id" name="teacher_id"/>
        </div>
      </div> -->
      <div class="col-md-3"><br/>
        <button class="btn btn-sm btn-success" type="button" id="load_schedule_report">
          <span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get('form.load') }}
        </button>

        <button class="btn btn-sm btn-primary" type="button" onclick="printDiv('schedule_report_container')">
          <span class="glyphicon glyphicon-print"></span> {{ Lang::get('form.print') }}
        </button>
      </div>
      <br><br><br><br><br><br><br>
      <div id="schedule_report_container" class="col-md-12">
      </div>
    </div>
  </div>
</div>
@stop

{{-- Scripts --}}
@section('scripts')
<script type="text/javascript">

function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}

$(function(){
    $("#load_schedule_report").click(function(){
      var term_id = $("#term_id").val();
      var classification_level_id = $("#classification_level_id").val();

            $.ajax({
                url:'{{{ URL::to("scheduler/getScheduleClassificationLevel") }}}',
                type:'GET',
                data:
                    {  
                        'term_id': term_id,
                        'classification_level_id': classification_level_id,
                    },
                dataType: "json",
                async:false,
                success: function (data) 
                {    
                    $("#schedule_report_container").empty();

                     // ----- Start .map ajax -----
                    $.map(data, function (item) 
                    {       
                        $("#schedule_report_container").append('<h3>Schedule Report</h3>'
                          +'<table class="table" border="1">'
                          +'<tbody id="classification_level_header_'+item.classification_level_id+'"></tbody>'
                                +'<th width="15%">'+item.level+'</th>'
                                +'<th width="10%">Monday</th>'
                                +'<th width="10%">Tuesday</th>'
                                +'<th width="10%">Wednesday</th>'
                                +'<th width="10%">Thursday</th>'
                                +'<th width="10%">Friday</th>'
                          +'<tbody id="classification_level_content_'+item.classification_level_id+'"></tbody>'
                          +'</table>'
                          +'');

                          // ----- Start Inner ajax -----

                          $.ajax({
                              url:'{{{ URL::to("scheduler/getScheduleDayReport") }}}',
                              type:'GET',
                              data:
                                  {  
                                      'term_id': item.term_id,
                                      'classification_level_id': item.classification_level_id,
                                  },
                              dataType: "json",
                              async:false,
                              success: function (data) 
                              {    
                                  $.map(data, function (item) 
                                  {       
                                      $("#classification_level_content_"+item.classification_level_id).append(''
                                        +'<tr>'
                                            +'<td class="f_td">'+item.time_start+' '+item.time_start_session+' - '+item.time_end+' '+item.time_end_session+'</td>'
                                            +'<td align="center" height="70px;" id="1_'+item.classification_level_id+'_'+item.time_start_id+'_'+item.time_end_id+'"></td>'
                                            +'<td align="center" height="70px;" id="2_'+item.classification_level_id+'_'+item.time_start_id+'_'+item.time_end_id+'"></td>'
                                            +'<td align="center" height="70px;" id="3_'+item.classification_level_id+'_'+item.time_start_id+'_'+item.time_end_id+'"></td>'
                                            +'<td align="center" height="70px;" id="4_'+item.classification_level_id+'_'+item.time_start_id+'_'+item.time_end_id+'"></td>'
                                            +'<td align="center" height="70px;" id="5_'+item.classification_level_id+'_'+item.time_start_id+'_'+item.time_end_id+'"></td>'
                                        +'</tr>'
                                        +'');

                                     
                                  });
                              }  

                          });

                          // ----- End Inner ajax -----

                          // ----- Start 2nd Inner ajax -----
                          $.ajax({
                              url:'{{{ URL::to("scheduler/getScheduleReport") }}}',
                              type:'GET',
                              data:
                                  {  
                                      'term_id': item.term_id,
                                      'classification_level_id': item.classification_level_id,
                                  },
                              dataType: "json",
                              async:false,
                              success: function (data) 
                              {    
                                  $.map(data, function (item) 
                                  {       
                                      var first_name = item.first_name;
                                      first_name = first_name.substr(0,1);
                                      $('#'+item.day_id+'_'+item.classification_level_id+'_'+item.time_start_id+'_'+item.time_end_id).text(item.class+' ('+first_name+'. '+item.last_name+')');

                                  });
                              }  

                          });

                          // ----- End 2nd Inner ajax -----
                    });

                    // ----- End .map ajax -----
                }  

            });
    });
});

</script>
@stop