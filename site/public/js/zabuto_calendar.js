/**
 * Zabuto Calendar
 *
 * Dependencies
 * - jQuery (2.0.3)
 * - Twitter Bootstrap (3.0.2)
 */

if (typeof jQuery === 'undefined') {
    throw new Error('jQuery is not loaded');
}

/**
 * Create calendar
 *
 * @param options
 * @returns {*}
 */
$.fn.zabuto_calendar = function (options) {
    //recupere les options et rajoute les valeurs par defaut
    var opts = $.extend({}, $.fn.zabuto_calendar_defaults(), options);
    //info du langage du calendrier
    var languageSettings = $.fn.zabuto_calendar_language(opts.language);
    //
    opts = $.extend({}, opts, languageSettings);

    this.each(function () {
        var $calendarElement = $(this);
        //id aleatoire genere pour le calendrier (pour eviter les conflit si plusieurs meme page)
        $calendarElement.attr('id', "zabuto_calendar_" + Math.floor(Math.random() * 99999).toString(36));

        $calendarElement.data('initYear', opts.year);
        $calendarElement.data('initMonth', opts.month);
        $calendarElement.data('monthLabels', opts.month_labels);
        $calendarElement.data('navIcons', opts.nav_icon);
        $calendarElement.data('dowLabels', opts.dow_labels);
        $calendarElement.data('showPrevious', opts.show_previous);
        $calendarElement.data('showNext', opts.show_next);
        $calendarElement.data('jsonData', opts.data);
        $calendarElement.data('legendList', opts.legend);
        $calendarElement.data('actionFunction', opts.action);
        $calendarElement.data('actionNavFunction', opts.action_nav);

        drawCalendar();

        /**
         * fonction de dessins du calendrier
         */
        function drawCalendar() {
            var dateInitYear = parseInt($calendarElement.data('initYear'));
            var dateInitMonth = parseInt($calendarElement.data('initMonth')) - 1;
            var dateInitObj = new Date(dateInitYear, dateInitMonth, 1, 0, 0, 0, 0);
            $calendarElement.data('initDate', dateInitObj);

            var $tableObj = $('<table class="table' + ' table-bordered' + '"></table>');
            //var $tableObj = $('<table border="1" class="table"></table>');
            $tableObj = drawTable($calendarElement, $tableObj, dateInitObj.getFullYear(), dateInitObj.getMonth());

            var $legendObj = drawLegend($calendarElement);

            var $containerHtml = $('<div class="zabuto_calendar" id="' + $calendarElement.attr('id') + '"></div>');
            $containerHtml.append($tableObj);
            $containerHtml.append($legendObj);

            $calendarElement.append($containerHtml);

            var jsonData = $calendarElement.data('jsonData');
            if (false !== jsonData) {
                checkEvents($calendarElement, dateInitObj.getFullYear(), dateInitObj.getMonth());
            }
        }

        //dessine la table
        function drawTable($calendarElement, $tableObj, year, month) {
            var dateCurrObj = new Date(year, month, 1, 0, 0, 0, 0);
            $calendarElement.data('currDate', dateCurrObj);

            $tableObj.empty();
            //nom du mois
            $tableObj = appendMonthHeader($calendarElement, $tableObj, year, month);
            //
            $tableObj = appendDayOfWeekHeader($calendarElement, $tableObj);
            $tableObj = appendDaysOfMonth($calendarElement, $tableObj, year, month);
            checkEvents($calendarElement, year, month);
            return $tableObj;
        }

        function drawLegend($calendarElement) {
            var $legendObj = $('<div class="legend" id="' + $calendarElement.attr('id') + '_legend"></div>');
            var legend = $calendarElement.data('legendList');
            if (typeof(legend) === 'object' && legend.length > 0) {
                $(legend).each(function (index, item) {
                    if (typeof(item) === 'object') {
                        if ('type' in item) {
                            var itemLabel = '';
                            if ('label' in item) {
                                itemLabel = item.label;
                            }
                            switch (item.type) {
                                case 'text':
                                    if (itemLabel !== '') {
                                        var itemBadge = '';
                                        $legendObj.append('<span class="legend-' + item.type + '">' + itemBadge + itemLabel + '</span>');
                                    }
                                    break;
                                case 'block':
                                    if (itemLabel !== '') {
                                        itemLabel = '<span>' + itemLabel + '</span>';
                                    }
                                    var listClassName;
                                    if (typeof(item.classname) === 'undefined') {
                                        listClassName = 'event';
                                    } else {
                                        listClassName = 'event-styled ' + item.classname;
                                    }
                                    $legendObj.append('<span class="legend-' + item.type + '"><ul class="legend"><li class="' + listClassName + '"></li></u>' + itemLabel + '</span>');
                                    break;
                                case 'list':
                                    if ('list' in item && typeof(item.list) === 'object' && item.list.length > 0) {
                                        var $legendUl = $('<ul class="legend"></u>');
                                        $(item.list).each(function (listIndex, listClassName) {
                                            $legendUl.append('<li class="' + listClassName + '"></li>');
                                        });
                                        $legendObj.append($legendUl);
                                    }
                                    break;
                                case 'spacer':
                                    $legendObj.append('<span class="legend-' + item.type + '"> </span>');
                                    break;

                            }
                        }
                    }
                });
            }

            return $legendObj;
        }

        function appendMonthHeader($calendarElement, $tableObj, year, month) {
            var navIcons = $calendarElement.data('navIcons');
            var $prevMonthNavIcon = $('<span><span class="glyphicon glyphicon-chevron-left"></span></span>');
            var $nextMonthNavIcon = $('<span><span class="glyphicon glyphicon-chevron-right"></span></span>');
            if (typeof(navIcons) === 'object') {
                if ('prev' in navIcons) {
                    $prevMonthNavIcon.html(navIcons.prev);
                }
                if ('next' in navIcons) {
                    $nextMonthNavIcon.html(navIcons.next);
                }
            }

            var prevIsValid = $calendarElement.data('showPrevious');
            if (typeof(prevIsValid) === 'number' || prevIsValid === false) {
                prevIsValid = checkMonthLimit($calendarElement.data('showPrevious'), true);
            }

            var $prevMonthNav = $('<div class="calendar-month-navigation"></div>');
            $prevMonthNav.attr('id', $calendarElement.attr('id') + '_nav-prev');
            $prevMonthNav.data('navigation', 'prev');
            if (prevIsValid !== false) {
                var prevMonth = (month - 1);
                var prevYear = year;
                if (prevMonth === -1) {
                    prevYear = (prevYear - 1);
                    prevMonth = 11;
                }
                $prevMonthNav.data('to', {year: prevYear, month: (prevMonth + 1)});
                $prevMonthNav.append($prevMonthNavIcon);
                if (typeof($calendarElement.data('actionNavFunction')) === 'function') {
                    $prevMonthNav.click($calendarElement.data('actionNavFunction'));
                }
                $prevMonthNav.click(function (e) {
                    drawTable($calendarElement, $tableObj, prevYear, prevMonth);
                });
            }

            var nextIsValid = $calendarElement.data('showNext');
            if (typeof(nextIsValid) === 'number' || nextIsValid === false) {
                nextIsValid = checkMonthLimit($calendarElement.data('showNext'), false);
            }

            var $nextMonthNav = $('<div class="calendar-month-navigation"></div>');
            $nextMonthNav.attr('id', $calendarElement.attr('id') + '_nav-next');
            $nextMonthNav.data('navigation', 'next');
            if (nextIsValid !== false) {
                var nextMonth = (month + 1);
                var nextYear = year;
                if (nextMonth === 12) {
                    nextYear = (nextYear + 1);
                    nextMonth = 0;
                }
                $nextMonthNav.data('to', {year: nextYear, month: (nextMonth + 1)});
                $nextMonthNav.append($nextMonthNavIcon);
                if (typeof($calendarElement.data('actionNavFunction')) === 'function') {
                    $nextMonthNav.click($calendarElement.data('actionNavFunction'));
                }
                $nextMonthNav.click(function (e) {
                    drawTable($calendarElement, $tableObj, nextYear, nextMonth);
                });
            }

            var monthLabels = $calendarElement.data('monthLabels');

            var $prevMonthCell = $('<th></th>').append($prevMonthNav);
            var $nextMonthCell = $('<th></th>').append($nextMonthNav);

            var $currMonthLabel = $('<span>' + monthLabels[month] + ' ' + year + '</span>');
            $currMonthLabel.dblclick(function () {
                var dateInitObj = $calendarElement.data('initDate');
                drawTable($calendarElement, $tableObj, dateInitObj.getFullYear(), dateInitObj.getMonth());
            });

            var $currMonthCell = $('<th colspan="5"></th>');
            $currMonthCell.append($currMonthLabel);

            var $monthHeaderRow = $('<tr class="calendar-month-header"></tr>');
            $monthHeaderRow.append($prevMonthCell, $currMonthCell, $nextMonthCell);

            $tableObj.append($monthHeaderRow);
            return $tableObj;
        }

        function appendDayOfWeekHeader($calendarElement, $tableObj) {
            var dowLabels = $calendarElement.data('dowLabels');
            var $dowHeaderRow = $('<tr class="calendar-dow-header"></tr>');
            $(dowLabels).each(function (index, value) {
                $dowHeaderRow.append('<th>' + value + '</th>');
            });
            $tableObj.append($dowHeaderRow);
            return $tableObj;
        }

        function appendDaysOfMonth($calendarElement, $tableObj, year, month) {
            var weeksInMonth = calcWeeksInMonth(year, month);
            var lastDayinMonth = calcLastDayInMonth(year, month);
            var firstDow = calcDayOfWeek(year, month, 1);
            var currDayOfMonth = 1;


            for (var wk = 0; wk < weeksInMonth; wk++) {
                var $dowRow = $('<tr class="calendar-dow"></tr>');
                for (var dow = 0; dow < 7; dow++) {
                    if (dow < firstDow || currDayOfMonth > lastDayinMonth) {
                        $dowRow.append('<td></td>');
                    } else {
                        var dateId = $calendarElement.attr('id') + '_' + dateAsString(year, month, currDayOfMonth);
                        var dayId = dateId + '_day';

                        var $dayElement = $('<div id="' + dayId + '" class="day" >' + currDayOfMonth + '</div>');
                        $dayElement.data('day', currDayOfMonth);
                        $dayElement.addClass('dropdown-toggle');
                        $dayElement.attr("data-toggle" , "dropdown");
                        $dayElement.attr("aria-haspopup", "true");
                        $dayElement.attr("aria-expanded", "true");

                        var $dowElement = $('<td id="' + dateId + '"></td>');
                        $dowElement.addClass('dropdown');
                        $dowElement.addClass('dow-clickable');
                        $dowElement.append($dayElement);

                        var $dayUL = $('<ul id="' + dayId + '_ul" class="dropdown-menu" ></ul>');
                        $dayUL.attr("aria-labelledby", dayId + "_ul");
                        $dowElement.append($dayUL);

                        //TODO ajout liste des options de façon automatique
                        var Activite =
                            {
                                "1" : "Garderie",
                                "2" : "JIS"
                            };
                        for(var key in Activite)
                        {
                            var $Li = $('<li></li>');
                            var $a = $('<a id="' + key + '_' + dateAsString(year, month, currDayOfMonth) + '" href="#">'+ Activite[key] +'</a>');
                            $a.click(function (event) {
                                event.preventDefault();
                                var $id = $(this).attr('id');
                                var type = $id.split("_")[0];
                                var date = $id.split("_")[1];
                                var tabLocation = window.location.href.split('/');
                                var id_enfant = tabLocation[tabLocation.length - 1];
                                ajaxInsert($calendarElement, id_enfant, type, date);
                            });
                            $Li.append($a);
                            $dayUL.append($Li);
                        }
                        //END TODO

                        $dowElement.data('date', dateAsString(year, month, currDayOfMonth));
                        $dowElement.data('hasEvent', false);

                        $dowRow.append($dowElement);

                        currDayOfMonth++;
                    }
                    if (dow === 6) {
                        firstDow = 0;
                    }
                }

                $tableObj.append($dowRow);
            }
            return $tableObj;
        }

        function ajaxInsert($calendarElement, id, type, date) {

            var data = {date: date, id_activite: type, id_enfant: id};

            $.ajax({
                type: 'POST',
                url: '/ajax/calendrierSetDay',
                data: data,
                dataType: 'json'
            }).done(function (response) {
                ajaxEvents($calendarElement, date.split('-')[0], date.split('-')[1], "reload");
            });

            return true;
        }

        /* ----- Event functions ----- */
        //todo gestion de l'event
        function checkEvents($calendarElement, year, month) {
            var jsonData = $calendarElement.data('jsonData');
            $calendarElement.data('events', false);
            return ajaxEvents($calendarElement, year, month, null);
        }

        function jsonEvents($calendarElement) {
            var jsonData = $calendarElement.data('jsonData');
            $calendarElement.data('events', jsonData);
            drawEvents($calendarElement);
            return true;
        }

        function ajaxEvents($calendarElement, year, month, callerMethod) {
            // Cas ou on appelle un rafraichissement des évènements affichés
            if (callerMethod === "reload")
            {
                var data = {annee: year, mois: month};
            }
            else
            {
                var data = {annee: year, mois: (month + 1)};
            }
            //todo l'ajax
            //effecture requete ajax
            var urlR =window.location.href.replace('calendrier',"ajax/calendrier");
            $.ajax({
                type: 'POST',
                url: urlR,
                data: data,
                dataType: 'json'
            }).done(function (response) {
                var events = [];
                $.each(response, function (k, v) {
                    events.push(response[k]);
                });
                //recupere les evente
                $calendarElement.data('events', events);
                //les dessines
                drawEvents($calendarElement);
            });

            return true;
        }

        //todo fonction de dessin des évenement
        //
        function drawEvents($calendarElement) {

            var events = $calendarElement.data('events');
            if (events !== false) {
                $(events).each(function (index, value) {
                    var id = $calendarElement.attr('id') + '_' + value.date;
                    var $dowElement = $('#' + id);
                    var $dayElement = $('#' + id + '_day');

                    $dowElement.data('hasEvent', true);

                    if (typeof(value.title) !== 'undefined') {
                        $dowElement.attr('title', value.title);
                    }

                    if (typeof(value.classname) === 'undefined') {
                        $dowElement.addClass('event');
                    } else {
                        $dowElement.addClass('event-styled');
                        //todo list class possible
                        $dayElement.removeClass('red green');
                        $dayElement.addClass(value.classname);
                    }
                });
            }
        }

        /* ----- Helper functions ----- */

        function dateAsString(year, month, day) {
            var d = (day < 10) ? '0' + day : day;
            var m = month + 1;
            m = (m < 10) ? '0' + m : m;
            return year + '-' + m + '-' + d;
        }

        function calcDayOfWeek(year, month, day) {
            var dateObj = new Date(year, month, day, 0, 0, 0, 0);
            var dow = dateObj.getDay();
            if (dow === 0) {
                dow = 6;
            } else {
                dow--;
            }
            return dow;
        }

        function calcLastDayInMonth(year, month) {
            var day = 28;
            while (checkValidDate(year, month + 1, day + 1)) {
                day++;
            }
            return day;
        }

        function calcWeeksInMonth(year, month) {
            var daysInMonth = calcLastDayInMonth(year, month);
            var firstDow = calcDayOfWeek(year, month, 1);
            var lastDow = calcDayOfWeek(year, month, daysInMonth);
            var days = daysInMonth;
            var correct = (firstDow - lastDow);
            if (correct > 0) {
                days += correct;
            }
            return Math.ceil(days / 7);
        }

        function checkValidDate(y, m, d) {
            return m > 0 && m < 13 && y > 0 && y < 32768 && d > 0 && d <= (new Date(y, m, 0)).getDate();
        }

        function checkMonthLimit(count, invert) {
            if (count === false) {
                count = 0;
            }
            var d1 = $calendarElement.data('currDate');
            var d2 = $calendarElement.data('initDate');

            var months;
            months = (d2.getFullYear() - d1.getFullYear()) * 12;
            months -= d1.getMonth() + 1;
            months += d2.getMonth();

            if (invert === true) {
                if (months < (parseInt(count) - 1)) {
                    return true;
                }
            } else {
                if (months >= (0 - parseInt(count))) {
                    return true;
                }
            }
            return false;
        }
    }); // each()

    return this;
};

