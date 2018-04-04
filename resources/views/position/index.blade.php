@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("position.position_list") }}} :: @parent
@stop
{{-- Content --}}
@section('content')
<div class="navbar-default sidebar" role="navigation">
	<div class="sidebar-nav navbar-collapse">    
	    <ul class="nav" id="side-menu"> 
	      @include('hrms_sidebar')
	    </ul>
	</div>
</div>
<div id="page-wrapper">
    <div class="row">
		<div class="page-header"><br>
			<h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left">
				{{{ Lang::get("position.position_list") }}}
				<div class="pull-right" style="margin-right: 10px !important">
					<a href="{{{ URL::to('position/create') }}}" class="btn btn-sm  btn-primary"><span class="glyphicon glyphicon-plus-sign"></span>  {{ Lang::get('position.create_new_position') }}</a>
				</div>
			</h3>
		</div>
        <div class="col-md-12">
            <!-- <div class="col-md-4">
                <div class="form-group {{{ $errors->has('classification_id') ? 'has-error' : '' }}}">
                    <label for="classification_id">Search by {{ Lang::get('position.classification') }}</label>
                    <select class="form-control" name="classification_id" id="classification_id">
                        <option type="text" name="0" id="" value=""></option>
                    </select>
                </div>
            </div>
            <div id="program_container"></div>
            <div class="col-md-4">
                <div class="form-group {{{ $errors->has('classification_level_id') ? 'has-error' : '' }}}">
                    <label for="classification_level_id">Search by {{ Lang::get('position.classification_level') }}</label>
                    <select class="form-control" name="classification_level_id" id="classification_level_id">
                        <option type="text" name="0" id="" value=""></option>
                        <option selected>--Please Select Classification First--</option>
                    </select>
                </div>
            </div>  -->     
        </div>
		<table id="PositionFilter" class="table table-striped table-hover">
			<thead>
				<tr>
                    <th> {{ Lang::get("position.position_name") }}</th>
<!--                     <th> {{ Lang::get("position.employee_type") }}</th>
					<th> {{ Lang::get("position.department_team") }}</th> -->
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
    $(document).ready(function() {

            $("#classification_id").change(function(){
                  selectListChange('classification_level_id','{{{URL::to("classification_level/dataJson")}}}',  { 'is_active':1 , 'classification_id': $(this).val() } ,'Please select a Classification Level')
            });

            PositionFilter = $('#PositionFilter').dataTable( {
                "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
                "sPaginationType": "bootstrap",
                "bProcessing": true,
                "bServerSide": true,
                "bStateSave": true,
                "sAjaxSource": "{{ URL::to('position/data/') }}",
                "fnDrawCallback": function ( oSettings ) {
                },

                "fnServerParams": function(aoData){
                    // aoData.push(
                    //     { "name":"classification_id", "value": $("#classification_id").val() },
                    //     { "name":"classification_level_id", "value": $("#classification_level_id").val() }
                    // );
                }
            });

            // $("#classification_id").change(function(){
            //    PositionFilter.fnDraw();
            // });
            // $("#classification_level_id").change(function(){
            //    PositionFilter.fnDraw();
            // });
    });
</script>
@stop