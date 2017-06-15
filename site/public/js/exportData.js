/**
 * Created by thomas on 14/06/17.
 */
function ajaxSelectClasse() {

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

    });
}
