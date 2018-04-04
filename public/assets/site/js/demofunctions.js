$(document).ready(function () {
    var opened = true;
    $("#toggleButtonLabel").click(function () {
        $("#toggleButton").trigger('click');
    });
    $("#toggleButton").click(function () {
        if (opened) {
            $("#toggleButton").parent().next().slideUp(function () {
                $(".widgets").addClass('closed');
            });
            $("#toggleButton").addClass('closed');
            opened = false;
            $("#toggleButtonLabel").html("Show Demo List");
        }
        else {
            $(".widgets").removeClass('closed');
            $("#toggleButton").parent().next().slideDown();
            $("#toggleButton").removeClass('closed');
            $("#toggleButtonLabel").html("Hide Demo List");
            opened = true;
        }
    });
    if (window.location.href.indexOf('mobiledemos') == -1) {
        initDemo(false);
    }
    else {
        initDemo(true);
    }

    if ($.jqx.browser.msie && $.jqx.browser.version < 9) {
        $(document.body).css('min-width', 1400);
        $(document.body).css('overflow-x', 'auto');
        $('html').css('overflow-x', 'auto');

        var url = "../../resources/design/css/img.css";
        $('head').append('<link rel="stylesheet" href="' + url + '" media="screen" />');
        var url = "../../resources/design/css/img_ie.css";
        $('head').append('<link rel="stylesheet" href="' + url + '" media="screen" />');
    }
});

function initthemes(initialurl) {
    if ($('#themeComboBox').length == 0) return;
    if (!$('#themeComboBox').jqxDropDownList) return;

    var loadedThemes = [0, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1];
    var themes = [
        { label: 'Arctic', group: 'Themes', value: 'arctic' },
        { label: 'Web', group: 'Themes', value: 'web' },
        { label: 'Bootstrap', group: 'Themes', value: 'bootstrap' },
        { label: 'Metro', group: 'Themes', value: 'metro' },
        { label: 'Metro Dark', group: 'Themes', value: 'metrodark' },
        { label: 'Office', group: 'Themes', value: 'office' },
        { label: 'Orange', group: 'Themes', value: 'orange' },
        { label: 'Fresh', group: 'Themes', value: 'fresh' },
        { label: 'Energy Blue', group: 'Themes', value: 'energyblue' },
        { label: 'Dark Blue', group: 'Themes', value: 'darkblue' },
        { label: 'Black', group: 'Themes', value: 'black' },
        { label: 'Shiny Black', group: 'Themes', value: 'shinyblack' },
        { label: 'Classic', group: 'Themes', value: 'classic' },
        { label: 'Summer', group: 'Themes', value: 'summer' },
        { label: 'High Contrast', group: 'Themes', value: 'highcontrast' },
        { label: 'Lightness', group: 'UI Compatible', value: 'ui-lightness' },
        { label: 'Darkness', group: 'UI Compatible', value: 'ui-darkness' },
        { label: 'Smoothness', group: 'UI Compatible', value: 'ui-smoothness' },
        { label: 'Start', group: 'UI Compatible', value: 'ui-start' },
        { label: 'Redmond', group: 'UI Compatible', value: 'ui-redmond' },
        { label: 'Sunny', group: 'UI Compatible', value: 'ui-sunny' },
        { label: 'Overcast', group: 'UI Compatible', value: 'ui-overcast' },
        { label: 'Le Frog', group: 'UI Compatible', value: 'ui-le-frog' }
    ];
    var mobilethemes = [
      { label: 'iOS', group: 'Themes', value: 'mobile' },
      { label: 'Android', group: 'Themes', value: 'android' },
      { label: 'Windows Phone', group: 'Themes', value: 'win8' },
      { label: 'Blackberry', group: 'Themes', value: 'blackberry' }
    ];
    var me = this;
    this.$head = $('head');
    if (window.location.href.indexOf('mobiledemos') != -1) {
        $('#themeComboBox').jqxDropDownList({ source: mobilethemes, theme: 'arctic', selectedIndex: 0, autoDropDownHeight: true, dropDownHeight: 200, width: '140px', height: '20px' });
    }
    else {
        $('#themeComboBox').jqxDropDownList({ source: themes, theme: 'arctic', selectedIndex: 0, dropDownHeight: 200, width: '140px', height: '20px' });
    }

    var hasParam = window.location.toString().indexOf('?');
    if (hasParam != -1) {
        var themestart = window.location.toString().indexOf('(');
        var themeend = window.location.toString().indexOf(')');
        var theme = window.location.toString().substring(themestart + 1, themeend);
        $.data(document.body, 'theme', theme);
        selectedTheme = theme;
        var themeIndex = 0;
        if (window.location.href.indexOf('mobiledemos') != -1) {
            $.each(mobilethemes, function (index) {
                if (this.value == theme) {
                    themeIndex = index;
                    return false;
                }
            });
        }
        else {
            $.each(themes, function (index) {
                if (this.value == theme) {
                    themeIndex = index;
                    return false;
                }
            });
        }
        $('#themeComboBox').jqxDropDownList({ selectedIndex: themeIndex });
        loadedThemes[0] = -1;
        loadedThemes[themeIndex] = 0;
    }
    else {
        $.data(document.body, 'theme', 'arctic');
    }

    $('#themeComboBox').on('select', function (event) {
        setTimeout(function () {
            var selectedIndex = event.args.index;
            var selectedTheme = '';
            var url = initialurl;

            var loaded = loadedThemes[selectedIndex] != -1;
            loadedThemes[selectedIndex] = selectedIndex;
            var mobilethemes = [
                { label: 'iOS', group: 'Themes', value: 'mobile' },
                { label: 'Android', group: 'Themes', value: 'android' },
                { label: 'Windows Phone', group: 'Themes', value: 'win8' },
                { label: 'Blackberry', group: 'Themes', value: 'blackberry' }
            ];

            var themes = [
              { label: 'Arctic', group: 'Themes', value: 'arctic' },
              { label: 'Web', group: 'Themes', value: 'web' },
              { label: 'Bootstrap', group: 'Themes', value: 'bootstrap' },
              { label: 'Metro', group: 'Themes', value: 'metro' },
              { label: 'Metro Dark', group: 'Themes', value: 'metrodark' },
              { label: 'Office', group: 'Themes', value: 'office' },
              { label: 'Orange', group: 'Themes', value: 'orange' },
              { label: 'Fresh', group: 'Themes', value: 'fresh' },
              { label: 'Energy Blue', group: 'Themes', value: 'energyblue' },
              { label: 'Dark Blue', group: 'Themes', value: 'darkblue' },
              { label: 'Black', group: 'Themes', value: 'black' },
              { label: 'Shiny Black', group: 'Themes', value: 'shinyblack' },
              { label: 'Classic', group: 'Themes', value: 'classic' },
              { label: 'Summer', group: 'Themes', value: 'summer' },
              { label: 'High Contrast', group: 'Themes', value: 'highcontrast' },
              { label: 'Lightness', group: 'UI Compatible', value: 'ui-lightness' },
              { label: 'Darkness', group: 'UI Compatible', value: 'ui-darkness' },
              { label: 'Smoothness', group: 'UI Compatible', value: 'ui-smoothness' },
              { label: 'Start', group: 'UI Compatible', value: 'ui-start' },
              { label: 'Redmond', group: 'UI Compatible', value: 'ui-redmond' },
              { label: 'Sunny', group: 'UI Compatible', value: 'ui-sunny' },
              { label: 'Overcast', group: 'UI Compatible', value: 'ui-overcast' },
              { label: 'Le Frog', group: 'UI Compatible', value: 'ui-le-frog' }
            ];

            if (window.location.href.indexOf('mobiledemos') != -1) {
                selectedTheme = mobilethemes[selectedIndex].value;
            }
            else {
                selectedTheme = themes[selectedIndex].value;
            }
            url += selectedTheme + '.css';

            if (!loaded) {
                if (document.createStyleSheet != undefined) {
                    document.createStyleSheet(url);
                }
                else me.$head.append('<link rel="stylesheet" href="' + url + '" media="screen" />');
            }
            $.data(document.body, 'theme', selectedTheme);
            var startedExample = $.data(document.body, 'example');
            if (startedExample != null) {
                startDemo(startedExample);
            }
        }, 5);
    });
};

