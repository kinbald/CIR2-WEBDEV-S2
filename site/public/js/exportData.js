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
        $resultat.append('<h2 class="form-title">Planning</h2> <ul class="list-group">');
        console.log(response);
        $.each(response, function (key, value) {
            var enfant=response[key];
            if(enfant.nom_enfant !== 'Nom') {
                $resultat.append('<li class="list-group-item">' + enfant.nom_enfant + ' | ' + enfant.prenom_enfant + ' | ' + enfant.intitule + '</li>');
            }
        });
        $resultat.append('</ul>');
        $resultat.append('<div> <a href="../../excel/planning.xls"><button type="button" class="btn">Télécharger</button></a></div>');
    });
}