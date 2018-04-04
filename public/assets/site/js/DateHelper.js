
//this function will format the received date that will be showed on the DATATABLE
//by reagan
function dateDTFormat(date)
{
    var dateGraduated = new Date(parseInt(date.replace("/Date(", "").replace(")/", ""), 10));
    return dateGraduated.getMonth() + 1 + "/" + dateGraduated.getDate() + "/" + dateGraduated.getFullYear();
}