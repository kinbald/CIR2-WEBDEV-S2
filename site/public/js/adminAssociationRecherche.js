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
    $('#searchEnfant').on('keyup', function (e) {
        $('#responseListEnfant').empty();
        if (this.value.length > 1 )
        {
            ajaxChilds(this.value);
        }
    });
    $('#button-search-enfant').on('click', function (e) {
        $('#responseListEnfant').empty();
        ajaxChilds( $('#searchEnfant').val() );
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
            var $check = $('<div class="checkbox badge icheckbox_flat"> <label> <input name="parent" type="checkbox" value="' + v.id_responsable_legal +'"></label> </div>');
            $li.append($check);
            $('#responseList').append($li);
        });
    });
}
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
            var $check = $('<div class="checkbox badge icheckbox_flat"> <label> <input name="enfant" type="checkbox" value="' + v.id_enfant +'"></label> </div>');
            $li.append($check);
            $('#responseListEnfant').append($li);
        });
    });
}