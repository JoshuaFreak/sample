//function print(container) {
//    var p = $("#" + container);
//    p.jqprint();
//}

function print(container) {
    // alert(container);
    var p = $("#" + container);
    //  p.jqprint();
    p.jqprint({ operaSupport: false });

}