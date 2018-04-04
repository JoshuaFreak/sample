@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("student.student_list") }}} :: @parent
@stop
{{-- Content --}}
@section('content')
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">    
        <ul class="nav" id="side-menu"> 
          @include('admin_sidebar')
        </ul>
    </div>
</div>
<div id="page-wrapper">
  <div class="row">
		<div class="page-header"><br>
		  <h2>Student Population Report</h2>
		</div>
    
    <form>
      <div class="form-horizontal">
          <div class="col-md-12">
              <label class="control-label col-md-1">Filter by:</label>
              <!-- <div class="col-md-3">
                  <select class="form-control" name="classification_level_id" id="classification_level_id">
                    @foreach($classification_level as $class_level)
                      <option value="{{$class_level->id}}">{{$class_level->level}}</option>
                    @endforeach
                  </select>
              </div> 
              <div class="col-md-3">
                  <select class="form-control" name="section_id" id="section_id">
                    <option></option>
                  </select>
              </div> -->  
              <div class="col-md-3 col-md-offset-1">
                  <select class="form-control" name="term_id" id="term_id">
                    <option selected></option>
                    @foreach($term as $term_list)
                      <option value="{{$term_list->id}}">{{$term_list->term_name}}</option>
                    @endforeach
                  </select>
              </div>  
              <div class="col-md-2">
                  <button type="submit" class="btn btn-danger" id="filter">Filter</button>
              </div>          
          </div>
      </div>
    </form>
    <br><br><br><br>

    <!-- <div class="form-group col-md-12">
        <label class="col-md-12">Legend</label>
    </div>
    <div class="form-group col-md-5">
        <div class="col-md-1">
            <div style="background-color: #D70206;width: 20px;height: 20px;"></div>
        </div>
        <div class="col-md-5" style="margin-top: -7px;">
            <label class="control-label">Male</label>
        </div>
        <div class="col-md-1">
            <div style="background-color: #F05B4F;width: 20px;height: 20px;"></div>
        </div>
        <div class="col-md-4" style="margin-top: -7px;">
            <label class="control-label">Females</label>
        </div>
    </div> -->
    <br>
    <div class="col-md-12" id="no_data" style="background-color: #eeeeee;" align="center"><br>No data available<br><br></div>

    <div class="col-md-12">
      <div class="col-md-6" style="display:none;" id="population">
        <div class="col-md-12">
          <h3>No. of Students Per Classication</h3>
        </div>
        <div class="col-md-9 col-md-offset-2">
          <table class="table table-striped table-hover">
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
      <div id="circle-chart" class="ct-chart col-md-4">
    </div>
  </div>
</div>
@stop
@section('scripts')
<script>
    $(document).ready(function(){
    })

    $('#classification_level_id').change(function(){
      selectListChange('section_id', '{{URL::to("admin/reports/student_contacts/dataJson")}}', {'classification_level_id':$(this).val()}, '')
    })

    $('form').submit(function(e){
      e.preventDefault()

      // var section_id = $('#section_id').val();
      var term_id = $('#term_id').val();
      // var classification_level_id = $('#classification_level_id').val();

      if($('#classification_level_id').val() == 0 && $('#term_id').val() == ""){
        swal('Fill some fields','Please select value(s) to continue!', 'warning')
        return false;
      }

      $.ajax({
        url: '{{URL::to("admin/reports/student_population/data")}}',
        type: 'GET',
        data: {
          // 'section_id': section_id,
          'term_id': term_id,
          // 'classification_level_id': classification_level_id,
        },
        dataType: 'json',
        success: function(data){
          var stopper = true;
            // console.log(data[0])
          $.map(data[0]['series'], function(label){
            if(label != 0){
              stopper = false;
            }
          });

          if(stopper == true){
            swal('No data filtered', 'Please select another value(s) to continue!', 'error')
            $('#no_data').next('div').hide()
            $('#no_data').show()
            return false;
          }

          $('#no_data').hide()
          $('#no_data').next('div').show()
          $('#population table>tbody').empty()

          var options = {
            labelInterpolationFnc: function(value) {
              // return value[0]
              // return Math.round(value / data.series.reduce(sum) * 100) + '%';
            }
          };

          var responsiveOptions = [
            ['screen and (min-width: 640px)', {
              chartPadding: 30,
              labelOffset: 100,
              labelDirection: 'explode',
              labelInterpolationFnc: function(value) {
                return value;
              }
            }],
            ['screen and (min-width: 1024px)', {
              labelOffset: 80,
              chartPadding: 40
            }]
          ];
          new Chartist.Pie('#circle-chart', data[0], options, responsiveOptions);

          $.map(data[1], function(label){
            $('table tbody').append('\
              <tr>\
                <td><b>'+label.text+'</b></td>\
                <td>'+label.value+'</td>\
              </tr>');
          });

          $('#population').show()
        }
      })
    });

    
    $(function() {
      $('#datepicker').datepicker({
          format: "MM d, yyyy",
      orientation: "top left",
      autoclose: true,
      startView: 1,
      todayHighlight: true,
      todayBtn: "linked",
      });
    });

    $("#printBTN").hide();
    $("#no_data").show();

    $("#loadCollection").click(function()
    {
        LoadCollection($("#date_start").val(), $("#date_end").val());
        $("#printBTN").show();
        $("#no_data").hide();
    });

    function LoadCollection(DateStart, DateEnd)
    {
      $.ajax(
          {
            url: "{{{ URL::to('accounting_report/collection_report_detail') }}}",
            data: { 
              'date_start': DateStart, 
              'date_end': DateEnd,
              'is_ar': 1,
            },
          }
        ).done(function(cashier_detail_html){
          $("#CollectionContainer").html(cashier_detail_html);
        });

    }

    function callReport(reportId)
    {
      var url = $("#"+reportId).data('url');
      var date_start  = $("#date_start").val(); 
      var date_end = $("#date_end").val();

      url = url +"?date_start="+date_start+"&date_end="+date_end;
      
      window.open(url);
    }
</script>
@stop