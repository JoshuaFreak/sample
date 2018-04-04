
function loadAndShowTab(tabContainer, url, param) {
    if (param != null) {
        url = url + "/" + param;
    }
    $.get(url)
            .done(function (content) {
                $(tabContainer).html(content);
            });
}

