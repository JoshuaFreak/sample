@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
Chat Monitoring :: @parent
@stop
{{-- Content --}}
@section('content')
<link href="{{asset('assets/site/css/glyphicon.design.css')}}" rel="stylesheet">
</style>

<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">    
        <ul class="nav" id="side-menu"> 
          @include('admin_sidebar')
        </ul>
    </div>
</div>
<div id="page-wrapper">
    <div class="row"><br>
        <div class="page-header">
          <h2>Online Users</h2>
        </div>
        <div class="row">
            <div class="col-md-12" id="online">
            </div>
        </div>
        <br/>
    </div>
    <br/><br/>
</div>
@stop
@section('scripts')
    <script type="text/javascript">

            
        $(function(){

            $.ajax({
                    url:'{{{ URL::to("admin/onlineTeacherDataJson") }}}',
                    type: "GET",
                    dataType: "json",
                    async:false,
                    success: function (data)
                    {
                            $("#online").empty();
                            $.map(data, function (item)
                            {
                                    $("#online").append('<div class="col-md-6">'

                                        +'<h4><b>'+item.name+'</b> - <span style="color:#00A400">'+item.first_name+' '+item.middle_name+' '+item.last_name+'</span></h4>'
                                    +'</div>');
                            });
                    }
                });

            setInterval(function() {

                $.ajax({
                    url:'{{{ URL::to("admin/onlineTeacherDataJson") }}}',
                    type: "GET",
                    dataType: "json",
                    async:false,
                    success: function (data)
                    {
                            $("#online").empty();
                            $.map(data, function (item)
                            {
                                    $("#online").append('<div class="col-md-6">'

                                        +'<h4><b>'+item.name+'</b> - <span style="color:#00A400">'+item.first_name+' '+item.middle_name+' '+item.last_name+'</span></h4>'
                                    +'</div>');
                            });
                    }
                });
                
            }, 5000);
        })
    </script>
@stop