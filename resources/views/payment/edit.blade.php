@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("payment.edit_payment") }}} :: @parent
@stop

{{-- Content --}}
@section('content')
<!-- Side Bar -->
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            @include('accounting_sidebar')
	    </ul>
    </div>
</div>
<div id="page-wrapper">
    <div class="row"><br>
		<div class="page-header">
			<h3> {{{ Lang::get("payment.edit_payment") }}}
				<div class="pull-right">
					<div class="pull-right">
			            <a href="{{{ URL::to('payment') }}}" class="btn btn-sm  btn-primary iframe"><span class="glyphicon glyphicon-list"></span> {{ Lang::get("payment.payment_list") }}</a>
			        </div>
				</div>
			</h3>
		</div>
		<form class="form-horizontal" method="post" action="{{ URL::to('payment/' . $payment->id . '/edit') }}" autocomplete="off">
			<input type="hidden" id="payment_id" value="{{ $payment->id }}" />
			@include('payment.form_edit')
			<div class="col-md-12">
				<div class="form-group">
					<label class="col-md-3 control-label" for="actions">&nbsp;</label>
					<div class="col-md-9">	
			 			<button type="button" id="save_changes" class="btn btn-sm btn-success">
			                <span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.save_changes") }}
			            </button>	
			 			<a href="{{{ URL::to('payment') }}}" class="btn btn-sm btn-warning close_popup">
			                <span class="glyphicon glyphicon-ban-circle"></span>  {{ Lang::get("form.cancel") }}
			       		</a>
			        </div>
			    </div>
			</div>	
		</form>
	</div>
</div>
@stop
@section('scripts')
<script type="text/javascript">
$(function()
{
	// $("#cash_tendered").decimalOnly();
	// $("#amount_paid").decimalOnly();
	// $("#change").decimalOnly();
	// $(".check_amount").decimalOnly();

	// $("#cash_tendered").keyup(function(){
	// 	var total_amount_paid = $("#amount_paid").val();
	// 	var cash_tendered = $("#cash_tendered").val();

	// 	var total = parseFloat(cash_tendered) - parseFloat(total_amount_paid);
	// 	$("#change").val(parseFloat(total).toFixed(2));
	// });

	// $("#amount_paid").keyup(function(){
	// 	var total_amount_paid = $("#amount_paid").val();
	// 	var cash_tendered = $("#cash_tendered").val();

	// 	var total = parseFloat(cash_tendered) - parseFloat(total_amount_paid);
	// 	$("#change").val(parseFloat(total).toFixed(2));
	// });

	$("#save_changes").click(function(){

		payment_id = $("#payment_id").val();
		created_at = $("#created_at").val();
		ar_no = $("#ar_no").val();
		or_no = $("#or_no").val();
		amount_paid = $("#amount_paid").val();
		cash_tendered = $("#cash_tendered").val();
		change = $("#change").val();

		$.ajax(
		{
			url:'{{{ URL::to("payment/edit") }}}',
			type:'post',
			data:
				{
					'payment_id': payment_id,
					'created_at': created_at,
					'ar_no': ar_no,
					'or_no': or_no,
					'amount_paid': amount_paid,
					'cash_tendered': cash_tendered,
					'change': change,
					'_token': $('input[name=_token]').val(),
				},
			async:false,
			success: function(data)
			{
				// swal("Succefully Edited!");
			},
			error: function(data)
			{
				swal("Edit Unsucceful!");
			}
		}

		).done(function(data){
			
		});

		$(".check_detail").each(function(){
			bank_name = $(this).find('input[name="bank_name"]').val();
			account_no = $(this).find('input[name="account_no"]').val();
			check_date = $(this).find('input[name="check_date"]').val();
			check_amount = $(this).find('input[name="check_amount"]').val();
			check_detail_id = $(this).find('input[name="check_detail_id"]').val();
			

			$.ajax(
			{
				url:'{{{ URL::to("payment/postCheckDetail") }}}',
				type:'post',
				data:
					{
						// 'payment_id': payment_id,
						'check_detail_id': check_detail_id,
						'bank_name': bank_name,
						'account_no': account_no,
						'check_date': check_date,
						'check_amount': check_amount,
						'_token': $('input[name=_token]').val(),
					},
				async:false,
				success: function(data)
				{
					swal("Succefully Edited!");
				},
				error: function(data)
				{
					swal("Edit Unsucceful!");
				}
			}

			).done(function(data){
				
			});
		});
	});
})
	
</script>
@stop
