@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("employee.add_employee") }}} :: @parent
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
    	 @include('notifications')

		<div class="page-header"><br>
			<h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left"> {{{ Lang::get("employee.add_employee") }}}
				<div class="pull-right" style="margin-right: 10px !important">
            <a href="{{{ URL::to('employee/') }}}" class="btn btn-sm  btn-primary iframe"><span class="glyphicon glyphicon-list"></span> {{ Lang::get("employee.employee_record") }}</a>
				</div>
			</h3>
		</div>
		{!! Form::open(array('url'=> URL::to('employee/create'),'method'=>'POST', 'files'=>true)) !!}
		<!-- <form class="form-horizontal" method="post" action="{{ URL::to('employee/create') }}" autocomplete="off"> -->
			@include('employee.form')
			<div class="col-md-12">
				<hr/>
			</div>
			<!-- <div class="col-md-12">
				<div class="form-group">
					<label class="col-md-3 control-label" for="actions">&nbsp;</label>
					<div class="col-md-9">	
			 			<button type="submit" class="btn btn-sm btn-success">
			                <span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.create") }}
			            </button>	
			            <button type="reset" class="btn btn-sm btn-default">
			                <span class="glyphicon glyphicon-remove-circle"></span>  {{ Lang::get("form.reset") }}
			            </button>	
			 			<a href="{{{ URL::to('employee/employee_list') }}}" class="btn btn-sm btn-warning close_popup">
			                <span class="glyphicon glyphicon-ban-circle"></span>  {{ Lang::get("form.cancel") }}
			       		</a>
			        </div>
			    </div>
			</div>	 -->
		<div class = "col-md-2"></div>
        <div class = "col-md-4">  
          <div id="success"> </div>
          {!! Form::submit('Submit', array('class'=>'btn send-btn btn-success')) !!}
          {!! Form::close() !!}
        </div>
        <div class = "col-md-12">
	        <br/><br/>
        </div>
		<!-- </form> -->
	</div>
</div>
@stop
@section('scripts')
<script type="text/javascript">
  $(":submit").closest("form").submit(function(){
                $(':submit').attr('disabled', 'disabled');
            });
	$(function() {
		$("#permission").select2();

		$("#employee_type_id").change(function(){
		selectListChange('position_id','{{{URL::to("employee/positionDataJson")}}}',  { 'employee_type_id': $("#employee_type_id").val() } ,'Please select a Employee Type')
        });
	});

    $('#datepicker').datepicker({
	    format: "yyyy-mm-dd",
	    orientation: "auto",
	    autoclose: true,
	    startView: 1,
	    todayHighlight: true,
	    todayBtn: "linked",
    });

    function displayImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#myImg').attr('src', e.target.result);
                $('#image_canvas').val(e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

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

