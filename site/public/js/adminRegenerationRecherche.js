/**
 * Created by kinbald on 15/06/17.
 */
$(document).ready(function () {
    $('#searchUser').on('keyup', function (e) {
        $('#responseList').empty();
        if (this.value.length > 1 )
        {
            ajaxUsers(this.value);
        }
    });
    $('#button-search').on('click', function (e) {
        $('#responseList').empty();
        ajaxUsers( $('#searchUser').val() );
    });
});

function ajaxUsers(nom) {
    var data = {nom_rl: nom};
    $.ajax({
        type: 'POST',
        url: '/admin/ajax/getUser/',
        data: data,
        dataType: 'json'
    }).done(function (response) {
        $.each(response, function (k, v) {
            var $li = $("<li class=\"list-group-item\"></li>");
            $li.append(v.nom_rl + " "  + v.prenom_rl);
            var $button = $('<a href="'+ v.path +'" id="rl_' + v.id_responsable_legal +'" class="badge badge-warning">Modifier</a>');
            $li.append($button);
            $('#responseList').append($li);
        });
    });
}