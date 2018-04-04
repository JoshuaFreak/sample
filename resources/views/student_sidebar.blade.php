<li style="text-align:center">
    <a {{ (Request::is('#') ? ' class=active' : '') }}><img src="{{{ asset('assets/site/images/STUDENT.png') }}}" alt="img" style="width:150px; height:150px;"></a>
    <!-- <p><b style="color:#2a3088">SCHEDULER</b></p> -->
</li>
<br>
<li>
    <a href="{{URL::to('module/')}}" class="sidebartext">
    <i class="fa fa-dashboard fa-fw"></i><span class="hidden-sm text"> Dashboard</span></a>
</li>
<li>
    <a href="{{URL::to('student/list')}}" class="sidebartext">
    <i class="fa fa-dashboard fa-fw"></i><span class="hidden-sm text"> Student List</span></a>
</li>
<li>
    <a href="{{URL::to('student/')}}" class="sidebartext">
    <i class="fa fa-calendar fa-fw"></i><span class="hidden-sm text"> Student's Schedule</span></a>
</li>
<li>
    <a href="{{URL::to('student/studentToArrive/')}}" class="sidebartext">
    <i class="fa fa-suitcase fa-fw"></i><span class="hidden-sm text"> Students to Arrive</span></a>
</li>
<li>
    <a href="{{URL::to('student/studentToDepart/')}}" class="sidebartext">
    <i class="fa fa-plane fa-fw"></i><span class="hidden-sm text"> Students to Depart</span></a>
</li>