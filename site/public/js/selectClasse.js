/**
 * Created by thomas on 14/06/17.
 */
$.ajax({
    type: 'POST',
    url: urlR,
    data: data,
    dataType: 'json'
}).done(function (response) {
    var events = [];
    $.each(response, function (k) {
        events.push(response[k]);
    });
    //recupere les evente
    $calendarElement.data('events', events);
    //les dessines
    drawEvents($calendarElement);
});