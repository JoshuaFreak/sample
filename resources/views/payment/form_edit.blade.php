
<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
<div class="form-group {{{ $errors->has('created_at') ? 'has-error' : '' }}}">
	<label class="col-md-2 control-label" for="created_at">{!! Lang::get('payment.date') !!}</label>
	<div class="col-md-8">
	<input type="date" class="form-control" id="created_at" value="{{{substr($payment -> transaction_date,0,10)}}}"></input>
		{!! $errors->first('created_at', '<label class="control-label" for="created_at">:message</label>')!!}

	</div>
</div>
<div class="form-group {{{ $errors->has('ar_no') ? 'has-error' : '' }}}">
	<label class="col-md-2 control-label" for="ar_no">{!! Lang::get('payment.ar_no') !!}</label>
	<div class="col-md-8">
	<input type="text" class="form-control" id="ar_no" value="{{{$payment -> ar_no}}}"></input>
		{!! $errors->first('ar_no', '<label class="control-label" for="ar_no">:message</label>')!!}

	</div>
</div>
<div class="form-group {{{ $errors->has('or_no') ? 'has-error' : '' }}}">
	<label class="col-md-2 control-label" for="or_no">{!! Lang::get('payment.or_no') !!}</label>
	<div class="col-md-8">
	<input type="text" class="form-control" id="or_no" value="{{{$payment -> or_no}}}"></input>
		{!! $errors->first('or_no', '<label class="control-label" for="or_no">:message</label>')!!}

	</div>
</div>
<!-- <div class="form-group {{{ $errors->has('amount_paid') ? 'has-error' : '' }}}">
	<label class="col-md-2 control-label" for="amount_paid">{!! Lang::get('payment.amount_paid') !!}</label>
	<div class="col-md-8">
	<input type="text" class="form-control" id="amount_paid" value="{{{$payment -> amount_paid}}}"></input>
		{!! $errors->first('amount_paid', '<label class="control-label" for="amount_paid">:message</label>')!!}

	</div>
</div>
<div class="form-group {{{ $errors->has('cash_tendered') ? 'has-error' : '' }}}">
	<label class="col-md-2 control-label" for="cash_tendered">{!! Lang::get('payment.cash_tendered') !!}</label>
	<div class="col-md-8">
	<input type="text" class="form-control" id="cash_tendered" value="{{{$payment -> cash_tendered}}}"></input>
		{!! $errors->first('cash_tendered', '<label class="control-label" for="cash_tendered">:message</label>')!!}

	</div>
</div>
<div class="form-group {{{ $errors->has('change') ? 'has-error' : '' }}}">
	<label class="col-md-2 control-label" for="change">{!! Lang::get('payment.change') !!}</label>
	<div class="col-md-8">
	<input type="text" class="form-control" id="change" value="{{{$payment -> change}}}"></input>
		{!! $errors->first('change', '<label class="control-label" for="change">:message</label>')!!}

	</div>
</div>
 --><div class="form-group">
	<div class="col-md-3">
			<label class="col-md-12 control-label">{!! Lang::get('payment.bank_name') !!}</label>
	</div>
	<div class="col-md-3">
			<label class="col-md-12 control-label">{!! Lang::get('payment.account_no') !!}</label>
	</div>
	<div class="col-md-3">
			<label class="col-md-12 control-label">{!! Lang::get('payment.check_date') !!}</label>
	</div>
	<div class="col-md-3">
			<label class="col-md-12 control-label">{!! Lang::get('payment.check_amount') !!}</label>
	</div>
</div>
@foreach($check_detail_list as $check_detail)
<div class="form-group check_detail" id="check_detail_id">
	<div class="col-md-3">
	<input type="hidden" class="form-control" name="check_detail_id" value="{{{$check_detail -> id}}}"></input>
	<input type="text" class="form-control" name="bank_name" value="{{{$check_detail -> bank_name}}}"></input>
		{!! $errors->first('bank_name', '<label class="control-label" for="bank_name">:message</label>')!!}
	</div>
	<div class="col-md-3">
	<input type="text" class="form-control" name="account_no" value="{{{$check_detail -> account_no}}}"></input>
		{!! $errors->first('account_no', '<label class="control-label" for="account_no">:message</label>')!!}
	</div>
	<div class="col-md-3">
	<input type="date" class="form-control" name="check_date" value="{{{$check_detail -> check_date}}}"></input>
		{!! $errors->first('check_date', '<label class="control-label" for="check_date">:message</label>')!!}
	</div>
	<div class="col-md-3">
	<input type="text" class="form-control check_amount" readOnly name="check_amount" value="{{{$check_detail -> check_amount}}}"></input>
		{!! $errors->first('check_amount', '<label class="control-label" for="check_amount">:message</label>')!!}
	</div>
</div>
@endforeach