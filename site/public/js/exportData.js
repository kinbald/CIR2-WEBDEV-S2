/**
 * Created by thomas on 14/06/17.
 */
function ajaxExportData() {

    var nom_ecole = $("#ecole option:selected").text();
    var nom_classe = $("#classe option:selected").text();
    var date_journee = $("#date_journee").val();
    var data = {nom_ecole: nom_ecole, nom_classe: nom_classe, date_journee: date_journee};
    $.ajax({
        type: 'POST',
        url: '/exportData',
        data: data,
        dataType: 'json'
    }).always(function (response) {
        console.log('Traitement de la réponse');
        $.each(response, function (key, value) {
            $("#classe").append(new Option({key:value}, {key:value}));

        });

    });
}
