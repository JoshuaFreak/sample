 @extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("admission_requirement.admission") }}} :: @parent
@stop
{{-- Content --}}
@section('content')

            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
           
        
<!-- Side Bar -->
    <div class="navbar-default sidebar" role="navigation">
      <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
                @include('payment_sidebar')
                
                <!-- <li>
                    <a href="{{URL::to('site/site_tagging')}}" {{ (Request::is('site/site_tagging*') ? ' class=active' : '') }}>
                    <i class="glyphicon glyphicon-list"></i><span cslass="hidden-sm text"> {{ Lang::get("site/site_tagging.site_tagging") }}</span></a>
                </li> -->
        </ul>
      </div>
    </div>

<div id="page-wrapper">
    <div class="row">
      <div class="page-header">
      		<h2>
      			{{{ Lang::get("payment.admission") }}}
      			
      		</h2>
      </div>
      	<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
      	<div class="col-md-12">
      	     <div class="form-group {{{ $errors->has('trainee_id') ? 'has-error' : '' }}}">
          			<label class="col-md-1 control-label" for="trainee_name">{!! Lang::get('payment.trainee_id') !!}</label>
          			<input type="hidden" name="trainee_id" id="trainee_id" value="0" />
          			<div class="col-md-5">
          					<input class="typeahead form-control" type="text" name="trainee_name" id="trainee_name" value="" autofocus/>
          				{!! $errors->first('trainee_id', '<label class="control-label" for="trainee_name">:message</label>')!!}

          			</div>
      		  </div>
      	</div>
        <div class="col-md-12"><hr></div>
        <div class="form-group col-md-4 {{{ $errors->has('course_id') ? 'has-error' : '' }}}">
            <label class="col-md-3 control-label" for="course_id">{!! Lang::get('registrar.course') !!}</label>
            <div class="col-md-9">
                <select class="form-control" name="course_id" id="course_id" tabindex="4">
                    @foreach($course_list as $course)
                        <option value="{{{$course->id}}}">{{{$course -> course_code}}}</option>
                    @endforeach
                </select>
                {!! $errors->first('course_id', '<label class="control-label" for="course_id">:message</label>')!!}

            </div>
        </div>  
        <div class="form-group col-md-3 {{{ $errors->has('month') ? 'has-error' : '' }}}">
            <label class="col-md-3 control-label" for="month">{!! Lang::get('registrar.month') !!}</label>
            <div class="col-md-9">
                <input class="form-control datepicker" type="text" name="month" id="month" value="" />
                {!! $errors->first('month', '<label class="control-label" for="month">:message</label>')!!}

            </div>
        </div>
        <div class="form-group col-md-3 {{{ $errors->has('batch_id') ? 'has-error' : '' }}}">
            <label class="col-md-3 control-label" for="batch_id">{!! Lang::get('registrar.batch') !!}</label>
            <div class="col-md-9">
                <select id="batch_container" class="form-control" name="batch_id" id="batch_id" tabindex="4">

                </select>
                {!! $errors->first('batch_id', '<label class="control-label" for="batch_id">:message</label>')!!}

            </div>
        </div> 
        <div class="form-group col-md-2 {{{ $errors->has('batch_id') ? 'has-error' : '' }}}">
            <button id="load"type="button" class="btn btn-sm btn-success">
            Load
            </button>   
        </div>   
      	
  </div>
  <!-- <div class="col-md-12"><hr></div> -->
  <div class="col-md-12" style="border: 2px solid;">
    <div id="admission_slip_table">
       <div class="col-md-12"><h4>Admission Slip</h4></div>
       <div class="col-md-12"><h5><label id="admission_trainee_name" class="control-label" ></label></h5></div>
      <table id="table" class="table table-striped table-bordered">
        <tr>
          <th><label class="control-label">Batch No.</label></th>
          <th><label class="control-label">Training Program</label></th>
          <th><label class="control-label">Day/s</label></th>
          <th><label class="control-label">Training Date</label></th>
          <th><label class="control-label">Time</label></th>
          <th><label class="control-label">Room</label></th>
        </tr>
      <tbody id="admission_slip">
      </tbody> 
      </table>
      <table id="table" class="table table-striped table-bordered">
        <tbody id="trainee_requirement">
          <div class="col-md-12"><h5>Requirement/s Submitted</h5></div>
        </tbody>
      </table>
      <table id="table" class="table table-striped table-bordered">
        <tbody id="requirement_to_submit">
          <div class="col-md-12"><h5>Requirement/s to be Submitted</h5></div>
          <span id="print_admission"></span>          
        </tbody>

      </table>
    </div>
  </div>
  <div class="col-md-12"></div>

  <!-- RECEIPT FORM -->
  <div style="display:none;font-family:monaco;">
    <div id="printAdmission">
      <table>
        <th colspan="6"><h3>Admission Slip</h3></th>
        <tr>
          <td colspan="5">Name : <span id="trainee_name_print"></span></td>
          <!-- <td></td> -->
          <!-- <td></td> -->
          <!-- <td></td> -->
          <!-- <td></td> -->
          <td>{!! date('M d, Y ')!!}</td>

        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr class="text-center">
          <td width="10%"><strong>Batch No</strong></td>
          <td width="20%"><strong>Days</strong></td>
          <td width="10%"><strong>Training Program</strong></td>
          <td width="20%"><strong>Training Date</strong></td>
          <td width="20%"><strong>Time</strong></td>
          <td width="10%"><strong>Room</strong></td>
        </tr>
        <tbody id="admission_content">

        </tbody>

      </table>

        <span>Requirement/s Submitted</span>

        <div id="requirements_submitted">
        </div>

        <span>Requirement/s to be submitted</span>
        <div id="requirements_to_be_submitted">
        </div>
    </div>
  </div>
