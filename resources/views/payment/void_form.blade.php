<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
<div class="col-md-12">
	<div class="form-group {{{ $errors->has('created_at') ? 'has-error' : '' }}}">
		<label class="col-md-2 control-label" for="created_at">{!! Lang::get('payment.created_at') !!}</label>
		<div class="col-md-8">
			<label class="control-label" type="date" name="created_at" id="created_at" >
			{!! date('Y-m-d')!!}</label>
			<input type="hidden" name="date"  value="{{{ date('Y-m-d') }}}"/>
			{!! $errors->first('created_at', '<label class="control-label" for="created_at">:message</label>')!!}

		</div>
	</div>
	<div class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
		<label class="col-md-2 control-label" for="name">{!! Lang::get('payment.name') !!}</label>
		<div class="col-md-8">
			<label class="control-label" type="text" name="name" id="name"/>{{{$payment->student_id }}}
			{!! $errors->first('name', '<label class="control-label" for="name">:message</label>')!!}

		</div>
	</div>
	<div class="form-group {{{ $errors->has('amount_paid') ? 'has-error' : '' }}}">
		<label class="col-md-2 control-label" for="amount_paid">{!! Lang::get('payment.amount_paid_void') !!}</label>
		<div class="col-md-8">
			<label class="control-label" type="text" name="amount_paid" id="amount_paid"/>{{{$payment->amount_paid }}}
			{!! $errors->first('amount_paid', '<label class="control-label" for="amount_paid">:message</label>')!!}

		</div>
	</div>
</div>