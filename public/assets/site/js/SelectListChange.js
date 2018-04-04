//by reagz: automatic parsing of dropdownlist or select list option 

function selectListChange(element, url, param, defaultOptionName) 
{


    var elementName = "#" + element;

    if ($("#" + element).length == 0) 
    {
        elementName = "." + element;
    }

    $(elementName + "> option").remove();
    $(elementName).append($("<option />").val("").text("---" + defaultOptionName + "---"));


     $.ajax({
        url: url,
        data: param,
        type: "GET", 
        dataType: "json",
        async:false,
        success: function (data) 
        {
                $(elementName).empty();
                // $(elementName).append('<option value=""></option>');
                $.map(data, function (item) 
                {      
                        $(elementName).append($("<option/>").val(item.value).text(item.text).attr('data-course_capacity_id',item.counter));
                });
        }
       
    }); 



    $(elementName).trigger("change");
}

function selectListRoomSpaceChange(element, url, param, defaultOptionName,counter) 
{


    var elementName = "#" + element;

    if ($("#" + element).length == 0) 
    {
        elementName = "." + element;
    }

    $(elementName + "> option").remove();
    $(elementName).append($("<option />").val("").text("---" + defaultOptionName + "---"));

    $("#modal_course_id_"+counter).find('option').each(function(){
        $(this).removeClass('hide');
    });

    var count_course_capacity = 1;
    var course_capacity_id = param.course_capacity_id;
    $("#modal_course_id_"+counter).find('option').each(function(){

        modal_course_capacity_id = $(this).data('course_capacity_id');
        if(course_capacity_id != modal_course_capacity_id)
        {
            $(this).addClass('hide');
        }
        else
        {
            if(count_course_capacity == 1)
            {
                $(this).attr('selected','selected');
                count_course_capacity++;
            }
        }
    });

     $.ajax({
        url: url,
        data: param,
        type: "GET", 
        dataType: "json",
        async:false,
        success: function (data) 
        {
                $(elementName).empty();
                $(elementName).append('<option value="0"></option>');

                var option_arr = [];
                var teacher_id_arr = [];
                var count_arr = 0;
                var data_counter = 0;
                $.map(data[1], function (item) 
                {       
                        data_counter++;
                        option_arr[count_arr] = [item.value,item.teacher_id,item.teacher_name];
                        $(elementName).append($("<option/>").val(item.value).text(item.text));
                        count_arr++;

                });

                if(data_counter != 0)
                {
                    item = get_random(option_arr);

                    value = item[0];
                    value1 = item[1];
                    value2 = item[2];
                    $("#modal_room_id_"+counter+" [value='"+value+"']").attr("selected","selected");

                    // $("#modal_teacher_name_"+counter).val(value2);
                    $("#modal_teacher_name_"+counter).append('<option value="'+value1+'">'+value2+'</option>');
                    $("#modal_teacher_name_"+counter+" [value='"+value1+"']").attr("selected","selected");
                    $("#modal_teacher_name_id_"+counter).val(value1);
                    $("#modal_teacher_name_"+counter).removeAttr('data-teacher_id');
                    $("#modal_teacher_name_"+counter).attr('data-teacher_id',value1);
                }

        }
       
    }); 



    $(elementName).trigger("change");
}

function selectListRoomSpaceChangeEmployee(element, url, param, defaultOptionName,counter) 
{


    var elementName = "#" + element;

    if ($("#" + element).length == 0) 
    {
        elementName = "." + element;
    }

    $(elementName + "> option").remove();
    $(elementName).append($("<option />").val("").text("---" + defaultOptionName + "---"));

    // $("#modal_course_id_"+counter).find('option').each(function(){

    //     modal_course_capacity_id = $(this).data('course_capacity_id');
    //     if(course_capacity_id != modal_course_capacity_id)
    //     {
    //         $(this).addClass('hide');
    //     }
    //     else
    //     {
    //         if(count_course_capacity == 1)
    //         {
    //             $(this).attr('selected','selected');
    //             count_course_capacity++;
    //         }
    //     }
    // });

     $.ajax({
        url: url,
        data: param,
        type: "GET", 
        dataType: "json",
        async:false,
        success: function (data) 
        {
                $(elementName).empty();
                $(elementName).append('<option value="0"></option>');

                var option_arr = [];
                var teacher_id_arr = [];
                var count_arr = 0;
                var data_counter = 0;
                $.map(data, function (item) 
                {       
                        data_counter++;
                        $(elementName).append($("<option/>").val(item.value).text(item.text));

                });

        }
       
    }); 



    $(elementName).trigger("change");
}
function get_random(list) {
  return list[Math.floor((Math.random()*list.length))];
} 

