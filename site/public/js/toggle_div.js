/**
 * Created by thomas on 14/06/17.
 */
function toggle_div(id) {

    var div = document.getElementById(id);

    if(div.style.display=="none") {

        div.style.display = "block";

    } else {

        div.style.display = "none";

    }

}