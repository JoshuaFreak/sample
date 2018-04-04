@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("score_entry_lang.score_entry") }}} :: @parent
@stop
{{-- Content --}}
@section('content')
<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
</button>

<div class="navbar-default sidebar" role="navigation">
	<div class="sidebar-nav navbar-collapse">    
	    <ul class="nav" id="side-menu"> 
	      @include('registrar_sidebar')
	    </ul>
	</div>
</div>

<div id="page-wrapper">
	<div class="row">
		<div class="page-header">
          <br/>
      		<h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left">
      			{{{ Lang::get("score_entry_lang.score_entry") }}}

      		</h3>
      	</div>
      	<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
      	@include('score_entry.form')
      	<div id="action_button" class="col-md-12 hidden">
			<div class="form-group">
				<label class="col-md-9 control-label" for="actions">&nbsp;</label>
					<div class="col-md-3">	
			 			<button type="button" id="save_create" class="btn btn-sm btn-success">
			                <span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.save_create") }}
			            </button>	
			            
			 			<a href="{{{ URL::to('scoreEntry') }}}" class="btn btn-sm btn-warning close_popup">
			                <span class="glyphicon glyphicon-ban-circle"></span>  {{ Lang::get("form.cancel") }}
			       		</a>
			        </div>
		        <br/>
		    </div>

		</div>
		<div class="clearfix"></div>

    </div>
