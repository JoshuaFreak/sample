@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("payment.payment_list") }}} :: @parent
@stop
{{-- Content --}}
@section('content')

<style>

td.details-control {
    background: url('{{ URL::to('assets/site/images/details_open.png') }}') no-repeat center center;
    cursor: pointer;
}
tr.shown td.details-control {
    background: url('{{ URL::to('assets/site/images/details_close.png') }}') no-repeat center center;
}
div.slider {
    display: none;
}
 
table.dataTable tbody td.no-padding {
    padding: 0;
}
</style>
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
                                @include('accounting_sidebar')
                                
                               
                		    </ul>
                	    </div>

        </div>
<div id="page-wrapper">
    <div class="row">
		<div class="page-header">
				<h2>
					{{{ Lang::get("payment.payment_list") }}}
				</h2>
			</div>

			<!-- date/time range filter -->
			<input id="date_from" type="hidden" value=""/>
			<input id="date_to" type="hidden" value=""/>
			<div class="col-md-6">
				<label class="control-label col-md-3" for="from">Date Range:</label>
				<div class="input-daterange input-group" id="datepicker">
					<input type="text" id="date_start" class="form-control" name="start" value="" />
				    <span class="input-group-addon">to</span>
				    <input type="text" id="date_end" class="form-control" name="end" value="" />				     
				</div>
			</div>
			<button class="btn btn-primary btn-sm" id="load" type="button">Preview</button>
			<br/><br/><br/>

			<table id="table" class="table table-striped table-hover">
				<thead>
					<tr>
						<th></th>
						<th>{{ Lang::get("payment.created_at") }}</th>
						<th> {{ Lang::get("payment.student_no") }}</th>
						<th> {{ Lang::get("payment.student_name") }}</th>
						<th> {{ Lang::get("payment.remark") }}</th>
						<th>{{ Lang::get("form.action") }}</th>
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

		/* Formatting function for row details - modify as you need */
			function format ( d ) {
			    // `d` is the original data object for the row
			    return '<div class="slider">'+
			    '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
			        '<tr>'+
			            '<td><b>Date/Time:</td>'+
			            '<td>'+d.created_at+'</td>'+
			            '<td>'+'</td>'+
			            '<td><b>Cash Tendered:</td>'+
			            '<td>&#8369; '+d.cash_tendered+'</td>'+
			            '<td>'+'</td>'+
			            '<td>'+'</td>'+
			            '<td>'+'</td>'+
			            '<td><b>Void By:</td>'+
			            '<td>'+d.void_by_id+'</td>'+
			        '</tr>'+
			        '<tr>'+
			            '<td><b>Student Number:</td>'+
			            '<td>'+d.student_no+'</td>'+			            
			            '<td>'+'</td>'+
			            '<td><b>Amount:</td>'+			            
			            '<td>&#8369; '+d.amount+'</td>'+
			            '<td>'+'</td>'+
			            '<td>'+'</td>'+
			            '<td>'+'</td>'+
			            '<td><b>Void Date/Time:</td>'+
			            '<td>'+d.date_time_void+'</td>'+
			        '</tr>'+
			        '<tr>'+
			            '<td><b>Student:</td>'+
			            '<td>'+d.first_name+'</td>'+
			            '<td>'+'</td>'+
			            '<td><b>Change:</td>'+
			            '<td>&#8369; '+d.change+'</td>'+
			            
			            '<td>'+'</td>'+
			            '<td>'+'</td>'+
			            '<td>'+'</td>'+
			            '<td><b>Void Remark:</td>'+
			            '<td>'+d.void_remark_id+'</td>'+
			        '</tr>'+
			        '<tr>'+
			            '<td><b>AR No.:</td>'+
			            '<td>'+d.ar_no+'</td>'+
			            '<td>'+'</td>'+
			            '<td><b>OR No.:</td>'+
			            '<td>'+d.or_no+'</td>'+
			            '<td>'+'</td>'+
			            '<td><b>Mode of Payment:</td>'+
			            '<td>'+d.payment_mode_name+'</td>'+
			            
			        '</tr>'+
			        '<tr>'+
			            '<td><b>Type of Payment:</td>'+
			            '<td>'+d.description+'</td>'+
			            '<td>'+'</td>'+
			            '<td><b>Admin:</td>'+
			            '<td>'+d.user_name+'</td>'+
			            '<td>'+'</td>'+
			            			            
			        '</tr>'+
			        '<tr>'+
			        	'<td>'+'</td>'+
			        	'<td>'+'</td>'+
			        	'<td>'+'</td>'+
			            '<td></td>'+
			            '<td>'+'</td>'+
			        '</tr>'+
			        '<tr>'+
			        	'<td>'+'</td>'+
			        	'<td>'+'</td>'+
			        	'<td>'+'</td>'+
			            '<td></td>'+
			            '<td>'+'</td>'+
			        '</tr>'+
			    '</table>'
			    '</div>';
			}

		var oTable;
		$(document).ready(function() {
			oTable = $('#table').dataTable( {
				"sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
				"sPaginationType": "bootstrap",
				"bDestroy": true,
				"bProcessing": true,
		        "bServerSide": true,

		        "sAjaxSource": "{{ URL::to('payment/data') }}",
		        "fnDrawCallback": function ( oSettings ) {
                    },
                "fnServerParams": function(aoData){
                    aoData.push(
                        { "name":"date_start", "value": $("#date_from").val() },
                        { "name":"date_end", "value": $("#date_to").val() }
                    );
                },
		         columns: [
		            {
		                "className":      'details-control',
		                "orderable":      false,
		                "data":           null,
		                "defaultContent": ''
		            },
		            {data: 'created_at', name: 'created_at'},
		            {data: 'student_no', name: 'student_no'},
		            {data: 'first_name', name: 'first_name'},
		            {data: 'remark', name: 'remark'},
		            {data: 'actions', name: 'actions'}
		        ],
		        order: [[1, 'asc']],
			});

			


			// Add event listener for opening and closing details
		    var paymentChild = $('#table').DataTable();

			    // Add event listener for opening and closing details
			    $('#table tbody').on('click', 'td.details-control', function () {
			        var tr = $(this).closest('tr');
			        var row = paymentChild.row(tr);

			        if (row.child.isShown()) {
			            // This row is already open - close it
			            $('div.slider', row.child()).slideUp( function () {
			            row.child.hide();
			            tr.removeClass('shown');
			        });
			        } else {
			            // Open this row
			            row.child(format(row.data()),'no-padding').show();
			            tr.addClass('shown');
			            $('div.slider', row.child()).slideDown();
			        }
			    });

			    $("#load").click(function(){
				var date_start = $("#date_start").val();
				start_date = new Date(date_start);
				year_start = start_date.getFullYear()+ "-" +(start_date.getMonth()+1) +"-" +start_date.getDate();

				var date_end = $("#date_end").val();
				end_date = new Date(date_end);
				year_end = end_date.getFullYear()+ "-" +(end_date.getMonth()+1)+ "-" +end_date.getDate();
				
				$("#date_from").val(year_start);
				$("#date_to").val(year_end);

				oTable.fnDraw();
        		
			});
		});
		$(function() {
	 		$('#datepicker').datepicker({
	        format: "MM d, yyyy",
			orientation: "top left",
			autoclose: true,
			startView: 1,
			todayHighlight: true,
			todayBtn: "linked",
			});
		});

		

		function oTableRedraw()
            {

                $('#EmployeeDT').dataTable( {
                    "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
                    "sPaginationType": "bootstrap",
                    "bProcessing": true,
                    "bServerSide": true,
                    "bStateSave": true,
                    "sAjaxSource": "{{ URL::to('employee/data/') }}",
                    "fnDrawCallback": function ( oSettings ) {
                    },

                    // "fnServerParams": function(aoData){
                    //     aoData.push(
                    //         { "name":"course_accreditation_id", "value": $("#course_accreditation_id").val() }
                    //     );
                    // },
                     columns: [
                        {
                            "className":      'details-control',
                            "orderable":      false,
                            "data":           null,
                            "defaultContent": ''
                        },
                        {data: 'employee_no', name: 'employee_no'},
                        {data: 'first_name', name: 'first_name'},
                        {data: 'is_active', name: 'is_active'},
                    ],
                    order: [[1, 'asc']]
    
                });

            }
	</script>


@stop