<html>
	<head>
		<title>Schedules</title>
		
		<!-- <link href='//fonts.googleapis.com/css?family=Bebas:100' rel='stylesheet' type='text/css'> -->

		<style>
			/*body {
				margin: 0;
				padding: 0;
				width: 100%;
				height: 100%;
				color: #B0BEC5;
				display: table;
				font-weight: 100;
				font-family: 'Bebas';
			}
*/
            body {
                margin: 0;
				padding: 0;
                width: 100%;
				height: 100%;
				color: #B0BEC5;
				display: table;
				font-weight: 100;
				font-family: 'Bebas';
                background:url({{{ asset('assets/site/images/bg.jpg') }}});
                background-repeat: no-repeat;
                background-attachment: fixed;
                background-position: center;
                background-size: 100%;
            }

			.container {
				text-align: center;
				display: table-cell;
				vertical-align: middle;
			}

			.content {
				text-align: center;
				display: inline-block;
				vertical-align: middle; 

			}

			.title {
				font-size: 96px;
				margin-bottom: 40px;
			}

			.quote {
				font-size: 24px;
			}
		</style>
	</head>
	<body style="background-image: url('assets/site/images/bg.jpg')">
		<div class="container">
			<center>
				<div class="content" style="width: 100%;">
					<div style="height: 300px;width: 2%;float: left;margin-top:2%;margin-bottom:2%">
					</div>
					<div style="height: 300px;width: 20%;float: left;margin:2%">
						<a href="{{ URL::to('/student_view') }}" target="_blank">
							<img width="100%" src="{{ asset('assets/site/images/student_sched.png') }}">
						</a>
						<br>
						<!-- <h1 style="color:#fff">Student Schedule</h1> -->
					</div>
					<div style="height: 300px;width: 20%;float: left;margin:2%">
						<a href="{{ URL::to('/teacher_view') }}" target="_blank">
							<img width="100%" src="{{ asset('assets/site/images/teacher_sched.png') }}">
						</a>
						<br>
						<!-- <h1 style="color:#fff">Teacher Schedule</h1> -->
					</div>
					<div style="height: 300px;width: 20%;float: left;margin:2%">
						<a href="{{ URL::to('/group_class_view') }}" target="_blank">
							<img width="100%" src="{{ asset('assets/site/images/group_sched.png') }}">
						</a>
						<br>
						<!-- <h1 style="color:#fff">Group Class</h1> -->
					</div>
					<div style="height: 300px;width: 20%;float: left;margin:2%">
						<a href="{{ URL::to('/activity_class_view') }}" target="_blank">
							<img width="100%" src="{{ asset('assets/site/images/activity_sched.png') }}">
						</a>
						<br>
						<!-- <h1 style="color:#fff">Activity Class</h1> -->
					</div>
					<div style="height: 300px;width: 2%;float: left;margin-top:2%;margin-bottom:2%">
					</div>
				</div>
			</center>
		</div>
	</body>
</html>