</div>
@stop
{{-- Scripts --}}
@section('scripts')
<script type="text/javascript">
			/********
                START OF student_name ->  typeahead
            *************************************************************************/
                var student_list = new Bloodhound({
                        datumTokenizer: function (datum) {
                            //return Bloodhound.tokenizers.whitespace(datum.student_name);
                               var tokens = [];
                                //the available string is 'name' in your datum
                                var stringSize = datum.student_name.length;
                                //multiple combinations for every available size
                                //(eg. dog = d, o, g, do, og, dog)
                                for (var size = 1; size <= stringSize; size++){
                                  for (var i = 0; i+size<= stringSize; i++){
                                      tokens.push(datum.student_name.substr(i, size));
                                  }
                                }

                                return tokens;


                        },
                        queryTokenizer: Bloodhound.tokenizers.whitespace,
                        limit: 10,

                        remote:{
                          url:'{{ URL::to("scoreEntry/dataJson?query=%QUERY") }}',
                          filter: function (student_list) {

                                    return $.map(student_list, function (student) {
                                        console.log(student); //debugging
                                        return {
                                            student_name: student.last_name + ', '+student.first_name + ' ' + student.middle_name + ' -( ' + student.student_english_name+')',
                                            id: student.id,
                                            examination_id: student.examination_id
                                        };
                                    });
                             }
                        }

                });

                student_list.initialize();
                console.log(student_list);

                  $('#student_name').typeahead(
                    {
                      hint: true,
                      highlight: true,
                      minLength: 1
                    },
                    {
                        student_name: 'student_list',
                        displayKey: 'student_name',
                        source: student_list.ttAdapter()


                    }

                    ).bind("typeahead:selected", function(obj, student, account_no) {
                        // console.log(student);

                       $("#student_id").val(student.id);
                       $("#student_name").val(student.student_name);
                       $("#examination_id [value='"+student.examination_id+"']").attr("selected","selected");
                       // selectListChange('examination_id','{{{URL::to("examination/dataJson")}}}',  { 'is_active':1 , 'id': $("#examination_id").val() } ,'Please search student') 
		    
		                $.ajax({

		                        url:'{{{ URL::to("examination/dataJson") }}}',
		                        type:'GET',
		                        data:
		                            {
		                                'student_id': $("#student_id").val(),
		                            },
		                        dataType: "json",
		                        async:false,

		                        success: function (data)
		                        {
		                        	$("#examination_select_id").empty();
		                        	var count = 1;
		                        	$("#examination_select_id").append(''
					                		+'<option name="examination_id" value="0"></option>'
										+'');
		                            $.map(data, function (item) 
					                {
					                
					                	$("#examination_select_id").append(''
					                		+'<option name="examination_id" value="'+item.value+'">'+item.text+'</option>'
										+'');
					                });
		                        }

		                });

		                $('#examination_select_id').change(function(){
		                	$('#action_button').removeClass('hidden');
		                	$('#monthly_result_card_btn').removeClass('hidden');
		                	$("#score_entry_container").empty();

			                	$.ajax({
			                		url:'{{{ URL::to("scoreEntry/studentScore")}}}',
			                		type:'GET',
			                		data:
			                			{
			                				'student_id': $("#student_id").val(),
			                			},
			                		dataType: "json",
		                            async:false,

		                            success: function (data)
		                            {
		                                if(data != "")
		                                {
			                                var count = 1;
			                                

			                                
			                                $("#score_entry_container").empty();

			                                $.map(data, function(value) {
			                                
			                                		// alert(value.score);
			                                	
				                                		var score_entry_count = $("#score_entry_count").val();
												    	score_entry_count = parseInt(score_entry_count) + 1;
														$("#score_entry_count").val(score_entry_count);

						                                $('#score_entry_container').append('<div class="counter"><input type="hidden" class="tr_count" id="tr_count" value="'+score_entry_count+'">'
						                                	+'<div class="form-group col-md-12">'
						                                		+'<div class="form-group col-md-12" id="student_examination_'+value.student_examination_course_id+'">'

						                                			+'<div class="form-group col-md-3">'
						                                              +'<input type="hidden" id="'+value.examination_id+'" name="examination_id" value="'+value.examination_id+'"/>'
						                                              +'<input type="hidden" id="course_id_'+value.course_id+'" name="course_id" value="'+value.course_id+'"/>'
						                                              +'<input type="hidden" id="examination_course_id_'+value.examination_course_id+'" name="examination_course_id" value="'+value.examination_course_id+'"/>'
						                                              +'<label class="control-label">'+value.course_name+'</label>'
						                                      		+'</div>'

						                                      		+'<div class="form-group col-md-3">'
						                                              
						                                              +'<label class="control-label">'+value.perfect_score+'</label>'
						                                              +'<input type="hidden" class="form-control" readOnly id="perfect_score_'+score_entry_count+'" name="perfect_score" value="'+value.perfect_score+'"/>'
						                                      		+'</div>'
						                                      		+'<div class="form-group col-md-3">'
						                                              
						                                              +'<input type="text" class="form-control score_entry" data-count="'+score_entry_count+'" name="score_entry" value="'+value.score+'"/>'
						                                      		+'</div>'
						                                      		+'<div class="form-group col-md-3">'
						                                              
						                                              +'<input type="text" class="form-control" readOnly id="rating_'+score_entry_count+'" name="rating" value="'+value.rating+'"/>'
						                                      		+'</div>'

																+'</div>'
															+'</div>'
															+'</div>');
			                                });//end map
		                            	}	//end if
		                            	   		else{
		                            	   			$.ajax({
							                		url:'{{{ URL::to("scoreEntry/studentNoScore")}}}',
							                		type:'GET',
							                		data:
							                			{
							                				'student_id': $("#student_id").val(),
							                			},
							                		dataType: "json",
						                            async:false,

							                            success: function (data)
							                            {
							                            	var count = 1;
			                                

			                                
							                                $("#score_entry_container").empty();

							                                $.map(data, function(value) {
					                                			var score_entry_count = $("#score_entry_count").val();
														    	score_entry_count = parseInt(score_entry_count) + 1;
																$("#score_entry_count").val(score_entry_count);

								                                $('#score_entry_container').append('<div class="counter"><input type="hidden" class="tr_count" id="tr_count" value="'+score_entry_count+'">'
								                                	+'<div class="form-group col-md-12">'
								                                		+'<div class="form-group col-md-12" id="student_examination_'+value.student_examination_course_id+'">'

								                                			+'<div class="form-group col-md-3">'
								                                              +'<input type="hidden" id="'+value.examination_id+'" name="examination_id" value="'+value.examination_id+'"/>'
								                                              +'<input type="hidden" id="course_id_'+value.course_id+'" name="course_id" value="'+value.course_id+'"/>'
								                                              +'<input type="hidden" id="examination_course_id_'+value.examination_course_id+'" name="examination_course_id" value="'+value.examination_course_id+'"/>'
								                                              +'<label class="control-label">'+value.course_name+'</label>'
								                                      		+'</div>'

								                                      		+'<div class="form-group col-md-3">'
								                                              
								                                              +'<label class="control-label">'+value.perfect_score+'</label>'
								                                              +'<input type="hidden" class="form-control" readOnly id="perfect_score_'+score_entry_count+'" name="perfect_score" value="'+value.perfect_score+'"/>'
								                                      		+'</div>'
								                                      		+'<div class="form-group col-md-3">'
								                                              
								                                              +'<input type="text" class="form-control score_entry" data-count="'+score_entry_count+'" name="score_entry" value="0"/>'
								                                      		+'</div>'
								                                      		+'<div class="form-group col-md-3">'
								                                              
								                                              +'<input type="text" class="form-control" readOnly id="rating_'+score_entry_count+'" name="rating" value="0"/>'
								                                      		+'</div>'

																		+'</div>'
																	+'</div>'
																	+'</div>');
								                            	});//end map
							                            }//end success
						                        	});//end ajax
						                        }//end else

		                            	
			                                    	var score_entry = 0;
				                                $('.score_entry').keyup(function(){
				                                	count = $(this).data('count');
				                                	perfect_score = parseInt($('#perfect_score_'+count).val());
				                                	score = parseInt($(this).val())	;
				                                	
				                                	if(perfect_score < score)
				                                	{
				                                		alert('The Score must not exceed the Perfect Score');
				                                		$(this).val('');

				                                	}
				                                	score_entry = (parseInt($(this).val()) / parseInt($('#perfect_score_'+count).val())) * 100
				                                	$('#rating_'+count).val(score_entry);
				                                });
		                            }//end success

			                	});//end ajax

		                });//end.change
		                
                    });//endbind

	$('#save_create').click(function(){

		$("#score_entry_container > div").each(function(){
	        	var score_entry = $(this).find('input[name="score_entry"]').val();
	        	var rating = $(this).find('input[name="rating"]').val();

	        	var examination_course_id = $(this).find('input[name="examination_course_id"]').val();

				var student_id = $("#student_id").val();

				
					
						$.ajax(
		                {

			                    url:'{{{URL::to("scoreEntry/create")}}}',
			                    type:'post',
			                    data: 
			                        { 
			                            'student_id': $("#student_id").val(),
			                            'score_entry': score_entry,
			                            'rating': rating,
			                            'examination_course_id': examination_course_id,
			                            '_token': $("input[name=_token]").val(),
			                            
			                      		                              
			                        },
			                    async:false
		                });
					

			});
				swal("Good job!", "You clicked the button!", "success");
		
					       location.reload();
				

	});

	function callReport(reportId)
	{
			var url = $("#"+reportId).data('url');
			var examination_id = $("#examination_select_id").val();
			var student_id = $("#student_id").val();
			url = url +"?examination_id="+examination_id+"&student_id="+student_id;
			window.open(url);
	}
		


</script>
@stop