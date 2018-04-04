<li style="text-align:center">
    <a {{ (Request::is('#') ? ' class=active' : '') }}><img src="{{{ asset('assets/site/images/SCHEDULER.png') }}}" alt="img" style="width:150px; height:150px;"></a>
    <!-- <p><b style="color:#2a3088">SCHEDULER</b></p> -->
</li>
<br>
<li>
    <a href="{{URL::to('module/')}}" class="sidebartext">
    <i class="fa fa-dashboard fa-fw"></i><span class="hidden-sm text"> Dashboard</span></a>
</li>
<li>
    <a href="{{URL::to('scheduler/')}}" class="sidebartext">
    <i class="fa fa-th-large fa-fw"></i><span class="hidden-sm text"> Scheduler Dashboard</span></a>
</li>
<li>
    <a href="{{URL::to('scheduler/create_schedule')}}" target="_blank" class="sidebartext">
    <i class="fa fa-calendar fa-fw"></i><span class="hidden-sm text"> Student Schedule</span></a>
</li>
<li>
    <a href="{{URL::to('scheduler/create')}}" target="_blank" class="sidebartext">
    <i class="fa fa-calendar fa-fw"></i><span class="hidden-sm text"> Teacher Schedule</span></a>
</li>
<li>
    <a href="{{URL::to('scheduler/group_class')}}" target="_blank" class="sidebartext">
    <i class="fa fa-calendar fa-fw"></i><span class="hidden-sm text"> Group Class Schedule</span></a>
</li>
<li>
    <a href="{{URL::to('scheduler/activity_class')}}" target="_blank" class="sidebartext">
    <i class="fa fa-calendar fa-fw"></i><span class="hidden-sm text"> Activity Class Schedule</span></a>
</li>
<li>
    <a href="{{URL::to('scheduler/teacher_report')}}" target="_blank" class="sidebartext">
    <i class="fa fa-calendar fa-fw"></i><span class="hidden-sm text"> Teacher Schedule Report</span></a>
</li>
<li>
    <a href="#" class="sidebartext" ><i class="glyphicon-sidebar glyphicon glyphicon-tasks fa-fw"></i><span class="hidden-sm text"> Rooms Data</span><span class="fa arrow"></span></a>
    <ul class="nav nav-second-level">
        <li>
            <a href="{{URL::to('scheduler/books_per_room_1on1')}}" class="sidebartext">
            <i class="fa fa-book fa-fw"></i><span class="hidden-sm text"> Room Books(ESL 1:1)</span></a>
        </li>
        <li>
            <a href="{{URL::to('scheduler/ieltsBook1on1')}}" class="sidebartext">
            <i class="fa fa-book fa-fw"></i><span class="hidden-sm text"> Room Books(IELTS 1:1)</span></a>
        </li>
        <li>
            <a href="{{URL::to('scheduler/toeicBook1on1')}}" class="sidebartext">
            <i class="fa fa-book fa-fw"></i><span class="hidden-sm text"> Room Books(TOEIC 1:1)</span></a>
        </li>
        <li>
            <a href="{{URL::to('scheduler/businessBook1on1')}}" class="sidebartext">
            <i class="fa fa-book fa-fw"></i><span class="hidden-sm text"> Room Books(Business 1:1)</span></a>
        </li>
        <li>
            <a href="{{URL::to('scheduler/workingBook1on1')}}" class="sidebartext">
            <i class="fa fa-book fa-fw"></i><span class="hidden-sm text"> Room Books(Working 1:1)</span></a>
        </li>
        <li>
            <a href="{{URL::to('scheduler/books_per_room')}}" class="sidebartext">
            <i class="fa fa-book fa-fw"></i><span class="hidden-sm text"> Room Books(Group)</span></a>
        </li>
        <li>
            <a href="{{URL::to('scheduler/teachers_per_room')}}" class="sidebartext">
            <i class="fa fa-user fa-fw"></i><span class="hidden-sm text"> Teachers Per Room</span></a>
        </li>
    </ul>
</li>
<!-- <li>
    <a href="{{URL::to('scheduler/men_to_men')}}" target="_blank" class="sidebartext">
    <i class="fa fa-calendar fa-fw"></i><span class="hidden-sm text"> Man to Man Schedule</span></a>
</li> -->
@if($gen_role -> name == "Scheduler" || $gen_role -> name == "Admin")
<li>
    <a href="#" class="sidebartext" ><i class="glyphicon-sidebar glyphicon glyphicon-tasks fa-fw"></i><span class="hidden-sm text"> Manage</span><span class="fa arrow"></span></a>
    <ul class="nav nav-second-level">
        <li>
            <a href="{{URL::to('room/')}}" class="sidebartext">
            <i class="fa fa-dashboard fa-fw"></i><span class="hidden-sm text"> Room List</span></a>
        </li>
        <li>
            <a href="{{URL::to('campus/')}}" class="sidebartext">
            <i class="fa fa-dashboard fa-fw"></i><span class="hidden-sm text"> Campus List</span></a>
        </li>
        <li>
            <a href="{{URL::to('teacher_subject/')}}" class="sidebartext">
            <i class="fa fa-dashboard fa-fw"></i><span class="hidden-sm text"> Teacher Subjects</span></a>
        </li>
    </ul>
</li>
<li>
    <a href="{{URL::to('scheduler/import_class')}}" target="_blank" class="sidebartext">
    <i class="fa fa-arrow-up fa-fw"></i><span class="hidden-sm text"> Import Class Schedule</span></a>
</li>
@endif