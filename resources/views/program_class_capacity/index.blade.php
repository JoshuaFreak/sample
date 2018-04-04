@extends('site.layouts.default')

{{-- Title --}}
@section('title')
Course
@endsection

{{-- Content --}}
@section('content')
<div class="navbar-default sidebar" role="navigation">
	<div class="sidebar-nav navbar-collapse">    
	    <ul class="nav" id="side-menu"> 
	      @include('registrar_sidebar')
	    </ul>
	</div>
</div>
<div id="page-wrapper">
   <div class="row">
   		<input type="hidden" id="counter" value="0">
	    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
		<div class="page-header"><br>
			<h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left">
				{{ Lang::get('program_course.program_class_capacity') }}
				<button id="save_program_capacity" type="button" class="btn btn-sm btn-success pull-right" style="margin-right: 20px">Update Program Capacity</button>
			</h3>
		</div>
		<div class="col-md-12">
			<div class="form-group col-md-4">
				<label class="control-label col-md-3">Program</label>
				<div class="col-md-9">
					<select class="form-control" name="program_id" id="program_id">
						<option type="text" name="0" id="" value=""></option>
						@foreach($program_list as $program)
							<option class="form-control" id="program_id" value="{{ $program -> id }}">{{ $program -> program_name }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="form-group col-md-4">
				<label class="control-label col-md-3">Type of Class</label>
				<div class="col-md-9">
					<select class="form-control" name="course_capacity_id" id="course_capacity_id">
						<!-- <option type="text" name="0" id="" value=""></option> -->
						@foreach($course_capacity_list as $course_capacity)
							<option class="form-control" id="course_capacity_id" value="{{ $course_capacity -> id }}">{{ $course_capacity -> capacity_name }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="form-group col-md-2">
				<button class="btn btn-sm btn-primary" type="button" id="add">Add Capacity</button>
			</div>
		</div>

		<div class="col-md-12" id="container">
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		$("#program_id").change(function(){
			$("#container").empty();
			$.ajax({
	            url:'{{{ URL::to("program_class_capacity/dataJson") }}}',
	            type:'GET',
	            data: {
	                'program_id' : $(this).val(),
	                '_token' : $("input[name=_token]").val(),
	            },
	            dataType: "json",
	            async:false,
	            success: function (data) 
	            {             

					counter = $("#counter").val();
	            	$.map(data,function(item){
	            		counter++;
	            		$("#counter").val(counter);

	            		$("#container").append('<div class="col-md-12 form-group" id="cc_'+counter+'">'
							+'<div class="col-md-4">'
								+'<label class="control-label">'+item.capacity_name+'</label>'
							+'</div>'
							+'<div class="col-md-2">'
								+'<button type="button" class="btn btn-sm btn-danger remove_data" data-db="'+item.id+'" data-id="'+counter+'">X</button>'
							+'</div>'
						+'</div>');
	            	});

					$(".remove_data").click(function(){
						remove_counter = $(this).data('id');
						id = $(this).data('db');
						$("#cc_"+remove_counter).remove();

						$.ajax({
		                    url:'{{{ URL::to("employee/deleteRow") }}}',
		                    type:'POST',
		                    data: {
		                        'id' : id,
		                        'table' : "program_class_capacity",
		                        '_token' : $("input[name=_token]").val(),
		                    },
		                    dataType: "json",
		                    async:false,
		                    success: function (data) 
		                    {            
		                        swal("Deleted!", "Your data has been deleted.", "success");  
		                    }  
		                });
					});
	            }  
	        });
		});

		$("#add").click(function(){

			program_id = $("#program_id").val();
			if(program_id != 0)
			{
					counter = $("#counter").val();
					counter++;
					$("#counter").val(counter);
					id = $("#course_capacity_id").val();
					text = $("#course_capacity_id option:selected").text();

					$("#container").append('<div class="col-md-12 form-group" id="cc_'+counter+'">'
						+'<div class="col-md-4">'
							+'<label class="control-label course_capacity" data-id="'+id+'" data-program_id="'+program_id+'">'+text+'</label>'
						+'</div>'
						+'<div class="col-md-2">'
							+'<button type="button" class="btn btn-sm btn-danger remove" data-id="'+counter+'">X</button>'
						+'</div>'
					+'</div>');

					$(".remove").click(function(){
						remove_counter = $(this).data('id');
						$("#cc_"+remove_counter).remove();
					});
			}

		});
		$("#save_program_capacity").click(function(){

				var program_capacity_arr = [];
		        var count = 0;
		        $(".course_capacity").each(function(){
		            id = $(this).data('id');
		            program_id = $(this).data('program_id');
		            program_capacity_arr[count] = [program_id,id];
		            count++;
		        });

		        $.ajax({
		            url:'{{{ URL::to("program_class_capacity/postDataJson") }}}',
		            type:'POST',
		            data: {
		                'program_capacity_arr' : program_capacity_arr,
		                '_token' : $("input[name=_token]").val(),
		            },
		            dataType: "json",
		            async:false,
		            success: function (data) 
		            {        
		                swal('Successfully Saved!');  
		                location.reload();     
		            }  
		        });
		});
	});
</script>
@endsection