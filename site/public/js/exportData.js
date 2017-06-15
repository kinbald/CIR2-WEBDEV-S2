/**
 * Created by thomas on 14/06/17.
 */
function ajaxExportData() {
        $("#resultat").html("<p> Teste </p>");
        var nom_ecole = $("#ecole").val();
        var nom_classe = $("#classe").val();
        var date_journee = $("#date_journee").text();
        var data = {nom_ecole: nom_ecole, nom_classe: nom_classe, date_journee: date_journee};
    $.ajax({
        type: 'POST',
        url: '/exportData',
        data: data,
        dataType: 'json'
    }).done(function (response) {
        var events = [];
        $.each(response, function (k) {
            events.push(response[k]);
        });
    });
}
