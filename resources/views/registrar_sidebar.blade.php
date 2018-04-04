<li style="text-align:center">
    <a {{ (Request::is('#') ? ' class=active' : '') }}><img src="{{{ asset('assets/site/images/REGISTRAR.png') }}}" alt="img" style="width:150px; height:150px;"></a>
    <!-- <p><b style="color:#2a3088">SCHEDULER</b></p> -->
</li>
<br>
<li>
    <a href="{{URL::to('module/')}}" class="sidebartext">
    <i class="fa fa-dashboard fa-fw"></i><span class="hidden-sm text"> Dashboard</span></a>
</li>
<li>
    <a href="#" class="sidebartext" ><i class="glyphicon-sidebar glyphicon glyphicon-tasks"></i><span class="hidden-sm text"> Enrollment</span><span class="fa arrow"></span></a>
    <ul class="nav nav-second-level">
    
         <li>
            <a href="{{URL::to('register')}}" class="sidebartext" {{ (Request::is('#') ? ' class=active' : '') }}> Register Student</a>
        </li>
        <li>
            <a href="{{URL::to('register/import')}}" class="sidebartext" {{ (Request::is('#') ? ' class=active' : '') }}> Import Student</a>
        </li>
        <li>
            <a href="{{URL::to('register/student_list')}}" class="sidebartext" {{ (Request::is('#') ? ' class=active' : '') }}> Student List</a>
        </li>
    </ul>
</li>

<li>
    <a href="#" class="sidebartext" ><i class="glyphicon-sidebar glyphicon glyphicon-tasks"></i><span class="hidden-sm text"> Registrar</span><span class="fa arrow"></span></a>
    <ul class="nav nav-second-level">
        <li>
            <a href="{{URL::to('course/')}}" class="sidebartext" {{ (Request::is('#') ? ' class=active' : '') }}> Subject</a>
        </li>
        <li>
            <a href="{{URL::to('program/')}}" class="sidebartext" {{ (Request::is('#') ? ' class=active' : '') }}> Course</a>
        </li>
        <li>
            <a href="{{URL::to('program_course/')}}" class="sidebartext" {{ (Request::is('#') ? ' class=active' : '') }}> Course Subject</a>
        </li>
        <li>
            <a href="{{URL::to('program_class_capacity')}}" class="sidebartext" {{ (Request::is('#') ? ' class=active' : '') }}> Course Class Capacity</a>
        </li>
        <!-- <li>
            <a href="{{URL::to('percentage_level/')}}" class="sidebartext" {{ (Request::is('#') ? ' class=active' : '') }}> Percentage Level</a>
        </li> -->
     
    </ul>
</li>    
<!-- <li>
    <a href="{{URL::to('scoreEntry')}}" class="sidebartext" {{ (Request::is('#') ? ' class=active' : '') }}><i class="glyphicon-sidebar glyphicon glyphicon-tasks"></i><span class="hidden-sm text"> Score Entry</span></a>
</li> -->
<li>
    <a href="{{URL::to('registrar/import_student_score')}}" class="sidebartext" {{ (Request::is('#') ? ' class=active' : '') }}><i class="glyphicon-sidebar glyphicon glyphicon-tasks"></i><span class="hidden-sm text"> Import Student's Test/Examination</span></a>
</li>
