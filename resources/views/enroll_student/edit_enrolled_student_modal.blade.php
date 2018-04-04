
<div class="modal fade" id="editEnrolledStudent" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" >&times;</button>
                <h5 class="modal-title"><b style="color:#14335F"><div class="edit_enrolled_student_modal"></div></b></h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="col-md-12">
                       <b id="edit_enrolled_student_name"></b>
                    </div>
                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                    <input type="hidden" name="enrollment_id" id="enrollment_id" value="0" />
                    <input type="hidden" name="student_id" id="student_id" value="0" />
                    <br/><br/>
                    <div class="col-md-12">
                        <div class = "col-md-5">
                            <div class="form-group {{{ $errors->has('edit_enrolled_student_payment_scheme') ? 'has-error' : '' }}}">
                                <label for="edit_enrolled_student_payment_scheme">{{ Lang::get('enrollment.payment_scheme') }}</label>
                                <select class="form-control" name="edit_enrolled_student_payment_scheme" id="edit_enrolled_student_payment_scheme" tabindex="4">
                                    @foreach($payment_scheme_list as $payment_scheme)
                                        <option name="edit_enrolled_student_payment_scheme" value="{{{ $payment_scheme->id }}}" >{{{ $payment_scheme->payment_scheme_name }}}</option>
                                    @endforeach
                                </select>
                            </div>  
                        </div>                      
                    </div>
                    <div class="col-md-12">
                        <button type="button" class="btn btn-sm btn-success EditEnrolledStudent">
                            <span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.save_changes") }}
                        </button>   
                    </div> 
                </div>
                .
            </div>
        </div>
    </div>
</div>