function selectListChangeClass(element, url, param, element2,defaultOptionName,date,date_end,course_counter) 
{

    var elementName = "#" + element;

    if ($("#" + element).length == 0) 
    {
        elementName = "." + element;
    }

    $("#course"+course_counter).find(elementName + "> option").remove();
    $("#course"+course_counter).find(elementName).append($("<option />").val("").text("---" + defaultOptionName + "---"));


     $.ajax({
        url: url,
        data: param,
        type: "GET", 
        dataType: "json",
        async:false,
        success: function (data) 
        {   
            $("#course"+course_counter).find(elementName).each(function(){
                $(this).empty();
            });
            var elementId = "#" + element;
            var elementId2 = "#" + element2;
            var count = 1;
            $("#course"+course_counter).find(elementName).each(function(){

                class_id = $(this).data('counter');

                $("#course"+course_counter).find(elementId+"_"+count).append('<option value=""></option>');
                $.map(data, function (item) 
                {      
                    if(item.count == count)
                    {
                        $("#course"+course_counter).find(elementId+"_"+count).append($("<option selected/>").val(item.value).text(item.text));
                        $("#course"+course_counter).find(elementId2+"_"+count+" [value='"+item.counter+"']").attr('selected','selected');

                        // if(item.text1 != 1 && item.text1 != 5 && item.text1 != 7)
                        if(item.text1 != 1 && item.text1 != 5 && item.text1 != 6)
                        {
                             $.ajax({
                                url: "../scheduler/courseCapacityRoomTeacherDataJson",
                                data: 
                                {
                                    'course_capacity_id' : 8, 
                                    'class_id' : class_id,
                                    'date' : date,
                                    'date_end' : date_end,
                                },
                                type: "GET", 
                                dataType: "json",
                                async:false,
                                success: function (data) 
                                {
                                        $("#modal_room_id_"+count).empty();
                                        $("#modal_teacher_name_"+count).empty();
                                        $("#modal_room_id_"+count).append('<option value="0"></option>');


                                        var modal_course_capacity_id_data = $("#course"+course_counter).find("#div_"+count+" div .modal_course_capacity_id").val();
                                        
                                        if(course_counter > 1 && modal_course_capacity_id_data != 0 && modal_course_capacity_id_data != 6)
                                        {
                                            $("#course"+course_counter).find("#div_"+count+" div .modal_room_id:first").remove();
                                            $("#course"+course_counter).find("#div_"+count+" div .modal_teacher_name:first").remove();
                                        }

                                        var option_arr = [];
                                        var teacher_id_arr = [];
                                        var count_arr = 0;
                                        var data_counter = 0;

                                        $.map(data[1], function (item) 
                                        {      
                                                data_counter++;
                                                option_arr[count_arr] = [item.value,item.teacher_id,item.teacher_name];
                                                $("#course"+course_counter).find("#modal_room_id_"+count).append($("<option/>").val(item.value).text(item.text));
                                                count_arr++;
                                        });

                                        if(data_counter != 0)
                                        {
                                            item = get_random(option_arr);

                                            value = item[0];
                                            value1 = item[1];
                                            value2 = item[2];
                                            $("#course"+course_counter).find("#modal_room_id_"+count+" [value='"+value+"']").attr("selected","selected");
                                            $("#course"+course_counter).find("#modal_room_id_"+count).select2().select2('val',value);

                                            // $("#course"+course_counter).find("#modal_teacher_name_"+count).val(value2);
                                            $("#course"+course_counter).find("#modal_teacher_name_"+count).append('<option value="'+value1+'">'+value2+'</option>');
                                            $("#course"+course_counter).find("#modal_teacher_name_"+count+" [value='"+value1+"']").attr("selected","selected");
                                            $("#course"+course_counter).find("#modal_teacher_name_"+count).select2().select2('val',value1);
                                            
                                            $("#course"+course_counter).find("#modal_teacher_name_id_"+count).val(value1);
                                            $("#course"+course_counter).find("#modal_teacher_name_"+count).removeAttr('data-teacher_id');
                                            $("#course"+course_counter).find("#modal_teacher_name_"+count).attr('data-teacher_id',value1);
                                        }

                                        $.map(data[0], function (item) 
                                        {      
                                                $("#course"+course_counter).find("#modal_teacher_name_"+count).append('<option value="'+item.teacher_id+'">'+item.teacher_name+'</option>');
                                                
                                        });

                                }
                               
                            });  
                        }
                        else
                        {   
                             $.ajax({
                                url: "../scheduler/courseCapacityRoomTeacherDataJson",
                                data: 
                                {
                                    'course_capacity_id' : item.text1, 
                                    'class_id' : class_id,
                                    'date' : date,
                                    'date_end' : date_end,
                                },
                                type: "GET", 
                                dataType: "json",
                                async:false,
                                success: function (data) 
                                {
                                        $("#course"+course_counter).find("#modal_room_id_"+count).empty();
                                        $("#course"+course_counter).find("#modal_teacher_name_"+count).empty();
                                        $("#course"+course_counter).find("#modal_room_id_"+count).append('<option value="0"></option>');

                                        var modal_course_capacity_id_data = $("#course"+course_counter).find("#div_"+count+" div .modal_course_capacity_id").val();
                                        
                                        if(course_counter > 1 && modal_course_capacity_id_data != 0 && modal_course_capacity_id_data != 6)
                                        {
                                            $("#course"+course_counter).find("#div_"+count+" div .modal_room_id:first").remove();                                            
                                            $("#course"+course_counter).find("#div_"+count+" div .modal_teacher_name:first").remove();
                                        }


                                        var option_arr = [];
                                        var teacher_id_arr = [];
                                        var count_arr = 0;
                                        var data_counter = 0;

                                        $.map(data[1], function (item) 
                                        {      
                                                data_counter++;
                                                option_arr[count_arr] = [item.value,item.teacher_id,item.teacher_name];
                                                $("#course"+course_counter).find("#modal_room_id_"+count).append($("<option/>").val(item.value).text(item.text).attr('data-course_capacity_id',item.counter));
                                                count_arr++;
                                        });

                                        if(data_counter != 0)
                                        {
                                            item = get_random(option_arr);

                                            value = item[0];
                                            value1 = item[1];
                                            value2 = item[2];
                                            $("#course"+course_counter).find("#modal_room_id_"+count+" [value='"+value+"']").attr("selected","selected");
                                            $("#course"+course_counter).find("#modal_room_id_"+count).select2().select2('val',value);

                                            // $("#course"+course_counter).find("#modal_teacher_name_"+count).val(value2);
                                            $("#course"+course_counter).find("#modal_teacher_name_"+count).append('<option value="'+value1+'">'+value2+'</option>');
                                            $("#course"+course_counter).find("#modal_teacher_name_"+count+" [value='"+value1+"']").attr("selected","selected");
                                            $("#course"+course_counter).find("#modal_teacher_name_"+count).select2().select2('val',value1);
                                            $("#course"+course_counter).find("#modal_teacher_name_id_"+count).val(value1);
                                            $("#course"+course_counter).find("#modal_teacher_name_"+count).removeAttr('data-teacher_id');
                                            $("#course"+course_counter).find("#modal_teacher_name_"+count).attr('data-teacher_id',value1);
                                        }

                                        $.map(data[0], function (item) 
                                        {      
                                                $("#course"+course_counter).find("#modal_teacher_name_"+count).append('<option data-course_capacity_id="'+item.counter+'" value="'+item.teacher_id+'">'+item.teacher_name+'</option>');
                                                
                                        });
                                }
                               
                            }); 
                            
                        }
                    }
                    else
                    {
                        $("#course"+course_counter).find(elementId+"_"+count).append($("<option/>").val(item.value).text(item.text).attr('data-course_capacity_id',item.counter));
                    }   

                }); 

                count++;      
            }); 
            
            var newElement = elementId2.replace("#","");
            $("#course"+course_counter).find("."+newElement).each(function(){

                var modal_course_capacity_id = $(this).val();
                var modal_course_capacity_counter =$(this).data('counter');

                $(elementId+"_"+modal_course_capacity_counter).find('option').each(function(){
                    var course_capacity_id = $(this).data('course_capacity_id');
                    if(course_capacity_id != modal_course_capacity_id && course_capacity_id != undefined)
                    {
                        $(this).addClass('hide');
                    }
                });
            });

            $("#course"+course_counter).find(".modal_room_id").change(function(){

                counter = $(this).data('counter');
                data = $("#course"+course_counter).find("#modal_course_capacity_id_"+counter).val();
                // selectListRoomSpaceChange('modal_room_id_'+counter,'../scheduler/courseCapacityRoomTeacherDataJson',  { 'course_capacity_id': data, 'class_id': counter } ,'Please select a Class Capacity',counter);
                // $.ajax({
                //     url: "../scheduler/getRoomTeacher",
                //     data: 
                //     {
                //         'room_id' : $(this).val(), 
                //         'class_id' : counter, 
                //     },
                //     type: "GET", 
                //     dataType: "json",
                //     async:false,
                //     success: function (data) 
                //     {

                //         $("#modal_teacher_name_"+counter).empty();
                //         $("#course"+course_counter).find("#modal_teacher_name_"+counter).val("");
                //         $("#course"+course_counter).find("#modal_teacher_name_id_"+counter).val("");
                //         $("#course"+course_counter).find("#modal_teacher_name_"+counter).removeAttr('data-teacher_id');

                //         if($data[0])
                //         {
                //             if(data[0].first_name != undefined && data[0].first_name != null)
                //             {
                //                 // $("#course"+course_counter).find("#modal_teacher_name_"+counter).val(data.first_name+' '+data.last_name+'('+data.nickname+')');
                //                 $("#course"+course_counter).find("#modal_teacher_name_"+counter).append('<option value="'+data[0].id+'">'+data[0].first_name+" "+data[0].last_name+" - "+data[0].nickname+'</option>');
                //                 $("#course"+course_counter).find("#modal_teacher_name_"+counter+" [value='"+data[0].id+"']").attr("selected","selected");
                //                 $("#course"+course_counter).find("#modal_teacher_name_"+counter).select2().select2('val',data[0].id);
                //                 $("#course"+course_counter).find("#modal_teacher_name_id_"+counter).val(data[0].id);
                //                 $("#course"+course_counter).find("#modal_teacher_name_"+counter).attr('data-teacher_id',data[0].id);
                //             }
                //         }

                //         // var option_arr = [];
                //         // var option_arr_counter = 0;
                //         // $.map(data[1], function (item) 
                //         // {      
                //         //         $("#course"+course_counter).find("#modal_teacher_name_"+counter).append('<option value="'+item.teacher_id+'">'+item.teacher_name+'</option>');
                //         //         item = get_random(option_arr);
                //         // });
                //     }
                // });
            });  
        }
       
    }); 



    $("#course"+course_counter).find(elementName).trigger("change");
}

