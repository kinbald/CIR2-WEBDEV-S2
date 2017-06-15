/**
 * Created by thomas on 14/06/17.
 */
function ajaxExportDataGetClasses()
{
    var nom_ecole = $("#ecole option:selected").text();
    var data = {nom_ecole: nom_ecole};
    $.ajax({
        type: 'POST',
        url: 'ajax/exportDataGetClasses',
        data: data,
        dataType: 'json'
    }).done(function (response) {
        console.log('Traitement de la réponse');
        $.each(response, function (key, value) {
            console.log(key,  value);
            $("#classe").append('<option value="' + key + '" >' + value + '</option>');
        });
    });
}
