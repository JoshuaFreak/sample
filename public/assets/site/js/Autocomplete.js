//autocomplete plugin : compiled by reagz 10/23/10
$(document).ready(function () {
    //params: url , resultFieldName - usually contains id of the selected item on the autocomplete
    $.fn.genericAutocomplete = function (resultFieldId, url) {
        this.autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: url, type: "POST", dataType: "json", autofill: true,
                        data: { searchText: request.term, maxResult: 10 },
                        success: function (data) {
                            response($.map(data, function (item) {
                                return { label: item.label, value: item.value, id: item.id }
                            }))
                        }
                    });
                },
                select: function (event, ui) {
                    $("#" + resultFieldId).val(ui.item.id);
                    $("#" + resultFieldId).trigger("change");
                },
                change: function (event, ui) {
                    if (!ui.item) {
                        this.value = '';
                        $("#" + resultFieldId).val(null);
                    } else {

                        //alert("change");
                        $("#" + resultFieldId).val(ui.item.id)
                        /*log(ui.item.id, ui.item.name);*/
                    }
                }
           
        });
        return this;
    }
});