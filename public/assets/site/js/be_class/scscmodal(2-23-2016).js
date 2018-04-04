$(document).ready(function () {
        var component = (function ($) {
            var classStandingOffset = 3,
            theme, 
            onComponent = false, componentItems = [];
            var source =
            {
                datatype: "json",
                datafields: [
                    { name: "id" },
                    { name: "name" }
                    ],
                url: "../../teachers_portal/be_class/classStandingJson",
                async: false
            };
            var classCategory = new $.jqx.dataAdapter(source);
            classCategory.dataBind();

            var Componentsource =
            {
                datatype: "json",
                datafields: [
                    { name: "id" },
                    { name: "name" },
                    { name: "component_weight" }
                    ],
                url: "../../teachers_portal/be_class/classComponentWeight",
                async: false
            };
            var componentWeight = new $.jqx.dataAdapter(Componentsource);
            componentWeight.dataBind();


            var name;
            function render() {
                classStandingComponent();
                classStandingGrid();
            };
            function classStandingComponent() {
                var class_standing = $('#class_standing'),
                    imageContainer = $('</div>'),
                    image, category, left = 0, top = 0, counter = 0;
                var classCategoryCount = classCategory.records.length;
                for (var i = 0; i < classCategoryCount; i++) {
                    category = classCategory.records[i];
                    id = category.id;
                    name = category.name;
                    image = createclassStanding(id,name, category);
                    image.appendTo(class_standing);
                }

                $('.draggable-class-standing').jqxDragDrop({ dropTarget: $('#component'), revert: true });

            };
            function createclassStanding(id,name, classCategory) {
                return $('<div class="draggable-class-standing jqx-rc-all">' +
                        '<div class="jqx-rc-t draggable-class-standing-header jqx-widget-header-' + name + ' jqx-fill-state-normal-' + name + '">' +
                        '<div class="draggable-class-standing-header-label" data-id="'+id+'"> ' + name + '</div></div>' +
                        '</div>');
            };
            function classStandingGrid() {
                $("#jqxgrid").jqxGrid(
                {
                    source:componentWeight,   
                    height: 335,
                    width: 435,
                    editable: true,
                    showaggregates: true, 
                    showstatusbar: true,
                    keyboardnavigation: false,
                    selectionmode: 'selectallrows',
                    columns: [
                        { text: 'Class Component', editable: false, dataField: 'name', width: 180 },
                        { text: 'Percentage(%)', dataField: 'count', cellsalign: 'center', width: 180, columntype: 'numberinput' , aggregates: ['sum'],
                              aggregatesrenderer: function (aggregates, column, element) {
                                  var renderstring = "<div>";
                                  $.each(aggregates, function (key, value) {
                                      var name = key == 'sum' ? 'Total Component': '';
                                      var color = 'black';
                                      if (value >= 100)
                                        {   
                                            if(value == 100)
                                            {
                                                $("#saveComponent").removeAttr("disabled");
                                            }
                                            else
                                            {   
                                                color = 'red';
                                                sweetAlert("Ooops!","Total Component Weight Exceeds 100%", "error");
                                                $("#saveComponent").attr("disabled","disabled");                                       
                                            }                                          
                                        }
                                    renderstring += '<span style="color: ' + color + ';">' + name + ': ' + value + ' % </span>';
                                  });
                                  renderstring += "</div>";
                                  return renderstring;
                              }
                        },
                        { text: 'Remove', editable: false, dataField: 'remove', width: 75 }
                    ]
                });
                $("#jqxgrid").bind('cellclick', function (classStanding, commit) {
                    var index = classStanding.args.rowindex;
                      if (classStanding.args.datafield == 'remove') {
                            removeGridRow(index);
                    }
                });
            };
            function init() {
                render();
                draggableClassStanding();
            };
            function addItem(item) {
                    var id = componentItems.length,
                        item = {
                            name: item.name,
                            count: 10,
                            index: id,
                            remove: '<div style="text-align: center; cursor: pointer; width: 53px;"' +
                         'id="draggable-class-row-' + id + '">X</div>'
                        };
                    addGridRow(item);
            };
            function addGridRow(row) {
                $("#jqxgrid").jqxGrid('addrow', null, row);
            };
            function updateGridRow(id, row) {
                var rowID = $("#jqxgrid").jqxGrid('getrowid', id);

                $("#jqxgrid").jqxGrid('updaterow', rowID, row);
            };
            function removeGridRow(id) {
                var rowID = $("#jqxgrid").jqxGrid('getrowid', id);
                $("#jqxgrid").jqxGrid('deleterow', rowID);
                // alert($(id).data('id'));
                $('.draggable-class-standing').bind('dropTargetEnter', function (classStanding) {
                    $(classStanding.args.target);
                    onComponent = true;
                    $(this).jqxDragDrop('dropAction', 'none');
                });
                $("#saveComponent").removeAttr("disabled");
            };
            function draggableClassStanding() {
                $('.draggable-class-standing').mouseenter(function () {
                    $(this).children('.draggable-class-standing-percentage').fadeTo(100, 0.9);
                });
                $('.draggable-class-standing').mouseleave(function () {
                    $(this).children('.draggable-class-standing-percentage').fadeTo(100, 0);
                });
                $('.draggable-class-standing').bind('dropTargetEnter', function (classStanding) {
                    $(classStanding.args.target);
                    onComponent = true;
                    $(this).jqxDragDrop('dropAction', 'none');
                });
                $('.draggable-class-standing').bind('dropTargetLeave', function (classStanding) {
                    $(classStanding.args.target);
                    onComponent = false;
                    $(this).jqxDragDrop('dropAction', 'default');
                });
                $('.draggable-class-standing').bind('dragEnd', function (classStanding) {
                    $('#component');
                    if (onComponent) {
                        addItem({name: classStanding.args.name });
                        onComponent = false;
                    }
                });
                $('.draggable-class-standing').bind('dragStart', function (classStanding) {
                    var classComponent = $(this).find('.draggable-class-standing-header').text(),
                        count = $(this).find('.draggable-class-standing-percentage').text().replace('Percentage:', '', '%');
                    $('#component');
                    count = parseInt(count, 10);
                    $(this).jqxDragDrop('data', {
                        count: count,
                        name: classComponent
                    });
                });
            };
            return {
                init: init
            }
        } ($));
        $(document).ready(function () {
            component.init();
        });
});



