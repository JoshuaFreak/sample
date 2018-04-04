@extends('site.layouts.default_full_page')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("student.delete_student") }}} :: @parent
@stop

{{-- Content --}}
@section('content')
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">    
        <ul class="nav" id="side-menu"> 
            @include('enrollment_sidebar')
        </ul>
    </div>
</div>
<div id="page-wrapper">
    <div class="row">
        <div class="page-header">
            <h3> {{{ Lang::get("student.delete_student") }}}  
                <div class="pull-right">
                    <div class="pull-right">
                        <a href="{{{ URL::to('enroll_student') }}}" class="btn btn-sm  btn-primary iframe"><span class="glyphicon glyphicon-list"></span> {{ Lang::get("student.student_list") }}</a>
                    </div>
                </div>
            </h3>
        </div>
        <div>
            {{ Lang::get("student.delete_message") }}
        </div>
        <form class="form-horizontal" method="post" action="{{ URL::to('enroll_student/' . $enrollment->id . '/delete') }}" autocomplete="off">
            <input type="hidden" name="id" value="{{ $enrollment->id }}" />   
            @include('enroll_student.form')

            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-md-3 control-label" for="actions">&nbsp;</label>
                    <div class="col-md-9">  
                        <a href="{{{ URL::to('enroll_student') }}}" class="btn btn-sm btn-warning close_popup">
                            <span class="glyphicon glyphicon-ban-circle"></span>  {{ Lang::get("form.cancel") }}
                        </a>
                        <button type="submit" class="btn btn-sm btn-danger">
                            <span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}
                        </button>   
                    </div>
                </div>
            </div>  
        </form>
    </div>
</div>  
@stop
@section('scripts')
<script type="text/javascript">
    $(function() {
        $("#roles").select2()
    });

    //term
    // $.ajax({
    //     url:'{{{URL::to("term/dataJson")}}}',
    //     type:'GET',
    //     data:
    //         {  
    //             'classification_id': $("#classification_id").val(),
    //         },
    //     dataType: "json",
    //     async:false,
    //     success: function (data) 
    //     {    
            
    //         $("#term_id").empty();
    //         $("#term_id").append('<option value=""></option>');
    //         $.map(data, function (item) 
    //         {      
    //                 $("#term_id").append('<option value="'+item.value+'">'+item.text+'</option>');
    //         });
    //     }  

    // });
       
    $("#term_id [value={{{ $term->id }}}]").attr("selected","selected");

    //classification_level
    $.ajax({
        url:'{{{URL::to("classification_level/dataJson")}}}',
        type:'GET',
        data:
            {  
                'classification_id': $("#classification_id").val(),
            },
        dataType: "json",
        async:false,
        success: function (data) 
        {    
            
            $("#classification_level_id").empty();
            $("#classification_level_id").append('<option value=""></option>');
            $.map(data, function (item) 
            {      
                    $("#classification_level_id").append('<option value="'+item.value+'">'+item.text+'</option>');
            });
        }  

    });

    $("#classification_level_id [value={{{ $classification_level->id }}}]").attr("selected","selected");

    //section
    $.ajax({
        url:'{{{URL::to("section/dataJson")}}}',
        type:'GET',
        data:
            {  
                'classification_level_id': $("#classification_level_id").val(),
            },
        dataType: "json",
        async:false,
        success: function (data) 
        {    
            
            $("#section_id").empty();
            $("#section_id").append('<option value=""></option>');
            $.map(data, function (item) 
            {      
                    $("#section_id").append('<option value="'+item.value+'">'+item.text+'</option>');
            });
        }  

    });

    $("#section_id [value={{{ $section->id }}}]").attr("selected","selected");
</script>
@stop