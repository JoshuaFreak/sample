@extends('site.layouts.default')

{{-- Title --}}
@section('title')
Edit Percentage Level
@endsection

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
			<h2>
				Create Percentage Level
				<div class="pull-right">
					<a href="{{{ URL::to('percentage_level/') }}}" class="btn btn-sm  btn-primary"><span class="glyphicon glyphicon-plus-sign"></span>  Percentage Level List</a>
				</div>
			</h2>
		</div>
	</div>
</div>
@endsection