<!-- RECEIPT END -->
</div> <!-- admission end -->



  
@stop
    
{{-- Scripts --}}
@section('scripts')

<script>
    function Print() {
      $("#print_admission").find('.print').on('click', function() {
      //Print printAdmission with default options
      $("#printAdmission").print({
        mediaPrint: true,
            });
          });
        }

  </script>
	<script type="text/javascript">
    $(function() {
            var substringMatcher = function(strs) {
                      return function findMatches(q, cb) {
                        var matches, substringRegex;
                     
                        // an array that will be populated with substring matches
                        matches = [];
                     
                        // regex used to determine if a string contains the substring `q`
                        substrRegex = new RegExp(q, 'i');
                     
                        // iterate through the pool of strings and for any string that
                        // contains the substring `q`, add it to the `matches` array
                        $.each(strs, function(i, str) {
                          if (substrRegex.test(str)) {
                            matches.push(str);
                          }
                        });
                     
                        cb(matches);
                      };
                    };


            /********
                RMD 2015-03-07
                START OF Trainee_name ->  typeahead
            *************************************************************************/
                var trainee_list = new Bloodhound({
                        datumTokenizer: function (datum) {
                            //return Bloodhound.tokenizers.whitespace(datum.trainee_name);
                               var tokens = [];
                                //the available string is 'name' in your datum
                                var stringSize = datum.trainee_name.length;
                                //multiple combinations for every available size
                                //(eg. dog = d, o, g, do, og, dog)
                                for (var size = 1; size <= stringSize; size++){          
                                  for (var i = 0; i+size<= stringSize; i++){
                                      tokens.push(datum.trainee_name.substr(i, size));
                                  }
                                }

                                return tokens;    


                        },
                        queryTokenizer: Bloodhound.tokenizers.whitespace,
                        limit: 10,
                       
                        remote:{
                        	url:'{{{ URL::to("information/trainee/dataJson?query=%QUERY") }}}',
                        	filter: function (trainee_list) {
                                      // alert('this is an alert script from create');
                                  // console.log(trainee_list); //debugging
                                    // Map the remote source JSON array to a JavaScript object array
                                    return $.map(trainee_list, function (trainee) {
                                        console.log(trainee); //debugging
                                        return {
                                            trainee_name: trainee.last_name + ', '+trainee.first_name + ' ' + trainee.middle_name + ' - ' + trainee.trainee_no,
                                            id: trainee.id
                                        };
                                    });
                             }
                        }

                });

                trainee_list.initialize();
                 console.log(trainee_list);

                //its_customs_office_code_typeahead   -- this is the id of the div element that handles typeahead for export country name
               // $('#trainee_trainee_no_typeahead .typeahead').typeahead(null, {
                  $('#trainee_name').typeahead(
                    {
                      hint: true,
                      highlight: true,
                      minLength: 1
                    }, 
                    {
                        trainee_name: 'trainee_list',
                         displayKey: 'trainee_name',
                        source: trainee_list.ttAdapter()
                        

                    }

                    ).bind("typeahead:selected", function(obj, trainee, trainee_no) {
                        console.log(trainee);
  
                       $("#trainee_id").val(trainee.id);
                       $("#trainee_name").val(trainee.trainee_name);
                       $("#admission_trainee_name").text(trainee.trainee_name);
                    });

                   /********
                END OF Trainee_name ->  typeahead
            *************************************************************************/


                   
    });


</script>
<script>