function jqxBrowser() {
    var ua = navigator.userAgent.toLowerCase();

    var match = /(chrome)[ \/]([\w.]+)/.exec(ua) ||
        /(webkit)[ \/]([\w.]+)/.exec(ua) ||
        /(opera)(?:.*version|)[ \/]([\w.]+)/.exec(ua) ||
        /(msie) ([\w.]+)/.exec(ua) ||
        ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(ua) ||
        [];

    var obj = {
        browser: match[1] || "",
        version: match[2] || "0"
    };
    if (ua.indexOf("rv:11.0") >= 0 && ua.indexOf(".net4.0c") >= 0) {
        obj.browser = "msie";
        obj.version = "11";
        match[1] = "msie";
    }
    if (ua.indexOf("edge") >= 0) {
        obj.browser = "msie";
        obj.version = "12";
        match[1] = "msie";
    }
    obj[match[1]] = match[1];
    return obj;
}
function initDemo(ismobile, isIndex) {
    var resize = function () {
        if ($(window).width() < 1330) {
            $(".doc_mask").css('visibility', 'hidden');
        }
        else {
            $(".doc_mask").css('visibility', 'visible');
        }

        if ($(".doc_menu").length > 0) {
            if (jqxBrowser().msie && jqxBrowser().version < 8) {
                $(".doc_content").css('padding', '5px');
                return;
            }
            var w = $(".doc_menu").offset().left;
            $(".doc_mask").css('left', $(".doc_content").offset().left + $(".doc_content").width());
            $(".doc_mask").width($(window).width() - $(".doc_content").offset().left - $(".doc_content").width());
            if (jqxBrowser().msie && jqxBrowser().version < 9) {
                $(".doc_mask").width($(document).width() - $(".doc_content").offset().left - $(".doc_content").width());
                $(".doc_mask").css('visibility', 'visible');
                return;
            }

            $(".doc_content").css('min-height', 50 + $(".documentation").height());
        }
    }

    if (ismobile) {
        var isIndex = window.location.href.indexOf('jqx') == -1 && window.location.href.indexOf('php') == -1 && window.location.href.indexOf('jquerymobile') == -1 && window.location.href.indexOf('mvc') == -1 && window.location.href.indexOf('jsp') == -1 && window.location.href.indexOf('requirejs') == -1 && window.location.href.indexOf('twitter') == -1;
        this.mobile = true;
        var path = "../../";
        if (isIndex === true) {
            var path = "../";
        }


        if (!isIndex) {
            $.ajax({
                async: false,
                url: path + "bottom.htm",
                success: function (data) {
                    $("#pageBottom").append(data);
                }
            });
            var initialurl = "http://www.jqwidgets.com/jquery-widgets-demo/jqwidgets/styles/jqx.";
            initthemes(initialurl);

            $.ajax({
                async: false,
                cache: false,
                url: path + "mobiletop.htm",
                success: function (data) {
                    $("#pageTop").append(data);
                    if ($.jqx.browser.msie && $.jqx.browser.version < 9) {
                        var images = $("img");
                        $.each(images, function (index, value) {
                            var src = this.src;
                            this.src = src.toString().substring(0, src.toString().length - 3) + "png";
                        });
                    }

                    var opened = false;
                    $("#toggleButtonLabel").click(function () {
                        $("#toggleButton").trigger('click');
                    });
                    $("#toggleButton").click(function () {
                        if (opened) {
                            $("#toggleButton").parent().next().slideUp(function () {
                                $(".widgets").addClass('closed');
                            });
                            $("#toggleButton").addClass('closed');
                            opened = false;
                            $("#toggleButtonLabel").html("Show Demo List");
                        }
                        else {
                            $(".widgets").removeClass('closed');
                            $("#toggleButton").parent().next().slideDown();
                            $("#toggleButton").removeClass('closed');
                            $("#toggleButtonLabel").html("Hide Demo List");
                            opened = true;
                        }
                    });
                
                    $(".doc_menu").find('a').mousedown(function (event) {
                        var href = event.target.href;
                        if (href && href.indexOf("#") == -1) {
                            resize();
                            $(".doc_menu").find('a').removeClass('anchorSelected');
                            $(this).addClass('anchorSelected');
                            startDemo(event.target);
                            $(document).trigger('mousedown');
							event.preventDefault();
                            return false;
                        }
                        event.preventDefault();
                    });
                    $(".doc_menu").find('a').each(function () {
                        if (this.id == "demoLink") {
                            $(".doc_menu").find('a').removeClass('anchorSelected');
                            $(this).addClass('anchorSelected');
                            $(this).trigger('mousedown');
                        }
                    });

                    $(".doc_menu").find('a').mouseup(function (event) {
                        event.preventDefault();
                        return false;
                    });
                    $(".doc_menu").find('a').click(function (event) {
                        event.preventDefault();
                        return false;
                    });
                    var timeout;
                    $(window).resize(
                        function () {
                            if (timeout) {
                                clearTimeout(timeout);
                            }
                            timeout = setTimeout(function () {
                                resize();
                            });
                        });
                    resize();
                }
            });

        }
        document.body.style.visibility = "visible";
    }
    else {
        var isIndex = window.location.href.indexOf('jqx') == -1 && window.location.href.indexOf('php') == -1 && window.location.href.indexOf('jquerymobile') == -1 && window.location.href.indexOf('mvc') == -1 && window.location.href.indexOf('jsp') == -1 && window.location.href.indexOf('requirejs') == -1 && window.location.href.indexOf('twitter') == -1;
        if (!isIndex) {

        }

        var loc = window.location.pathname;
        var dir = loc.substring(0, loc.lastIndexOf('/')) + "/";
        var bottom = isIndex ? dir + "bottom.htm" : "../../bottom.htm";
        var top = isIndex ? dir + "top.htm" : "../../top.htm";

        if (!isIndex) {
            var initialurl = "http://www.jqwidgets.com/jquery-widgets-demo/jqwidgets/styles/jqx.";
            initthemes(initialurl);
            $.ajax({
                async: false,
                url: top,
                success: function (data) {
                    $("#pageTop").append(data);
                    if ($.jqx.browser.msie && $.jqx.browser.version < 9) {
                        var images = $("img");
                        $.each(images, function (index, value) {
                            var src = $(this).attr('src');
                            this.src = src.toString().substring(0, src.toString().length - 3) + "png";
                        });
                    }
                    var timer;
                    var filterMenuItems = function (fromSearch) {
                        if (timer != undefined) clearTimeout(timer);
                        var filterNow = function () {
                            var searchString = $("#searchField").val();
                            var items = $(".sub_menu > a");
                            $.each(items, function () {
                                var item = $(this);
                                var itemText = $.trim(item.text());

                                var match = itemText.toUpperCase().indexOf(searchString.toUpperCase()) != -1;

                                if (!match) {
                                    item.parent().hide();
                                }
                                else {
                                    item.parent().show();
                                }
                            });
                            var items = $(".doc_menu > ul >  li:not(.sub_menu) a");
                            $.each(items, function () {
                                var item = $(this);
                                var itemText = $.trim(item.text());

                                var match = itemText.toUpperCase().indexOf(searchString.toUpperCase()) != -1;

                                if (!match) {
                                    item.parent().hide();
                                }
                                else {
                                    item.parent().show();
                                }
                            });
                            resize();
                        }
                        if (fromSearch) {
                            timer = setTimeout(function () {
                                filterNow();
                            }, 500);
                        }
                        else {
                            filterNow();
                        }
                    }
                    if ($("#searchField").length > 0) {
                        $("#searchField").keydown(function (event) {
                            filterMenuItems(true);                     
                        });
                        $("#searchField").on('change', function (event) {
                            filterMenuItems(true);
                        });
                        if ($("#searchField").val().length > 0) {
                            filterMenuItems(true);
                        }
                    }
                    var opened = false;
                    $("#toggleButtonLabel").click(function () {
                        $("#toggleButton").trigger('click');
                    });
                    $("#toggleButton").click(function () {
                        if (opened) {
                            $("#toggleButton").parent().next().slideUp(function () {
                                $(".widgets").addClass('closed');
                            });
                            $("#toggleButton").addClass('closed');
                            opened = false;
                            $("#toggleButtonLabel").html("Show Demo List");
                        }
                        else {
                            $(".widgets").removeClass('closed');
                            $("#toggleButton").parent().next().slideDown();
                            $("#toggleButton").removeClass('closed');
                            $("#toggleButtonLabel").html("Hide Demo List");
                            opened = true;
                        }
                    });
                    $(".doc_menu").find('a').mousedown(function (event) {
                        var href = event.target.href;
                        if (href && href.indexOf("#") == -1) {
                            resize();
                            $(".doc_menu").find('a').removeClass('anchorSelected');
                            $(this).addClass('anchorSelected');
                            startDemo(event.target);
                            $(document).trigger('mousedown');
						    event.preventDefault();
                            return false;
                        }
                        event.preventDefault();
                    });
                    $(".doc_menu").find('a').each(function () {
                        if (this.id == "demoLink") {
                            $(".doc_menu").find('a').removeClass('anchorSelected');http://localhost:38652/demos/jqxscrollview
                            $(this).addClass('anchorSelected');
                            $(this).trigger('mousedown');
                        }
                    });

                    $(".doc_menu").find('a').mouseup(function (event) {
                        event.preventDefault();
                        return false;
                    });
                    $(".doc_menu").find('a').click(function (event) {
                        event.preventDefault();
                        return false;
                    });

                    var timeout;
                    $(window).resize(
                        function () {
                            if (timeout) {
                                clearTimeout(timeout);
                            }
                            timeout = setTimeout(function () {
                                resize();
                            });
                        });
                    resize();
                }
            });
            $.ajax({
                async: false,
                url: bottom,
                success: function (data) {
                    $("#pageBottom").append(data);
                    if ($.jqx.browser.msie && $.jqx.browser.version < 9) {
                        var images = $("img");
                        $.each(images, function (index, value) {
                            var src = $(this).attr('src');
                            this.src = src.toString().substring(0, src.toString().length - 3) + "png";
                        });
                    }
                }
            });
        }
        else {
            if ($.jqx.browser.msie && $.jqx.browser.version < 9) {
                var images = $("img");
                $.each(images, function (index, value) {
                    var src = this.src;
                    this.src = src.toString().substring(0, src.toString().length - 3) + "png";
                });
            }
        }
    }
};
function closewindows() {
    var windows = $.data(document.body, 'jqxwindows-list');
    if (windows && windows.length > 0) {
        var count = windows.length;
        while (count) {
            count -= 1;
            windows[count].remove();
        }
    }
    var window = $.data(document.body, 'jqxwindow-modal');
    if (window != null && window.length && window.length > 0) {
        window.jqxWindow('closeWindow', 'close');
    }

    $.data(document.body, 'jqxwindow-modal', []);
    $.data(document.body, 'jqxwindows-list', []);
};

