var hot;

$(document).ready(function () {
    ClassRecordHandsonTable();
});

function ClassRecordHandsonTable() {

    $('#classrecordmodal').on('shown.bs.modal', function (e) {

        var button =  $(e.relatedTarget)
            var GradingPeriodId = button.data('id')
            var ClassId = button.data('class_id')
            var GradingPeriodName = button.data('grading_period_name')

            var modal = $(this)
            $('.class_record').text('View Class Record : ' + GradingPeriodName)
            $('#grading_period_id_class_record').val(GradingPeriodId)
            $('#class_id_class_record').val(ClassId)

        $.ajax({
            url:"../../class_record/dataJson",
            type:'get',
            data:
                {  
                    'grading_period_id' : GradingPeriodId,
                    'class_id' : ClassId
                },
            dataType: "json",
            async:false,
            success: function (data) 
            { 
                ScoreEntryJson = data;
            } 

        });
        
        function firstRowRenderer(instance, td, row, col, prop, value, cellProperties) {
            Handsontable.renderers.TextRenderer.apply(this, arguments);
            td.style.background = '#E6E6E6';
        }

        var data = ScoreEntryJson[0];
        var score_entry_id_arr = ScoreEntryJson[1];
        var perfect_score_arr = ScoreEntryJson[2];
        var container = document.getElementById("class_record_excel");
        hot = new Handsontable(container, {
            data: data,
            className: "htCenter htMiddle",
            height: 300,
            currentRowClassName: 'currentRow',
            currentColClassName: 'currentCol',
            fixedRowsTop: 3,
            mergeCells: [
              {row: 0, col: 0, rowspan: 3, colspan: 1},
              {row: 0, col: 1, rowspan: 3, colspan: 1},
              {row: 0, col: 2, rowspan: 3, colspan: 1},
              {row: 0, col: 3, rowspan: 3, colspan: 1},
              {row: 0, col: 4, rowspan: 3, colspan: 1}
            ],
            cells: function (row, col, prop) {
              var cellProperties = {};

                if(row != 0 || row === 0) {
                    cellProperties.editor = false;
                }
                if (row === 0 || row === 1|| row === 2|| col === 0) {
                    cellProperties.renderer = firstRowRenderer; // uses function directly
                }

              return cellProperties;
            }
        });
    });
	$("#classrecordmodal").draggable({
      	handle: ".modal-header"
  	});
}
function callReport(reportId)
{
  var url = $("#"+reportId).data('url');
  var class_id = $("#class_id_class_record").val();
  var grading_period_id  = $("#grading_period_id_class_record").val();

  url = url +"?class_id="+class_id+"&grading_period_id="+grading_period_id;
  
  window.open(url);
}