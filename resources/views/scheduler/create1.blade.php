@extends('scheduler.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("scheduler.scheduler") }}} :: @parent
@stop

{{-- Content --}}
@section('content')
<style type="text/css">
.fix_con{
	width: 200px;
	display: inline-block;
}
.checkbox{
	width: 60px;
}
</style>
<div class="page-header">
	<h3> {{{ Lang::get("scheduler/scheduler.scheduler") }}}
		<div class="pull-right">
			<div class="pull-right">
	            <a href="{{{ URL::to('scheduler/schedule') }}}" class="btn btn-sm  btn-primary iframe"><span class="glyphicon glyphicon-list"></span> {{ Lang::get("scheduler/scheduler.scheduler_list") }}</a>
	        </div>
		</div>
	</h3>
</div>

<form id="my_form" name= "input" class="form-horizontal col-md-16" >
	@include('scheduler/scheduler.form')
	<div class="col-md-16">
		<div class="form-group">
			<label class="col-md-3 control-label" for="actions">&nbsp;</label>
			<div class="col-md-9">	
	 			<!-- <button type="submit" class="btn btn-sm btn-success">
	                <span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.create") }}
	            </button>	
	            <button type="reset" class="btn btn-sm btn-default">
	                <span class="glyphicon glyphicon-remove-circle"></span>  {{ Lang::get("form.reset") }}
	            </button>	
	 			<a href="{{{ URL::to('scheduler') }}}" class="btn btn-sm btn-warning close_popup">
	                <span class="glyphicon glyphicon-ban-circle"></span>  {{ Lang::get("form.cancel") }}
	       		</a> -->
	        </div>
	    </div>
	</div>	
</form>

<input type="hidden" id="time_range_count" value="0">
<input type="hidden" id="date_range_count" value="1">
<input type="hidden" id="training_id" value="">
@stop
@section('scripts')
	<script id="week_template" type="application/html-template">
		<div class="fix_con">
			<div class="checkbox col-md-12">
				<input type="checkbox" name="day[]" id="day_<<day_id>>" class="day form-control"  value="<<day_value>>"  data-day="<<day>>" data-date="<<date>>"></input>
				<label class="control-label"><b>&nbsp;&nbsp;&nbsp;&nbsp;<<day_label>></b</label>
			</div>
		</div>
	</script>


	<script id="time_range_template" type="application/html-template">
		<div class="fix_con">
			<div class="time_start form-group">
				<div class="col-md-11">
					<div class="col-md-12">
						<div class="datetimepicker3 input-group date col-md-12" >
							<input type="text" class="<<start>> form-control" id="<<time_start>>" value=""/>
							<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
			            </div>
			        </div>
				</div>
				<div class="col-md-1">
			    </div>
			</div>   
			<div class="time_end form-group">
				<div class="col-md-11">
					<div class="col-md-12">
						<div class="datetimepicker3 input-group date col-md-12">
							<input type="text" class="<<end>> form-control" id="<<time_end>>" value=""/>
							<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
			            </div>
			        </div>
				</div>
				<div class="col-md-1">
			    </div>
			</div>
			<div class="room form-group">
				<div class="col-md-11">
					<div class="col-md-12">
						<input class="<<room_class>> room form-control col-md-11" type="text" placeholder="Room" id="<<room>>" value=""/>
					</div>
				</div>
				<div class="col-md-1">
			    </div>
			</div>
			<div class="room form-group">
				<div class="col-md-11">
					<div class="col-md-12">
						<input class="<<module_class>> room form-control col-md-11" type="text" placeholder="Module" id="<<module>>" value=""/>
					</div>
				</div>
				<div class="col-md-1">
			    </div>
			</div>
		</div>
	</script>

	<script type="text/javascript">


		$(function(){
			
			$('#datepicker').datepicker(
			{ 	
				format: "yyyy-mm-dd",
				orientation: "top left",
				autoclose: true,
			})

			$("#date_check").click(function(){

				generateTimePicker();
				var date_from_date = $("#date_range_from").val();
				var date_to_date = $("#date_range_to").val();

				var date_from = new Date(date_from_date);
				var date_to = new Date(date_to_date);


				$("#week_container").empty();
				var time_range_count = $("#time_range_count").val();
				for(var i =1; i<= time_range_count; i++)
				{
					$("#time_range_"+i).remove();
					$("#time_range_count").val(0);
				}

				var count = 1;
				while(date_from <= date_to)
				{	

					var day = date_from.getUTCDay();
					var month = date_from.getUTCMonth()+1;
					var date = date_from.getUTCDate();
					var year = date_from.getFullYear();

					var weekdays = ["sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"];
					
					
					var template = $("#week_template").clone().html();
					var html = template
							.replace('<<day_id>>',count++)
							.replace('<<day_value>>',day)
							.replace('<<day>>',weekdays[day])
							.replace('<<date>>',year+'-'+month+'-'+date)
							.replace('<<day_label>>',weekdays[day]);

					$("#week_container").append(html);

					var newDate = date_from.setDate(date_from.getDate() + 1);
					date_from = new Date(newDate);

				}
		
				generateTimeRange();

				// $(".day").click(function(){
					
				// 	var className = $(this).data('day').toLowerCase();
				// 	var id = $(this).val();

				// 	$("."+className).attr("data-id",id);
					
				// 	if($(this).is(':checked'))
				// 	//alert(className);
				// 	{
				// 		$("."+className).prop("disabled",false);
				// 	}
				// 	else
				// 	{
				// 		$("."+className).prop("disabled",true);
				// 		$("."+className).val("");
				// 	}


				// });

			});
		});


		
		function generateTimeRange()
		{

				var date_from_date = $("#date_range_from").val();
				var date_to_date = $("#date_range_to").val();

				var date_from = new Date(date_from_date);
				var date_to = new Date(date_to_date);
				date_from_day = date_from.getUTCDay();
				date_to_day= date_to.getUTCDay();

				var count = 1;

				var time_range_count = parseInt($("#time_range_count").val()) + 1;
				$("#time_range_count").val(time_range_count);
				$("#schedule_container").append('<div class="form-group" id="time_range_'+time_range_count+'"></div>');	 

				while(date_from <= date_to)
				{	
       				
					day = date_from.getUTCDay();
					var weekdays = ["sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"];
					
					var newDate = date_from.setDate(date_from.getDate() + 1);
					date_from = new Date(newDate);

					var template = $("#time_range_template").clone().html();
					var html = template
						.replace('<<time_start>>','time_start_'+weekdays[day]+'_'+time_range_count)
						.replace('<<time_end>>','time_end_'+weekdays[day]+'_'+time_range_count)
						.replace('<<room_class>>',weekdays[day])
						.replace('<<room>>','room_'+weekdays[day]+'_'+time_range_count)
						.replace('<<module_class>>',weekdays[day])
						.replace('<<module>>','module_'+weekdays[day]+'_'+time_range_count)
						.replace('<<start>>',weekdays[day])
						.replace('<<end>>',weekdays[day])
						.replace('<<time_range_id>>','time_range_'+time_range_count);

					$("#time_range_"+time_range_count).append(html);
				}

				
				generateTimePicker();
				// var time_range_id = "#time_range_id"+time_range_count;
				// if($(time_range_id).html()==null)
				// {
				// 	for(var i=$("#time_range_count").val(); i<= $("#time_range_count").val(); i++)
		  //      			{
				// 			var template = $("#time_range_template").clone().html();
				// 			var html = template
				// 				.replace('<<time_start>>','time_start_'+ +'_'+i)
				// 				.replace('<<time_end>>','time_end_monday_'+i)
				// 				.replace('<<room>>','room_monday_'+i)
				// 				.replace('<<time_range_id>>','time_range_'+i);
				// 			$("#time_range_container").append(html);

				// 		}
				// }	
		}

		function generateTimePicker() 
		{
            $('.datetimepicker3').datetimepicker({
                format: 'LT'
            })
        }

	</script>
	<script>
			// code for adding a time_range
			$("#add_time").click(function(){
				
				generateTimeRange();
				generateTimePicker();
		    });


			$("#course_id").change(function(){


				$.ajax( 
		                    {
		                        url:'training/createJson',
		                        type:'get',
		                        data:
		                            {  
		                                'course_id': $("#couser_id").val(),
		                                'date_from' : $("#date_range_from").val(),
		                                '_token' : $("input[name=_token]").val(),
		                            },
		                        async:false		                    
		                    }
	                	).done(function( training ) {
	                    	$("#batch_no").val(training.batch_no+" "+training.batch_letter);

	                    // $("#date_range_"+i).remove();
	                    // $("#message").html('Date Range.'+i+' was successfully saved.');

	                	});
			});


		    // code for saving the dat
		    $("#save_update").click(function(){
		    	// alert($("input[name=_token]").val());
	    		$.ajax( 
		                    {
		                        url:'training/createJson',
		                        type:'post',
		                        data:
		                            {  
		                                'course_id': $("#course_id").val(),
		                                'instructor_id': $("#instructor_id").val(),
		                                'assessor_id': $("#assessor_id").val(),
		                                'date_from' : $("#date_range_from").val(),
		                                'date_to' : $("#date_range_to").val(),
		                                '_token' : $("input[name=_token]").val(),
		                            },
		                        async:false		                    
		                    }
	                	).done(function( training ) {
	                    
	                    	$("#training_id").val(training.id);
	                    // $("#date_range_"+i).remove();
	                    // $("#message").html('Date Range.'+i+' was successfully saved.');

	            });

	            $('input.day').each( function() {
					
					if($(this).prop('checked'))
            		{
            				 // alert($(this).data("day"));
            				//this is our loop for time range
			                for(var time_range = 1; time_range <= $("#time_range_count").val(); time_range++)
			       			{	
			       				
			       				// alert($("#time_start_"+$(this).data("day").toLowerCase()+"_"+time_range).val());
			            		if($("#time_range_"+time_range).html().length > 0)
			            			{
						                $.ajax( 
						                    {
						                        url:'{{{ URL::to("scheduler/createJson") }}}',
						                        type:'post',
						                        data:
						                            {  
						                            	'training_id': $("#training_id").val(),
			                                			'day_id': $(this).val(),
			                                			'date':$(this).data("date"),
						                                'room':$("#room_"+$(this).data("day").toLowerCase()+"_"+time_range).val(),
						                                'module':$("#module_"+$(this).data("day").toLowerCase()+"_"+time_range).val(),
						                                'time_in':$("#time_start_"+$(this).data("day").toLowerCase()+"_"+time_range).val(),
						                                'time_out':$("#time_end_"+$(this).data("day").toLowerCase()+"_"+time_range).val(),
						                                '_token' : $("input[name=_token]").val(),
						                            },
						                        async:false		                    
						                    }
						                ).done(function( data ) {
						                	alert("Schedule successfully saved!");
						                });
			            			}
					        }
            		}
	            });

				location.reload();
		    });





	

</script>
@stop