function getBrowser() {
    return $.jqx.browser;
};

function startDemo(target) {
    if (target == null || target.href == null) {
        if (target && target.href == null) {
            var child = $(target).children('a');
            if (child.length > 0) {
                target = child[0];
            }
        }
        else {
            return;
        }
    }

    if ($(".doc_menu").length > 0) {
        $(".doc_content").css('min-height', 200 + $(".doc_menu").height());

    }

    var scrollTop = $(window).scrollTop();
    var hasHref = target.href;
    if (!hasHref) {
        return;
    }
    if ($(window).width() <= 920) {
        window.open(hasHref, "_blank");
        return;
    }

    if (getBrowser().browser == 'chrome') {
        $(".jqx-validator-hint").remove();
        $("#jqxMenu").remove();
        $("#Menu").remove();
        $("#gridmenujqxgrid").remove();
    }

    closewindows();
    $('#tabs').css('visibility', 'visible');
    $('#tabs').css('display', 'block');

    if (!this.jqxtabsinitialized) {
        $('#tabs').show();
        $('#tabs').jqxTabs({ width: 940, theme: 'jqxtabs', keyboardNavigation: false, selectionTracker: false });
        this.jqxtabsinitialized = true;
        $('#tabs ').on('tabclick', function (event) {
            var tab = event.args.item;
            if (tab == 0) {
                $('.demoLink').show();
                $('#resources').show();
                if ($(".doc_menu").length > 0) {
                    $(".doc_content").css('min-height', 200 + $(".doc_menu").height());

                }
            }
            else {
                if ($(".doc_menu").length > 0) {
                    $(".doc_content").css('min-height', 200 + $(".doc_menu").height());

                }
                $(".demoLink").hide();
                $('#resources').hide();
            }
            if (tab == 1) {
                setTimeout(function () {
                    var childrenHeight = 100 + $("#tabs-2").children().height();
                    var maxHeight = Math.max(200 + $(".doc_menu").height(), childrenHeight);
                    $("#tabs-2").height(maxHeight);
                    $(".doc_content").css('min-height', maxHeight);
                });
            }
            else if (tab == 3) {
                setTimeout(function () {
                    var childrenHeight = 100 + $("#tabs-4").children().height();
                    var maxHeight = Math.max(200 + $(".doc_menu").height(), childrenHeight);
                    $("#tabs-4").height(maxHeight);
                    $(".doc_content").css('min-height', maxHeight);
                });
            } else if (tab == 4) {
                setTimeout(function () {
                    var childrenHeight = 100 + $("#tabs-5").children().height();
                    var maxHeight = Math.max(200 + $(".doc_menu").height(), childrenHeight);
                    $("#tabs-5").height(maxHeight);
                    $(".doc_content").css('min-height', maxHeight);
                });
            }
        });
    }

    var demoUrlLeft = -20;
    var tabsResize = function () {
        if ($(window).width() < 1330) {
            $('#tabs').jqxTabs({ width: '100%' });
            $('#tabs #demourl').css('left', '-20px');
            demoUrlLeft = -20;
        }
        else {
            $('#tabs').jqxTabs({ width: 940 });
            $('#tabs #demourl').css('left', '-65px');
            demoUrlLeft = -65;
        }
    }
    $(window).resize(function () {
        tabsResize();
    });
    tabsResize();

    $('#tabs').jqxTabs('select', 0);
    $("#introduction").css('display', 'none');
    $("#introduction").empty();
    $.data(document.body, 'example', target);

    var url = target.href;
    var startindex = url.toString().indexOf('demos');
    var demourl = url.toString().substring(startindex);
    window.location.hash = demourl;
    $("#themeSelector").css('visibility', 'visible');
    if (url.toString().indexOf('chart') >= 0 ||
        url.toString().indexOf('gauge') >= 0 ||
        url.toString().indexOf('twitter') >= 0) {
        $("#themeSelector").css('visibility', 'hidden');
    }
    if ($("iframe").length > 0) {
        var iframe = $("iframe");
        iframe.unload();
        iframe.remove();
        iframe.attr('src', null)
    }

    $("#innerdemoContainer").empty();
    $('#tabs-1').css({ height: 50 + 'px' });
    $('#tabs-2').css({ height: 50 + 'px' });
    $('#tabs-3').css({ height: 50 + 'px' });
    height = $('.doc_content').height();
    height = Math.max(height, $(".doc_menu").height());
    if (height < 1200) height = 1200;
    var width = 910;
    height -= parseInt(55);

    $('#tabs-1').css({ height: height + 'px', width: width + 'px' });
    $('#tabs-2').css({ height: height + 'px', width: width + 'px' });
    $('#tabs-3').css({ height: height + 'px', width: width + 'px' });

    var demoHeight = parseInt(height);
    var demoWidth = parseInt($("#demoContainer").width()) / 2;
    var loader = $("<table style='border-color: transparnet; border: none; border-collapse: collapse;'><tr><td align='center'><img src='../../images/loadingimage.gif' /></td></tr><tr><td align='center' style='padding: 10px; padding-left: 20px;'>Loading Example...</td></tr></table>");
    //   loader.css('margin-top', (demoHeight / 2 - 18) + 'px');
    loader.css('margin-left', (demoWidth - 110) + 'px');
    loader.css('margin-top', '150px');

    $("#innerdemoContainer").html(loader);

    var theme = $.data(document.body, 'theme');
    $("#innerdemoContainer").removeAttr('theme');
    var that = this;
    if (theme == undefined) theme = '';

    if (url.toString().indexOf('angular') >= 0 || url.toString().indexOf('requirejs') >= 0 || url.toString().indexOf('jquerymobile') >= 0 || url.toString().indexOf('php') >= 0 || url.toString().indexOf('twitter') >= 0 || url.toString().indexOf('jsp') >= 0
        || url.toString().indexOf('knockout') >= 0) {
        $('#tab3').hide();
    }

    if (!that.mobile) {
        if (url.toString().indexOf('mvcexamples') >= 0) {
            url += "/" + theme;
        }
        else {
            url += '?' + theme.toString();
        }
    }
    else {
        url += '?(' + theme.toString() + ")";
    }

    var isnonpopupdemo = url.indexOf('window') == -1;
    if (url.indexOf('jqxwindow') != -1) {
        $.jqx.theme = theme;
    }

    if (this.isTouchDevice && url.indexOf('chart') == -1) isnonpopupdemo = false;
    if (that.mobile) {
        isnonpopupdemo = true;
    }
    else {
        $('#tabs-1').css('margin-left', '20px');
    }

    if ($.jqx.response) {
        var response = new $.jqx.response();
        if (response.device.type != "Desktop") {
            isnonpopupdemo = true;
        }
    }

    var _url = url;

    if (url.toString().indexOf('mvcexamples') >= 0) {
        $('#tabs-4').hide();
        $('#tab4').hide();
        $('#tabs-5').hide();
        $('#tab5').hide();
        var anchor = $("<div id='demourl' style='float:right;top: -25px; left: " + demoUrlLeft + "px; position: relative; z-index:9999;'><a class='demoLink'  target='_blank' href='" + _url + "'>View in new window</a></div>");
        $('#tabs #demourl').remove();
        $('#tabs #resources').remove();
        $('#tabs .jqx-tabs-header').append(anchor);
        var w = url.split('/')[4].toLowerCase();
        $.get('Widgets/' + w + '/controller.txt', function (data) {
            var result = formatCode(data);
            result = colourKeywords("public|int|float|double|private|new|void|synchronized|if|from|select|in|base|override|return|new|string|for|byte|break|else|protected|using|var|namespace|HttpStatusCode ", result);
            $('#tabs-2').html(result);
        });
        $.get('Widgets/' + w + '/view.txt', function (data) {
            var result = formatCode(data);
            $('#tabs-3').html(result);
        });
        $("#examplePath").hide();
        switch (w) {
            case "treewithcheckboxes":
                $('#tabs').jqxTabs('setTitleAt', 3, 'View(Tree)');
                $('#tab4 .jqx-tabs-titleContentWrapper').css('margin-top', '0px');
                $('#tab4').show();
                $.get('Widgets/' + w + '/viewtree.txt', function (data) {
                    var result = formatCode(data);
                    $('#tabs-4').html(result);
                });
                break;
            case "dropdownlist":
                $('#tabs').jqxTabs('setTitleAt', 3, 'View(Store)');
                $('#tab4 .jqx-tabs-titleContentWrapper').css('margin-top', '0px');
                $('#tab4').show();
                $.get('Widgets/' + w + '/store.txt', function (data) {
                    var result = formatCode(data);
                    $('#tabs-4').html(result);
                });
                break;
            case "loginform":
                $('#tabs').jqxTabs('setTitleAt', 3, 'View(Login Failed)');
                $('#tab4 .jqx-tabs-titleContentWrapper').css('margin-top', '0px');
                $('#tab4').show();
                $('#tabs').jqxTabs('setTitleAt', 4, 'View(Login)');
                $('#tab5 .jqx-tabs-titleContentWrapper').css('margin-top', '0px');
                $('#tab5').show();
                $.get('Widgets/' + w + '/viewloginfailed.txt', function (data) {
                    var result = formatCode(data);
                    $('#tabs-4').html(result);
                });
                $.get('Widgets/' + w + '/viewlogin.txt', function (data) {
                    var result = formatCode(data);
                    $('#tabs-5').html(result);
                });
                break;
            case "registrationform":
                $('#tabs').jqxTabs('setTitleAt', 3, 'View(Register Failed)');
                $('#tab4 .jqx-tabs-titleContentWrapper').css('margin-top', '0px');
                $('#tab4').show();
                $('#tabs').jqxTabs('setTitleAt', 4, 'View(Register)');
                $('#tab5 .jqx-tabs-titleContentWrapper').css('margin-top', '0px');
                $('#tab5').show();
                $.get('Widgets/' + w + '/viewregisterfailed.txt', function (data) {
                    var result = formatCode(data);
                    $('#tabs-4').html(result);
                });
                $.get('Widgets/' + w + '/viewregister.txt', function (data) {
                    var result = formatCode(data);
                    $('#tabs-5').html(result);
                });
                break;
            case "combobox":
            case "listbox":
                $('#tabs').jqxTabs('setTitleAt', 3, 'View(Details)');
                $('#tab4 .jqx-tabs-titleContentWrapper').css('margin-top', '0px');
                $('#tab4').show();
                $.get('Widgets/' + w + '/details.txt', function (data) {
                    var result = formatCode(data);
                    $('#tabs-4').html(result);
                });
                break;
        }

    }
    else if (url.toString().indexOf('jsp') >= 0) {
        $('#tabs-4').hide();
        $('#tab4').hide();
        $('#tabs-5').hide();
        $('#tab5').hide();
        var anchor = $("<div id='demourl' style='float:right;top: -25px; left: " + demoUrlLeft + "px; position: relative; z-index:9999;'><a class='demoLink'  target='_blank' href='" + _url + "'>View in new window</a></div>");
        $('#tabs #demourl').remove();
        $('#tabs #resources').remove();
        $('#tabs .jqx-tabs-header').append(anchor);
        var w = url.split('/')[4].toLowerCase();
        //$.get('Widgets/' + w + '/controller.txt', function (data) {
        //    var result = formatCode(data);
        //    result = colourKeywords("public|int|float|double|private|new|void|synchronized|if|from|select|in|base|override|return|new|string|for|byte|break|else|protected|using|var|namespace|HttpStatusCode ", result);
        //    $('#tabs-2').html(result);
        //});

        $("#examplePath").hide();
        $('#tabs').jqxTabs('setTitleAt', 3, 'JSP');
        $('#tab4 .jqx-tabs-titleContentWrapper').css('margin-top', '0px');
        $('#tab4').show();
        if (w.indexOf('grid-sorting') >= 0) {
            $.get('jsp/populate-grid.txt', function (data) {
                var result = formatCode(data);
                $('#tabs-4').html(result);
            });
        }
        else if (w.indexOf('datatable-sorting') >= 0) {
            $.get('jsp/populate-datatable.txt', function (data) {
                var result = formatCode(data);
                $('#tabs-4').html(result);
            });
        }
        else if (w.indexOf('tree') >= 0) {
            $.get('jsp/select-tree-data.txt', function (data) {
                var result = formatCode(data);
                $('#tabs-4').html(result);
            });
        }
        else if (w.indexOf('chart') >= 0) {
            $.get('jsp/select-chart-data.txt', function (data) {
                var result = formatCode(data);
                $('#tabs-4').html(result);
            });
        }
        else if (w.indexOf('listbox') >= 0 || w.indexOf('dropdownlist') >= 0 || w.indexOf('datatable') >= 0 || w.indexOf('combobox') >= 0 || w.indexOf('grid') >= 0) {
            $.get('jsp/select-data-simple.txt', function (data) {
                var result = formatCode(data);
                $('#tabs-4').html(result);
            });
        }
        $.get(w,
                        function (data) {
                            var originalData = data;
                            var descriptionLength = "<title id='Description'>".toString().length;
                            var startIndex = data.indexOf('<title') + descriptionLength;
                            var endIndex = data.indexOf('</title>');
                            var description = data.substring(startIndex, endIndex);
                            if (!that.mobile) {
                                $('#divDescription').html('<div style="width: 800px; margin: 10px;">' + description + '</div>');
                            }
                            var anchor = $("<div id='demourl' style='float:right;top: -25px; left: " + demoUrlLeft + "px; position: relative; z-index:9999;'><a class='demoLink'  target='_blank' href='" + _url + "'>View in new window</a></div>");
                            if (that.mobile) {
                                var linkText = "View in full screen";
                                var anchor = $("<div id='demourl' style='float:right;top: -25px; left: " + demoUrlLeft + "px; position: relative; z-index:9999;'><a class='demoLink' target='_blank' href='" + _url + "&=fullscreen'>" + linkText + "</a></div>");
                            }
                            $('#tabs #demourl').remove();
                            $('#tabs #resources').remove();
                            $('#tabs .jqx-tabs-header').append(anchor);

                            var result = formatCode(originalData);
                            $('#tabs-2').html(result);
                        }, "html"
                )
    }
    else {
        $.get(url,
                        function (data) {
                            var originalData = data;
                            var descriptionLength = "<title id='Description'>".toString().length;
                            var startIndex = data.indexOf('<title') + descriptionLength;
                            var endIndex = data.indexOf('</title>');
                            var description = data.substring(startIndex, endIndex);
                            if (!that.mobile) {
                                $('#divDescription').html('<div style="width: 800px; margin: 10px;">' + description + '</div>');
                            }
                            var anchor = $("<div id='demourl' style='float:right;top: -25px; left: " + demoUrlLeft + "px; position: relative; z-index:9999;'><a class='demoLink'  target='_blank' href='" + _url + "'>View in new window</a></div>");
                            if (that.mobile) {
                                var linkText = "View in full screen";
                                var anchor = $("<div id='demourl' style='float:right;top: -25px; left: " + demoUrlLeft + "px; position: relative; z-index:9999;'><a class='demoLink' target='_blank' href='" + _url + "&=fullscreen'>" + linkText + "</a></div>");
                            }
                            $('#tabs #demourl').remove();
                            $('#tabs #resources').remove();
                            $('#tabs .jqx-tabs-header').append(anchor);

                            //    var resources = $("<div id='resources' style='color: #444; line-height: 23px; top: 90px; text-align: left; left: 760px; margin-right: 10px; position: absolute; font-size: 13px; '><div><strong>Resources</strong></div><div><div><a class='demoLink'  target='_blank' href='http://www.jqwidgets.com/download/'>Download</a></div><a class='demoLink'  target='_blank' href='http://www.jqwidgets.com/jquery-widgets-documentation'>Documentation</a></div><div><a class='demoLink'  target='_blank' href='http://www.jqwidgets.com/community/'>Forum</a></div><div><a class='demoLink'  target='_blank' href='http://www.jqwidgets.com/jquery-widgets-documentation/documentation/releasehistory/releasehistory.htm'>Release History</a></div><div><a class='demoLink'  target='_blank' href='http://www.jqwidgets.com/jquery-widgets-documentation/documentation/roadmap/roadmap.htm'>Roadmap</a></div><div><a class='demoLink'  target='_blank' href='http://www.jqwidgets.com/license'>License</a></div>");
                            //    $('#tabs .jqx-tabs-header').append(resources);
                            //    $("#downloadButton").addClass('downloadButton');

                            if (!isnonpopupdemo) {
                                data = data.replace(/<script.*>.*<\/script>/ig, ""); // Remove script tags
                                data = data.replace(/<\/?link.*>/ig, ""); //Remove link tags
                                data = data.replace(/<\/?html.*>/ig, ""); //Remove html tag
                                data = data.replace(/<\/?body.*>/ig, ""); //Remove body tag
                                data = data.replace(/<\/?head.*>/ig, ""); //Remove head tag
                                data = data.replace(/<\/?!doctype.*>/ig, ""); //Remove doctype
                                data = data.replace(/<title.*>.*<\/title>/ig, ""); // Remove title tags
                                data = data.replace(/..\/..\/jqwidgets\/globalization\//g, "jqwidgets/globalization/"); // fix localization path
                                $("#innerdemoContainer").removeClass();

                                var url = "../../jqwidgets/styles/jqx." + theme + '.css';
                                if (document.createStyleSheet != undefined) {
                                    document.createStyleSheet(url);
                                }
                                else $(document).find('head').append('<link rel="stylesheet" href="' + url + '" media="screen" />');

                                $("#innerdemoContainer").attr('theme', theme.toString());
                                $("#innerdemoContainer").html('');
                                $("#innerdemoContainer").html('<div id="jqxInnerdemoContainer" style="position: relative; top: 10px; left: 10px; width: 900px; height: 90%;">' + data + '</div>');
                                var jqxInnerdemoContainer = $("#innerdemoContainer").find('#jqxInnerdemoContainer');
                                var jqxWidget = $("#innerdemoContainer").find('#jqxWidget');
                                jqxInnerdemoContainer.css('visibility', 'visible');
                            }

                            //populate tabs.

                            var result = formatCode(originalData);
                            $('#tabs-2').html(result);
                            $('#tabs-2').find('pre').css('border', 'none !important');
                            var demourl = _url.toString().substring(_url.toString().indexOf('demos'));
                            var widgetNameStartIndex = demourl.indexOf('/');
                            var widgetNameEndIndex = demourl.toString().substring(widgetNameStartIndex + 1).indexOf('/');
                            var widgetName = demourl.substring(widgetNameStartIndex + 1, 1 + widgetNameStartIndex + widgetNameEndIndex);
                            if (widgetName == 'jqxbutton' && (_url.indexOf('checkbox') != -1)) {
                                widgetName = 'jqxcheckbox';
                            }
                            if (widgetName == 'jqxbutton' && (_url.indexOf('radiobutton') != -1)) {
                                widgetName = 'jqxradiobutton';
                            }
                            if (widgetName == 'jqxbutton' && (_url.indexOf('dropdownbutton') != -1)) {
                                widgetName = 'jqxdropdownbutton';
                            }
                            if (widgetName == 'jqxpanel' && _url.indexOf('dockpanel') != -1) {
                                widgetName = 'jqxdockpanel';
                            }
                            if (widgetName == 'jqxbutton' && (_url.indexOf('switch') != -1)) {
                                widgetName = 'jqxswitchbutton';
                            }
                            if (widgetName == 'jqxbutton' && (_url.indexOf('group') != -1)) {
                                widgetName = 'jqxbuttongroup';
                            }
                            if (widgetName == 'jqxgauge' && (_url.indexOf('linear') != -1)) {
                                widgetName = 'jqxlineargauge';
                            }

                            try {
                                if (widgetName != "php" && widgetName != "twitter" && widgetName != "jqxangular" && widgetName != "jquerymobile" && widgetName != "aspnetmvc" && widgetName != "requirejs") {
                                    var apiURL = '../../documentation/' + widgetName + '/' + widgetName + '-api.htm';
                                    var frame = '<iframe frameborder="0" src="' + apiURL + '" id="widgetAPI" style="height: ' + parseInt(demoHeight) + 'px; border-collapse: collapse; border:none !important; overflow-y: hidden; width: 900px !important;"></iframe>';
                                    $('#tabs-3').html(frame);
                                    $('#tabs-3').css('overflow', 'hidden');
                                }
                            }
                            catch (error) {
                            }
                        }, "html"
                )
    }
    if (isnonpopupdemo) {
        if ($.jqx.browser.msie && $.jqx.browser.version < 9) {
            try {
                var iframe = $('<iframe frameborder="0" src="' + url + '" id="jqxInnerdemoContainer" style="border-collapse: collapse; margin-top: 10px; width: 900px !important;"></iframe>');
                if (getBrowser().browser == 'chrome') {
                    iframe = $('<iframe frameborder="0" src="' + url + '" id="jqxInnerdemoContainer" style="border-collapse: collapse; margin: 0px !important; padding: 0px !important; width: 900px !important;"></iframe>');
                }

                $("#innerdemoContainer").html('');
                $("#innerdemoContainer").append(iframe);
            }
            catch (error) {
            }
        }
        else {
            var iframe = $('<iframe frameborder="0" src="' + url + '" id="jqxInnerdemoContainer" style="border-collapse: collapse; margin-top: 10px; width: 900px !important;"></iframe>');
            if (getBrowser().browser == 'chrome') {
                iframe = $('<iframe frameborder="0" src="' + url + '" id="jqxInnerdemoContainer" style="border-collapse: collapse; margin: 0px !important; padding: 0px !important; width: 900px !important;"></iframe>');
            }
            if (getBrowser().browser == 'mozilla') {
                //    iframe = $('<iframe frameborder="0" src="' + url + '" id="jqxInnerdemoContainer" style="border-collapse: collapse; margin: 0px !important; margin-top: 80px !important; padding: 0px !important; width: 900px;"></iframe>');
            }
            if (url.toString().indexOf('mvcexamples') >= 0) {
                $("#innerdemoContainer").append(iframe);
                iframe.on('load', function () {
                    loader.remove();
                });
            }
            else {
                $("#innerdemoContainer").html('');
                $("#innerdemoContainer").append(iframe);
            }
        }

        if ($(".content").css('display') == 'none') {
            window.open(_url, '_self');
        }
        //     $("#tabs-1").width(730);
        //     $("#tabs-1").css('border-right', '1px solid #e4e4e4');
        //     var parentTable = $("#innerdemoContainer").parents('table:first');
        //     parentTable.css('margin-left', 'auto');
        //     parentTable.css('margin-right', 'auto');
        //     parentTable.css('margin-top', '25px');


        adjust();
        iframe.height(1040);
    }
    return false;
};
function saveImageAs(imgOrURL) {
    if (typeof imgOrURL == 'object')
        imgOrURL = imgOrURL.src;
    window.win = open(imgOrURL);
    setTimeout('win.document.execCommand("SaveAs")', 500);
};
function adjust() {
    this.adjustFramePosition();
};

function adjustFramePosition() {
    var iframe = $('#jqxInnerdemoContainer');
    if (!iframe || iframe.length == 0)
        return;

    var offset = iframe.offset();
    var diff = parseFloat(offset.left) - parseInt(offset.left);
    if (diff != 0) {
        iframe[0].style.marginLeft = (1.0 - diff) + 'px';
    }

    var diffTop = parseFloat(offset.top) - parseInt(offset.top);
    if (diffTop != 0) {
        iframe[0].style.marginTop = (1.5 - diffTop) + 'px';
    }

};