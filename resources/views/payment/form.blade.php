<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
<input type="hidden" id="pdc_count" value="1" />
<input type="hidden" name="misc_count" id="misc_count" value="0" />
<input type="hidden" name="tuition_count" id="tuition_count" value="0" />
<input type="hidden" name="payment_id" id="payment_id" value="" />
	<div class="form-group {{{ $errors->has('created_at') ? 'has-error' : '' }}}">
		<label class="col-md-2 control-label" for="created_at">{!! Lang::get('student.date') !!}</label>
		<div class="col-md-2">
			<input class="form-control" type="date" name="transaction_date" id="transaction_date" value="{{date('Y-m-d')}}"/>
			{!! $errors->first('created_at', '<label class="control-label" for="created_at">:message</label>')!!}

		</div>
	</div>
    <div class="form-group {{{ $errors->has('student_no') ? 'has-error' : '' }}}">
        <label class="col-md-2 control-label" for="student_no">Search Student</label>
		<div class="col-md-5">
            <input type="text" class="typeahead form-control" id="student_list_typeahead" name="student_list_typeahead" value="">
            {!! $errors->first('student_no', '<label class="control-label" for="student_no">:message</label>')!!}
            <input type="hidden" id="student_id" name="student_id" value="">
            <input type="hidden" id="term_id" name="term_id" value="">
            <input type="hidden" id="classification_id" name="classification_id" value="">
        </div>
		<!-- <div class="col-md-4">
            <input type="text" class="typeahead form-control" id="student_name" name="student_name" value="">
            {!! $errors->first('student_name', '<label class="control-label" for="student_name">:message</label>')!!}
        </div> -->
    </div>
<!-- 	<div class="form-group {{{ $errors->has('payment_scheme_id') ? 'has-error' : '' }}}">
		<label class="col-md-2 control-label" for="payment_scheme_id">{!! Lang::get('payment.payment_scheme') !!}</label>
		<div class="col-md-5">
			<label class="col-md-2 control-label" id="payment_scheme_label"></label>
			<div class="col-md-12"><hr/></div>
			<div class="col-md-12" id="payment_scheme_enrollment_container">
			</div>
			<div class="col-md-12" id="payment_scheme_container">
			</div>
			<div class="col-md-12"><hr/></div> -->
						<!-- <select class="form-control" name="payment_scheme_id" id="payment_scheme_id">
							@if($action == 1)
								@foreach($payment_scheme_list as $payment_scheme)					
									@if($payment_scheme->id == $student_ledger->id) -->
								<!--    ^Column to dropdown   ^Table Name     -->
								<!-- 	<option name="payment_scheme_id" value="{{{ $payment_scheme->id }}}" selected>{{{ $payment_scheme->payment_scheme_name }}}</option>
									@else
									<option name="payment_scheme_id" value="{{{ $payment_scheme->id }}}">{{{ $payment_scheme->payment_scheme_name }}}</option>
									@endif
								@endforeach
							@else
								<option name="" value="" selected></option>
								@foreach($payment_scheme_list as $payment_scheme)
									<option name="payment_scheme_id" value="{{{ $payment_scheme->id }}}" >{{{ $payment_scheme->payment_scheme_name }}}</option>

								@endforeach
							@endif
						</select> -->
						<!-- {!! $errors->first('payment_scheme_id', '<label class="control-label" for="payment_scheme_id">:message</label>')!!} -->
		<!-- </div>
	</div> -->

	<div class="form-group {{{ $errors->has('payment_mode_id') ? 'has-error' : '' }}}">
		<label class="col-md-2 control-label" for="payment_mode_id">{!! Lang::get('payment.payment_mode_id') !!}</label>
		<div class="col-md-2">
			<select class="form-control" name="payment_mode_id" id="payment_mode_id">
				@if($action == 1)
					@foreach($payment_mode_list as $payment_mode)					
						@if($payment_mode->id == $student_ledger->id)
					<!--    ^Column to dropdown   ^Table Name     -->
						<option name="payment_mode_id" value="{{{ $payment_mode->id }}}" selected>{{{ $payment_mode->payment_mode_name }}}</option>
						@else
						<option name="payment_mode_id" value="{{{ $payment_mode->id }}}">{{{ $payment_mode->payment_mode_name }}}</option>
						@endif
					@endforeach
				@else
					<option name="" value="" selected></option>
					@foreach($payment_mode_list as $payment_mode)
						<option name="payment_mode_id" value="{{{ $payment_mode->id }}}" >{{{ $payment_mode->payment_mode_name }}}</option>

					@endforeach
				@endif
			</select>
			{!! $errors->first('payment_mode_id', '<label class="control-label" for="payment_mode_id">:message</label>')!!}
		</div>
		<div class="col-md-3">
		</div>
		<div class="col-md-3">
			<button id="button_add_pdc" type="button" class="btn btn-primary hidden">Add Check Details
			</button>
		</div>
	</div>
	<div id="pdc_label" class="form-group hidden">
		<table id="receipt_table" class="table table-bordered">
            	<th>Bank Name & Branch</th>
            	<th>Campus</th>
            	<th>Check No.</th>
            	<th>Date</th>
            	<th>Amount</th>
            	<tbody id="pdc_container">
            	</tbody>
		</table>
	</div>

	
