<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
<!-- <div class="col-md-12">
	<div class="form-group col-md-6 {{{ $errors->has('employee_no') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label" for="employee_no">{!! Lang::get('employee.employee_no') !!}</label>
		<div class="col-md-8">
			<input type="text" class="form-control" name="employee_no" id="employee_no">
			{!! $errors->first('employee_no', '<label class="control-label" for="employee_no">:message</label>')!!}
		</div>
	</div>
</div> -->
<!-- <div class = "col-md-4">
  <img id="myImg" src="{{asset('assets/site/images/BLANK_IMAGE.jpg')}}" alt="image" width="192px" height="192px" />
</div> -->
<div class = "col-md-12">
  <div class = "col-md-12">
  	<div class="col-md-12 form-group">
	  	<div class = "col-md-6">
		    <div class="col-md-12" style="margin-left: -35px;">
			  	<img id="myImg" src="{{asset('assets/site/images/BLANK_IMAGE.jpg')}}" alt="image" width="170px" height="150px" data-toggle="modal" data-target="#photo_modal"/>
			</div>
			<input type="hidden" id="image_canvas" name="image_canvas" value="">
		</div>
	</div>
  	<hr/>
  </div>
    <div class = "col-md-6">
       <div class="control-group">
          <div class="controls">
            <input type="file" name="upload_image" onchange="displayImage(this);"></input>
          </div>
        </div><br>
    </div>
    <div class="form-group col-md-6 {{{ $errors->has('campus_id') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label" for="campus_id">{{ Lang::get('room.campus') }}</label>
		<div class="col-md-8">
		<select class="form-control" name="campus_id" id="campus_id" tabindex="4">
			<option></option>
			@foreach($campus_list as $campus)
				<option class="form-control" id="campus_id" value="{{ $campus -> id }}">{{ $campus -> campus_name }}</option>
			@endforeach
		</select>
		{!! $errors->first('campus_id', '<label class="control-label" for="campus_id">:message</label>')!!}
		</div>  	
	</div>
</div>
<div class="col-md-12">

	<div class="form-group col-md-6 {{{ $errors->has('first_name') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label" for="first_name">{!! Lang::get('employee.first_name') !!}</label>
		<div class="col-md-8">
			<input type="text" class="form-control" name="first_name" id="first_name">
			{!! $errors->first('first_name', '<label class="control-label" for="first_name">:message</label>')!!}
		</div>
	</div>

	<div class="form-group col-md-6 {{{ $errors->has('') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label" for="middle_name">{!! Lang::get('employee.middle_name') !!}</label>
		<div class="col-md-8">
			<input type="text" class="form-control" name="middle_name" id="middle_name">
			{!! $errors->first('middle_name', '<label class="control-label" for="middle_name">:message</label>')!!}
		</div>
	</div>

	<div class="form-group col-md-6 {{{ $errors->has('last_name') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label" for="last_name">{!! Lang::get('employee.last_name') !!}</label>
		<div class="col-md-8">
			<input type="text" class="form-control" name="last_name" id="last_name">
			{!! $errors->first('last_name', '<label class="control-label" for="last_name">:message</label>')!!}
		</div>
	</div>
	<div class="form-group col-md-6 {{{ $errors->has('nickname') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label" for="nickname">{!! Lang::get('employee.nickname') !!}</label>
		<div class="col-md-8">
			<input type="text" class="form-control" name="nickname" id="nickname">
			{!! $errors->first('nickname', '<label class="control-label" for="nickname">:message</label>')!!}
		</div>
	</div>
	
	<div class="form-group col-md-6 {{{ $errors->has('gen_role_id') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label" for="gen_role_id">{{ Lang::get('employee.gen_role') }}</label>
		<div class="col-md-8">
		<select class="form-control" name="gen_role_id" id="gen_role_id" tabindex="4">
			<option></option>
			@foreach($gen_role_list as $gen_role)
				<option class="form-control" id="gen_role_id" value="{{ $gen_role -> id }}">{{ $gen_role -> name }}</option>
			@endforeach
		</select>
		{!! $errors->first('gen_role_id', '<label class="control-label" for="gen_role_id">:message</label>')!!}
		</div>  	
	</div>  
	<div class="form-group col-md-6 {{{ $errors->has('employee_type_id') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label" for="employee_type_id">{{ Lang::get('employee.employee_type') }}</label>
		<div class="col-md-8">
		<select class="form-control" name="employee_type_id" id="employee_type_id" tabindex="4">
			<option></option>
			@foreach($employee_type_list as $employee_type)
				<option class="form-control" id="employee_type_id" value="{{ $employee_type -> id }}">{{ $employee_type -> employee_type_name }}</option>
			@endforeach
		</select>
		{!! $errors->first('employee_type_id', '<label class="control-label" for="employee_type_id">:message</label>')!!}
		</div>  	
	</div>  
	<div class="form-group col-md-6 {{{ $errors->has('position') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label" for="position">{!! Lang::get('employee.position') !!}</label>
		<div class="col-md-8">
			<select class="form-control" name="position_id" id="position_id" tabindex="4">
			</select>
			{!! $errors->first('position_id', '<label class="control-label" for="position_id">:message</label>')!!}
			
		</div>
	</div>
	<div class="form-group col-md-6 {{{ $errors->has('employment_status_id') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label" for="employment_status_id">{{ Lang::get('employee.employment_status') }}</label>
		<div class="col-md-8">
		<select class="form-control" name="employment_status_id" id="employment_status_id" tabindex="4">
			<option></option>
			@foreach($employment_status_list as $employment_status)
				<option class="form-control" id="employment_status_id" value="{{ $employment_status -> id }}">{{ $employment_status -> employment_status_name }}</option>
			@endforeach
		</select>
		{!! $errors->first('employment_status_id', '<label class="control-label" for="employment_status_id">:message</label>')!!}
		</div>  	
	</div>