function selectListRoomChange(element, url, param, defaultOptionName) 
{


    var elementName = "#" + element;

    if ($("#" + element).length == 0) 
    {
        elementName = "." + element;
    }

    $(elementName + "> option").remove();
    $(elementName).append($("<option />").val("").text("---" + defaultOptionName + "---"));


     $.ajax({
        url: url,
        data: param,
        type: "GET", 
        dataType: "json",
        async:false,
        success: function (data) 
        {
                $(elementName).empty();
                $(elementName).append('<option value="" selected style="display:none">Room</option>');
                $.map(data, function (item) 
                {      
                        $(elementName).append($("<option/>").val(item.value).text(item.text));
                });
        }
       
    }); 



    $(elementName).trigger("change");
}

function selectListSubjectChange(element, url, param, defaultOptionName) 
{


    var elementName = "#" + element;

    if ($("#" + element).length == 0) 
    {
        elementName = "." + element;
    }

    $(elementName + "> option").remove();
    $(elementName).append($("<option />").val("").text("---" + defaultOptionName + "---"));


     $.ajax({
        url: url,
        data: param,
        type: "GET", 
        dataType: "json",
        async:false,
        success: function (data) 
        {
                $(elementName).empty();
                $(elementName).append('<option value="" selected style="display:none">Subject</option>');
                $.map(data, function (item) 
                {      
                        $(elementName).append($("<option/>").val(item.value).text(item.text));
                });
        }
       
    }); 



    $(elementName).trigger("change");
}


