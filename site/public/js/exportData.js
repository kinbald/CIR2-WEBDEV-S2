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
        $("#classe").empty();
        $("#classe").append('<option value="0" disabled="">Sélectionner Classe</option>');
        $.each(response, function (key, value) {
            console.log(key,  value);
            $("#classe").append('<option value="' + key + '" >' + value + '</option>');
        });
    });
}
function ajaxExportDataGetPlanning()
{
    var nom_classe = $("#classe option:selected").text();
    var nom_ecole = $("#ecole option:selected").text();
    var data = {nom_classe: nom_classe,nom_ecole: nom_ecole};
    $.ajax({
        type: 'POST',
        url: 'ajax/exportDataGetPlanning',
        data: data,
        dataType: 'json'
    }).always(function (response) {
        console.log('Traitement de la réponse');
        $("#resultat").empty();
        $.each(response, function (key, value) {
            console.log(key,  value);
            $("#classe").append('<p>Retour'+key+':' + value + '</p>');
        });
    });
}