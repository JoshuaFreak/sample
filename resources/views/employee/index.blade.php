@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("employee.employee_data") }}} :: @parent
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
    			{{{ Lang::get("employee.employee_data") }}}
    		
    		</h3>
      </div>
    		<div class="col-md-8">
    			<div class="form-group {{{ $errors->has('employee_id') ? 'has-error' : '' }}}">
    				<label class="col-md-3 control-label" for="employee_name">Search {!! Lang::get('employee.employee') !!}</label>
            <input type="hidden" name="employee_id" id="employee_id" value="{{$employee_id}}" />
    				<div class="col-md-9">
    					<input class="typeahead form-control" type="text" name="employee_name" id="employee_name" value="" />
    					{!! $errors->first('employee_id', '<label class="control-label" for="employee_name">:message</label>')!!}
    				</div>
    			</div>
        </div>
        <div class="col-md-2">
            <a id="view_full_detail"><button class="btn btn-sm btn-default">View Full Detail</button></a>    
        </div> 
        <div class="col-md-2">
            <button class="btn btn-sm btn-danger" type="button" id="delete_employee">Delete Employee</button> 
        </div>   
    </div>
    <div id="employeeDetailContainer"> 
    </div>
</div>

      <br/><br/>
  <div class="modal fade" id="photo_modal" role="dialog">

      <div class="modal-dialog">
      
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" >&times;</button>
              <h4 class="modal-title">Take Photo</h4>
            </div>
            <div class="modal-body">
          <div class="col-md-12 form-group">
            <div class="col-md-6" id="camera" style="padding-left: 0px;margin-left: 5px;margin-top: -50px;">
              <video id="video" width="530" height="530" autoplay></video>
            </div>
            <div class="col-md-6" hidden id="canvas_div" style="padding-left: 0px;margin-left: 5px;margin-bottom: 80px;">
              <canvas id="canvas" width="530" height="400"></canvas>
            </div>
          </div>
          <br/>
          <br/>
          <br/>
          <br/>
          <br/>
          <br/>
          <br/>
          <br/>
          <br/>
          <br/>
          <br/>
          <br/>
          <br/>
          <br/>
          <br/>
          <br/><br/>
          <br/>
          <br/>
          <br/>
          <div class="col-md-12 form-group" style="margin-top: -70px;">
            <div class="col-md-12">
              <button type="button" id="snap">Capture</button>
              <button type="button" hidden id="take">Take Another Photo</button>
            </div>
          </div>
          <br/>
          <br/>
          

            </div>
            <br/>
            <div class="modal-footer">
                <a id="get_photo" class="btn btn-success btn-ok" data-dismiss="modal">Accept</a>
                <button id="close_photo" class="btn btn-default" data-dismiss="modal">Close</button>

            </div>
          </div>
        
      </div>
  </div>



	
@stop
    
{{-- Scripts --}}
@section('scripts')

