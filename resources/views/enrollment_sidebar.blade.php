<br/>
<li>
    <a href="#" class="sidebartext" ><i class="glyphicon-sidebar glyphicon glyphicon-tasks"></i><span class="hidden-sm text"> Enrollment</span><span class="fa arrow"></span></a>
    <ul class="nav nav-second-level">
    
         <li>
            <a href="{{URL::to('register')}}" class="sidebartext" {{ (Request::is('#') ? ' class=active' : '') }}> Register Student</a>
        </li>
        <li>
            <a href="{{URL::to('register/import')}}" class="sidebartext" {{ (Request::is('#') ? ' class=active' : '') }}> Import Student</a>
        </li>
    </ul>
</li>