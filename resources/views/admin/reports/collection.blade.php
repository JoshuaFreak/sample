@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ $title }}} :: @parent
@stop
{{-- Content --}}
@section('content')
@section('styles')
    <style>
        .panel-footer {
            background-color: #fff;
            color:black;
        }
        .col-xs-9{
            color: #fff;
        }
    </style>
@endsection
<link href="{{asset('assets/site/css/glyphicon.design.css')}}" rel="stylesheet">
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
          <h2>Collection Report</h2>
        </div>
        <div class="row">
            <div class="form-horizontal">
            <div class="col-md-12">
                <div class="form-group {{{ $errors->has('date') ? 'has-error' : '' }}}">
                    <label class="col-md-1 control-label" for="date">Date</label>
                    <div class="col-md-5">
                        <div class="input-daterange input-group" id="datepicker">
                          <input type="text" id="date_start" class="form-control" name="start" value="" />
                            <span class="input-group-addon">to</span>
                            <input type="text" id="date_end" class="form-control" name="end" value="" />             
                        </div>
                    </div>
                    <label class="col-md-1 control-label" for="date">Term</label>
                    <div class="col-md-2">
                        <select id="term_id" name="term_id" class="col-md-12 form-control">
                                <option value="0" selected=""></option>
                            @foreach($term_list as $term)
                                <option value="{{$term -> id}}">{{$term->term_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-sm btn-success" id="loadCollection">
                                <span class="glyphicon glyphicon-ok-circle"></span> Filter
                            </button>
                        </div>
                        <div class="col-md-2"></div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-sm btn-info" id="reload">
                                <span class="glyphicon glyphicon-ok-circle"></span> Refresh
                            </button>
                        </div>
                    </div>
                </div>
            </div><br/><br/><br/>
            <div class="form-group col-md-12">
                <label class="col-md-12">Legend</label>
            </div>
            <div class="form-group col-md-5">
                <div class="col-md-1">
                    <div style="background-color: #D70206;width: 20px;height: 20px;"></div>
                </div>
                <div class="col-md-5" style="margin-top: -7px;">
                    <label class="control-label">Pre-Elementary</label>
                </div>
                <div class="col-md-1">
                    <div style="background-color: #F05B4F;width: 20px;height: 20px;"></div>
                </div>
                <div class="col-md-4" style="margin-top: -7px;">
                    <label class="control-label">Elementary</label>
                </div>
                <div class="col-md-1">
                    <div style="background-color: #F4C63D;width: 20px;height: 20px;"></div>
                </div>
            </div>
            <div class="form-group col-md-5">
                <div class="col-md-5" style="margin-top: -7px;">
                    <label class="control-label">Junior High-School</label>
                </div>
            </div>
            <br/>
            <div class="col-md-12" id="no_data" style="background-color: #eeeeee;" align="center"><br>No data available<br/><br/>
            </div>
            <div class="col-md-12"><h3 id="bar_graph"></h3></div>
            <div class="chartist-div" style="padding:10px;">
                <div id="bar-chart" class="ct-chart">
                </div>
            </div>
            <br/><br/><br/><br/><br/><br/>
            <div class="col-md-12"><h3 id="circle_graph"></h3></div>
            <!-- <div class="chartist-div">
                <div class="col-md-6">
                    <label class="col-md-12" id="student_label"></label>
                    <div class="col-md-12" id="student_number">
                    </div>
                </div>
                <div id="circle-chart" class="ct-chart col-md-6">
                </div>
            </div> -->
            <div id="CollectionContainer" class="col-md-12"></div>
           <!--  <div class="col-md-12">
                <div id="graph">
                    <div id="chart1"></div><br/><br/><br/>
                    <div id="chart2"></div><br/><br/><br/>
                </div>
            </div> -->
        </div>
        </div>
    </div>
</div>

@section('scripts')
    <script>
        $(function() {
          $('#datepicker').datepicker({
              format: "MM d, yyyy",
              orientation: "top left",
              autoclose: true,
              startView: 1,
              todayHighlight: true,
              todayBtn: "linked"
          });
        });
        $("#loadCollection").click(function()
        {
                var date_start = $("#date_start").val();
                start_date = new Date(date_start);
                year_start = start_date.getFullYear()+ "-" +(start_date.getMonth()+1) +"-" +start_date.getDate();

                var date_end = $("#date_end").val();
                end_date = new Date(date_end);
                year_end = end_date.getFullYear()+ "-" +(end_date.getMonth()+1)+ "-" +end_date.getDate();

                // $("#graph").show();
                $("#no_data").hide();
                $("#student_number").empty();

                $(document).ready(function(){

                    $.ajax({
                        url:'{{{ URL::to("accounting_report/collectiondataJson") }}}',
                        type:'get',
                        data:{
                            'date_start': year_start, 
                            'date_end': year_end,
                            'term_id': $("#term_id").val(),
                        },
                        dataType: "json",
                        async:false,
                        success: function (data) 
                        { 
                            $("#bar_graph").text("Collection Bar Graph");
                            // $("#circle_graph").text("No. of Students Per Classication");
                            collection = data[0];

                                new Chartist.Bar('#bar-chart', collection, {
                                  fullWidth: true,
                                  lineSmooth: false,
                                  chartPadding: {
                                    right: 20,
                                    left: 10
                                  },
                                  axisX: {
                                    labelInterpolationFnc: function(value) {
                                      return value.split('').slice(0, 12).join('');
                                    }
                                  }
                                });

                                // var sum = function(a, b) { return a + b };

                                // var data1 = data[1];

                                // var options = {
                                //   labelInterpolationFnc: function(value) {
                                //     // return value[0]
                                //     // return Math.round(value / data.series.reduce(sum) * 100) + '%';
                                //   }
                                // };

                                // var responsiveOptions = [
                                //   ['screen and (min-width: 640px)', {
                                //     chartPadding: 30,
                                //     labelOffset: 100,
                                //     labelDirection: 'explode',
                                //     labelInterpolationFnc: function(value) {
                                //       return value;
                                //     }
                                //   }],
                                //   ['screen and (min-width: 1024px)', {
                                //     labelOffset: 80,
                                //     chartPadding: 20
                                //   }]
                                // ];
                                // new Chartist.Pie('#circle-chart', data1, options, responsiveOptions);

                                // data2 = data[2];
                                // // $("#student_label").text('No. of Students Per Classication');

                                // $.map(data2, function (item) 
                                // {
                                //     $("#student_number").append(''
                                //         +'<div class="col-md-12">'
                                //             +'<label class="control-label col-md-6">'+item.text+'</label>'
                                //             +'<label class="control-label col-md-6">'+item.value+'</label>'
                                //         +'</div>'
                                //         +'');
                                // });

                             LoadCollection(date_start, date_end)
                        }

                    });
                });

        });

        function LoadCollection(DateStart, DateEnd)
        {
          $.ajax(
              {
                url: "{{{ URL::to('accounting_report/collection_report_detail') }}}",
                data: { 
                  'date_start': DateStart, 
                  'date_end': DateEnd
                },
              }
            ).done(function(cashier_detail_html){
              $("#CollectionContainer").html(cashier_detail_html);
            });

        }
    </script>
@endsection
@stop


