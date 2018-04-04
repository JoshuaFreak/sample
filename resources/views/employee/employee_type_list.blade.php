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
<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
<input type="hidden" id="employee_type_id" value="{{ $_GET['id'] }}">
<input type="hidden" id="filter" value="{{ $_GET['filter'] }}">
<div id="page-wrapper">
    <div class="row">
<!-- Content Header -->
        <div class="page-header"><br>
            <h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left">  
                @if($_GET['filter'] == 'resigned_employee')
                    Resigned Employee/s
                @elseif($id == 0)
                    All Employee
                @elseif($_GET['filter'] == 'al')
                    Academic Leaders
                @elseif($_GET['filter'] == 'as')  
                    Academic Support  
                @else            
                    {{ $employee_type -> employee_type_name }}
                @endif
            </h3>
        </div>
        <!-- <div class="col-md-12">
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
        </div> -->
        <br/>

        
<!-- Table Section -->
    <table id="EmployeeFilter" class="table table-striped table-hover">
        <thead>
            <tr>
                    <!-- <th> {{ Lang::get("employee.employee_no") }}</th> -->
                    <th> </th>
                    <th> {{ Lang::get("employee.employee_name") }}</th>
                    <th> {{ Lang::get("employee.nickname") }}</th>
                    <th> {{ Lang::get("employee.contact_no") }}</th>
                    <th> {{ Lang::get("employee.birthdate") }}</th>
                    <th> {{ Lang::get("employee.address") }}</th>
                    <th> {{ Lang::get("employee.civil_status") }}</th>
                    <th> {{ Lang::get("employee.position") }}</th>
                    <th> {{ Lang::get("employee.employment_status") }}</th>
                    <th> {{ Lang::get("employee.department") }}</th>
                    <!-- <th> {{ Lang::get("employee.role_name") }}</th>
                    <th> {{ Lang::get("employee.is_active") }}</th> -->
                    @if($_GET['filter'] == 'resigned_employee')
                    <th> Active</th>
                    @else
                    <th> {{ Lang::get("form.action") }}</th>
                    @endif
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    </div>
</div>
  {{-- Scripts --}}
    @section('scripts')

        @if($_GET['filter'] == 'resigned_employee')
        <script type="text/javascript">
            $(document).ready(function() {

                EmployeeFilter = $('#EmployeeFilter').DataTable( {
                    "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
                    "sPaginationType": "bootstrap",
                    "bProcessing": true,
                    "bServerSide": true,
                    // "bStateSave": true,
                    "fnDrawCallback": function ( oSettings ) {                    

                            $(".active").each(function(){
                                var id = $(this).data('id');
                                if($("this").val() == 1)
                                {
                                    $('is_active_'+id).bootstrapToggle('on');
                                }
                                else
                                {
                                    $('#is_active_'+id).bootstrapToggle('off');
                                }
                            }); 


                            $(".is_active").change(function(){
                                id = $(this).data('id');
                                $.ajax({
                                    url:'{{{ URL::to("employee/set_to_active") }}}',
                                    type:'POST',
                                    data: {
                                        'employee_id' : id,
                                        '_token' : $("input[name=_token]").val(),
                                    },
                                    dataType: "json",
                                    async:false,
                                    success: function (data) 
                                    {        
                                        swal('Employee set as Active!');    
                                        location.reload();
                                    }  
                                });
                            });

                            // $(".is_active").change
                    },
                    "sAjaxSource": "{{ URL::to('employee/data?id=') }}"+$("#employee_type_id").val()+"&filter="+$("#filter").val(),
                    "columns": [
                        {data: 'img', name: 'img'},
                        {data: 'last_name', name: 'last_name'},
                        {data: 'nickname', name: 'nickname'},
                        {data: 'contact_no', name: 'contact_no'},
                        {data: 'birthdate', name: 'birthdate'},
                        {data: 'address', name: 'address'},
                        {data: 'civil_status_name', name: 'civil_status_name'},
                        {data: 'position_name', name: 'position_name', searchable:true},
                        {data: 'employment_status_name', name: 'employment_status_name'},
                        {data: 'department_name', name: 'department_name'},
                        {data: 'is_active', name: 'is_active'},
                    ],
                    order: [[1, 'asc']]
                });  

            });
        </script>
        @else
            <script type="text/javascript">
            $(document).ready(function() {

                EmployeeFilter = $('#EmployeeFilter').DataTable( {
                    "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
                    "sPaginationType": "bootstrap",
                    "bProcessing": true,
                    "bServerSide": true,
                    // "bStateSave": true,
                    "sAjaxSource": "{{ URL::to('employee/data?id=') }}"+$("#employee_type_id").val()+"&filter="+$("#filter").val(),
                    "columns": [
                        {data: 'img', name: 'img'},
                        {data: 'last_name', name: 'last_name'},
                        {data: 'nickname', name: 'nickname'},
                        {data: 'contact_no', name: 'contact_no'},
                        {data: 'birthdate', name: 'birthdate'},
                        {data: 'address', name: 'address'},
                        {data: 'civil_status_name', name: 'civil_status_name'},
                        {data: 'position_name', name: 'position_name', searchable:true},
                        {data: 'employment_status_name', name: 'employment_status_name'},
                        {data: 'department_name', name: 'department_name'},
                        {data: 'action', name: 'action'},
                    ],
                    order: [[1, 'asc']]
                });  

            });
        </script>
        @endif

        @stop
        </div>
    </div>
</div>
@stop