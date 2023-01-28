$(document).ready(function () {
    var request = $.ajax({
        method: "GET",
        url: "http://51.210.151.13/btssnir/projets2022/fablab/api/cadenas/recuperer.php",
        dataType: "json"
    });

    request.done(function (msg) {
        var nombre_cadenas = $(msg).get(-1).Nombre

        $("#Nombre_cadenas").text("Nombre de cadenas : " + nombre_cadenas);

        for (let i = 0; i < (msg.length - 1); i++) {

            if ((msg[i].NomCadenas).includes("'")) {
                msg[i].NomCadenas = msg[i].NomCadenas.replace("'", "&apos;")
            }

            switch (msg[i].Actif) {
                case 1:
                    msg[i].Actif = "Oui";
                    break;
                case 0:
                    msg[i].Actif = "Non";
                    break;
            }

            if (msg[i].Nouveau == 1) {
                $("tbody").append(" <tr class='Cadenas' id='" + msg[i].idCadenas + "'>\n" +
                    "                                            <td style=\"height: 50px;\"><input style=\'border: none\' placeholder='Nouveau'></input></td>\n" +
                    "                                    <td><select\n" +
                    "                                            style=\"margin-top: 5px;background: var(--bs-table-bg);color: var(--bs-table-striped-color);border-color: var(--bs-table-bg);\">\n" +
                    "<option value=\"0\" disabled selected>Non défini</option>\n" +
                    "                                            <option value=\"1\">Member</option>\n" +
                    "                                            <option value=\"2\">Teacher</option>\n" +
                    "                                            <option value=\"3\">Manager</option>\n" +
                    "                                            <option value=\"4\">Admin</option>\n" +
                    "                                        </optgroup>\n" +
                    "                                    </select></td>\n" +
                    "                                            <td>" + msg[i].Actif + "</td>\n" +
                    "                                            <td><div id='supprimer_" + msg[i].idCadenas + "'><label>Supprimer</label> <i style=\"cursor: pointer;\" class=\"fas fa-ban\"></i></div> " +
                    "<button class=\"btn btn-primary\" type=\"button\" style=\"display: none; height: 30px;font - size:12px;\">Confirmer</button></td></tr>")
            } else {
                $("tbody").append("<tr class='Cadenas' id='" + msg[i].idCadenas + "'>\n" +
                    "                                            <td style=\"height: 50px;\"><input style=\'border: none\' placeholder='" + msg[i].NomCadenas + "'></td>\n" +
                    "                                    <td><select\n" +
                    "                                            style=\"margin-top: 5px;background: var(--bs-table-bg);color: var(--bs-table-striped-color);border-color: var(--bs-table-bg);\">\n" +
                    "                                        <optgroup label=\"" + msg[i].Niveau_complet + "\">\n" +
                    "                                            <option value=\"1\">Member</option>\n" +
                    "                                            <option value=\"2\">Teacher</option>\n" +
                    "                                            <option value=\"3\">Manager</option>\n" +
                    "                                            <option value=\"4\">Admin</option>\n" +
                    "                                        </optgroup>\n" +
                    "                                    </select></td>\n" +
                    "                                            <td>" + msg[i].Actif + "</td>" +
                    "                                            <td ><div id='supprimer_" + msg[i].idCadenas + "'><label>Supprimer</label> <i style=\"cursor: pointer;\" class=\"fas fa-ban\"></i></div> " +
                    "<button class=\"btn btn-primary\" type=\"button\" style=\"display: none; height: 35px;font - size:12px;\">Confirmer</button></td></tr>")
            }

            //Sélectionner le bon grade dans le select
            $("#" + msg[i].idCadenas + " select option[value=" + msg[i].Niveau + "]").attr('selected', 'selected');

///////////////////////CODE SUPPRESSION////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            $("#supprimer_" + msg[i].idCadenas).click(function () {
                if (window.confirm("Voulez-vous supprimer ce cadenas ?")) {
                    request = $.ajax({
                        method: "POST",
                        url: "http://51.210.151.13/btssnir/projets2022/fablab/api/cadenas/supprimer.php",
                        data: {
                            id: msg[i].idCadenas
                        },
                        dataType: "json"
                    });
                    request.done(function (msg3) {
                        if (msg3.success == true) {
                            $("#" + msg[i].idCadenas).remove();
                        }
                    })
                }
            })
            //Détecter un changement dans les select
            $("#" + msg[i].idCadenas + " select").change(function () {
                //récupérer la valeur contenue dans l'affiche du menu déroulant
                var Ancien_Grade = $(this).find("optgroup").attr('label');
                //récupérer la valeur contenue dans la valeur du menu déroulant
                var Nouveau_Grade = $(this).find('option:selected').text();


                //Si la valeur du changement est différente de la valeur précédente
                if (Ancien_Grade != Nouveau_Grade) {
                    //Faire le focus sur le membre
                    $(".Cadenas").hide();
                    $("#" + msg[i].idCadenas).show();
                    //et afficher le bouton de confirmation
                    $("#" + msg[i].idCadenas + " button").show();
                    $("#" + msg[i].idCadenas + " div").hide();

                } else {
                    //Sinon remttre à l'état initial

                    if (!$("#" + msg[i].idCadenas + " input").val()) {
                        $(".Cadenas").show();
                        $("#" + msg[i].idCadenas + " button").hide();
                        $("#" + msg[i].idCadenas + " div").show();
                    }
                }
            })

            $("#" + msg[i].idCadenas + " input").on("input", function () {
                var Ancien_Grade = $("#" + msg[i].idCadenas + " select").find("optgroup").attr('label');
                var Nouveau_Grade = $("#" + msg[i].idCadenas + " select").find('option:selected').text();

                if ($("#" + msg[i].idCadenas + " input").val() == "" && Ancien_Grade == Nouveau_Grade) {
                    $(".Cadenas").show();
                    $("#" + msg[i].idCadenas + " button").hide();
                    $("#" + msg[i].idCadenas + " div").show();
                } else {
                    $(".Cadenas").hide();
                    $("#" + msg[i].idCadenas).show();
                    $("#" + msg[i].idCadenas + " button").show();
                    $("#" + msg[i].idCadenas + " div").hide();
                }
            });
            $("#" + msg[i].idCadenas + " button").click(function () {

                var Nouveau_Niveau = $("#" + msg[i].idCadenas + " select").find('option:selected');

                if ($("#" + msg[i].idCadenas + " input").val())
                    var Nouveau_Nom = $("#" + msg[i].idCadenas + " input").val();
                else
                    var Nouveau_Nom = $("#" + msg[i].idCadenas + " input").attr("placeholder");


                var request = $.ajax({
                    method: "POST",
                    url: "http://51.210.151.13/btssnir/projets2022/fablab/api/cadenas/modifier.php",
                    data: {id: msg[i].idCadenas, Niveau: $(Nouveau_Niveau[0]).val(), Nom: Nouveau_Nom},
                    dataType: "json"
                });

                request.done(function (msg2) {
                    if (msg2.success == true) {
                        alert("Changement effectué pour " + msg[i].NomCadenas)
                        location.reload();
                    } else {
                        alert("Erreur")
                    }
                })
            })
        }
///////////////////CODE RECHERCHE////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        //Detecter un changement dans la barre de recherche
        $("#recherche").on("input", function () {
            //recuperer les cases comportant le nom des adhérents
            var collection = $(".Cadenas td input");

            //recuperer la recherche et la formater pour retirer les majucules
            var recherche = this.value.toLowerCase()

            for (let i = 0; i < collection.length; i++) {

                //recuperer le nom des adhérents à l'intérieur des case et les formater pour retirer les majucules
                var nom = collection[i].placeholder.toLowerCase()

                //Si le nom correspond à la recherche
                if ((nom).includes(recherche))
                    //Le maintenir afffiché sur la page
                    collection[i].parentNode.parentNode.hidden = false
                else
                    //Sinon le cacher
                    collection[i].parentNode.parentNode.hidden = true
            }
        })
    })
})