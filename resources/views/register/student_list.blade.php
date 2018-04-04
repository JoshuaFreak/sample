@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("register_lang.register_list") }}} :: @parent
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
		<div class="page-header"><br>
			<h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left">
				{{{ Lang::get("register_lang.student_list") }}}
				<div class="pull-right">
					<!-- <a href="" class="btn btn-sm  btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> </a> -->
				</div>
			</h3>
		</div>

		<div class="col-md-12 form-group">
			<div class="col-md-2">
				<label class="control-label">Select Date</label>
			</div>
			<div class="col-md-3">
				<input id="date" type="date" class="input-sm form-control" value="{{ date('Y-m-d') }}">
			</div>
			<div class="col-md-3">
				<button id="load" type="button" class="btn btn-primary">Load</button>
			</div>
		</div>
		<table id="student_list" class="display" cellspacing="0" width="100%">
		        <thead>
		            <tr>
		                <th>Student ID</th>
		                <th>Name</th>
		            </tr>
		        </thead>
		        <tfoot>
		            <tr>
		                <th>Student ID</th>
		                <th>Name</th>
		            </tr>
		        </tfoot>
		</table>
	</div>
</div>

@stop
    
{{-- Scripts --}}
@section('scripts')
	<script type="text/javascript">
		// var oTable;
		// $(document).ready(function() {
		// 	oTable = $('#table').dataTable( {
		// 		"sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
		// 		"sPaginationType": "bootstrap",
		// 		"bProcessing": true,
		//         "bServerSide": true,
		//         "sAjaxSource": "{{ URL::to('register/data') }}",
		//         "fnDrawCallback": function ( oSettings ) {
	 //           		$(".iframe").colorbox({
  //                       iframe : true,
  //                       width : "80%",
  //                       height : "80%",
  //                       onClosed : function() {
  //                           window.location.reload();
  //                       }
  //                   });
	 //     		}
		// 	});
		
		// });

		// $.ajax({
	 //        url: 'http://cebucia.com/api/api.php', 
	 //        type: "GET", 
	 //        dataType: "json",
	 //        data:{
	 //        	'_token': 'L3tM3P@55!',
	 //        	'date': '2017-03-21'
	 //        },
	 //        async:false,
	 //        error: function (data, textStatus, errorThrown) {
  //           console.log(data)

  //           	var myJSON = JSON.stringify(data);

	 //            // $.map(myJSON,function(item){
	 //            // 	alert(item.sename);
	 //            // });

	 //        },
	 //        success: function (data) {
	 //            console.log(data);
	 //            // var myJSON = JSON.stringify(data);
	 //            // var myJSON = JSON.parse(myJSON);
	 //            // console.log(myJSON);

	 //            $.map(data,function(item){
	 //            	alert(item.sename);
	 //            });
	 //            // alert(data);

	 //        }
	 //    });

		$(document).ready(function() {

			var table = $('#student_list').DataTable();
			$('#load').on( 'click', function () {
			    $.getJSON('http://cebucia.com/api/student_list_check_in.php?_token=L3tM3P@55!&date='+$("#date").val(), null, function ( json ) {
			        table.destroy();
			        $('#student_list').empty(); // empty in case the columns change

			        table = $('#student_list').DataTable( {
			            //columns: json.columns,
			            //data:    json.rows
			            data:json.data,
			            	"columns": [
		                    { "data": "student_id" },
		                    { "data": "sename" }
		                ]
			        } );
			    } );
			} );

			
			$('#load').trigger("click");
			 

			//load_student($("#date").val());
			// table = $('#student_list').dataTable( {
			//     // "bProcessing": true,
			//     // "bServerSide": true,
			//     // "sServerMethod": "GET",
			//     "ajax": {
			// 		    "url": "http://cebucia.com/api/api.php",
			// 		    "data": {
			// 		        "_token": "L3tM3P@55!",
			// 		        "date": $("#date").val()
			// 		    }
			// 	},
			// 	"columns": [
   //                  { "data": "student_id" },
   //                  { "data": "sename" }
   //              ]
			// } );

			// var datatable = $('#student_list').dataTable({
			// 	"columns": [
   //                  { "data": "student_id" },
   //                  { "data": "sename" }
   //              ]
			// }).api();

			// $.get('http://cebucia.com/api/api.php?_token=L3tM3P@55!&date=2017-05-27', function(newDataArray) {
			// 	console.log(newDataArray);
			//     datatable.clear();
			//     datatable.rows.add(newDataArray);
			//     datatable.draw();
			// });

		} );

		// function load_student(date)
		// {

		//     table = $('#student_list').dataTable( {
		//         "ajax": {
		// 			    "url": "http://cebucia.com/api/api.php",
		// 			    "data": {
		// 			        "_token": "L3tM3P@55!",
		// 			        "date": date
		// 			    }
		// 		},
		// 		// "bProcessing": true,
		//   //       "bServerSide": true,
		//   //       "sAjaxSource": "http://cebucia.com/api/api.php"
		//   //       "fnServerParams": function ( aoData ) {
		//   //           aoData.push( { "_token": "L3tM3P@55!", "date": $("#date").val() } );
		//   //       }
		//         "columns": [
  //                   { "data": "student_id" },
  //                   { "data": "sename" }
  //               ]
		//     } );

			


		// }
		// 		    $("#load").click(function(){

		//    //  	table = $('#student_list').dataTable( {
		// 	  //       "ajax": {
		// 			// 	    "url": "http://cebucia.com/api/api.php",
		// 			// 	    "data": {
		// 			// 	        "_token": "L3tM3P@55!",
		// 			// 	        "date": date
		// 			// 	    }
		// 			// },
		// 			// // "bProcessing": true,
		// 	  // //       "bServerSide": true,
		// 	  // //       "sAjaxSource": "http://cebucia.com/api/api.php"
		// 	  // //       "fnServerParams": function ( aoData ) {
		// 	  // //           aoData.push( { "_token": "L3tM3P@55!", "date": $("#date").val() } );
		// 	  // //       }
		// 	  //       "columns": [
	 //    //                 { "data": "student_id" },
	 //    //                 { "data": "sename" }
	 //    //             ]
		// 	  //   } );
		// 	    console.log($("#date").val())
		// 		load_student($("#date").val());
		// 		// table.fnDraw();
		// 	});
	//  $.getJSON('cebucia.com/api/api.php?_token=L3tM3P@55!', function(data){
	//     console.log(data);
	// });
	</script>
@stop