<!-- <div class="form-group {{{ $errors->has('term_id') ? 'has-error' : '' }}}">
		<label class="col-md-2 control-label" for="term_id">{!! Lang::get('payment.term_id') !!}</label>
		<div class="col-md-4">
			<select class="form-control" name="term_id" id="term_id">
				@if($action == 1)
					@foreach($term_list as $term)					
						@if($term->id == $student_ledger->id)
						<option name="term_id" value="{{{ $term->id }}}" selected>{{{ $term->term_name }}}</option>
						@else
						<option name="term_id" value="{{{ $term->id }}}">{{{ $term->term_name }}}</option>
						@endif
					@endforeach
				@else
					<option name="" value="" selected></option>
					@foreach($term_list as $term)
						<option name="term_id" value="{{{ $term->id }}}" >{{{ $term->term_name }}}</option>

					@endforeach
				@endif
			</select>
			{!! $errors->first('term_id', '<label class="control-label" for="term_id">:message</label>')!!}
		</div>
	</div> -->
	<div class="form-group {{{ $errors->has('payment_type_id') ? 'has-error' : '' }}}">
		<label class="col-md-2 control-label" for="payment_type_id">{!! Lang::get('payment.payment_type_id') !!}</label>
		<div class="col-md-5">
			<button id="add_tuition_row" type="button" class="btn btn-sm btn-primary">
	                <span class="glyphicon glyphicon-plus-sign"></span> School Fees
	        </button>		        
	     <!--    <button id="add_misc_row" type="button" class="btn btn-sm btn-primary">
	                <span class="glyphicon glyphicon-plus-sign"></span> Misc
	        </button>	  -->       
	        <button id="add_other_row" type="button" class="btn btn-sm btn-primary">
	                <span class="glyphicon glyphicon-plus-sign"></span> Other
	        </button>
		</div>
	</div>
	<div>
		<table id="receipt_table" class="table table-bordered">
            	<th>Payment Type</th>
            	<th>Amount</th>
            	<!-- <th>Amount Paid</th> -->
            	<th>Action</th>
            	<tbody id="container">
            	</tbody>
		</table>
	</div>
	<!-- ============================== Start of code 2016-09-06 -->
	<!-- <div class="form-group {{{ $errors->has('amount_paid') ? 'has-error' : '' }}}">
		<label class="col-md-2 control-label" for="amount_paid">{!! Lang::get('payment.amount_paid') !!}</label>
		<div class="col-md-5">
			<label class="control-label" type="text" name="amount_paid_label" id="amount_paid_label">0</label>
			<input type="hidden" class="form-control" name="amount_paid" id="amount_paid" value="0" />
			{!! $errors->first('amount_paid', '<label class="control-label" for="amount_paid">:message</label>')!!}

		</div>
	</div> -->
	<!-- ============================== End of code 2016-09-06 -->
	<div class="form-group {{{ $errors->has('discount') ? 'has-error' : '' }}}">
		<label class="col-md-2 control-label" for="discount">{!! Lang::get('payment.discount') !!}</label>
		<div class="col-md-2">
			<input type="text" class="form-control" name="discount" id="discount"></input> 
		</div>
		<div class="col-md-3">
			<!-- <input type="text" class="form-control" placeholder="Discount Remark" name="discount_remark" id="discount_remark"></input> -->
			<select class="form-control" id="discount_remark" name="discount_remark">
				<option disabled selected>Discount Remark</option>
				@foreach($discount_list as $discount)
					<option value="{{ $discount -> id }}">{{ $discount -> discount_name }}</option>
				@endforeach
			</select> 
		</div>
	</div>
	
	<div class="form-group col-md-8 {{{ $errors->has('ar_no') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label" for="ar_no">{!! Lang::get('payment.ar_no') !!}</label>
		<div class="col-md-3">
			<input class="form-control" type="text" name="ar_no" id="ar_no" value="" />
			{!! $errors->first('ar_no', '<label class="control-label" for="ar_no">:message</label>')!!}
		</div>
		<label class="col-md-2 control-label" for="or_no">{!! Lang::get('payment.or_no') !!}</label>
		<div class="col-md-3">
			<input class="form-control" type="text" name="or_no" id="or_no" value="" />
			{!! $errors->first('or_no', '<label class="control-label" for="or_no">:message</label>')!!}
		</div>
	</div>
	<div class="col-md-12">
		
	</div>
	<div class="form-group {{{ $errors->has('total_student_balance') ? 'has-error' : '' }}}">
		<label class="col-md-2 control-label" for="total_student_balance">{!! Lang::get('payment.balance') !!}</label>
		<div class="col-md-5">
			<label class="col-md-2 control-label" id="total_student_balance"></label>
			<input type="hidden" class="form-control" name="total_student_balance_value" id="total_student_balance_value" value="0" />
			{!! $errors->first('total_student_balance', '<label class="control-label" for="total_student_balance">:message</label>')!!}

		</div>
	</div>

	<div class="form-group {{{ $errors->has('amount_paid') ? 'has-error' : '' }}}">
		<label class="col-md-2 control-label" for="amount_paid">{!! Lang::get('payment.amount_paid') !!}</label>
		<div class="col-md-5">
			<input type="text" class="form-control" name="amount_paid" id="amount_paid" value="" />
			{!! $errors->first('amount_paid', '<label class="control-label" for="amount_paid">:message</label>')!!}

		</div>
	</div>

	<div class="form-group {{{ $errors->has('cash_tendered') ? 'has-error' : '' }}}">
		<label class="col-md-2 control-label" for="cash_tendered">{!! Lang::get('payment.cash_tendered') !!}</label>
		<div class="col-md-5">
			<input class="form-control" type="text" name="cash_tendered" id="cash_tendered" value="" />
			{!! $errors->first('cash_tendered', '<label class="control-label" for="cash_tendered">:message</label>')!!}

		</div>
	</div>
	<!-- input to store label values -->
	<div class="form-group {{{ $errors->has('change') ? 'has-error' : '' }}}">
		<label class="col-md-2 control-label" for="change">{!! Lang::get('payment.change') !!}</label>
		<div class="col-md-5">
			<label class="control-label" type="text" name="change_label" id="change_label">0</label>
			<input type="hidden" class="form-control" name="change" id="change" value="0" />
			{!! $errors->first('change', '<label class="control-label" for="change">:message</label>')!!}

		</div>
	</div>