$(document).ready(function () {
   var a = 1;

        $('#scscmodal').on('show.bs.modal', function(e) {  

            // $("#jqxgrid").jqxGrid('refreshaggregates');
            $("#jqxgrid").jqxGrid('clear');
            var button =  $(e.relatedTarget)
            var Id = button.data('id')
            var classId = button.data('class_id')
            var gradingPeriod = button.data('grading_period_name')

            $.ajax({
                url:"../../teachers_portal/te_class/classComponentWeight",
                type:'get',
                data:{ 
                    'class_id': $("#class_id").val(),
                    'grading_period_id': $("#grading_period_id").val(),
                },async:false,
                datatype: "json",
                success: function (data) 
                {      

                    $.each(data, function(item, value){

                        // var id = componentItems.length,
                            item = {
                                name: value.name,
                                count: value.component_weight,
                                index: id,
                                remove: '<div style="text-align: center; cursor: pointer; width: 53px;" data-id="'+value.id+'"' +
                             'id="draggable-class-row-' + id + '">X</div>'
                            };

                       $("#jqxgrid").jqxGrid('addrow', null, item);

                    });           
                },
            });
            

            var modal = $(this)
            $('.modal-title').text('Setup Class Standing Component : ' + gradingPeriod)
            $('#class_id').val(classId)
            $('#grading_period_id').val(Id)

            if(a == 1)
            {

                $("#saveComponent").click(function(){
                    var count = 0;
                    var detail = "";
                    var percentage = 0;
                    $("#contenttablejqxgrid > div").each(function(){
                        
                            detail = $(this).find('div[class="jqx-grid-cell-left-align"]').text();
                            percentage = $(this).find('div[class="jqx-grid-cell-middle-align"]').text();

                            var length = parseInt(detail.length-2);
                            var str = detail;
                            result = str.substr(1,length);

                            if(result != "")
                            {   
                                $.ajax({
                                      url:"../../class_standing_component/BE_post_create",
                                      type:'post',
                                      data:
                                          {  
                                            '_token':$("input[name=_token]").val(),
                                            'class_id': $("#class_id").val(),
                                            'grading_period_id': $("#grading_period_id").val(),
                                            'detail': result,
                                            'percentage':percentage
                                          },
                                      dataType: "json",
                                      async:false
                                });

                                $("#scscmodal").modal('hide');
                                swal("Good Job!","Class Standing Component Saved!", "success"); 
                            }
                    });
                });

            }
            a++;
        });

        function deleteComponent(id){
            alert(id);
        }

});