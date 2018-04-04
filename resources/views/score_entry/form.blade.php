<div class="col-md-12">
     <div class="form-group {{{ $errors->has('student_id') ? 'has-error' : '' }}}">
  		<label class="col-md-1 control-label" for="student_name">Search Student</label>
        <input type="hidden" name="student_id" id="student_id" value="0" />

        <input type="hidden" name="score_entry_count" id="score_entry_count" value="0" />

  			<div class="col-md-4">
  					<input class="typeahead form-control" type="text" name="student_name" id="student_name" value="" autofocus/>
  				{!! $errors->first('student_id', '<label class="control-label" for="student_name">:message</label>')!!}

  			</div>
        <label class="col-md-1 control-label" for="student_name">Examination</label>
  			<div class="col-md-3">
  				<select class="form-control" name="examination_select_id" id="examination_select_id">
 
          </select>
  			</div>

        <div id="monthly_result_card_btn" class="hidden col-md-3">
            <a class="btn btn-primary" data-url="{{ URL::to('scoreEntry/scoreResultExcel') }}"
            id="score_entry_report"  href="javascript:callReport('score_entry_report');">
            Monthly Result Card</a>
        </div>

        </div>
	  </div>
</div>
<div class="col-md-12"><hr></div>
<div class="col-md-12">
      <div class="form-group col-md-12">
          <div class="form-group col-md-12">
              <div class="form-group col-md-3">
                  <label class="control-label">Course</label>
              </div>
              <div class="form-group col-md-3">
                  <label class="control-label">Perfect Score</label>
              </div>
 
              <div class="form-group col-md-3">
                  <label class="control-label">Score Entry</label>
              </div>
              <div class="form-group col-md-3">
                  <label class="control-label">Rating</label>
              </div>
          </div>
      </div>
      <div id="score_entry_container">
      </div>
</div>