<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
	<div class="col-md-12">
	    <div class="form-group {{{ $errors->has('first_name') ? 'has-error' : '' }}}">
	        <label class="col-md-3 control-label" for="first_name">{{ Lang::get('gen_person.first_name') }}</label>
	        <div class="col-md-6">
	            <input class="form-control"  type="first_name" tabindex="1" placeholder="{{ Lang::get('person.first_name') }}" name="first_name" id="first_name" value="{{{ Input::old('first_name', isset($person) ? $person->first_name : null) }}}" />
	            {!! $errors->first('first_name', '<label class="control-label" for="first_name">:message</label>')!!}
	        </div>
	    </div>
    </div>
    <div class="col-md-12">
	    <div class="form-group {{{ $errors->has('last_name') ? 'has-error' : '' }}}">
	        <label class="col-md-3 control-label" for="last_name">{{ Lang::get('gen_person.last_name') }}</label>
	        <div class="col-md-6">
	            <input class="form-control"  type="last_name" tabindex="1" placeholder="{{ Lang::get('person.last_name') }}" name="last_name" id="last_name" value="{{{ Input::old('last_name', isset($person) ? $person->last_name : null) }}}" />
	            {!! $errors->first('last_name', '<label class="control-label" for="last_name">:message</label>')!!}
	        </div>
	    </div>
    </div>
	<div class="col-md-12">
	    <div class="form-group {{{ $errors->has('username') ? 'has-error' : '' }}}">
	        <label class="col-md-3 control-label" for="username">{{ Lang::get('gen_user.username') }}</label>
	        <div class="col-md-6">
	            <input class="form-control"  type="username" tabindex="1" placeholder="{{ Lang::get('gen_user.username') }}" name="username" id="username" value="{{{ Input::old('username', isset($gen_user) ? $gen_user->username : null) }}}" />
	            {!! $errors->first('username', '<label class="control-label" for="username">:message</label>')!!}
	        </div>
	    </div>
    </div>
	<div class="col-md-12">
		<div class="form-group {{{ $errors->has('password') ? 'has-error' : '' }}}">
			<label class="col-md-3 control-label" for="password">{{ Lang::get('gen_user.password') }}</label>
			<div class="col-md-6">
				<input class="form-control"  tabindex="2" placeholder="{{ Lang::get('gen_user.password') }}" type="password" name="password" id="password" value="{{{ Input::old('username', isset($gen_user) ? $gen_user->secret : null) }}}" />
				{!!$errors->first('password', '<label class="control-label" for="password">:message</label>')!!}
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="form-group">
			<label class="col-md-3 control-label" for="confirm">{{ Lang::get('gen_user.activate_user') }}</label>
			<div class="col-md-6">
				<select class="form-control" name="confirmed" id="confirmed" tabindex="4">
					<option value="1"{{{ ((isset($gen_user) && $gen_user->confirmed == 1)? ' selected="selected"' : '') }}}>{{{ Lang::get('gen_user.yes') }}}</option>
					<option value="0"{{{ ((isset($gen_user) && $gen_user->confirmed == 0) ? ' selected="selected"' : '') }}}>{{{ Lang::get('gen_user.no') }}}</option>
				</select>
			</div>
		</div>
	</div>

	<div class="col-md-12">
		@if($action == 1)
		<div class="form-group {{{ $errors->has('roles') ? 'has-error' : '' }}}">
			<label class="col-md-3 control-label" for="roles">{!! Lang::get('gen_user.roles') !!}</label>
			<div class="col-md-8">
				<?php $count = 1?>
				@foreach($gen_user_role_list as $gen_user_role)

				<div class="form-group" id="gen_role_container_{{{$gen_user_role->id}}}" >
					<div class="col-md-8"><label class="control-label" id="gen_role_id{{{$gen_user_role->id}}}" >{{{ $gen_user_role->name }}}</label></div>
					<div class="col-md-1"><a data-href="{{{ URL::to('gen_user_role/delete') }}}" data-value="{{{$gen_user_role->id}}}" data-toggle="modal" data-target="#gen_user_role_modal" class="btn btn-sm btn-danger close_popup delete_button" ><span class="glyphicon glyphicon-remove"></span></a></div>
				</div>
				@endforeach

				{!! $errors->first('roles', '<label class="control-label" for="roles">:message</label>')!!}

			</div>
		</div>
	@endif
		<div class="form-group {{{ $errors->has('roles') ? 'has-error' : '' }}}">
			<label class="col-md-3 control-label" for="roles">{!! Lang::get('gen_user.add_roles') !!}</label>
			<div class="col-md-8">
				<select name="roles[]" id="roles" multiple style="width: 75%">
					@foreach($gen_role_list as $gen_role)
						<option value="{{{ $gen_role->id }}}">{{{$gen_role->name}}}</option>
					@endforeach

				</select>

				{!! $errors->first('roles', '<label class="control-label" for="roles">:message</label>')!!}

			</div>
		</div>

<!-- MODAL -->
	<div class="modal fade" id="gen_user_role_modal" role="dialog">

	    <div class="modal-dialog">

	      <!-- Modal content-->
	     	<div class="modal-content">
		        <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" >&times;</button>
			        <h4 class="modal-title">Confirm Delete</h4>
		        </div>
		        <div class="modal-body">
		        	<ul style="list-style-type:none;">
		        		Are you sure you want to <strong>delete this item?</strong><br/><br/>
		          		<li class="debug-url" ><span id="gen_role_item"></span></li>
		          	</ul>
		        </div>
		        <div class="modal-footer">
		          	<button class="btn btn-default" data-dismiss="modal">Close</button>
		          	<a id="delete" class="btn btn-danger btn-ok">Delete</a>

		        </div>
	      	</div>

	    </div>
	</div>
<!-- END MODAL -->

    </div>

	


    