@section('scripts')

<script id="pdc_template" type="application/html-template">
	<tr>
		<td><input id="<<bank_id>>" name="bank_name" type="text" class="form-control"></input></td>
		<td>
			<select id="<<branch_id>>" name="school_branch" type="text" class="form-control">
				<option></option>
			</select>
		</td>
		<td><input id="<<acoount_id>>" name="account_no" type="text" class="form-control"></input></td>
		<td><input id="<<date_id>>" name="check_date" type="date" class="form-control"></input></td>
		<td><input id="<<amount_id>>" name="check_amount" type="text" class="form-control"></input></td>
	</tr>
</script>
<script type="text/javascript">

	$(function(){
		$("#button_add_pdc").click(function(){
			count = $("#pdc_count").val();
			count++;
			generatePDC(count);
			$("#pdc_count").val(count);
		});

		$("#payment_mode_id").change(function(){
			$("#pdc_container").empty();
			
			var data = $(this).val();

			if(data != 1 && data != "")
			{
				$("#pdc_label").removeClass('hidden');
				$("#pdc_label").addClass('show');
				$("#button_add_pdc").removeClass('hidden');
				$("#button_add_pdc").addClass('show');

				generatePDC(1);
			}
			else
			{
				$("#pdc_label").removeClass('show');
				$("#pdc_label").addClass('hidden');
				$("#button_add_pdc").removeClass('show');
				$("#button_add_pdc").addClass('hidden');
			}
			
			// $("#pdc_container").append(
			// 	+'<tr>'
			// 		+'<td><input class="form-control"></input></td>'
			// 		+'<td><input class="form-control"></input></td>'
			// 		+'<td><input class="form-control"></input></td>'
			// 		+'<td><input class="form-control"></input></td>'
			// 		+'<td><input class="form-control"></input></td>'
			// 	+'</tr>'
			// 	+'');

		});
	});

	function generatePDC(count){

			var template = $("#pdc_template").clone().html();
			var html = template
					.replace('<<bank_id>>','bank_name_'+count)
					.replace('<<branch_id>>','school_branch_'+count)
					.replace('<<acoount_id>>','acount_no_'+count)
					.replace('<<date_id>>','check_date_'+count)
					.replace('<<amount_id>>','check_amount_'+count);

			$("#pdc_container").append(html);

			$.ajax({
			        url:'{{{ URL::to("campus/dataJson") }}}', 
			        type: "GET", 
			        dataType: "json",
			        async:false,
			        success: function (data) 
			        {		
			                $.map(data, function (item) 
			                {
			                	$("#school_branch_"+count).append(''
			                		+'<option name="campus_id" value="'+item.value+'">'+item.text+'</option>'
								+'');
			                });
			         }
			});

			// $("#acount_no_"+count).append(''
			// 	+'');
	}

	$('#cash_tendered').keyup(function(){
		// var tuition_val = 0;
		// $('input.tuition_val').each( function() {
		// 	tuition_val = parseFloat(tuition_val) + parseFloat($(this).val());
		// });

		// var misc_val = 0;
		// $("[name='misc_val']").each( function() {
		// 	misc_val = parseFloat(misc_val) + parseFloat($(this).val());
		// });

		// var payment_val = 0;
		// $("[name='payment_val']").each( function() {
		// 	payment_val = parseFloat(payment_val) + parseFloat($(this).val());
		// });

		// $('#amount_paid').val(tuition_val + misc_val + payment_val);
		// var amount_paid = $('#amount_paid').val();
		// $('#amount_paid_label').text(amount_paid);
		var amount_paid = $('#amount_paid').val();

		var cash_tendered = $('#cash_tendered').val();
		change = parseFloat(cash_tendered) - parseFloat(amount_paid);
		$('#change_label').text(change.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"));
		$('#change').val(change);

	});

	$('#amount_paid').keyup(function(){
		var balance = $("#total_student_balance_value").val();
		var amount = $(this).val();

		amount = parseFloat(balance) - parseFloat(amount);

		$("#total_student_balance").text(amount.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"));
		$('#cash_tendered').val('');

	});

	function keyUp(){

		$('#tuition_val').keyup(function(){
			// var tuition_val = $('#tuition_val').val();
			// var misc_val = $('#misc_val').val();
			// var payment_val = $('#payment_val').val();
			// var amount_paid = parseFloat(tuition_val) + parseFloat(misc_val) + parseFloat(payment_val);
			// $('#amount_paid').text(amount_paid);
			// $('#amount_paid').val(amount_paid);
			$("#amount_paid_label").text($(this).val());
		});

	}




    /********
        RMD 2015-03-07
        START OF student_no ->  typeahead
    *************************************************************************/
        var student_list = new Bloodhound({
                datumTokenizer: function (datum) {
                    return Bloodhound.tokenizers.whitespace(datum.student_no);
                },
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                limit: 10,
                remote: {
                    // url points to a json file that contains an array of country names, see
                    // https://github.com/twitter/typeahead.js/blob/gh-pages/data/countries.json
                    //url: '../../its/student/dataJson',
                    //url:'http://boc.itechrar.com/its/student/dataJson',
                    url:'../enroll_student/dataJson?query=%QUERY',
                    
                    // the json file contains an array of strings, but the Bloodhound
                    // suggestion engine expects JavaScript objects so this converts all of
                    // those strings
                     filter: function (student_list) {
                                 // alert('this is an alert script from create');
                            //console.log(student_list); //debugging
                            // Map the remote source JSON array to a JavaScript object array
                            return $.map(student_list, function (student) {
                                // console.log(student.student_no); //debugging

                                return {
                                    student_name_arr: student.student_no+' - '+student.last_name+', '+student.first_name+' '+student.middle_name+' (' +student.level+')',
                                    student_no: student.student_no,
                                    student_name: student.last_name + ', '+student.first_name + ' ' + student.middle_name,
                                    term_id: student.term_id,
                                    classification_id: student.classification_id,
                                    classification_level_id: student.classification_level_id,
                                    id: student.id,
                                    student_id: student.student_id,
                                    is_new: student.is_new,
                                    payment_scheme_id: student.payment_scheme_id,
                                    payment_scheme_name: student.payment_scheme_name,
                                    student_curriculum_id: student.student_curriculum_id,
                                };
                            });
                    }
                }
        });

        student_list.initialize();
         console.log(student_list);

        //its_customs_office_code_typeahead   -- this is the id of the div element that handles typeahead for export country name
       // $('#student_student_no_typeahead .typeahead').typeahead(null, {
          $('#student_list_typeahead').typeahead(null, {
          student_name_arr: 'student_list',
          displayKey: 'student_name_arr',
          source: student_list.ttAdapter()
        }).bind("typeahead:selected", function(obj, student, student_name_arr) {
                console.log(student);
               $("#student_id").val(student.student_id);
               $("#student_name").val(student.student_name);
               $("#term_id").val(student.term_id);
               $("#classification_id").val(student.classification_id);
               $("#payment_scheme_label").text(student.payment_scheme_name);
               $("#container").empty();

               $("#total_student_balance").text(0);
			   $("#total_student_balance_value").val(0);

			   // $("#payment_scheme_container").empty();
      //   	   $("#payment_scheme_enrollment_container").empty();
				if(student.payment_scheme_name == "B")
				{ 
						enrollment_amount = 0;
						$.ajax({
					        url:'{{{ URL::to("classification_level_fee/dataJson")}}}',
					        data: {
					        	'classification_level_id': student.classification_level_id,
					        	'student_curriculum_id': student.student_curriculum_id,
					        	'payment_scheme_id': student.payment_scheme_id,
					        },
					        type: "GET", 
					        dataType: "json",
					        async:false,
					        success: function (data) 
					        {		
					                enrollment_amount = data;
					        }
					       
					    }); 

		    //            	$.ajax({
					 //        url:'{{{ URL::to("payment_installment/schemeDataJson") }}}',
					 //        data: 
					 //       		{
					 //       			'classification_level_id': student.classification_level_id,
					 //       			'term_id': student.term_id,
					 //       			'payment_scheme_id': student.payment_scheme_id,
					 //       		}, 
					 //        type: "GET", 
					 //        dataType: "json",
					 //        async:false,
					 //        success: function (data) 
					 //        {		
					 //        		$("#payment_scheme_container").empty();
					 //        		$("#payment_scheme_enrollment_container").empty();
					 //        		var count = 0;
					 //        		var total_amount = 0;
					 //        		var count = 2;
					 //                $.map(data, function (item) 
					 //                {
					 //                	var monthNames = [
						// 				  "January", "February", "March",
						// 				  "April", "May", "June", "July",
						// 				  "August", "September", "October",
						// 				  "November", "December"
						// 				];

						// 				var date = new Date(item.date);
						// 				var day = date.getDate();
						// 				var month = date.getMonth();
						// 				var year = date.getFullYear();

						// 				date = monthNames[month]+' '+day+', '+year;

						// 				amount = item.text;

					 //                	$("#payment_scheme_container").append(''
					 //                		+'<div class="col-md-6">'
					 //                			+'<label class="control-label">'+date+'</label>'
					 //                		+'</div>'
					 //                		+'<div class="col-md-6">'
					 //                			+'<label class="control-label" id="amount_'+count+'">'+amount.replace(/(\d)(?=(\d{3})+\.)/g, "$1,")+'</label>'
					 //                		+'</div>'
					 //                		+'');

					 //                	count++;
					 //                	// $("#balance").val(item.text);
					 //                	// alert(item.text);
					 //                	total_amount = parseFloat(total_amount) + parseFloat(item.text);
					 //                });

					 //                total_amount = parseFloat(enrollment_amount) - parseFloat(total_amount);
					 //                $("#payment_scheme_enrollment_container").append(''
					 //                		+'<div class="col-md-6">'
					 //                			+'<label class="control-label">Enrollment</label>'
					 //                		+'</div>'
					 //                		+'<div class="col-md-6">'
					 //                			+'<label class="control-label" id="amount_1">'+total_amount.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")+'</label>'
					 //                		+'</div>'
					 //                		+'');
					 //         }
						// });

						$.ajax({
					        url:'{{{ URL::to("student_ledger/debitDataJson") }}}',
					        data: 
					       		{
					       			'student_id': student.student_id,
					       			'term_id': student.term_id,
					       		}, 
					        type: "GET", 
					        dataType: "json",
					        async:false,
					        success: function (data) 
					        {	
					        	var debit = data;

					        	for (var i = 1; i <= 5; i++) {

					        		amount = $("#amount_"+i).text();

					        		amount = amount.replace(',','');
					        		
					        		if(debit > amount)
					        		{
					        			debit = parseFloat(debit) - parseFloat(amount);
					        			amount = 0;
					        			$("#amount_"+i).text(amount.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"));
					        		}
					        		else
					        		{
					        			amount = parseFloat(amount) - parseFloat(debit);
					        			
					        			$("#amount_"+i).text(amount.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"));
					        			break;
					        		}
					        	}


					        }
					    });
				}

				$.ajax({
			        url:'{{{ URL::to("student_ledger/totalBalanceDataJson") }}}',
			        data: 
			       		{
			       			'student_id': $("#student_id").val(),
			       			'term_id': $("#term_id").val(),
			       		}, 
			        type: "GET", 
			        dataType: "json",
			        async:false,
			        success: function (data) 
			        {	
						var amount = data;

						$("#total_student_balance").text(amount.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"));
						$("#total_student_balance_value").val(amount);

						if(amount <= 0)
						{
							alert("No Balance")
						}
			        }
			       
			    }); 
               // selectListChange('term_id','{{{URL::to("term/dataJson")}}}',  { 'is_active':1 , 'classification_id': $("#classification_id").val() } ,'Please select a Student No')


            });



    $("#add_tuition_row").click(function(){

   //  		$.ajax({
			//         url:'{{{ URL::to("student_ledger/balanceDataJson") }}}',
			//         data: 
			//        		{
			//        			'student_id': $("#student_id").val(),
			//        			'term_id': $("#term_id").val(),
			//        		}, 
			//         type: "GET", 
			//         dataType: "json",
			//         async:false,
			//         success: function (data) 
			//         {		
			//         		$("#container").empty();
			//         		var count = 0;
			//                 $.map(data, function (item) 
			//                 {
			//                 	// $("#balance").val(item.text);
			//                 	// alert(item.text);
			//                 });
			//          }
			// });



	    	$.ajax({
		        url:'{{{ URL::to("student_ledger/dataJson") }}}',
		        data: 
		       		{
		       			'student_id': $("#student_id").val(),
		       			'term_id': $("#term_id").val(),
		       		}, 
		        type: "GET", 
		        dataType: "json",
		        async:false,
		        success: function (data) 
		        {		

		        		$("#container").empty();
		        		var tuition_count = $("#tuition_count").val();
		        		var count = 0;
		        		var value_count = 0;

		                $.map(data[0], function (item) 
		                {   
		                	count++; 
		                	value_count++;
		                	tuition_count = parseInt(tuition_count)+1;
		                	tr_count = tuition_count;
		                	// -------------------- start of code 2016-09-06
		                	// if(item.balance >0) 
		                	// {
		                		// count++;
		                		// value_count++;
						    	// tuition_count = parseInt(tuition_count)+1;
		                		// var tuition_count = $("#tuition_count").val();
								// $("#tuition_count").val(tuition_count);
								// // alert(tuition_count);

								// var misc_count = $("#misc_count").val();
								// var tr_count = parseInt(tuition_count) + parseInt(misc_count);

		                        // $("#container").append('<tr class="counter"><input type="hidden" class="tr_count" id="tr_count" value="'+tr_count+'"/><td>'
		                        	// +'<label class="control-label" id="student_text_'+count+'"/>'+"Tuition"+'<input type="hidden" name="student_text" value="'+item.desc+'"/></td>'											
									// +'<td><label class="control-label" id="balance_text_'+count+'" />'
									// +'<input id="balance_'+count+'" class="balance form-control" type="hidden" name="balance_'+count+'" value="'+item.balance+'" /></td>'
									// +'<td><input id="tuition_val" class="tuition_val form-control" type="text" name="tuition_val" value="0" /></td>'
									// +'</tr>');

								// 	$("#balance_text_"+count).text(item.balance);

								// $("#tuition_val").keyup(function(){
								// 	$("#cash_tendered").val("");
								// 	$("#change").val(0);
								// 	$("#change_label").text("0");
								// });

							// }
							// -------------------- end of code 2016-09-06

							$("#container").append('<tr class="counter" id="row_'+item.id+'"><input type="hidden" class="tr_count" id="tr_count" value="'+tr_count+'"/><td>'
		                        	+'<label class="control-label" id="student_text_'+count+'">'+item.fee_type_name+'</label><input type="hidden" name="student_text" value="'+item.desc+'"/></td>'											
									// +'<td><input id="balance_'+count+'" class="balance form-control" type="text" name="balance_'+count+'" value="'+item.amount+'" /></td>'
									+'<td><label id="balance_'+count+'" class="balance control-label" name="balance_'+count+'">'+item.amount+'</ label></td>'
									// +'<td></td>'
									+'<td><button type="button" class="btn btn-default remove_fee_type" data-fee_type_id="'+item.id+'">X</button></td>'
									+'</tr>');	

		                });	

						$.map(data[3], function (item) 
		                {   
		                	count++; 
		                	value_count++;
		                	tuition_count = parseInt(tuition_count)+1;
		                	tr_count = tuition_count;

							$("#container").append('<tr class="counter" id="row_"><input type="hidden" class="tr_count" id="tr_count" value="'+tr_count+'"/><td>'
		                        	+'<label class="control-label" id="student_text_'+count+'">'+item.remark+'</label><input type="hidden" name="student_text" value="'+item.desc+'"/></td>'											
									// +'<td><input id="balance_'+count+'" class="balance form-control" type="text" name="balance_'+count+'" value="'+item.amount+'" /></td>'
									+'<td><label id="balance_'+count+'" class="balance control-label" name="balance_'+count+'">'+item.debit+'</ label></td>'
									// +'<td></td>'
									+'<td><button type="button" class="btn btn-default remove_fee_type" data-fee_type_id="'+item.id+'">X</button></td>'
									+'</tr>');	

		                });	

						$.map(data[1], function (item) 
		                {
		                	$('#row_'+item.fee_type_id).remove();
						});	

						$("#tuition_count").val(tuition_count);

						

						$(".remove_fee_type").click(function(){
							var fee_type_id = $(this).data('fee_type_id');
							$('#row_'+fee_type_id).remove();

								$.ajax(
							        {

						                url:'{{{URL::to("payment/remove_fee_type")}}}',
						                type:'post',
						                data: 
						                    { 
						                        'student_id': $("#student_id").val(),
						                        'fee_type_id': fee_type_id,
						                        'term_id': $("#term_id").val(),
						                        '_token': $("input[name=_token]").val()
						                          
						                    },
						                async:false,
						                success: function (data) {

						                	var amount = $("#total_student_balance_value").val();
						                	amount = parseFloat(amount) - parseFloat(data);
						                	$("#total_student_balance").text(amount.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"));
						                	$("#total_student_balance_value").val(amount);
						                	swal('Succefully Remove!');
						                }
							        });
						});
		        }
		       
		    }); 
		keyUp();
    });



        $("#add_misc_row").click(function(){

		var misc_count = $("#misc_count").val();
    	misc_count = parseInt(misc_count)+1;
		$("#misc_count").val(misc_count);
		// alert(misc_count);
		var tuition_count = $("#tuition_count").val();
		var tr_count = parseInt(tuition_count) + parseInt(misc_count);

      	$("#container").append('<tr class="counter"><input type="hidden" class="tr_count" id="tr_count" value="'+tr_count+'"/><td><select id="misc_'+misc_count+'" name="miscellaneous" class="form-control">'
	      								+'<option type="text"></option>'
							            +'@foreach($miscellaneous_fee_detail_list as $miscellaneous_fee_detail)'
							            	+'<option type="text" name="miscellaneous_fee_detail_id_'+misc_count+'" value="{{{ $miscellaneous_fee_detail->id }}}">{{{ $miscellaneous_fee_detail->description }}}</option>'		            	
							            +'@endforeach'
					            	+'</select></td>'
									+'<td><label class="control-label" id="misc_text_'+misc_count+'" />'
									+'<input id="price_'+misc_count+'" class="form-control" type="hidden" name="price_'+misc_count+'" value="" /></td>'
									+'<td><input id="misc_val" class="form-control" type="text" name="misc_val" value="" /></td>'
									+'</tr>');
      	selectListChange('misc_'+misc_count,'{{{URL::to("miscellaneous_fee_detail/dataJsonList")}}}',  {'classification_id': $("#classification_id").val() } ,'Please select a Student No')
      	$("#misc_"+misc_count).change(function(){

			     $.ajax({
			        url:'{{{ URL::to("miscellaneous_fee_detail/dataJson")}}}',
			        data: {
			        	'id': $("#misc_"+misc_count).val(),
			        },
			        type: "GET", 
			        dataType: "json",
			        async:false,
			        success: function (data) 
			        {		

			        		$("#misc_text_"+misc_count).empty();
			        		$("#price_"+misc_count).val(" ");

			                $.map(data, function (item) 
			                {
								$("#price_"+misc_count).val(item.text);    
								$("#misc_text_"+misc_count).text(item.text); 
			                });
			        }
			       
			    }); 


			});

      	keyUp();
    });



        $("#add_other_row").click(function(){

		var payment_count = $("#payment_count").val();
    	payment_count = parseInt(payment_count)+1;
		$("#payment_count").val(payment_count);
		// alert(payment_count);
		var tuition_count = $("#tuition_count").val();
		tuition_count++;
		$("#tuition_count").val(tuition_count);
		var tr_count = parseInt(tuition_count) + parseInt(payment_count);

      	$("#container").append('<tr class="counter" id="row_'+tuition_count+'"><input type="hidden" class="tr_count" id="tr_count" value="'+tr_count+'"/><td><select id="misc_'+tuition_count+'" name="miscellaneous" class="form-control">'
	      								+'<option type="text"></option>'
							            +'@foreach($payment_type_list as $payment_type)'
							            	+'<option type="text" name="payment_type_id_'+tuition_count+'" value="{{{ $payment_type->id }}}">{{{ $payment_type->description }}}</option>'		            	
							            +'@endforeach'
					            	+'</select></td>'									
									// +'<td><label class="control-label" id="payment_text_'+tuition_count+'" />'
									+'<td><input id="price_'+tuition_count+'" class="form-control" type="text" name="price" id="price_'+tuition_count+'" value="" /></td>'
									// +'<td><input id="payment_val" class="form-control" type="text" name="payment_val" value="" /></td>'
									+'<td><button type="button" class="btn btn-default remove_other" data-other="'+tuition_count+'">X</button></td>'
									+'</tr>');

      	$(".remove_other").click(function(){
			var other = $(this).data('other');
			$('#row_'+other).remove();
		});

      	$("#misc_"+tuition_count).change(function(){

			     $.ajax({
			        url:'{{{ URL::to("payment_type/dataJson")}}}',
			        data: {
			        	'id': $("#misc_"+tuition_count).val(),
			        },
			        type: "GET", 
			        dataType: "json",
			        async:false,
			        success: function (data) 
			        {		

			        		$("#payment_text_"+tuition_count).empty();
			        		$("#price_"+tuition_count).val("");

			                $.map(data, function (item) 
			                {
								$("#price_"+tuition_count).val(item.text);    
								$("#payment_text_"+tuition_count).text(item.text);
			                });
			        }
			       
			    }); 
			});


    });

	
		$("#savePayment").click(function(){

			var amount_paid = $("#amount_paid").val();
			var discount = $("#discount").val();
			if(amount_paid != 0 && amount_paid != "")
			{
				$.ajax(
		        {

	                url:'{{{URL::to("payment/create")}}}',
	                type:'post',
	                data: 
	                    { 
	                        'student_id': $("#student_id").val(),
	                        'payment_mode_id': $("#payment_mode_id").val(),
	                        'transaction_date': $("#transaction_date").val(),
	                        'ar_no': $("#ar_no").val(),
	                        'or_no': $("#or_no").val(),
	         				'term_id': $("#term_id").val(),
	         				'amount_paid': $("#amount_paid").val(),
	                        'cash_tendered': $("#cash_tendered").val(),
	                        'change': $("#change").val(),
	                        '_token': $("input[name=_token]").val()
	                          
	                    },
	                async:false,
	                success: function (data) {
	                	$("#payment_id").val(data.id);
	                }


		        });
			}

			if(discount != 0 && discount != "")
			{
				$.ajax(
		        {

	                url:'{{{URL::to("payment/createDiscount")}}}',
	                type:'post',
	                data: 
	                    { 
	                        'student_id': $("#student_id").val(),
	                        'payment_mode_id': $("#payment_mode_id").val(),
	                        'transaction_date': $("#transaction_date").val(),
	                        'ar_no': $("#ar_no").val(),
	                        'or_no': $("#or_no").val(),
	         				'term_id': $("#term_id").val(),
	         				'discount': $("#discount").val(),
	         				'discount_remark': $("#discount_remark").val(),
	                        '_token': $("input[name=_token]").val()
	                          
	                    },
	                async:false,
	                success: function (data) {
	                	$("#payment_id").val(data.id);
	                }


		        });
			}
	        $("#pdc_container > tr").each(function(){
	        	var bank_name = $(this).find('input[name="bank_name"]').val();
	        	var check_date = $(this).find('input[name="check_date"]').val();
	        	var account_no = $(this).find('input[name="account_no"]').val();
	        	var check_amount = $(this).find('input[name="check_amount"]').val();
				var school_branch = $(this).find('select[name="school_branch"] option:selected').text();
				var school_branch_id = $(this).find('select[name="school_branch"] option:selected').val();
				var payment_id = $("#payment_id").val();
 				
 				if(bank_name != "" && account_no != "" && check_amount != "")
 				{
					$.ajax(
	                {

		                    url:'{{{URL::to("payment/check_detail/saveDataJson")}}}',
		                    type:'post',
		                    data: 
		                        { 
		                            'payment_id':payment_id,
		                            'bank_name': bank_name,
		                            'campus_id': school_branch_id,
		                            'account_no': account_no,
		                            'check_date': check_date,
		                            'check_amount': check_amount,
		                            '_token': $("input[name=_token]").val()
		                      		                              
		                        },
		                    async:false

	                });
	            }
	        });


			$("#container > tr").each(function(){
				var miscellaneous_remark = $(this).find('select[name="miscellaneous"] option:selected').text();
				var miscellaneous_remark_value = $(this).find('select[name="miscellaneous"]').val();
				// var tuition_val = $(this).find('input[name="tuition_val"]').val();
				// var payment_val = $(this).find('input[name="payment_val"]').val();    
				// var misc_val = $(this).find('input[name="misc_val"]').val();    
				var price = $(this).find('input[name="price"]').val();    

				if(miscellaneous_remark != "" && miscellaneous_remark_value != 0 && miscellaneous_remark_value != undefined)
				{	

					$.ajax(
	                {

		                    url:'{{{URL::to("payment/postOther")}}}',
		                    type:'post',
		                    data: 
		                        { 
		                            'student_id': $("#student_id").val(),
		                            'price': price,
		                            'remark': miscellaneous_remark,
		                            'term_id': $("#term_id").val(),
		                            '_token': $("input[name=_token]").val()
		                      		                              
		                        },
		                    async:false

	                })
				}
		//-------------------
				// if(discount_amount != "" && discount_amount != 0 && discount_amount != undefined)
				// {	

				// 	$.ajax(
	   //              {

		  //                   url:'{{{URL::to("payment/discount")}}}',
		  //                   type:'post',
		  //                   data: 
		  //                       { 
		  //                           'training_id': training_id,
		  //                           'trainee_id': $("#trainee_id").val(),
		  //                           'discount': discount_amount,
		  //                           'payment_id':$("#payment_id").val(),
		  //                           '_token': $("input[name=_token]").val()
		                      		                              
		  //                       },
		  //                   async:false
	   //              })
				// }

		///----------------		
				// if(tuition_val != "" && tuition_val > 0 && tuition_val != undefined)
				// {
				// 	$.ajax(
	   //              {

		  //                   url:'{{{URL::to("payment/tuition")}}}',
		  //                   type:'post',
		  //                   data: 
		  //                       { 
		  //                           'student_id': $("#student_id").val(),
		  //                           'tuition_val': tuition_val,
		  //                           'term_id': $("#term_id").val(),
		  //                           'payment_id':$("#payment_id").val(),
		  //                           '_token': $("input[name=_token]").val()
		                      		                              
		  //                       },
		  //                   async:false
	   //              })
				// }


			});

			alert("Payment Saved");
			location.reload();
	
		});


</script>
@stop