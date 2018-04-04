$(document).ready(function () {

        var value = 0;
    $('#scscmodal').on('show.bs.modal', function(e) {  
        value++;
        var button =  $(e.relatedTarget)
        var Id = button.data('id');
        var classId = button.data('class_id');
        var gradingPeriod = button.data('grading_period_name');
        var SubjectId = button.data('subject_id');
        var ClassificationId = button.data('classification_id');

        $('.scsc_modal').text('Setup Class Standing Component : ' + gradingPeriod);
        $('#scsc_class_id').val(classId);
        $('#scsc_grading_period_id').val(Id);
        $('#scsc_subject_id').val(SubjectId);
        $('#scsc_classification_id').val(ClassificationId);

        
        // $.ajax({
        //     url:"../../teachers_portal/be_class/classComponentWeight",
        //     type:'get',
        //     data:{ 
        //         'class_id': $("#scsc_class_id").val(),
        //         'grading_period_id': $("#scsc_grading_period_id").val(),
        //     },async:false,
        //     datatype: "json",
        //     success: function (data) 
        //     {      
        //         // $("#contenttablejqxgrid").empty();
        //         alert();
        //         // $("#jqxgrid").jqxGrid('updatebounddata');
        //         $.each(data, function(item, value){

        //             // var id = componentItems.length,
        //                 item = {
        //                     name: value.name,
        //                     component_weight: value.component_weight,
        //                     index: value.id,
        //                     remove: '<div class="remove" style="text-align: center; cursor: pointer; width: 53px;" data-id="'+value.id+'"' +
        //                  'id="draggable-class-row-' + value.id + '">X</div>'
        //                 };

        //            $("#jqxgrid").jqxGrid('addrow', null, item);

        //         });           
        //     },
        // });
        
            
        // if(value == 1){


            var component = (function ($) {
                var classStandingOffset = 3,
                theme, 
                onComponent = false, componentItems = [];
                var source =
                {
                    datatype: "json",
                    datafields: [
                        { name: "id" },
                        { name: "name" },
                        { name: "component_weight" }
                        ],
                    data:{ 
                        'subject_id': $("#scsc_subject_id").val(),
                        'classification_id': $("#scsc_classification_id").val()
                        },
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
                var component_weight;
                function render() {
                    classStandingComponent();
                    classStandingGrid();
                };
                function classStandingComponent() {
                    var catalog = $('#catalog'),
                        imageContainer = $('</div>'),
                        image, category, left = 0, top = 0, counter = 0;
                    var classCategoryCount = classCategory.records.length;

                     $("#catalog").empty();
                    for (var i = 0; i < classCategoryCount; i++) {
                        category = classCategory.records[i];
                        id = category.id;
                        name = category.name;
                        component_weight = category.component_weight;
                        image = createclassStanding(id, name, component_weight);
                        image.appendTo(catalog);
                    }

                    $('.draggable-class-standing').jqxDragDrop({ dropTarget: $('#component'), revert: true });

                };
                var theme;
                function createclassStanding(id, name, component_weight) {
                    return $('<div class="draggable-class-standing jqx-rc-all">' +
                                '<div class="jqx-rc-t draggable-class-standing-header jqx-widget-header-' + theme + ' jqx-fill-state-normal-' + theme + '">' +
                                    '<div class="draggable-class-standing-header-label" data-id="'+id+'">'+
                                        '<b class="name_class">' + name +'</b>'+
                                        '&nbsp;-&nbsp;<b class="weight_class">'+ component_weight + '</b>'+
                                    '</div>'+
                                '</div>' +
                            '</div>');
                };
                function classStandingGrid() {


                    $("#jqxgrid").jqxGrid(
                    {
                        source:componentWeight,   
                        height: 335,
                        width: 435,
                        editable: false,
                        showaggregates: true, 
                        showstatusbar: true,
                        keyboardnavigation: false,
                        selectionmode: 'selectallrows',
                        columns: [
                            { text: 'Class Component',  dataField: 'name', width: 180 },
                            { text: 'Percentage(%)',  dataField: 'component_weight', cellsalign: 'center', width: 180,  aggregates: ['sum'],
                                  aggregatesrenderer: function (aggregates, column, element) {
                                      var renderstring = "<div>";
                                      $.each(aggregates, function (key, value) {
                                          var description = key == 'sum' ? 'Total Component': '';
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
                                                    swal("Total Component Weight Exceeds 100%");
                                                    $("#saveComponent").attr("disabled","disabled");                                       
                                                }                                          
                                            }
                                        renderstring += '<span style="color: ' + color + ';">' + description + ': ' + value + ' % </span>';
                                      });
                                      renderstring += "</div>";
                                      return renderstring;
                                  }
                            },
                            { text: 'Remove',  dataField: 'remove', width: 75 }
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
                                component_weight: item.component_weight,
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
                    
                    var component_id = $("#row"+id+"jqxgrid :nth-last-child(1) > div > div").data('id');
                    var rowID = $("#jqxgrid").jqxGrid('getrowid', id);
                    $("#jqxgrid").jqxGrid('deleterow', rowID);

                    removeComponent(component_id);
                    // alert($("row"+id+"jqxgrid > div > div > div").data('id'));
                    // var $this = $(this);
                    // if (!$this.next("div :nth-last-child(3)").data('loaded')) {
                    //     // if (!$this.next("div").data('loaded')) {
                    //     //     alert($this.next().find("div[class=remove]").data('id'));
                    //     // }

                    //     $(this).attr('data-id','1');
                    // }
                    // alert($("row"+id+"jqxgrid > div:nth-last-child(3) > div > div").data('id'));
                  
                    
                    // if(!$("#row"+id+"jqxgrid :nth-last-child(2)").data('loaded'))
                    // {
                    //     if (!$("#row"+id+"jqxgrid :nth-last-child(2)").next("div").data('loaded')) {
                    //     //     // if (!$this.next("div").data('loaded')) {
                    //     //     //     alert($this.next().find("div[class=remove]").data('id'));
                    //     //     // }

                            
                    //     }
                    // }

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
                            addItem({name: classStanding.args.name , component_weight: classStanding.args.component_weight });
                            onComponent = false;
                        }
                    });
                    $('.draggable-class-standing').bind('dragStart', function (classStanding) {
                        var classComponent = $(this).find('.name_class').text();
                        var classComponentWeight = $(this).find('.weight_class').text();
                            // count = $(this).find('.draggable-class-standing-percentage').text().replace('Percentage:', '', '%');
                        $('#component');
                        // count = parseInt(count, 10);
                        $(this).jqxDragDrop('data', {
                            component_weight: classComponentWeight,
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
        

        var a = 1;

        $.ajax({
            url:"../../teachers_portal/be_class/classComponentWeight",
            type:'get',
            data:{ 
                'class_id': $("#scsc_class_id").val(),
                'grading_period_id': $("#scsc_grading_period_id").val(),
            },async:false,
            datatype: "json",
            success: function (data) 
            {      
                $("#contenttablejqxgrid").empty();

                $.each(data, function(item, value){

                    // var id = componentItems.length,
                        item = {
                            name: value.name,
                            component_weight: value.component_weight,
                            index: value.id,
                            remove: '<div class="remove" style="text-align: center; cursor: pointer; width: 53px;" data-id="'+value.id+'"' +
                         'id="draggable-class-row-' + value.id + '">X</div>'
                        };

                   $("#jqxgrid").jqxGrid('addrow', null, item);

                });           
            },
        });

        function removeComponent(id)
        {
            if(id != "" && id != null)
            {
                $.ajax({
                      url:"../../class_standing_component/BE_post_delete",
                      type:'post',
                      data:
                          {  
                            '_token':$("input[name=_token]").val(),
                            'id' : id,
                          },
                      dataType: "json",
                      async:false,
                      success: function (data) 
                      {
                        swal("Delete Successful!");
                      }  

                });
            }

        }
        
    // }

        if(a == 1)
        {

            $("#saveComponent").click(function(){
                var count = 0;
                var detail = "";
                var percentage = 0;
                $("#contenttablejqxgrid > div").each(function(){
                    
                        detail = $(this).find('div[class="jqx-grid-cell-left-align"]').text();
                        percentage = $(this).find('div[class="jqx-grid-cell-middle-align"]').text();

                        var length = parseInt(detail.length-1);
                        var str = detail;
                        result = str.substr(0,length);
                        // alert(str);

                        if(result != "")
                        {   
                            $.ajax({
                                  url:"../../class_standing_component/BE_post_create",
                                  type:'post',
                                  data:
                                      {  
                                        '_token':$("input[name=_token]").val(),
                                        'class_id': $("#scsc_class_id").val(),
                                        'grading_period_id': $("#scsc_grading_period_id").val(),
                                        'detail': result,
                                        'percentage':percentage,
                                        '_token': $('input[name=_token]').val(),
                                      },
                                  dataType: "json",
                                  async:false
                            });

                            $("#scscmodal").modal('hide');
                            swal("Class Standing Component Saved!"); 
                        }
                });
            });

        }
        a++;

        function deleteComponent(id){
            alert(id);
        }

    });

});