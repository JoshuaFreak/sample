@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
Chat Monitoring :: @parent
@stop
{{-- Content --}}
@section('content')
<link href="{{asset('assets/site/css/glyphicon.design.css')}}" rel="stylesheet">
<link href="{{asset('assets/site/css/chat.css')}}" rel="stylesheet">
<style type="text/css">
#chat_message_container
{
    height: 550px !important;
}

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
          <h2>Chat Monitoring</h2>
        </div>
        <div class="row">
            <div class="col-md-1"><label class="control-label" >{{{ Lang::get("teacher.teacher") }}}</label>
            </div>
            <div class="col-md-4">
                <select class="form-control" id="teacher_id" name="teacher_id">
                    <option value="0"></option>
                    @foreach($teacher_list as $teacher)
                        <option value="{{$teacher -> id}}">{{ $teacher -> first_name." ".$teacher -> last_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <br/>
        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
        <input type="hidden" id="teacher_id" value="0" />
        <input type="hidden" id="guardian_id" value="0" />
        <input type="hidden" id="is_guardian" value="0" />
        <div class="col-md-12">
               <div class="col-md-4" id="guardian_container" style="padding-left:0px;padding-right:0px; ">
                    <div id="guardian_container_search"clas="col-md-12" style="background-color: #F6F7F9;height: 45px;margin:10px;padding: 7px;">
                        <div class="col-md-12" style="padding:0px;">
                            <div class="col-md-1" style="padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:4px;color: #CCCCCC;">
                                <span class="glyphicon glyphicon-search" style="font-size: 1.8em;margin-left: -7px;"></span>
                            </div>
                            <div id="search_text" class="col-md-11" style="padding:0px;">
                                <input type="text" class="form-control input-sm" placeholder="Search Name"></input>
                            </div>
                        </div>
                    </div>
                    <div id="guardian_main_con">
                    </div>
               </div>
               <div class="col-md-8" id="message_property">
                    <div id="chat_message_container"clas="col-md-12">
                        
                    </div>

                  <!--   <div clas="col-md-12" style="background-color: #fff;color: #fff;height: 54px;">
                        <div class="form-group">
                            <textarea placeholder="Type a message here!" class="form-control" rows="2" id="message_text_area"></textarea>
                        </div>
                    </div>
                    <div clas="col-md-12" style="background-color: #BFC0C1;color: #fff;height: 40px;">
                        <div>
                            <button type="button" class="btn" id="attach_file"><span class="glyphicon glyphicon-paperclip" style="font-size:21px;color:#14335F;text-shadow:0px 0px 0px #000;"></span></button>
                        </div>
                    </div> -->
               </div>
                <div class="upload">
                        <input type="file" id="uploadfile" /> 
                        <p id="upload" class="hidden"><label>Drag & drop not supported, but you can still upload via this input field:<br><input type="file"></label></p>
                        <p id="filereader">File API & FileReader API not supported</p>
                        <p id="formdata">XHR2's FormData is not supported</p>
                        <p id="progress">XHR2's upload progress isn't supported</p>
                        <p>Upload progress: <progress id="uploadprogress" min="0" max="100" value="0">0</progress></p>
                </div>
        </div>
    </div>
    <br/><br/>
</div>
@stop
@section('scripts')
<script type="text/javascript">
$(function(){

            // $("#attach_file").click(function () {
            //     $("#uploadfile").click();
            // });

            $("input:file").change(function (){
               var fileName = $(this).val();
               // $(".filename").html(fileName);
               $("#no_message_error").remove();
               $container.append(''
                                +'<div class="col-md-12 chat_sender">'
                                    +'<div class="col-md-2"></div>'
                                    +'<div class="col-md-10">'
                                        +'<div>'+fileName+'</div>'
                                    +'</div>'
                                +'</div>');

            });

            // loadMessage(0,0);
        $("#teacher_id").change(function(){

            $("#teacher_id").val($(this).val());
            $("#guardian_id").val(0);
            $("#chat_message_container").empty();

            $.ajax({
                    url:'{{{ URL::to("chat_message/getGuardianDataJson") }}}',
                    data:
                        {
                            'teacher_id': $(this).val(),
                        },
                    type: "GET",
                    dataType: "json",
                    async:false,
                    success: function (data)
                    {
                            $("#guardian_main_con").empty();
                            var count = 0;
                            $.map(data, function (item)
                            {
                                    $("#guardian_main_con").append(''
                                            +'<div id="guardian_container_list "class="col-md-12 chat_enactive guardian_container_list" data-id="'+item.id+'">'
                                                +'<div class="col-md-12">'
                                                    +'<div class="col-md-3 guardian_container_first">'
                                                        +'<p class="glyphicon glyphicon-user icon"></p>'
                                                    +'</div>'
                                                    +'<div class="col-md-9 guardian_container_second">'
                                                        +'<div>'
                                                            +'<h4>'+item.name+'</h4>'
                                                        +'</div>'
                                                    +'</div>'
                                                +'</div>'
                                            +'</div>');
                            });
                    }
            });
            $(".guardian_container_list").on("click", function() {

                teacher_id = $("#teacher_id").val(); 
                guardian_id = $("#guardian_id").val(); 
                id = $(this).data('id');
                is_guardian = $("#is_guardian").val();
                
                if(is_guardian == 1)
                {
                    loadMessage(guardian_id,id);
                    $("#teacher_id").val(id);
                }
                else
                {
                    loadMessage(id,teacher_id);
                    $("#guardian_id").val(id); 
                }

                $(".guardian_container_list").each(function(){
                    $(this).removeClass("chat_active");
                });

                $(this).removeClass("chat_enactive");
                $(this).addClass("chat_active");
                
            
            $container = $("#chat_message_container");
            $container.animate({ scrollTop: $container[0].scrollHeight }, "fast");
                
            });

                    setInterval(function()
                    {   
                        teacher_id = $("#teacher_id").val(); 
                        guardian_id = $("#guardian_id").val(); 

                        loadMessage(guardian_id,teacher_id);
                    }, 3000);
            

            $container = $("#chat_message_container");
            $container[0].scrollTop = $container[0].scrollHeight;
            $("#message_text_area").focus();

            // $("#message_text_area").keypress(function (e) {
            //     if (e.keyCode == 13 && e.shiftKey)
            //     {
            //     }
            //     else if(e.which == 13) {
            //         e.preventDefault();
            //         $container = $("#chat_message_container");
            //         value = e.target.value.replace(/\r?\n/g,'<br/>');

            //         if(value != "")
            //         {   
            //             $("#no_message_error").remove();
            //             teacher_id = $("#teacher_id").val(); 
            //             guardian_id = $("#guardian_id").val(); 

            //             $.ajax({

            //                  url:'{{{URL::to("chat_message/postCreate")}}}',
            //                  type:'post',
            //                  data:
            //                      {

            //                          'guardian_id': guardian_id,
            //                          'teacher_id': teacher_id,
            //                          'message': value,
            //                          '_token': $("input[name=_token]").val(),

            //                      },
            //                  async:false,
            //                  success: function (data) {
            //                 }
            //             });

            //             $container.append(''
            //                     +'<div class="col-md-12 chat_sender">'
            //                         +'<div class="col-md-2"></div>'
            //                         +'<div class="col-md-10">'
            //                             +'<div>'+value+'</div>'
            //                         +'</div>'
            //                     +'</div>');
            //             $container.animate({ scrollTop: $container[0].scrollHeight }, "slow");

            //             $("#message_text_area").val('');
            //         }
            //         // $("#message_text_area").focus();
            //     }      
            //     else
            //     {

            //     }        
            // });

        });
});
function loadMessage(guardian_id,teacher_id)
{
        var guardian_id = $("#guardian_id").val();
        if(guardian_id != 0)
        {

            $.ajax({
                    url:'{{{ URL::to("chat_message/dataJson") }}}',
                    data:
                        {
                            'teacher_id': teacher_id,
                            'guardian_id': guardian_id,
                        },
                    type: "GET",
                    dataType: "json",
                    async:false,
                    success: function (data)
                    {        
                            
                            $("#chat_message_container").empty();
                            if(data == "")
                            {
                                 $("#chat_message_container").append(''
                                        +'<div class="col-md-12 no_message" id="no_message_error">'
                                            +'<div class="col-md-12" align="center">'
                                                +'<div>No Messages Yet!</div>'
                                            +'</div>'
                                        +'</div>');
                            }
                            var count = 0;

                            is_guardian = $("#is_guardian").val();

                            $.map(data, function (item)
                            {
                                var str = item.message;
                                var file_name = str.substr(str.lastIndexOf("/") + 1);

                                if(is_guardian == 1)
                                {
                                    if(item.is_sender == 2)
                                    {
                                        if(item.is_file == 1)
                                        {
                                            if(item.is_image == 1)
                                            {
                                                $("#chat_message_container").append(''
                                                        +'<div class="col-md-12 chat_sender">'
                                                            +'<div class="col-md-2"></div>'
                                                            +'<div class="col-md-10">'
                                                                +'<div><a download href="'+item.message+'" target="_blank"><image class="image_chat_hover" width="50px" src="'+item.message+'"/></a></div>'
                                                            +'</div>'
                                                        +'</div>');
                                            }
                                            else
                                            {
                                                 $("#chat_message_container").append(''
                                                        +'<div class="col-md-12 chat_sender">'
                                                            +'<div class="col-md-2"></div>'
                                                            +'<div class="col-md-10">'
                                                                +'<div><a download href="'+item.message+'" target="_blank">'+file_name+'</a></div>'
                                                            +'</div>'
                                                        +'</div>');
                                            }
                                        }
                                        else
                                        {
                                            $("#chat_message_container").append(''
                                                    +'<div class="col-md-12 chat_sender">'
                                                        +'<div class="col-md-2"></div>'
                                                        +'<div class="col-md-10">'
                                                            +'<div>'+item.message+'</div>'
                                                        +'</div>'
                                                    +'</div>');
                                        }
                                        $("#chat_message_container").append('<div style="color:#CCCCCC;font-size:.8em;float:right;margin-right:15px">'+item.date_time+'</div>');
                                    }
                                    if(item.is_sender == 1)
                                    {   
                                        if(item.is_file == 1)
                                        {       
                                            if(item.is_image == 1)
                                            {
                                                $("#chat_message_container").append(''
                                                        +'<div class="col-md-12 chat_receiver">'
                                                            +'<div class="col-md-10">'
                                                                +'<div><a download href="'+item.message+'" target="_blank"><image class="image_chat_hover" width="50px" src="'+item.message+'"/></a></div>'
                                                            +'</div>'
                                                            +'<div class="col-md-2"></div>'
                                                        +'</div>');

                                            }
                                            else
                                            {
                                                 $("#chat_message_container").append(''
                                                        +'<div class="col-md-12 chat_receiver">'
                                                            +'<div class="col-md-10">'
                                                                +'<div><a download href="'+item.message+'" target="_blank">'+file_name+'</a></div>'
                                                            +'</div>'
                                                            +'<div class="col-md-2"></div>'
                                                        +'</div>');
                                            }
                                        }
                                        else
                                        {
                                            $("#chat_message_container").append(''
                                                    +'<div class="col-md-12 chat_receiver">'
                                                        +'<div class="col-md-10">'
                                                            +'<div>'+item.message+'</div>'
                                                        +'</div>'
                                                        +'<div class="col-md-2"></div>'
                                                    +'</div>'); 
                                        }
                                        $("#chat_message_container").append('<div style="color:#CCCCCC;font-size:.8em;float:left;margin-left:15px">'+item.date_time+'</div>');
                                        
                                    }
                                }
                                else
                                {
                                    if(item.is_sender == 1)
                                    {
                                        if(item.is_file == 1)
                                        {

                                            if(item.is_image == 1)
                                            {
                                                    $("#chat_message_container").append(''
                                                            +'<div class="col-md-12 chat_sender">'
                                                                +'<div class="col-md-2"></div>'
                                                                +'<div class="col-md-10">'
                                                                    +'<div><a download href="'+item.message+'" target="_blank"><image class="image_chat_hover" width="50px" src="'+item.message+'"/></a></div>'
                                                                +'</div>'
                                                            +'</div>');
                                            }   
                                            else
                                            {
                                                
                                                 $("#chat_message_container").append(''
                                                        +'<div class="col-md-12 chat_sender">'
                                                            +'<div class="col-md-2"></div>'
                                                            +'<div class="col-md-10">'
                                                                +'<div><a download href="'+item.message+'" target="_blank">'+file_name+'</a></div>'
                                                            +'</div>'
                                                        +'</div>');
                                            }
                                        }
                                        else
                                        {
                                            $("#chat_message_container").append(''
                                                    +'<div class="col-md-12 chat_sender">'
                                                        +'<div class="col-md-2"></div>'
                                                        +'<div class="col-md-10">'
                                                            +'<div>'+item.message+'</div>'
                                                        +'</div>'
                                                    +'</div>');
                                        }
                                        $("#chat_message_container").append('<div style="color:#CCCCCC;font-size:.8em;float:right;margin-right:15px">'+item.date_time+'</div>');
                                        
                                    }
                                    if(item.is_sender == 2)
                                    {
                                        if(item.is_file == 1)
                                        {
                                            if(item.is_image == 1)
                                            {
                                                $("#chat_message_container").append(''
                                                        +'<div class="col-md-12 chat_receiver">'
                                                            +'<div class="col-md-10">'
                                                                +'<div><a download href="'+item.message+'" target="_blank"><image class="image_chat_hover" width="50px" src="'+item.message+'"/></a>  </div>'
                                                            +'</div>'
                                                            +'<div class="col-md-2"></div>'
                                                        +'</div>')
                                            }
                                            else
                                            {
                                                 $("#chat_message_container").append(''
                                                        +'<div class="col-md-12 chat_receiver">'
                                                            +'<div class="col-md-10">'
                                                                +'<div><a download href="'+item.message+'" target="_blank">'+file_name+'</a></div>'
                                                            +'</div>'
                                                            +'<div class="col-md-2"></div>'
                                                        +'</div>');
                                            }
                                        }
                                        else
                                        {
                                            $("#chat_message_container").append(''
                                                    +'<div class="col-md-12 chat_receiver">'
                                                        +'<div class="col-md-10">'
                                                            +'<div>'+item.message+'</div>'
                                                        +'</div>'
                                                        +'<div class="col-md-2"></div>'
                                                    +'</div>');
                                        }
                                        $("#chat_message_container").append('<div style="color:#CCCCCC;font-size:.8em;float:left;margin-left:15px">'+item.date_time+'</div>');

                                    }
                                }
                            });
                     }
            });
    }

}
</script>
@stop