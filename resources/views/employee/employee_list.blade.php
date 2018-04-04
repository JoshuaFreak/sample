@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("employee.employee") }}} :: @parent
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
            
            @include('hrms_sidebar')
            
        </ul>
    </div>
</div>
<div id="page-wrapper">
    <div class="row">
<!-- Content Header -->
        <div class="page-header"><br>
            <h2>
                {{{ Lang::get("employee.employee_list") }}}                

            </h2>
        </div>
        <div class="col-md-12">
            <label class="control-label col-md-2">Filter By</label>
            <div class="col-md-3">
                <select class="form-control" name="filter" id="filter">
                    <option type="text" name="0" id="" value=""></option>
                    <option type="text" name="0" id="" value="GenRole">Position</option>
                    <option type="text" name="0" id="" value="Gender">Gender</option>
                    <option type="text" name="0" id="" value="EmploymentStatus">Employment Status</option>
                    <option type="text" name="0" id="" value="BloodType">Blood Type</option>
                    <option type="text" name="0" id="" value="Citizenship">Citizenship</option>
                    <option type="text" name="0" id="" value="Religion">Religion</option>
                    <option type="text" name="0" id="" value="CivilStatus">Civil Status</option>
                </select>
            </div>  
            <div class="col-md-3">
                <select class="form-control" name="filter_data" id="filter_data">
                    <option type="text" name="0" id="" value=""></option>
                </select>
            </div>            
        </div>
        <br/>
        <br/>

        
<!-- Table Section -->
    <table id="EmployeeFilter" class="table table-striped table-hover">
        <thead>
            <tr>
                    <th> {{ Lang::get("employee.employee_no") }}</th>
                    <th> {{ Lang::get("employee.employee_name") }}</th>
                    <th> {{ Lang::get("employee.role_name") }}</th>
                    <th> {{ Lang::get("employee.is_active") }}</th>
                    <th> {{ Lang::get("form.action") }}</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    </div>
</div>
  {{-- Scripts --}}
    @section('scripts')
        <script type="text/javascript">
            $(":submit").closest("form").submit(function(){
                $(':submit').attr('disabled', 'disabled');
            });

        $("#filter").change(function(){

            $("#filter_data").empty();
            EmployeeFilter.fnDraw();
            var column = $("#filter").val();
            $.ajax({
            url: "{{ URL::to('employee/getFilter') }}",
            data: {
                        'column': column,
                    },
            type: "GET", 
            dataType: "json",
            async:false,
            }).done(function(data) {
                $("#filter_data").append("<option></option>");
                $.each(data,function(key, value)
                {
                    $("#filter_data").append('<option value="'+value.value+'">'+value.text+'</option>');
                });
            });
        });

        $(document).ready(function() {

            EmployeeFilter = $('#EmployeeFilter').dataTable( {
                "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
                "sPaginationType": "bootstrap",
                "bProcessing": true,
                "bServerSide": true,
                "bStateSave": true,
                "sAjaxSource": "{{ URL::to('employee/employee_list/data') }}",
                "fnDrawCallback": function ( oSettings ) {
                },

                "fnServerParams": function(aoData){
                    aoData.push(
                        { "name":"filter_data", "value": $("#filter_data").val() },
                        { "name":"filter", "value": $("#filter").val() }
                    );
                }
            });

          

            $("#filter_data").change(function(){
               EmployeeFilter.fnDraw();
            });


                            
        });
    </script>

        @stop
        </div>
    </div>
</div>
@stop