</div>
<div class="col-md-12">
	<div class="form-group col-md-6 {{{ $errors->has('date_hired') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label" for="date_hired">{!! Lang::get('employee.date_hired') !!}</label>
		<div class="col-md-8">
			<input type="date" class="form-control" name="date_hired" id="date_hired">
			{!! $errors->first('date_hired', '<label class="control-label" for="date_hired">:message</label>')!!}
		</div>
	</div> 	
	<div class="col-md-6 form-group">
	    <label class="col-md-3 control-label" for="from">Contract Period</label>
	    <div class="input-daterange input-group col-md-8" id="datepicker" style="padding-left: 15px !important;padding-right: 15px !important;">
	        <input type="text" class="contract_date_start form-control" name="contract_date_start" value="" />
	        <span class="input-group-addon">to</span>
	        <input type="text" class="contract_date_end form-control" name="contract_date_end" value="" />
	    </div>
	</div>
	<div class="form-group col-md-6 {{{ $errors->has('length_of_service') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label" for="length_of_service">{!! Lang::get('employee.length_of_service') !!}</label>
		<div class="col-md-8">
			<input type="text" class="form-control" readonly name="length_of_service" id="length_of_service">
			{!! $errors->first('length_of_service', '<label class="control-label" for="length_of_service">:message</label>')!!}
		</div>
	</div> 	
	<div class="form-group col-md-6 {{{ $errors->has('rate') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label" for="rate">{!! Lang::get('employee.rate') !!}</label>
		<div class="col-md-8">
			<input type="text" class="form-control" name="rate" id="rate">
			{!! $errors->first('rate', '<label class="control-label" for="rate">:message</label>')!!}
		</div>
	</div> 	