$(function() {

        $('.datepicker').datepicker(
        { 
            startView: "months", 
            minViewMode: "months",
            format: 'MM ,yyyy',
            orientation: "top left",
            autoclose: true,

        })

    });


    $("#load").click(function(){
        var trainee_name = $("#trainee_name").val();

        $("#trainee_name_print").text(trainee_name);
        // alert(trainee_name);
        var course_id = $("#course_id").val();


        var date = $("#month").val();
        var date = new Date(date);
        month = date.getMonth()+1;
        year = date.getFullYear();
        var batch = $("#batch_container").val();

        var year_month = year+"-"+month+"-"+batch;

        var trainee_id =$("#trainee_id").val();
        

         $.ajax( 
            {
                url:'{{{ URL::to("payment/admission_slip/admissionDataJson") }}}',
                type:'GET',
                data:
                    {  
                        'course_id': course_id,
                        'year_month': year_month,
                    },
                dataType: "json",
                async:false,
                success: function (data) 
                {       
                    $("#admission_slip").empty();
                    $("#trainee_requirement").empty();
                    $("#requirement_to_submit").empty();
                    $("#print_admission").empty();

                    if(data == "")
                    {
                        alert("No Schedule");
                    }
                    $.each(data, function(key, value) {
                        
                        $("#admission_slip").append(''
                            +'<tr>'
                              +'<td><label class="control-label" name="admission_batch_no">'+value.batch_no+'</label></td>'
                              +'<td><label class="control-label" name="admission_course_code">'+value.course_code+'</label></td>'
                              +'<td><label class="control-label" name="admission_day">'+value.day_name+'</label></td>'
                              +'<td><label class="control-label" name="admission_date">'+value.date+'</label></td>'
                              +'<td><label class="control-label" name="admission_time">'+value.time_in+' - '+value.time_out+'</label></td>'
                              +'<td><label class="control-label" name="admission_room">'+value.room+'</label></td>'
                            +'</tr>'
                          +'');

                    });
                    $("#print_admission").append('<button type="button" id="print_me" class="btn btn-sm btn-primary print no-print">Print</button>');
                    Print();
                }

            });
        traineeRequirement($("#trainee_id").val());
        $("#admission_slip > tr").each(function(){
        
        var batch_no = $(this).find('label[name="admission_batch_no"]').text();
        var course_code = $(this).find('label[name="admission_course_code"]').text();
        var day_name = $(this).find('label[name="admission_day"]').text();
        var date = $(this).find('label[name="admission_date"]').text();
        var time = $(this).find('label[name="admission_time"]').text();
        var room = $(this).find('label[name="admission_room"]').text();
        
        
        $("#admission_content").append("<tr class='text-center'><td width='10%'>"+batch_no+"</td><td width='10%'>"+day_name+"</td><td width='20%'>"+course_code+"</td><td width='20%'>"+date+"</td><td width='20%'>"+time+"</td><td width='10%'>"+room+"</td></tr>");
        
        });
        $("#trainee_requirement > tr").each(function(){
        var req_desc = $(this).find('span[name="req_desc"]').text();
        $("#requirements_submitted").append("<ul><li>"+req_desc+"</li></ul>");
        });
    });

    $("#month").change(function(){

        var date = $("#month").val();
        var date = new Date(date);
        month = date.getMonth()+1;
        year = date.getFullYear();

        var counter = weekCount(year, month);

        $("#batch_container").empty();

        var prefix = ['0','st','nd','rd','th','th','th','th'];
        for(var i=1; i <= counter; i++)
        {

            $("#batch_container").append('<option value="'+i+'">'+i+''+prefix[i]+' week</option>');
        }
    });

    function weekCount(year, month_number) {

        // month_number is in the range 1..12

        var firstOfMonth = new Date(year, month_number-1, 1);
        var lastOfMonth = new Date(year, month_number, 0);

        var used = firstOfMonth.getDay() + lastOfMonth.getDate();

        return Math.ceil( used / 7);
    } 


    function traineeRequirement($trainee_id)
    {
        $.ajax({

            url:'{{{ URL::to("information/trainee_requirement/dataJson") }}}',
            type:'GET',
            data:
                {  
                    'trainee_id': $trainee_id,
                },
            dataType: "json",
            async:false,

            success: function (data) 
            {     
                var count =1;
                $("#trainee_requirements").empty();
                $.each(data, function(key, value) {

                    $("#trainee_requirement").append('<tr>'
                        +'<td><label class="control-label">'+count+'. <span name="req_desc"> '+value.description+'</span></label></td>'
                        +'</tr>');
                    count++;
                });
            }


        });
    }

</script>
<script>

      
</script>
@stop