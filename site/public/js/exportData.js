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
        $("#resultat").empty();
        $("#resultat").append('<table>');
        $("#resultat").append('<tr><td>NOM</td><td>PRENOM</td><td>ACTIVITÉ</td></tr>');
        for(var key in response){
            var enfant=response[key];
            $("#resultat").append('<tr><td>'+enfant.nom_enfant+'</td><td>'+enfant.prenom_enfant+'</td><td>'+enfant.intitule+'</td></tr>');
        }
        $("#resultat").append('</table>');
        $("#resultat").append('<a href="../../public/excel/planning.xls">Télécharger le Planning</a>');
    });
}