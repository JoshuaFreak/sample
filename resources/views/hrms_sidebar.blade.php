
<li style="text-align:center">
    <a {{ (Request::is('#') ? ' class=active' : '') }}><img src="{{{ asset('assets/site/images/HRMS.png') }}}" alt="img" style="width:150px; height:150px;"></a>
    <!-- <p><b style="color:#2a3088">SCHEDULER</b></p> -->
</li>
<br>
<li>
    <a href="{{URL::to('module/')}}" class="sidebartext">
    <i class="fa fa-dashboard fa-fw"></i><span class="hidden-sm text"> Dashboard</span></a>
</li>  
<li>
    <a href="{{URL::to('hrms/')}}" class="sidebartext">
    <i class="fa fa-th-large fa-fw"></i><span class="hidden-sm text"> Hrms Dashboard</span></a>
</li>  
<li>
    <a href="{{URL::to('employee/create')}}" class="sidebartext" {{ (Request::is('#') ? ' class=active' : '') }}> 
    <i class="glyphicon glyphicon-plus fa-fw"></i><span class="hidden-sm text" > Add New Employee</span></a>
</li>
<li>
    <a href="{{URL::to('employee/')}}" class="sidebartext" {{ (Request::is('#') ? ' class=active' : '') }}>
     <i class="fa fa-list-alt fa-fw"></i><span class="hidden-sm text" > Employee's Data</span></a>
</li>
<li>
    <a href="#" class="sidebartext" ><i class="glyphicon glyphicon-list fa-fw"></i><span class="hidden-sm text"> Employee List</span><span class="fa arrow"></span></a>
    <ul class="nav nav-second-level">
        <li>
            <a href="{{URL::to('employee/employee_type?id=0&filter=')}}" class="sidebartext" {{ (Request::is('#') ? ' class=active' : '') }}> All Employee</a>
        </li>
        <li>
            <a href="{{URL::to('employee/employee_type?id=0&filter=resigned_employee')}}" class="sidebartext" {{ (Request::is('#') ? ' class=active' : '') }}> Resigned Employee/s</a>
        </li>
        <li>
            <a href="{{URL::to('teacher/')}}" class="sidebartext" {{ (Request::is('#') ? ' class=active' : '') }}> Teacher List</a>
        </li>
        @foreach($employee_type_list as $employee_type)
            @if($employee_type->id != 4 && $employee_type->id != 3)
                    <li>
                        <a href="{{URL::to('employee/employee_type?id='.$employee_type->id.'&filter=')}}" class="sidebartext" {{ (Request::is('#') ? ' class=active' : '') }}> {{$employee_type->employee_type_name}}</a>
                    </li>
            @endif
        @endforeach
        <li>
            <a href="{{URL::to('employee/employee_type?id=3&filter=al')}}" class="sidebartext" {{ (Request::is('#') ? ' class=active' : '') }}> Academic Leaders</a>
        </li>
        <li>
            <a href="{{URL::to('employee/employee_type?id=3&filter=as')}}" class="sidebartext" {{ (Request::is('#') ? ' class=active' : '') }}> Academic Support</a>
        </li>
    </ul>
</li>  
<li>
    <a href="#" class="sidebartext" >
        <i class="glyphicon-sidebar glyphicon glyphicon-tasks  fa-fw"></i><span class="hidden-sm text"> Manage</span><span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level">
    
        <li>
            <a href="{{URL::to('position/')}}" class="sidebartext" {{ (Request::is('#') ? ' class=active' : '') }}>Position List</a>
        </li>
    </ul>
</li>
<li>
    <a href="{{URL::to('gen_user/')}}" class="sidebartext" {{ (Request::is('#') ? ' class=active' : '') }}>
            <i class="glyphicon glyphicon-user fa-fw"></i><span class="hidden-sm text"> User Accounts</span></a>
</li>
