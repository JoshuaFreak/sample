<li style="text-align:center">
    <a {{ (Request::is('#') ? ' class=active' : '') }}><img src="{{{ asset('assets/site/images/Teacher.png') }}}" alt="img" style="width:150px; height:150px;"></a>
    <!-- <p><b style="color:#2a3088">SCHEDULER</b></p> -->
</li>
<br>
<!-- <li>
    <a href="{{URL::to('module/')}}" class="sidebartext">
    <i class="fa fa-dashboard fa-fw"></i><span class="hidden-sm text"> Dashboard</span></a>
</li> -->
<li>
    <a href="{{URL::to('teacher_portal/')}}" class="sidebartext">
    <i class="fa fa-user fa-fw"></i><span class="hidden-sm text"> Basic Information</span></a>
</li>
<li>
    <a href="{{URL::to('teacher_portal/schedule')}}" class="sidebartext">
    <i class="fa fa-calendar fa-fw"></i><span class="hidden-sm text"> Class Load</span></a>
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
<!-- <li>
    <a href="{{URL::to('teacher_portal/schedule')}}" class="sidebartext">
    <i class="fa fa-calendar fa-fw"></i><span class="hidden-sm text"> Teacher's Info</span></a>
</li> -->