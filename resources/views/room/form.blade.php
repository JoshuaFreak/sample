<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
<div class="col-md-9">
    <div class="form-group {{{ $errors->has('campus_id') ? 'has-error' : '' }}}">
            <label class="col-md-3 control-label" for="campus_id">{{ Lang::get('room.campus') }}</label>
           <div class="col-md-9">
            <select class="form-control" name="campus_id" id="campus_id" tabindex="4">
                <option value=""></option>
                @if($action == 0)
                    @foreach($campus_list as $campus)
                        <option value="{{ $campus -> id }}">{{ $campus -> campus_name }}</option>
                    @endforeach
                @else
                    @foreach($campus_list as $campus)
                        @if($campus -> id == $room ->campus_id)
                            <option value="{{ $campus -> id }}" selected>{{ $campus -> campus_name }}</option>
                        @else
                            <option value="{{ $campus -> id }}">{{ $campus -> campus_name }}</option>
                        @endif
                    @endforeach
                @endif
            </select>
            {!! $errors->first('campus_id', '<label class="control-label" for="campus_id">:message</label>')!!}
        </div>  
    </div>
</div>
<div class="col-md-9">
    <div class="form-group {{{ $errors->has('course_capacity_id') ? 'has-error' : '' }}}">
            <label class="col-md-3 control-label" for="course_capacity_id">{{ Lang::get('room.class_capacity') }}</label>
           <div class="col-md-9">
            <select class="form-control" name="course_capacity_id" id="course_capacity_id" tabindex="4">
                <option value=""></option>
                @if($action == 0)
                    @foreach($course_capacity_list as $course_capacity)
                        <option value="{{ $course_capacity -> id }}">{{ $course_capacity -> capacity_name }}</option>
                    @endforeach
                @else
                    @foreach($course_capacity_list as $course_capacity)
                        @if($course_capacity -> id == $room ->course_capacity_id)
                            <option value="{{ $course_capacity -> id }}" selected>{{ $course_capacity -> capacity_name }}</option>
                        @else
                            <option value="{{ $course_capacity -> id }}">{{ $course_capacity -> capacity_name }}</option>
                        @endif
                    @endforeach
                @endif
            </select>
            {!! $errors->first('course_capacity_id', '<label class="control-label" for="course_capacity_id">:message</label>')!!}
        </div>  
    </div>
</div>
<div class="col-md-9">
	<div class="form-group {{{ $errors->has('room_name') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label" for="room_name">{!! Lang::get('room.room_name') !!}</label>
		<div class="col-md-9">
			<input class="form-control" type="text" name="room_name" id="room_name" value="{{{ Input::old('room_name', isset($room) ? $room->room_name : null) }}}" />
			{!! $errors->first('room_name', '<label class="control-label" for="room_name">:message</label>')!!}

		</div>
	</div>
</div>
<div class="col-md-9">
    <div class="form-group {{{ $errors->has('capacity') ? 'has-error' : '' }}}">
        <label class="col-md-3 control-label" for="capacity">{!! Lang::get('room.capacity') !!}</label>
        <div class="col-md-9">
            <input class="form-control" type="text" name="capacity" id="capacity" value="{{{ Input::old('capacity', isset($room) ? $room->room_capacity : null) }}}" />
            {!! $errors->first('capacity', '<label class="control-label" for="capacity">:message</label>')!!}

        </div>
    </div>
</div>



@section('scripts')
<script type="text/javascript">
</script>
@stop

