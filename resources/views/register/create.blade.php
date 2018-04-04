@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("register_lang.create_register") }}} :: @parent
@stop

{{-- Content --}}
@section('content')
<div class="navbar-default sidebar" role="navigation">
  <div class="sidebar-nav navbar-collapse">    
      <ul class="nav" id="side-menu"> 
      @include('registrar_sidebar')
    </ul>
  </div>

</div>
<div id="page-wrapper">
    <div class="row">
    	@include('notifications')
		<div class="page-header"><br>
			<h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left">
			{{{ Lang::get("register_lang.create_register") }}}
				<div class="pull-right" style="margin-right: 10px !important">
			            <a href="{{{ URL::to('register') }}}" class="btn btn-sm btn-primary iframe"><span class="glyphicon glyphicon-list"></span> {{ Lang::get("register_lang.register_list") }}</a>
				</div>
			</h3>
		</div>

		<form class="form-horizontal" method="post" action="{{ URL::to('register/create') }}" autocomplete="off">
			@include('register.form')
			<div class="col-md-12">
				<div class="form-group">
					<label class="col-md-3 control-label" for="actions">&nbsp;</label>
					<div class="col-md-9">	
			 			<button type="submit" class="btn btn-sm btn-success">
			                <span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.create") }}
			            </button>	
			            <button type="reset" class="btn btn-sm btn-default">
			                <span class="glyphicon glyphicon-remove-circle"></span>  {{ Lang::get("form.reset") }}
			            </button>	
			 			<a href="{{{ URL::to('register_lang') }}}" class="btn btn-sm btn-warning close_popup">
			                <span class="glyphicon glyphicon-ban-circle"></span>  {{ Lang::get("form.cancel") }}
			       		</a>
			        </div>
			    </div>
			</div>	
		</form>
    </div>
</div>	
@stop
@section('scripts')
<script type="text/javascript">
  $(":submit").closest("form").submit(function(){
                $(':submit').attr('disabled', 'disabled');
            });
	$(function() {
		$("#permission").select2()
	});
	$("#last_name").keyup(function(){
		$("#username").val($("#last_name").val());
	});
</script>
<script type="text/javascript">
	
	$(function () {
        $('#date_enrolled').datepicker({
        	"format": 'yyyy-mm-dd',
        });
        $('#datepicker').datepicker({
	    format: "yyyy-mm-dd",
	    orientation: "top left",
	    autoclose: true,
	    startView: 1,
	    todayHighlight: true,
	    todayBtn: "linked",
	    });

    });

</script>

<script>
var chars = "123456789";
var string_length = 6;
var randomstring = '';
var charCount = 1;
var numCount = 1;

for (var i=1; i<string_length; i++) {
    // If random bit is 0, there are less than 3 digits already saved, and there are not already 5 characters saved, generate a numeric value. 
    if((Math.floor(Math.random() * 2) == 1) && numCount < 3 || charCount >= 5) {
        var rnum = Math.floor(Math.random() * 10);
        randomstring += rnum;
        numCount += 1;
    } else {
        // If any of the above criteria fail, go ahead and generate an alpha character from the chars string
        var rnum = Math.floor(Math.random() * chars.length);
        randomstring += chars.substring(rnum,rnum+1);
        charCount += 1;
    }
}
	$('#last_name')
    

$(function() {
    $('#last_name').on('blur' , function() {
        $("#username").val($('#last_name').val() +'_'+randomstring);
        if($('#last_name').val() == ''){

    	$('#username').val('');
    		}
    });

  });
</script>

@stop