</div>
<div class="col-md-12">
	<div class="form-group col-md-6 {{{ $errors->has('address') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label" for="address">{!! Lang::get('employee.address') !!}</label>
		<div class="col-md-8">
			<input type="text" class="form-control" name="address" id="address">
			{!! $errors->first('address', '<label class="control-label" for="address">:message</label>')!!}
		</div>
	</div>
	<div class="form-group col-md-6 {{{ $errors->has('contact_no') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label" for="contact_no">{!! Lang::get('employee.contact_no') !!}</label>
		<div class="col-md-8">
			<input type="text" class="form-control" name="contact_no" id="contact_no">
			{!! $errors->first('contact_no', '<label class="control-label" for="contact_no">:message</label>')!!}
		</div>
	</div>
	<div class="form-group col-md-6 {{{ $errors->has('birthdate') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label" for="birthdate">{!! Lang::get('employee.birthdate') !!}</label>
		<div class="col-md-8">
			<input type="date" class="form-control" name="birthdate" id="birthdate">
			{!! $errors->first('birthdate', '<label class="control-label" for="birthdate">:message</label>')!!}
		</div>
	</div>
	<!-- <div class="form-group col-md-6 {{{ $errors->has('place_of_birth') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label" for="place_of_birth">{!! Lang::get('employee.place_of_birth') !!}</label>
		<div class="col-md-8">
			<input type="text" class="form-control" name="place_of_birth" id="place_of_birth">
			{!! $errors->first('place_of_birth', '<label class="control-label" for="place_of_birth">:message</label>')!!}
		</div>
	</div>  -->
	<div class="form-group col-md-6 {{{ $errors->has('civil_status_id') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label" for="civil_status_id">{!! Lang::get('employee.civil_status') !!}</label>
		<div class="col-md-8">
			<select class="form-control" name="civil_status_id" id="civil_status_id" tabindex="4">
				<option></option>
				@foreach($civil_status_list as $civil_status)
					<option class="form-control" id="civil_status_id" value="{{ $civil_status -> id }}">{{ $civil_status -> civil_status_name }}</option>
				@endforeach
			</select>
			{!! $errors->first('civil_status_id', '<label class="control-label" for="civil_status_id">:message</label>')!!}
		</div>
	</div>
	<div class="form-group col-md-6 {{{ $errors->has('gender_id') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label" for="gender_id">{{ Lang::get('employee.gender_id') }}</label>
		<div class="col-md-8">
		<select class="form-control" name="gender_id" id="gender_id" tabindex="4">
			<option></option>
			@foreach($gender_list as $gender)
				<option class="form-control" id="gender_id" value="{{ $gender -> id }}">{{ $gender -> gender_name }}</option>
			@endforeach
		</select>
		{!! $errors->first('gender_id', '<label class="control-label" for="gender_id">:message</label>')!!}
		</div>  	
	</div>
	<div class="form-group col-md-6 {{{ $errors->has('passport_number') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label" for="passport_number">{!! Lang::get('employee.passport_number') !!}</label>
		<div class="col-md-8">
			<input type="text" class="form-control" name="passport_number" id="passport_number">
			{!! $errors->first('passport_number', '<label class="control-label" for="passport_number">:message</label>')!!}
		</div>
	</div>

	<!-- <div class="form-group col-md-6 {{{ $errors->has('i_card') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label" for="i_card">{!! Lang::get('employee.i_card') !!}</label>
		<div class="col-md-8">
			<input type="text" class="form-control" name="i_card" id="i_card">
			{!! $errors->first('i_card', '<label class="control-label" for="i_card">:message</label>')!!}
		</div>
	</div> 	 -->
	<div class="form-group col-md-6 {{{ $errors->has('is_active') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label" for="is_active">{!! Lang::get('employee.is_active') !!}</label>
		<div class="col-md-8" style="padding-top:7px;">
			<!-- {{ $default = Input::old('is_active', isset($employee) ? $employee->is_active : null) }} -->
			<input type="checkbox" name="is_active" id="is_active" class="reset-data"  value="1" checked />
			{!! $errors->first('is_active', '<label class="control-label" for="is_active">:message</label>')!!}

		</div>
	</div>
</div>

<div class="modal fade" id="photo_modal" role="dialog">

      <div class="modal-dialog">
      
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" >&times;</button>
              <h4 class="modal-title">Take Photo</h4>
            </div>
            <div class="modal-body">
          <div class="col-md-12 form-group">
            <div class="col-md-6" id="camera" style="padding-left: 0px;margin-left: 5px;margin-top: -50px;">
              <video id="video" width="530" height="530" autoplay></video>
            </div>
            <div class="col-md-6" hidden id="canvas_div" style="padding-left: 0px;margin-left: 5px;margin-bottom: 80px;">
              <canvas id="canvas" width="530" height="400"></canvas>
            </div>
          </div>
          <br/>
          <br/>
          <br/>
          <br/>
          <br/>
          <br/>
          <br/>
          <br/>
          <br/>
          <br/>
          <br/>
          <br/>
          <br/>
          <br/>
          <br/>
          <br/><br/>
          <br/>
          <br/>
          <br/>
          <div class="col-md-12 form-group" style="margin-top: -70px;">
            <div class="col-md-12">
              <button type="button" id="snap">Capture</button>
              <button type="button" hidden id="take">Take Another Photo</button>
            </div>
          </div>
          <br/>
          <br/>
          

            </div>
            <br/>
            <div class="modal-footer">
                <a id="get_photo" class="btn btn-success btn-ok" data-dismiss="modal">Accept</a>
                <button id="close_photo" class="btn btn-default" data-dismiss="modal">Close</button>

            </div>
          </div>
        
      </div>
  </div>