/**
 * Default settings
 *
 * @returns object
 *   language:          string,
 *   year:              integer,
 *   month:             integer,
 *   show_previous:     boolean|integer,
 *   show_next:         boolean|integer,
 *   nav_icon:          object: prev: string, next: string
 *   legend:            object array, [{type: string, label: string, classname: string}]
 *   action:            function
 *   action_nav:        function
 */
$.fn.zabuto_calendar_defaults = function () {
    var now = new Date();
    var year = now.getFullYear();
    var month = now.getMonth() + 1;
    var settings = {
        language: false,
        year: year,
        month: month,
        show_previous: true,
        show_next: true,
        nav_icon: false,
        data: false,

        legend: false,
        action: false,
        action_nav: false
    };
    return settings;
};

/**
 * Language settings
 *
 * @param lang
 * @returns {{month_labels: Array, dow_labels: Array}}
 */
$.fn.zabuto_calendar_language = function (lang) {
    if (typeof(lang) === 'undefined' || lang === false) {
        lang = 'fr';
    }
    switch (lang.toLowerCase()) {
        case 'en':
            return {
                month_labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                dow_labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"]
            };
            break;

        case 'fr':
            return {
                month_labels: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
                dow_labels: ["Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim"]
            };
            break;
    }
};


/**
 * lancement du calendrier
 */
$(document).ready(function () {
    //language=JQuery-CSS
    $("#my-calendar").zabuto_calendar();
});