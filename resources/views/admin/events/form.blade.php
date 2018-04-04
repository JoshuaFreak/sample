<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
	<div class="col-md-12">
		<div class="form-group {{{ $errors->has('date') ? 'has-error' : '' }}}">
			<label class="col-md-2 control-label" for="date">{!! Lang::get('Date Created') !!}</label>
			<div class="col-md-2">
				<input class="form-control" type="text" name="datepicker" id="datepicker" value="{{{ Input::old('datepicker', isset($event) ? date_format(date_create($event->date),'F d, Y') : null) }}}" required/>
				{!! $errors->first('name', '<label class="control-label" for="datepicker">:message</label>')!!}

			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
			<label class="col-md-2 control-label" for="event_name">{!! Lang::get('Event Name') !!}</label>
			<div class="col-md-5">
				<input class="form-control" name="event_name" id="event_name" value="{{{ Input::old('event_name', isset($event) ? $event->event_name : null) }}}" required>
				{!! $errors->first('name', '<label class="control-label" for="event_name">:message</label>')!!}

			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
			<label class="col-md-2 control-label" for="description">{!! Lang::get('Description') !!}</label>
			<div class="col-md-5">
				<textarea class="form-control" name="description" id="description" rows='5' required>{{{ Input::old('description', isset($event) ? $event->description : null) }}}</textarea>
				{!! $errors->first('name', '<label class="control-label" for="description">:message</label>')!!}

			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
			<label class="col-md-2 control-label" for="description">{!! Lang::get('Location') !!}</label>
			<div class="col-md-5">
				<input class="form-control" name="location" id="location" value="{{{ Input::old('location', isset($event) ? $event->location : null) }}}">
				{!! $errors->first('name', '<label class="control-label" for="location">:message</label>')!!}

			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
			<label class="col-md-2 control-label" for="photo">{!! Lang::get('Photo') !!}</label>
			<div class="col-md-5">
				<input type="file" name="photo" id="photo" class="form-control">
				{!! $errors->first('name', '<label class="control-label" for="photo">:message</label>')!!}
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
			<label class="col-md-2"></label>
			<div class="col-md-5">
				@if($action == 1)
					<img src="{{URL::to('admin/events/image/'.$event->id)}}" onerror="this.src='{{asset('assets/site/images/not-found.png')}}'" id="image" width="100%">
				@else
					<img src="" onerror="this.src='{{asset('assets/site/images/not-found.png')}}'" id="image" width="100%">
				@endif
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="form-group">
			<label class="col-md-2 control-label" for="confirm">{{ Lang::get('Active') }}</label>
			<div class="col-md-5">
				<select class="form-control" name="is_active" id="is_active" tabindex="4">
					<option value="1"{{{ ((isset($event) && $event->is_active == 1)? ' selected="selected"' : '') }}}>{{{ Lang::get('Yes') }}}</option>
					<option value="0"{{{ ((isset($event) && $event->is_active == 0) ? ' selected="selected"' : '') }}}>{{{ Lang::get('No') }}}</option>
				</select>
			</div>
		</div>
	</div>

@section('scripts')
@parent
<script type="text/javascript">
    $(function(){
    	$('#photo').change(function(){
    		if($(this).val()){
   				$('#image').attr('src', URL.createObjectURL(event.target.files[0]));
    		}else{
   				$('#image').attr('src', '{{URL::to("assets/site/images/not-found.png")}}');
    		}
    	});
    })
</script>
@endsection