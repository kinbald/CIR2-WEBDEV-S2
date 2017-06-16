$(document).ready(function () {
    $('#searchUser').on('keyup', function (e) {
        $('#responseList').empty();
        if (this.value.length > 1 )
        {
            ajaxChilds(this.value);
        }
    });
    $('#button-search').on('click', function (e) {
        $('#responseList').empty();
        ajaxChilds( $('#searchUser').val() );
    });
});

function ajaxChilds(nom) {
    var data = {nom_enfant: nom};
    $.ajax({
        type: 'POST',
        url: '/admin/ajax/getChild/',
        data: data,
        dataType: 'json'
    }).done(function (response) {
        $.each(response, function (k, v) {
            var $li = $("<li class=\"list-group-item\"></li>");
            $li.append(v.nom_enfant + " " + v.prenom_enfant);
            var $button = $('<a href="'+ v.path +'" id="rl_' + v.id_enfant +'" class="badge badge-warning">Modifier planning</a>');
            $li.append($button);
            $('#responseList').append($li);
        });
    });
}