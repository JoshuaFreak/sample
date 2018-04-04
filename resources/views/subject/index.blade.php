@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("subject.subject_list") }}} :: @parent
@stop
{{-- Content --}}
@section('content')

 <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
           
        
<!-- Side Bar -->
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            
            @include('registrar_sidebar')
            
	    </ul>
    </div>
</div>
<div id="page-wrapper">
    <div class="row">
<!-- Content Header -->
        <div class="page-header"><br>
            <h2>
				{{{ Lang::get("subject.subject_list") }}}
				<div class="pull-right">
					<a href="{{{ URL::to('subject/create') }}}" class="btn btn-sm  btn-primary"><span class="glyphicon glyphicon-plus-sign"></span>  {{ Lang::get('subject.create_new_subject') }}</a>
				</div>
            </h2>
        </div>

        <div class="col-md-12">
            <label class="control-label col-md-2">Filter by Classification</label>
            <div class="col-md-3">
                <select class="form-control" name="classification_id" id="classification_id">
                    <option type="text" name="0" id="" value=""></option>
                        @foreach($classification_list as $classification)                                
                                <option type="text" name="classification_id" id="classification_id" value="{{{$classification->id}}}">{{$classification->classification_name}}</option>                                                           
                        @endforeach
                </select>
            </div>            
        </div>
        <br/>
        <br/>        
<!-- Table Section -->
    <table id="SubjectFilter" class="table table-striped table-hover">
        <thead>
            <tr>
					<th> {{ Lang::get("subject.classification") }}</th>
                    <th> {{ Lang::get("subject.classification_level") }}</th>
					<th> {{ Lang::get("subject.code") }}</th>
                    <th> {{ Lang::get("subject.name") }}</th>
					<!-- <th> {{ Lang::get("subject.is_pace") }}</th> -->
					<!-- <th> {{ Lang::get("subject.credit_unit") }}</th> -->
					<!-- <th> {{ Lang::get("subject.hour_unit") }}</th> -->
					<th> {{ Lang::get("form.action") }}</th>
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

                SubjectFilter = $('#SubjectFilter').dataTable( {
                    "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
                    "sPaginationType": "bootstrap",
                    "bProcessing": true,
                    "bServerSide": true,
                    "bStateSave": true,
                    "sAjaxSource": "{{ URL::to('subject/data/') }}",
                    "fnDrawCallback": function ( oSettings ) {
                    },

                    "fnServerParams": function(aoData){
                        aoData.push(
                            { "name":"classification_id", "value": $("#classification_id").val() }
                        );
                    }
                });

              

                $("#classification_id").change(function(){
                   SubjectFilter.fnDraw();
                });

                                
            });
        </script>
@stop