function selectDataChange(element, url, param, defaultOptionName) 
{


    
    var elementName = "#" + element;

    if ($("#" + element).length == 0) 
    {
        elementName = "." + element;
    }

    $(elementName + "> option").remove();
    $(elementName).append($("<option />").val("").text("---" + defaultOptionName + "---"));


     $.ajax({
        url: url,
        data: param,
        type: "GET", 
        dataType: "json",
        async:false,
        success: function (data) 
        {
                $(elementName).empty();
                $.map(data, function (item) 
                {
                        $(elementName).append($("<option/>").val(item.value).text(item.text+" "+item.text1));
                        
                });
        }
       
    }); 



    $(elementName).trigger("change");
}


/*function selectListChange(element, url, defaultOptionName) {

   
    var elementName = "#" + element;

    if ($("#" + element).length == 0) {
        elementName = "." + element;
    }

    $(elementName + "> option").remove();
    $(elementName).append($("<option />").val("").text("---" + defaultOptionName + "---"));

    $.ajax({
        url: url, type: "POST", dataType: "json",async:false,
        success: function (response) {
            if (response.success == true)
            {
                $.map(response.data, function (item) {
                    if (item.value == response.selected) {
                        $(elementName).append($("<option selected />").val(item.value).text(item.text));
                    }
                    else {
                        $(elementName).append($("<option />").val(item.value).text(item.text));
                    }
                });
            }
        }
    });


    $(elementName).trigger("change");
}*/


    function selectListChangeMultiParam(element, url,param, defaultOptionName) {

        var elementName = "#" + element;

        if ($("#" + element).length == 0) {
            elementName = "." + element;
        }

        $(elementName + "> option").remove();
        $(elementName).append($("<option />").val("").text("---" + defaultOptionName + "---"));

        $.ajax({
            url: url, type: "POST", dataType: "json", async: false, data: JSON.stringify(param),
            contentType: "application/json; charset=utf-8",

            success: function (response) {
                if (response.success == true) {
                    $.map(response.data, function (item) {
                        if (item.value == response.selected) {
                            $(elementName).append($("<option selected />").val(item.value).text(item.text));
                        }
                        else {
                            $(elementName).append($("<option />").val(item.value).text(item.text));
                        }
                    });
                }
            }
        });

        $(elementName).trigger("change");
/*

        var elementName = "#" + element;
        if ($("#" + element) == null) {
            elementName = "." + element;
        }
        $(elementName + "> option").remove();
        $(elementName).append($("<option />").val("").text("--" + defaultOptionName + "--"));


        $.ajax({
            url: url,
            type: 'POST',
            data: JSON.stringify(param),
            contentType: "application/json; charset=utf-8"
        })
         .done(function (response) {
             var data = $.parseJSON(response);
             for (i = 0; i < data.length; i++) {
                 $(elementName).append($("<option />").val(data[i].Value).text(data[i].Text));
             }
         });

       /* $.get(url).done(function (response) {
            var data = $.parseJSON(response);
            for (i = 0; i < data.length; i++) {
                $(elementName).append($("<option />").val(data[i].Value).text(data[i].Text));
            }
        });
        */

    }