<script type="text/javascript">
     // $(":submit").closest("form").submit(function(){
     //      $(':submit').attr('disabled', 'disabled');
     //  });

  $(function(){
      var employee_id = $("#employee_id").val();
      if(employee_id != 0)
      {
        loadEmployeeDetail(employee_id);
      }
      $("#delete_employee").click(function(){

          swal({
                  title: "Are you sure?",
                  text: "You will not be able to recover this data!",
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "Yes, delete it!",
                  closeOnConfirm: false
                },
                function(){
                    $.ajax({
                        url:'{{{ URL::to("employee/delete_employee_data") }}}',
                        type:'POST',
                        data: {
                            'id' : $("#employee_id").val(),
                            '_token' : $("input[name=_token]").val(),
                        },
                        dataType: "json",
                        async:false,
                        success: function (data) 
                        {            
                            swal("Deleted!", "Your data has been deleted.", "success");  
                            location.reload();    
                        }  
                    });
                });
          })
  });

	function loadEmployeeDetail(employeeId)
	{
		//alert(employeeId);
		$.ajax(
				{
					url: "{{{ URL::to('employee/detail') }}}",
					data: { 
						'employee_id': employeeId 
					},
				}
			).done(function(employee_detail_html){
				$("#employeeDetailContainer").html(employee_detail_html);
          $('.datepicker1').datepicker({
            format: "yyyy-mm-dd",
            orientation: "auto",
            autoclose: true,
            startView: 1,
            todayHighlight: true,
            todayBtn: "linked",
          });

          $(".datepicker2").datepicker({ 
          format: "MM-yyyy",
          orientation: "top left",
          autoclose: true,    
          startView: "months", 
          minViewMode: "months",
          todayHighlight: true,
          todayBtn: "linked"
          });

          $("#employee_type_id").change(function(){
                selectListChange('position_id','{{{URL::to("employee/positionDataJson")}}}',  { 'employee_type_id': $("#employee_type_id").val() } ,'Please select a Employee Type')
          });

          $("#snap").click(function(){

            $("#canvas_div").removeAttr('hidden');
            $("#take").removeAttr('hidden');
            $("#camera").attr('hidden','hidden');
          });

          $("#take").click(function(){

            
            $("#canvas_div").attr('hidden','hidden');
            $("#camera").removeAttr('hidden');
            $("#take").attr('hidden','hidden');
          });
           $("#close_photo, #get_photo, #save_photo").click(function(){

            $("#canvas_div").attr('hidden','hidden');
            $("#camera").removeAttr('hidden');
            $("#take").removeAttr('hidden','hidden');
          });

          $(".skill_action").each(function(){
            var default_var = $(this).data('default');
            if(default_var == 1)
            {
             $(this).bootstrapToggle('on');
            }
            else
            {
             $(this).bootstrapToggle('off');
            }
          });
          
			});

  }


 

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
                START OF employee_name ->  typeahead
            *************************************************************************/
                var employee_list = new Bloodhound({
                        datumTokenizer: function (datum) {
                            //return Bloodhound.tokenizers.whitespace(datum.employee_name);
                               var tokens = [];
                                //the available string is 'name' in your datum
                                var stringSize = datum.employee_name.length;
                                //multiple combinations for every available size
                                //(eg. dog = d, o, g, do, og, dog)
                                for (var size = 1; size <= stringSize; size++){          
                                  for (var i = 0; i+size<= stringSize; i++){
                                      tokens.push(datum.employee_name.substr(i, size));
                                  }
                                }

                                return tokens;    


                        },
                        queryTokenizer: Bloodhound.tokenizers.whitespace,
                        limit: 10,
                       
                        remote:{
                        	url:'{{{URL::to("employee/dataJson") }}}?query=%QUERY',
                        	filter: function (employee_list) {
                                      // alert('this is an alert script from create');
                                      // console.log(employee_list); //debugging
                                      // Map the remote source JSON array to a JavaScript object array
                                    return $.map(employee_list, function (employee) {
                                        console.log(employee); //debugging
                                        return {
                                            employee_name: employee.first_name +' '+ employee.middle_name +' '+ employee.last_name+' - '+employee.nickname,
                                            id: employee.employee_id
                                        };
                                    });
                             }
                        }

                });

                employee_list.initialize();
                 console.log(employee_list);

                //its_customs_office_code_typeahead   -- this is the id of the div element that handles typeahead for export country name
               // $('#employee_employee_no_typeahead .typeahead').typeahead(null, {
                  $('#employee_name').typeahead(
                    {
                      hint: true,
                      highlight: true,
                      minLength: 1
                    }, 
                    {
                        employee_name: 'employee_list',
                         displayKey: 'employee_name',
                        source: employee_list.ttAdapter()
                        

                    }

                    ).bind("typeahead:selected", function(obj, employee, employee_no) {
                        //console.log(employee);
  						        loadEmployeeDetail(employee.id);
                      $("#employee_id").val(employee.id);
                      $("#view_full_detail").removeAttr('href');
                      $("#view_full_detail").attr('href','{{{ URL::to("employee/full_detail") }}}'+'?employee_id='+employee.id);
                      $("#view_full_detail").attr('target','_blank');
                      //$("#employee_name").val(employee.employee_name);

                     // alert(employee.employee_name);
                     
                    });

                   /********
                END OF employee_name ->  typeahead
            *************************************************************************/


                   
    });

        $('#photo_modal').on('shown.bs.modal', function(e) { 

            var canvas = document.getElementById("canvas"),
            context = canvas.getContext("2d");
            context.clearRect(0, 0, canvas.width, canvas.height);

            $(this).find('.btn-ok').attr('onclick', 'saveImage()');

          });
        function saveImage()
        { 
          var canvas = document.getElementById("canvas"),

            url = canvas.toDataURL("image/png");

            newImg =  document.getElementById("myImg"); // create img tag
            input =  document.getElementById("image_canvas"); // create img tag
            newImg.src = url;
            input.value = url;
            $("#camera").removeClass("hidden");
            // document.body.appendChild(newImg);
          // img = convertCanvasToImage(canvas);

        }
        // Put event listeners into place
        window.addEventListener("DOMContentLoaded", function() {
          // Grab elements, create settings, etc.
          var canvas = document.getElementById("canvas"),
            context = canvas.getContext("2d"),
            video = document.getElementById("video"),
            videoObj = { "video": true },
            errBack = function(error) {
              var code = "Webcam not found";
              console.log("Video capture error: ", code); 
               
            };

          document.getElementById("snap").addEventListener("click", function() {
            var canvas = document.getElementById("canvas"),
            image = context.drawImage(video, 0, 0, canvas.width, canvas.height);

            $("#camera").addClass("hidden");
          });

          document.getElementById("take").addEventListener("click", function() {
            var canvas = document.getElementById("canvas"),
            context = canvas.getContext("2d");

            context.clearRect(0, 0, canvas.width, canvas.height);
            $("#camera").removeClass("hidden");
          });


          // Put video listeners into place
          if(navigator.getUserMedia) { // Standard
            navigator.getUserMedia(videoObj, function(stream) {
              video.srcObject = stream;
              video.play();
            }, errBack);
          }
          else if(navigator.webkitGetUserMedia) { // WebKit-prefixed
            navigator.webkitGetUserMedia(videoObj, function(stream){
              video.srcObject = window.URL.createObjectURL(stream);
              video.play();
            }, errBack);
          }
          else if(navigator.mozGetUserMedia) { // Firefox-prefixed
            navigator.mozGetUserMedia(videoObj, function(stream){
              video.srcObject = window.URL.createObjectURL(stream);
              video.play();
            }, errBack);
          }
        }, false);

</script>


@stop