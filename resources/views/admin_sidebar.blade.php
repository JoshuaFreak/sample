<li style="text-align:center">
    <a href="{{URL::to('module')}}" {{ (Request::is('#') ? ' class=active' : '') }}><img src="{{{ asset('assets/site/images/ADMIN_HOVER.png') }}}" alt="img" style="width:150px; height:150px;"></a>
    <p><b style="color:#2a3088">ADMINISTRATION</b></p>
</li>
<br>

<li>
    <a href="{{URL::to('module')}}" class="sidebartext">
    <i class="fa fa-dashboard fa-fw"></i><span class="hidden-sm text"></span></a>
</li>


<!-- <li>
    <a href="{{URL::to('admin/events')}}"  {!! (Request::is('admin/events*') ? ' class="active sidebartext" ' : ' class="sidebartext" ') !!}>
    <i class="glyphicon-sidebar glyphicon glyphicon-tasks"></i><span class="hidden-sm text"> Events</span></a>
</li> -->




<!-- <li>
    <a href="{{URL::to('admin_report/students_master_list')}}" class="sidebartext" {{ (Request::is('gen_role*') ? ' class=active' : '') }}>
    <i class="glyphicon-sidebar glyphicon glyphicon-tasks"></i><span class="hidden-sm text"> Student Master List</span></a>
</li>
<li>
    <a href="{{URL::to('admin/chat_monitoring')}}" class="sidebartext" {{ (Request::is('gen_role*') ? ' class=active' : '') }}>
    <i class="glyphicon-sidebar glyphicon glyphicon-tasks"></i><span class="hidden-sm text"> Chat Monitoring</span></a>
</li>
<li>
    <a href="{{URL::to('admin/online_teacher')}}" class="sidebartext" {{ (Request::is('gen_role*') ? ' class=active' : '') }}>
    <i class="glyphicon-sidebar glyphicon glyphicon-tasks"></i><span class="hidden-sm text"> Online Users</span></a>
</li> -->