function getScheduleStudentByTeacher(date,student_id)
{
    $.ajax({
        url: "../scheduler/getScheduleStudentByTeacher",
        data: 
        {
            'student_id' : student_id, 
            'date' : date, 
        },
        type: "GET", 
        dataType: "json",
        async:false,
        success: function (data) 
        {
          scheduleJsonArray =  data;
        }
    });

    return scheduleJsonArray;
}


//this function will populate the time header 
function populateTimeHeader(timeJsonArray,name){
  $("#MainDiv").append('<div class="div-table-row1" id="TimeHeaderRow" style=""></div>');
  $("#TimeHeaderRow").append('<div class="div-table-col-sec">No.</div>');
  $("#TimeHeaderRow").append('<div class="div-table-col-sec-1">'+name+'</div>');

  var count = 0;
  var counter = 0;

  $(jQuery.parseJSON(JSON.stringify(timeJsonArray))).each(function() { 
          count++; 
          var timeStart = this;
          var timeEnd = getTimeByOrderNo(timeStart.order_no+1, timeJsonArray);

          // if(timeEnd != null && count % 2 < 1 || count == 1){
          if(timeStart.order_no % 2 > 0){

              counter++; 
              $("#TimeHeaderRow").append('<div class="div-table-col-time" id="TimeStart'+timeStart.id+'">'+timeStart.time+' '+timeStart.time_session+'<br/>'
                +'<span class="top_text">'
                +'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                +timeEnd.time+' '+timeEnd.time_session
                +'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                +'</span><br/>Period '+counter+'</div>');
          }
  });
}

function populateTimeRoomHeader(timeJsonArray,name){
  $("#MainDiv").append('<div class="div-table-row"  id="TimeHeaderRow"></div>');
  $("#TimeHeaderRow").append('<div class="div-table-col-sec-1">'+name+'</div>');

  var count = 0;
  var counter = 0;

  $(jQuery.parseJSON(JSON.stringify(timeJsonArray))).each(function() { 
          count++; 
          var timeStart = this;
          var timeEnd = getTimeByOrderNo(timeStart.order_no+1, timeJsonArray);
          if(timeEnd != null && count % 2 < 1 || count == 1){

              counter++; 
              $("#TimeHeaderRow").append('<div class="div-table-col-time" id="TimeStart'+timeStart.id+'">'+timeStart.time+' '+timeStart.time_session+'<br/>'
                +'<span class="top_text">'
                +'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                +timeEnd.time+' '+timeEnd.time_session
                +'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                +'</span><br/>Period '+counter+'</div>');
          }
  });
}

function populateTimeHeaderGroup(timeJsonArray){
  $("#MainDiv").append('<div class="div-table-row1"  id="TimeHeaderRow"></div>');
  $("#TimeHeaderRow").append('<div class="div-table-col-sec">Room</div>');

  var count = 0;
  var counter = 0;

  $(jQuery.parseJSON(JSON.stringify(timeJsonArray))).each(function() { 
          count++; 
          var timeStart = this;
          var timeEnd = getTimeByOrderNo(timeStart.order_no+1, timeJsonArray);
          if(timeEnd != null && count % 2 < 1 || count == 1){

              counter++; 
              $("#TimeHeaderRow").append('<div class="div-table-col-time-group1" id="TimeStart'+timeStart.id+'">'+timeStart.time+' '+timeStart.time_session+'<br/>'
                +'<span class="top_text">'
                +'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                +timeEnd.time+' '+timeEnd.time_session
                +'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                +'</span><br/>Period '+counter+'</div>');
          }
  });
}

function getTimeByOrderNo(orderNo, timeJsonArray)
{
  var time = null; 

    $(jQuery.parseJSON(JSON.stringify(timeJsonArray))).each(function() {
        if(orderNo == this.order_no){  
          time = this; 
        }
    });

  return time;
}


function getTimeById(timeId, timeJsonArray)
{
  var time = null;

   $(jQuery.parseJSON(JSON.stringify(timeJsonArray))).each(function() {
              if(timeId == this.id){  
                time = this; 
            }
    });

   return time;
}


function populateIndexSchedule(sectionJsonArray,scheduleJsonArray,timeJsonArray){


  $(jQuery.parseJSON(JSON.stringify(sectionJsonArray))).each(function() {  
           var id = this.id;
           var level = this.level;
            var name = this.name;
            $("#MainDiv").append('<div class="div-table-row"  id="Section'+id+'"></div>');
            $("#Section"+id).append('<div class="div-table-col-sec">'+name+'('+level+')</div>');

            populateScheduleBySection(scheduleJsonArray, timeJsonArray, id)

  });
}

function populateSchedule(sectionJsonArray,scheduleJsonArray,timeJsonArray){


  $(jQuery.parseJSON(JSON.stringify(sectionJsonArray))).each(function() {  
           var id = this.id;
           var level = this.level;
            var name = this.name;
            $("#MainDiv").append('<div class="div-table-row"  id="Section'+id+'"></div>');
            $("#Section"+id).append('<div class="div-table-col-sec">'+name+'</div>');

            populateScheduleBySection(scheduleJsonArray, timeJsonArray, id)

  });
}

function populateRoomSchedule(sectionJsonArray,scheduleJsonArray,timeJsonArray){

  var count = 0;
  $(jQuery.parseJSON(JSON.stringify(sectionJsonArray))).each(function() {  
           var id = this.value;
            var name = this.text;
            count++;
            $("#MainDiv").append('<div class="div-table-row1" id="Room'+id+'"></div>');
            $("#Room"+id).append('<div class="div-table-col-sec">'+count+'</div>');
            $("#Room"+id).append('<div class="div-table-col-sec-1">'+name.substr(0,10)+'</div>');

            populateScheduleByRoom(scheduleJsonArray,timeJsonArray, id)
  });
}

function populateRoomGroupSchedule(roomJsonArray,scheduleJsonArray,timeJsonArray){


  $(jQuery.parseJSON(JSON.stringify(roomJsonArray))).each(function() {  
           var id = this.value;
           // alert(id);
            var name = this.text;
            var room_capacity = this.capacity;

            
            $("#MainDiv").append('<div class="div-table-row1"  id="Room'+id+'"></div>');
            // for (var i = 0; i < 10; i++) {
            //   if(i == 0)
            //   {
                $("#Room"+id).append('<div id="room_name_'+id+'" class="div-table-col-sec-group"><span class="div-table-col-sec-group-span">'+name+'</span></div>');
                // $("#Room"+id).append('<div id="room_name_'+id+'" class="div-table-col-sec-group"><span class="div-table-col-sec-group-span">'+name.substr(0,10)+'</span></div>');
              // }
              // else
              // {
                // $("#Room"+id+"_"+i).append('<div class="div-table-col-sec"></div>');
              // }
              populateScheduleByRoomGroup(scheduleJsonArray,timeJsonArray, id,room_capacity)
            // }
  });
}

function populateTeacherSchedule(teacherJsonArray,scheduleJsonArray,timeJsonArray){

  var count = 0;
  $(jQuery.parseJSON(JSON.stringify(teacherJsonArray))).each(function() {  
            count++;
            var id = this.id;
            var fname = this.first_name;
            var lname = this.last_name;
            var nickname = this.nickname;
            var room_name = this.room_name;
            var program_color = this.program_color;

            $("#MainDiv").append('<div class="div-table-row1"  id="Teacher'+id+'"></div>');
            if(room_name== null)
            {
              $("#Teacher"+id).append('<div class="div-table-col-sec"></div>');
            }
            else
            {
              $("#Teacher"+id).append('<div class="div-table-col-sec">'+room_name+'</div>');
            }
            $("#Teacher"+id).append('<div class="div-table-col-sec-student" id="teacher_color_'+id+'" style="background-color:'+program_color+'">'+fname+' '+lname+'<br>('+nickname+')</div>');

            populateScheduleByTeacher(scheduleJsonArray,timeJsonArray, id)

  });
}

function populateStudentSchedule(studentJsonArray,scheduleJsonArray,timeJsonArray){

  var count = 0;
  $(jQuery.parseJSON(JSON.stringify(studentJsonArray))).each(function() {  
            count++;
            var id = this.student_id;
            // var fname = this.first_name;
            // var lname = this.last_name;
            // console.log("Display student");
            var name = this.sename;
            var abrod_date = this.date_from;
            var end_date = this.date_to;
            var nickname = this.nickname;

            $("#MainDiv").append('<div class="div-table-row1"  id="Teacher'+id+'"></div>');
            $("#Teacher"+id).append('<div class="div-table-col-sec">'+count+'</div>');

            var date_now = new Date();
            var year = date_now.getFullYear();
            var month = date_now.getMonth()+1;
            var day = date_now.getDate();

            var date_now1 = new Date();
            var year = date_now1.getFullYear();
            var month = date_now1.getMonth()+1;
            var day = date_now1.getDate();

            day_length = day.toString().length;
            month_length = month.toString().length;

            if(month_length == 1)
            {
                month = '0'+month;
            }

            if(day_length == 1)
            {
                day = '0'+day;
            }

            date_deducted = date_now.setDate(date_now.getDate()+5);
            date_subtracted = date_now1.setDate(date_now1.getDate()-5);

            date_now = year+'-'+month+'-'+day;
            date_add = new Date(date_deducted);

            date_add_year = date_add.getFullYear();
            date_add_month = date_add.getMonth()+1;
            date_add_day = date_add.getDate();

            date_new = new Date(date_subtracted);

            date_new_year = date_new.getFullYear();
            date_new_month = date_new.getMonth()+1;
            date_new_day = date_new.getDate();

            date_new_length = date_new_day.toString().length;
            month_new_length = date_new_month.toString().length;

            if(month_new_length == 1)
            {
                date_new_month = '0'+date_new_month;  
            }

            if(date_new_length == 1)
            {
                date_new_day = '0'+date_new_day;
            }

            day_add_length = date_add_day.toString().length;
            month_add_length = date_add_month.toString().length;

            if(month_add_length == 1)
            {
                date_add_month = '0'+date_add_month;
            }
            
            if(day_add_length == 1)
            {
                date_add_day = '0'+date_add_day;
            }

            
            date_add = date_add_year+'-'+date_add_month+'-'+date_add_day;
            date_new = date_new_year+'-'+date_new_month+'-'+date_new_day;
            // $("#Teacher"+id).append('<div class="div-table-col-sec-1 student_name_block" style="background-color:#CC99FF" data-student_eng_name="'+name+'" data-student="'+id+'" data-toggle="modal" data-target="#timeModal" data-abrod_date="'+abrod_date+'" data-end_date="'+end_date+'">'+name+'</div>');
            
            if(end_date >= date_now && end_date <= date_add)
            {
              $("#Teacher"+id).append('<div id="student_color_'+id+'" data-modal_count="1" class="div-table-col-sec-student student_data" data-nickname="'+nickname+'" data-student_eng_name="'+name+'" data-student="'+id+'" data-toggle="modal" data-target="#timeModal" data-near="1" data-new="0" data-abrod_date="'+abrod_date+'" data-end_date="'+end_date+'">'+name+'<br><span id="student_course_name_'+id+'"></span></div>');
            }
            else
            {
              if(date_new <= abrod_date && abrod_date <= date_now)
              {
                $("#Teacher"+id).append('<div id="student_color_'+id+'" data-modal_count="1" class="div-table-col-sec-student student_data" data-nickname="'+nickname+'" data-student_eng_name="'+name+'" data-student="'+id+'" data-toggle="modal" data-target="#timeModal" data-near="0" data-new="1" data-abrod_date="'+abrod_date+'" data-end_date="'+end_date+'">'+name+'<br><span id="student_course_name_'+id+'"></span></div>');
              }
              else
              {
                $("#Teacher"+id).append('<div id="student_color_'+id+'" data-modal_count="1" class="div-table-col-sec-student student_data" data-nickname="'+nickname+'" data-student_eng_name="'+name+'" data-student="'+id+'" data-toggle="modal" data-target="#timeModal" data-near="0" data-new="0" data-abrod_date="'+abrod_date+'" data-end_date="'+end_date+'">'+name+'<br><span id="student_course_name_'+id+'"></span></div>');
              
              }
            }


            populateScheduleByStudent(scheduleJsonArray,timeJsonArray, id,name)

  });
}


function populateScheduleBySection(scheduleJsonArray,timeJsonArray, sectionId){
        var timeId;
        var timeDescription;
        var nextTimeId = null;


         $(jQuery.parseJSON(JSON.stringify(timeJsonArray))).each(function() {  
            timeId = this.id;
            timeDescription = this.time;

            if(timeId > 62 && timeId < 242)
            {

                  var timeEndCounter = getTimeByOrderNo(this.order_no+1, timeJsonArray);
                  // var condition = 0;
                  

                  if(nextTimeId==null){
                      nextTimeId = timeId;
                  }

            
                  if(timeId <= nextTimeId){
                      $(jQuery.parseJSON(JSON.stringify(scheduleJsonArray))).each(function() {  
                            // var id = this.id;
                            // var timeStart = this.time_start;
                            // var timeEnd = this.time_end;
                            // var classs = this.class
                            // var scheduleSectionId = this.section_id


                            var schedule = this;
                            

                            if(timeId == schedule.time_start && sectionId == schedule.section_id){

                                var timeStart = getTimeById(schedule.time_start,timeJsonArray);
                                var timeEnd = getTimeById(schedule.time_end,timeJsonArray);
                                var colspan = timeEnd.order_no - timeStart.order_no;

                                if(colspan>1){
                                  $("#Section"+sectionId).append('<div class="div-table-colspan'+colspan+'" onclick="editSchedule('+schedule.class_id+','+schedule.subject_offered_id+','+sectionId+','+schedule.time_start+','+schedule.time_end+','+schedule.teacher_id+','+schedule.campus_id+','+schedule.room_id+','+schedule.building_id+','+schedule.schedule_type_id+','+schedule.capacity+')" data-id="'+schedule.id+'" data-section="'+sectionId+'" data-time_start="'+schedule.time_start+'" data-time_end="'+schedule.time_end+'">'+schedule.class+'</div>');  
                                }else
                                {
                                  $("#Section"+sectionId).append('<div class="div-table-col" onclick="editSchedule('+schedule.class_id+','+schedule.subject_offered_id+','+sectionId+','+schedule.time_start+','+schedule.time_end+','+schedule.teacher_id+','+schedule.campus_id+','+schedule.room_id+','+schedule.building_id+','+schedule.schedule_type_id+','+schedule.capacity+')" data-id="'+schedule.id+'" data-section="'+sectionId+'" data-time_start="'+schedule.time_start+'" data-time_end="'+schedule.time_end+'">'+schedule.class+'</div>')
                                }
                                nextTimeId = schedule.time_end;
                                // condition = schedule.time_start;

                            }
                            
                           
                      });
                            if(timeId == nextTimeId){

                            
                                nextTimeId = null;
                                if(timeEndCounter != null && timeEndCounter != undefined)
                                {
                                  $("#Section"+sectionId).append('<div class="div-table-col-blank" data-toggle="modal" data-target="#timeModal" data-section="'+sectionId+'" data-time="'+timeId+'"></div>');  
                                }
                            }

                  }
            }
            // else if(timeEndCounter != null && timeEndCounter != undefined){
            //    $("#Section"+sectionId).append('<div class="div-table-col-blank" data-toggle="modal" data-target="#timeModal" data-section="'+sectionId+'" data-time="'+timeId+'">no classs</div>');  
            // }

            // else{

            // }  



          });
    closeModal();
} 

function populateScheduleByRoom(scheduleJsonArray,timeJsonArray, roomId){
        var timeId;
        var timeDescription;
        var nextTimeId = null;
        var count = 0;

        $(jQuery.parseJSON(JSON.stringify(timeJsonArray))).each(function() {  
            timeId = this.id;
            tempId = this.temp_id;
            timeDescription = this.time;
            count++;
            // if(timeId > 62 && timeId < 242)
            // if(count % 2 < 1 || count == 1)
            if(this.order_no % 2 > 0)
            {

                    var timeEndCounter = getTimeByOrderNo(this.order_no+1, timeJsonArray);
                    // var condition = 0;
                    

                    if(nextTimeId==null){
                        nextTimeId = tempId;
                    }

                    if(tempId <= nextTimeId){
                        $(jQuery.parseJSON(JSON.stringify(scheduleJsonArray))).each(function() {  
                              // var id = this.id;
                              // var timeStart = this.time_start;
                              // var timeEnd = this.time_end;
                              // var classs = this.class
                              // var scheduleSectionId = this.section_id


                              var schedule = this;
                              

                              if(timeId == schedule.time_start && roomId == schedule.room_id){

                                  var timeStart = getTimeById(schedule.time_start,timeJsonArray);
                                  var timeEnd = getTimeById(schedule.time_end,timeJsonArray);
                                  var colspan = timeEnd.order_no - timeStart.order_no;

                                  // if(colspan>1){
                                  //   $("#Room"+roomId).append('<div class="div-table-colspan'+colspan+'" data-id="'+schedule.id+'" data-room="'+roomId+'" data-time_start="'+schedule.time_start+'" data-time_end="'+schedule.time_end+'">'+schedule.class+'</div>');  
                                  // }else
                                  // {
                                    $("#Room"+roomId).append('<div class="div-table-col" data-id="'+schedule.id+'" data-room="'+roomId+'" style="background-color:'+schedule.program_color+' !important;color:#000 !important;" data-time_start="'+schedule.time_start+'" data-time_end="'+schedule.time_end+'">'+schedule.class+'</div>')
                                  // }
                                  nextTimeId = schedule.tempId;
                                  // condition = schedule.time_start;

                              }
                              
                             
                        });
                              if(tempId == nextTimeId){

                              
                                  nextTimeId = null;
                                  if(timeEndCounter != null && timeEndCounter != undefined)
                                  {
                                    $("#Room"+roomId).append('<div class="div-table-col-blank sched" data-toggle="modal" data-target="#timeModal" data-room="'+roomId+'" data-time="'+timeId+'">Vacant</div>');  
                                  }
                              }

                    }
            }
            // else if(timeEndCounter != null && timeEndCounter != undefined){
            //    $("#Section"+sectionId).append('<div class="div-table-col-blank" data-toggle="modal" data-target="#timeModal" data-section="'+sectionId+'" data-time="'+timeId+'">no classs</div>');  
            // }

            // else{

            // }  

        });
} 

function populateScheduleByRoomGroup(scheduleJsonArray,timeJsonArray, roomId,room_capacity){
        var timeId;
        var timeDescription;
        var nextTimeId = null;
        var count = 0;
        var count_in = 0;

        var activity_class_sched = ['EVERYDAY IDIOMS AND EXPRESSIONS','PRESENTATION 1','COMMUNICATION A-Z 3','MARKET LEADER','CINEMA FOCUS','HELLO POPS','AMERICAN ACCENT TRAINING 1','COMMUNICATION','AMERICAN ACCENT TRAINING 2','SPEAK ENGLISH LIKE AN AMERICAN',''];

        $(jQuery.parseJSON(JSON.stringify(timeJsonArray))).each(function() {  
            timeId = this.id;
            tempId = this.temp_id;
            timeDescription = this.time;
            count++;
            // if(timeId > 62 && timeId < 242)
            // if(count % 2 < 1 || count == 1)
            if(this.order_no % 2 > 0)
            {
                if(count <= 21)
                {

                    $("#Room"+roomId).append('<div class="div-table-col-blank-group-room1" id="room_div_'+roomId+'_'+timeId+'"></div>');
                    var timeEndCounter = getTimeByOrderNo(this.order_no+1, timeJsonArray);
                    // var condition = 0;
                    
                       // $("#room_div_"+roomId+'_'+timeId).append('<div class="div-table-col-blank-group" data-toggle="modal" data-target="#timeGroupModal" data-room="'+roomId+'" data-time="'+timeId+'">Vacant</div>');  
                    if(roomId == 149)
                    {
                      var activity = activity_class_sched[count_in];
                      $("#room_div_"+roomId+'_'+timeId).append('<div class="div-table-col-blank-group1" style="background-color:#99CCFF !important;" data-room="'+roomId+'" data-time="'+timeId+'">'+activity+'<span id="teacher_sched_'+roomId+'_'+timeId+'"></span></div>'); 
                    }
                    else
                    {
                      $("#room_div_"+roomId+'_'+timeId).append('<div id="teacher_sched_'+roomId+'_'+timeId+'" class="div-table-col-blank-group1" style="background-color:#808080 !important;" data-room="'+roomId+'" data-time="'+timeId+'"></div>');   
                    }
                    
                    count_in++;
                    

                    if(nextTimeId==null){
                        nextTimeId = tempId;
                    }

                    var counter_class = 0;
                    // if(tempId <= nextTimeId){
                        $(jQuery.parseJSON(JSON.stringify(scheduleJsonArray))).each(function() {  
                              // var id = this.id;
                              // var timeStart = this.time_start;
                              // var timeEnd = this.time_end;
                              // var classs = this.class
                              // var scheduleSectionId = this.section_id


                              var schedule = this;
                              if(timeId == schedule.time_start && roomId == schedule.room_id){

                                  var timeStart = getTimeById(schedule.time_start,timeJsonArray);
                                  var timeEnd = getTimeById(schedule.time_end,timeJsonArray);
                                  var colspan = timeEnd.order_no - timeStart.order_no;

                                  var level_code = schedule.level_code;
                                  var nickname = schedule.nickname;
                                  if(level_code == null)
                                  {
                                    level_code = "";
                                  }
                                  if(nickname == null)
                                  {
                                    nickname = "";
                                  }
                                  else
                                  {
                                    nickname = '('+nickname+')';
                                  }

                                  var schedule_book_title = "";
                                  if(schedule.book_title != "undefined")
                                  {
                                    schedule_book_title = schedule.book_title;
                                    if(schedule_book_title != undefined)
                                    {
                                      schedule_book_title = schedule_book_title.substr(0,19)+"<br>";
                                    }
                                    else
                                    {
                                      schedule_book_title = schedule_book_title+"<br>";
                                    }
                                  }
                                  counter_class++;
                                    
                                    $("#teacher_sched_"+roomId+'_'+timeId).empty();
                                    if(schedule_book_title && nickname)
                                    {
                                      $("#teacher_sched_"+roomId+'_'+timeId).append(schedule_book_title+"<b style='font-size:12px;'>"+nickname+"</b> - "+schedule.course_name);
                                    }
                                    else if(nickname)
                                    {
                                      $("#teacher_sched_"+roomId+'_'+timeId).append("<b style='font-size:12px;'>"+nickname+"</b> - "+schedule.course_name);
                                    
                                    }
                                    else
                                    {
                                      $("#teacher_sched_"+roomId+'_'+timeId).append(schedule.course_name);

                                    }
                                    $("#teacher_sched_"+roomId+'_'+timeId).removeAttr('style');

                                    var teacher_program_color = schedule.teacher_program_color;

                                    if(teacher_program_color == null)
                                    {
                                      teacher_program_color = "#00FFFF";
                                    }
                                    if(roomId != 149)
                                    {
                                      $("#teacher_sched_"+roomId+'_'+timeId).attr('style','background-color:'+teacher_program_color);
                                    }

                                    if(schedule.course_id == 26 || schedule.course_id == 27 || schedule.course_id == 28 || schedule.course_id == 29 || schedule.course_id == 45 || schedule.course_id == 7 || schedule.course_id == 14 || schedule.course_id == 6)
                                    {
                                        $("#teacher_sched_"+roomId+'_'+timeId).removeAttr('style');
                                        $("#teacher_sched_"+roomId+'_'+timeId).attr('style','background-color:#33CCCC');
                                    }
                                    else if(schedule.course_id == 69)
                                    {
                                        $("#teacher_sched_"+roomId+'_'+timeId).removeAttr('style');
                                        $("#teacher_sched_"+roomId+'_'+timeId).attr('style','background-color:#99CCFF');
                                    }
                                    else if(schedule.program_category_id == 1)
                                    {
                                        if(schedule.course_id == 38)
                                        {  
                                            if(schedule.program_id == 6)
                                            {
                                                $("#teacher_sched_"+roomId+'_'+timeId).removeAttr('style');
                                                $("#teacher_sched_"+roomId+'_'+timeId).attr('style','background-color:#8064A2');
                                            }
                                            else
                                            {
                                                $("#teacher_sched_"+roomId+'_'+timeId).removeAttr('style');
                                                $("#teacher_sched_"+roomId+'_'+timeId).attr('style','background-color:#CC99FF');
                                            }
                                        }
                                        else{

                                            $("#teacher_sched_"+roomId+'_'+timeId).removeAttr('style');
                                            $("#teacher_sched_"+roomId+'_'+timeId).attr('style','background-color:#CC99FF');

                                        }
                                    }
                                    else if(schedule.program_category_id == 2)
                                    {
                                        if(schedule.course_id == 23)
                                        {  
                                            if(schedule.program_id == 7)
                                            {
                                                $("#teacher_sched_"+roomId+'_'+timeId).removeAttr('style');
                                                $("#teacher_sched_"+roomId+'_'+timeId).attr('style','background-color:#FF00FF');
                                            }
                                            else
                                            {
                                                $("#teacher_sched_"+roomId+'_'+timeId).removeAttr('style');
                                                $("#teacher_sched_"+roomId+'_'+timeId).attr('style','background-color:#FF8080');
                                            }
                                        }
                                        else{

                                            $("#teacher_sched_"+roomId+'_'+timeId).removeAttr('style');
                                            $("#teacher_sched_"+roomId+'_'+timeId).attr('style','background-color:#FF8080');

                                        }
                                    }
                                    else if(schedule.program_category_id == 3)
                                    {
                                        if(schedule.level_value != "")
                                        {
                                            if(schedule.level_value <= 3)
                                            {
                                              $("#teacher_sched_"+roomId+'_'+timeId).removeAttr('style');
                                              $("#teacher_sched_"+roomId+'_'+timeId).attr('style','background-color:#339966');
                                            }
                                            else
                                            {
                                              $("#teacher_sched_"+roomId+'_'+timeId).removeAttr('style');
                                              $("#teacher_sched_"+roomId+'_'+timeId).attr('style','background-color:#99CC00');
                                            }
                                        }

                                        if(schedule.program_id == 22 || schedule.program_id == 23)
                                        {
                                            $("#teacher_sched_"+roomId+'_'+timeId).removeAttr('style');
                                            $("#teacher_sched_"+roomId+'_'+timeId).attr('style','background-color:#808000');
                                        }
                                    }
                                    else
                                    {
                                    }

                                    $("#room_div_"+roomId+'_'+timeId).append('<div class="div-table-col-blank-student1" id="room_div_data_'+roomId+'_'+timeId+'" data-toggle="modal" data-target="#timeGroupEditModal" data-id="'+schedule.id+'" data-room="'+roomId+'" data-time_start="'+schedule.time_start+'" data-time_end="'+schedule.time_end+'">'+schedule.student_english_name+'<br/>'+schedule.student_nickname+'('+level_code+')</div>')
                                    $("#room_div_data_"+roomId+'_'+timeId).removeAttr('style');
                                    $("#room_div_data_"+roomId+'_'+timeId).attr('style','background-color:'+schedule.program_color);
                                  nextTimeId = schedule.tempId;
                                  // condition = schedule.time_start;

                              }       
                             
                        });
                              // if(tempId == nextTimeId){
                                
                                $("#room_name_"+roomId).removeAttr('style');
                                var height = (parseInt(room_capacity)+1) * 40;

                                $("#room_name_"+roomId).attr('style','height:'+height+'px;');

                                  for(counter_class; counter_class < room_capacity; counter_class++)
                                  {
                                      nextTimeId = null;
                                      if(timeEndCounter != null && timeEndCounter != undefined)
                                      {
                                        // $("#room_div_"+roomId+'_'+timeId).append('<div class="div-table-col-blank-group" data-toggle="modal" data-target="#timeGroupModal" data-room="'+roomId+'" data-time="'+timeId+'">Vacant</div>');  
                                        $("#room_div_"+roomId+'_'+timeId).append('<div class="div-table-col-blank-group1" data-room="'+roomId+'" data-time="'+timeId+'">Vacant</div>');  
                                      }
                    
                                  } 
                              // }

                    // }
                }
            }
            // else if(timeEndCounter != null && timeEndCounter != undefined){
            //    $("#Section"+sectionId).append('<div class="div-table-col-blank" data-toggle="modal" data-target="#timeModal" data-section="'+sectionId+'" data-time="'+timeId+'">no classs</div>');  
            // }

            // else{

            // }  

        });
} 


function populateScheduleByTeacher(scheduleJsonArray,timeJsonArray, teacherId){
        var timeId;
        var timeDescription;
        var nextTimeId = null;
        var count = 0;


          var class_counter = 0;
          $(jQuery.parseJSON(JSON.stringify(timeJsonArray))).each(function() {  
            timeId = this.id;
            tempId = this.temp_id;

            timeDescription = this.time;
            count++;
            // if(count % 2 < 1 || count == 1)
            if(this.order_no % 2 > 0)
            {
                  class_counter++;
                    var timeEndCounter = getTimeByOrderNo(this.order_no+1, timeJsonArray);
                    // var condition = 0;
                    

                    if(nextTimeId==null){
                        nextTimeId = tempId;
                    }

                    if(tempId <= nextTimeId){
                              $(jQuery.parseJSON(JSON.stringify(scheduleJsonArray))).each(function() {  
                                    // var id = this.id;
                                    // var timeStart = this.time_start;
                                    // var timeEnd = this.time_end;
                                    // var classs = this.class
                                    // var scheduleSectionId = this.section_id

                                    var schedule = this;
                                    color_count = 0;

                                    if(timeId == schedule.time_start && teacherId == schedule.teacher_id){

                                        var timeStart = getTimeById(schedule.time_start,timeJsonArray);
                                        var timeEnd = getTimeById(schedule.time_end,timeJsonArray);
                                        var colspan = timeEnd.order_no - timeStart.order_no;
                                        var subject_class = schedule.class;
                                        var student_english_name = schedule.student_english_name;
                                        var level_code = schedule.level_code;
                                        var nickname = schedule.nickname;
                                        var course_capacity_count = schedule.course_capacity_count;

                                        // if(color_count == 0)
                                        // {
                                        //   $("#teacher_color_"+teacherId).removeAttr('style');
                                        //   $("#teacher_color_"+teacherId).attr('style','background-color:'+schedule.program_color);
                                        //   color_count++;
                                        // }

                                        if(course_capacity_count > 1)
                                        {
                                          if(schedule.teacher_room != 0)
                                          {
                                            // $("#Teacher"+teacherId).append('<div class="div-table-col sched edit_schedule" style="background-color:'+schedule.program_color+' !important;color:#000 !important;" data-batch_id="'+schedule.batch_id+'"  data-course_capacity_id="'+schedule.course_capacity_id+'" data-program_id="'+schedule.program_id+'" data-course_id="'+schedule.course_id+'" data-room_id="'+schedule.room_id+'" data-id="'+schedule.id+'" data-teacher="'+teacherId+'" data-time_start="'+schedule.time_start+'" data-time_end="'+schedule.time_end+'">'
                                            $("#Teacher"+teacherId).append('<div data-class_id="'+class_counter+'" id="sched_'+teacherId+'_'+class_counter+'" data-room_name="" data-details="Group Class" data-color="'+schedule.program_color+'" data-toggle="modal" data-target="#timeDutyModal" class="div-table-col sched" style="background-color:'+schedule.program_color+' !important;color:#000 !important;" data-batch_id="'+schedule.batch_id+'"  data-course_capacity_id="'+schedule.course_capacity_id+'" data-program_id="'+schedule.program_id+'" data-course_id="'+schedule.course_id+'" data-room_id="'+schedule.room_id+'" data-id="'+schedule.id+'" data-teacher="'+teacherId+'" data-time_start="'+schedule.time_start+'" data-time_end="'+schedule.time_end+'">'
                                              +'<span class="top_text">'
                                              // +'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                                              // + subject_class.substr(0,10)
                                              // +'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                                              +'</span><br/>Group Class</div>');
                                          }
                                          else
                                          {

                                            if(schedule.program_id == 23 || schedule.program_id == 22)
                                            {
                                              $("#Teacher"+teacherId).append('<div data-class_id="'+class_counter+'" id="sched_'+teacherId+'_'+class_counter+'" data-room_name="'+schedule.room_name+'" data-details="Group Class" data-color="'+schedule.program_color+'" data-toggle="modal" data-target="#timeDutyModal" class="div-table-col sched" style="background-color:'+schedule.program_color+' !important;color:#000 !important;" data-batch_id="'+schedule.batch_id+'"  data-course_capacity_id="'+schedule.course_capacity_id+'" data-program_id="'+schedule.program_id+'" data-course_id="'+schedule.course_id+'" data-room_id="'+schedule.room_id+'" data-id="'+schedule.id+'" data-teacher="'+teacherId+'" data-time_start="'+schedule.time_start+'" data-time_end="'+schedule.time_end+'">'
                                                +'<span class="top_text">'
                                                +'</span><br/>Group Class <br>(<b style="color:#fff">'+schedule.room_name+'</b>)</div>');
                                            }
                                            else
                                            {
                                              $("#Teacher"+teacherId).append('<div data-class_id="'+class_counter+'" id="sched_'+teacherId+'_'+class_counter+'" data-room_name="'+schedule.room_name+'" data-details="Group Class" data-color="'+schedule.program_color+'" data-toggle="modal" data-target="#timeDutyModal" class="div-table-col sched" style="background-color:'+schedule.program_color+' !important;color:#000 !important;" data-batch_id="'+schedule.batch_id+'"  data-course_capacity_id="'+schedule.course_capacity_id+'" data-program_id="'+schedule.program_id+'" data-course_id="'+schedule.course_id+'" data-room_id="'+schedule.room_id+'" data-id="'+schedule.id+'" data-teacher="'+teacherId+'" data-time_start="'+schedule.time_start+'" data-time_end="'+schedule.time_end+'">'
                                                +'<span class="top_text">'
                                                +'</span><br/>Group Class <br>(<b style="color:#D14130">'+schedule.room_name+'</b>)</div>');
                                            }
                                          }  
                                        }
                                        else
                                        {
                                          if(schedule.teacher_room != 0)
                                          {
                                            // if(colspan>1){
                                              // $("#Teacher"+teacherId).append('<div class="div-table-col sched edit_schedule" style="background-color:'+schedule.program_color+' !important;color:#000 !important;" data-batch_id="'+schedule.batch_id+'"  data-course_capacity_id="'+schedule.course_capacity_id+'" data-program_id="'+schedule.program_id+'" data-course_id="'+schedule.course_id+'" data-room_id="'+schedule.room_id+'" data-id="'+schedule.id+'" data-teacher="'+teacherId+'" data-time_start="'+schedule.time_start+'" data-time_end="'+schedule.time_end+'">'
                                              $("#Teacher"+teacherId).append('<div data-class_id="'+class_counter+'" id="sched_'+teacherId+'_'+class_counter+'" data-room_name="" data-toggle="modal" data-target="#timeDutyModal" class="div-table-col sched" style="background-color:'+schedule.program_color+' !important;color:#000 !important;" data-batch_id="'+schedule.batch_id+'"  data-course_capacity_id="'+schedule.course_capacity_id+'" data-program_id="'+schedule.program_id+'" data-course_id="'+schedule.course_id+'" data-room_id="'+schedule.room_id+'" data-id="'+schedule.id+'" data-teacher="'+teacherId+'" data-time_start="'+schedule.time_start+'" data-time_end="'+schedule.time_end+'">'
                                                +'<span class="top_text">'
                                                +'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                                                +'<span style="font-size:9px !important;">'+student_english_name+'</span>'
                                                +'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                                                +'</span><br/>'
                                                +schedule.course_name+' ('+schedule.course_capacity_code+')<br/>'
                                                // +'<span style="font-size:9px !important;">('+nickname+') '+level_code+'</span>'
                                                +'<span style="font-size:9px !important;">('+nickname+') </span>'
                                                +'</div>');  
                                            // }else
                                            // {
                                            //   $("#Teacher"+teacherId).append('<div class="div-table-col sched" style="background-color:'+schedule.program_color+' !important;" data-id="'+schedule.id+'" data-teacher="'+teacherId+'" data-time_start="'+schedule.time_start+'" data-time_end="'+schedule.time_end+'">'+schedule.class+'</div>')
                                            // }
                                          }
                                          else
                                          {

                                            if(schedule.program_id == 23 || schedule.program_id == 22)
                                            {
                                              $("#Teacher"+teacherId).append('<div data-class_id="'+class_counter+'" id="sched_'+teacherId+'_'+class_counter+'" data-toggle="modal" data-target="#timeDutyModal" class="div-table-col sched" style="background-color:'+schedule.program_color+' !important;color:#000 !important;" data-batch_id="'+schedule.batch_id+'"  data-course_capacity_id="'+schedule.course_capacity_id+'" data-program_id="'+schedule.program_id+'" data-course_id="'+schedule.course_id+'" data-room_id="'+schedule.room_id+'" data-id="'+schedule.id+'" data-teacher="'+teacherId+'" data-time_start="'+schedule.time_start+'" data-time_end="'+schedule.time_end+'">'
                                                +'<span class="top_text">'
                                                +'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                                                +'<span style="font-size:9px !important;">'+student_english_name+'</span>'
                                                +'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                                                +'</span><br/>'
                                                +schedule.course_name+' ('+schedule.course_capacity_code+')<br/>'
                                                +'<span style="font-size:9px !important;">('+nickname+') <b style="color:#fff">'+schedule.room_name+'</b></span>'
                                                +'</div>');  
                                            }
                                            else
                                            {
                                              $("#Teacher"+teacherId).append('<div data-class_id="'+class_counter+'" id="sched_'+teacherId+'_'+class_counter+'" data-toggle="modal" data-target="#timeDutyModal" class="div-table-col sched" style="background-color:'+schedule.program_color+' !important;color:#000 !important;" data-batch_id="'+schedule.batch_id+'"  data-course_capacity_id="'+schedule.course_capacity_id+'" data-program_id="'+schedule.program_id+'" data-course_id="'+schedule.course_id+'" data-room_id="'+schedule.room_id+'" data-id="'+schedule.id+'" data-teacher="'+teacherId+'" data-time_start="'+schedule.time_start+'" data-time_end="'+schedule.time_end+'">'
                                                +'<span class="top_text">'
                                                +'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                                                +'<span style="font-size:9px !important;">'+student_english_name+'</span>'
                                                +'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                                                +'</span><br/>'
                                                +schedule.course_name+' ('+schedule.course_capacity_code+')<br/>'
                                                +'<span style="font-size:9px !important;">('+nickname+') <b style="color:#D14130">'+schedule.room_name+'</b></span>'
                                                +'</div>');  
                                            }
                                          }
                                        }
                                        nextTimeId = schedule.temp_id;
                                        // condition = schedule.time_start;

                                    }
                                    
                                   
                              });
                              if(tempId == nextTimeId){                              
                                  nextTimeId = null;
                                  if(timeEndCounter != null && timeEndCounter != undefined)
                                  {

                                    timeEnd = getTimeByOrderNo(timeId + 1, timeJsonArray);
                                    $("#Teacher"+teacherId).append('<div class="div-table-col-blank sched" data-color="#00FFFF" id="sched_'+teacherId+'_'+class_counter+'" data-toggle="modal" data-target="#timeDutyModal" data-class_id="'+class_counter+'"  data-teacher="'+teacherId+'" data-time="'+timeId+'" data-time_next="'+timeEnd.id+'" data-details="Vacant">Vacant</div>');  
                                    // $("#Teacher"+teacherId).append('<div class="div-table-col-blank sched" data-toggle="modal" data-target="#timeModal" data-teacher="'+teacherId+'" data-time="'+timeId+'" data-time_next="'+timeEnd.id+'">Vacant</div>');  
                                  }
                              }

                    }
            }
            // else if(timeEndCounter != null && timeEndCounter != undefined){
            //    $("#Section"+sectionId).append('<div class="div-table-col-blank" data-toggle="modal" data-target="#timeModal" data-section="'+sectionId+'" data-time="'+timeId+'">no classs</div>');  
            // }

            // else{

            // }  



          });
} 

function populateScheduleByStudent(scheduleJsonArray,timeJsonArray, studentId,name){
        var timeId;
        var timeDescription;
        var nextTimeId = null;
        var count = 0;
        var counter = 0;

        var is_near = $("#student_color_"+studentId).data('near');
        var is_new = $("#student_color_"+studentId).data('new');
        $("#student_color_"+studentId).removeAttr('style');
        if(is_near == 1)
        {
          $("#student_color_"+studentId).attr('style','background-color:#D74937;color:#fff;');
        }
        if(is_new == 1)
        {
          $("#student_color_"+studentId).attr('style','background-color:#FFCE45;color:#000;');
        }
        


         $(jQuery.parseJSON(JSON.stringify(timeJsonArray))).each(function() {  
            timeId = this.id;
            tempId = this.temp_id;

            timeDescription = this.time;
            count++;
            // if(count % 2 < 1 || count == 1)
            if(this.order_no % 2 > 0)
            {
              counter++;
                    var timeEndCounter = getTimeByOrderNo(this.order_no+1, timeJsonArray);
                    // var condition = 0;
                    

                    if(nextTimeId==null){
                        nextTimeId = tempId;
                    }

                    if(tempId <= nextTimeId){
                              $(jQuery.parseJSON(JSON.stringify(scheduleJsonArray))).each(function() {  
                                    // var id = this.id;
                                    // var timeStart = this.time_start;
                                    // var timeEnd = this.time_end;
                                    // var classs = this.class
                                    // var scheduleSectionId = this.section_id

                                    var schedule = this;
                                    
                                    color_count = 0;
                                    if(timeId == schedule.time_start && studentId == schedule.teacher_id){

                                        var timeStart = getTimeById(schedule.time_start,timeJsonArray);
                                        var timeEnd = getTimeById(schedule.time_end,timeJsonArray);
                                        var colspan = timeEnd.order_no - timeStart.order_no;
                                        var subject_class = schedule.class;
                                        var course_name = schedule.course_name;
                                        var capacity_code = schedule.capacity_code;
                                        var room_name = schedule.room_name;
                                        nickname = schedule.nickname;

                                        var is_near = $("#student_color_"+studentId).data('near');

                                        if(color_count == 0)
                                        {
                                          $("#student_color_"+studentId).removeAttr('style');
                                          if(is_near == 0)
                                          {
                                            $("#student_color_"+studentId).attr('style','background-color:'+schedule.program_color);
                                          }
                                          else
                                          {
                                            $("#student_color_"+studentId).attr('style','background-color:#D74937;color:#fff;');
                                          }

                                          $("#student_course_name_"+studentId).text(subject_class);
                                          color_count++;
                                        }

                                        if(nickname == null && room_name == null)
                                        {
                                          $("#Teacher"+studentId).append('<div class="div-table-col sched edit_schedule" style="background-color:'+schedule.program_color+' !important;color:#000 !important;" data-class_id="'+counter+'" data-batch_id="'+schedule.batch_id+'"  data-course_capacity_id="'+schedule.course_capacity_id+'" data-program_id="'+schedule.program_id+'" data-course_id="'+schedule.course_id+'" data-room_id="'+schedule.room_id+'" data-id="'+schedule.id+'" data-teacher="'+studentId+'" data-time_start="'+schedule.time_start+'" data-time_end="'+schedule.time_end+'">'
                                            +'<span class="top_text">'
                                            +'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                                            + course_name.substr(0,15)
                                            +'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                                            +'</span></div>'); 
                                        }
                                        else if(nickname == null && room_name != null)
                                        {
                                          $("#Teacher"+studentId).append('<div class="div-table-col sched edit_schedule" style="background-color:'+schedule.program_color+' !important;color:#000 !important;" data-class_id="'+counter+'" data-batch_id="'+schedule.batch_id+'"  data-course_capacity_id="'+schedule.course_capacity_id+'" data-program_id="'+schedule.program_id+'" data-course_id="'+schedule.course_id+'" data-room_id="'+schedule.room_id+'" data-id="'+schedule.id+'" data-teacher="'+studentId+'" data-time_start="'+schedule.time_start+'" data-time_end="'+schedule.time_end+'">'
                                            +'<span class="top_text">'
                                            +'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                                            + course_name.substr(0,15)
                                            +'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                                            +'</span><br/><span>('+room_name+')</span>'+'</div>'); 
                                        }
                                        else if(nickname != null && room_name == null)
                                        {
                                          $("#Teacher"+studentId).append('<div class="div-table-col sched edit_schedule" style="background-color:'+schedule.program_color+' !important;color:#000 !important;" data-class_id="'+counter+'" data-batch_id="'+schedule.batch_id+'"  data-course_capacity_id="'+schedule.course_capacity_id+'" data-program_id="'+schedule.program_id+'" data-course_id="'+schedule.course_id+'" data-room_id="'+schedule.room_id+'" data-id="'+schedule.id+'" data-teacher="'+studentId+'" data-time_start="'+schedule.time_start+'" data-time_end="'+schedule.time_end+'">'
                                            +'<span class="top_text">'
                                            +'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                                            + nickname+' ('+capacity_code+')'
                                            +'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                                            +'</span><br/>'+course_name.substr(0,15)
                                            +'</span><br/><span></span>'+'</div>'); 
                                        }
                                        else
                                        {
                                        // if(colspan>1){

                                          $("#Teacher"+studentId).append('<div class="div-table-col sched edit_schedule" style="background-color:'+schedule.program_color+' !important;color:#000 !important;" data-class_id="'+counter+'" data-batch_id="'+schedule.batch_id+'"  data-course_capacity_id="'+schedule.course_capacity_id+'" data-program_id="'+schedule.program_id+'" data-course_id="'+schedule.course_id+'" data-room_id="'+schedule.room_id+'" data-id="'+schedule.id+'" data-teacher="'+studentId+'" data-time_start="'+schedule.time_start+'" data-time_end="'+schedule.time_end+'">'
                                            +'<span class="top_text">'
                                            +'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                                            + nickname+' ('+capacity_code+')'
                                            +'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                                            +'</span><br/>'
                                            +course_name.substr(0,15)+'<br/><span>('+room_name+')</span>'+'</div>');  
                                        // }else
                                        // {
                                        //   $("#Teacher"+studentId).append('<div class="div-table-col sched" style="background-color:'+schedule.program_color+' !important;" data-id="'+schedule.id+'" data-teacher="'+studentId+'" data-time_start="'+schedule.time_start+'" data-time_end="'+schedule.time_end+'">'+schedule.class+'</div>')
                                        // 
                                        }
                                        nextTimeId = schedule.temp_id;
                                        // condition = schedule.time_start;

                                    }
                                    
                                   
                              });
                              if(tempId == nextTimeId){                              
                                  nextTimeId = null;
                                  if(timeEndCounter != null && timeEndCounter != undefined)
                                  {

                                    timeEnd = getTimeByOrderNo(timeId + 1, timeJsonArray);
                                    // $("#Teacher"+studentId).append('<div class="div-table-col-blank sched" data-class_id="'+counter+'" data-toggle="modal" data-target="#timeModal" data-student="'+studentId+'" data-student_eng_name="'+name+'" data-time="'+timeId+'" data-time_next="'+timeEnd.id+'">Vacant</div>');  
                                    $("#Teacher"+studentId).append('<div class="div-table-col-blank sched" data-class_id="'+counter+'" data-student="'+studentId+'" data-student_eng_name="'+name+'" data-time="'+timeId+'" data-time_next="'+timeEnd.id+'">Vacant</div>');  
                                  }
                              }

                    }
            }
            // else if(timeEndCounter != null && timeEndCounter != undefined){
            //    $("#Section"+sectionId).append('<div class="div-table-col-blank" data-toggle="modal" data-target="#timeModal" data-section="'+sectionId+'" data-time="'+timeId+'">no classs</div>');  
            // }

            // else{

            // }  



          });
} 