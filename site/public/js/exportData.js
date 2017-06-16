/**
 * Created by thomas on 14/06/17.
 */
function ajaxExportDataGetClasses() {
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
        $("#classe").append('<option value="0" >Sélectionner Classe</option>');
        $.each(response, function (key, value) {
            console.log(key, value);
            $("#classe").append('<option value="' + key + '" >' + value + '</option>');
        });
    });
}
function ajaxExportDataGetPlanning() {
    var nom_classe = $("#classe option:selected").text();
    var nom_ecole = $("#ecole option:selected").text();
    var data = {nom_classe: nom_classe, nom_ecole: nom_ecole};
    $.ajax({
        type: 'POST',
        url: 'ajax/exportDataGetPlanning',
        data: data,
        dataType: 'json'
    }).always(function (response) {
        console.log('Traitement de la réponse');
        var $resultat = $("#resultat");
        $resultat.empty();
        $resultat.append('<div class="panel panel-default"></div><div class="panel-heading">Planning</div> <table class="table">');
        console.log(response);
        $.each(response, function (key, value) {
            var enfant=response[key];
            $resultat.append('<tbody><tr><td>'+enfant.nom_enfant+'</td><td>'+enfant.prenom_enfant+'</td><td>'+enfant.intitule+'</td></tr></tbody>');
        });
        $resultat.append('</table></div>');
        $resultat.append('<div> <a href="../../excel/planning.xls"><button type="button" class="btn">Télécharger</button></a></div>');
    });
}