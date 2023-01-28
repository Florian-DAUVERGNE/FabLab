$(document).ready(function () {
    //Requête pour récupérer les cadenas ouverts
    var request = $.ajax({
        url: "http://51.210.151.13/btssnir/projets2022/fablab/api/log/recuperer.php",
        method: "GET",
        data: {cadenas: 1},
        dataType: "json"
    });
    request.done(function (msg) {
//Créer des tableaux pour contenir la réponse et les réutiliser pour les graphiques
        let cadenas = [];
        let badger = [];
        let couleur = [];


        for (let i = 0; i < msg.length; i++) {
            //Ajouter les valeurs de la réponse dans le tableau correspondant
            badger.push(msg[i].badger);
            cadenas.push(msg[i].NomCadenas)

            //Générer aléatoirement une couleur
            var r = Math.floor(Math.random() * 255);
            var g = Math.floor(Math.random() * 255);
            var b = Math.floor(Math.random() * 255);

            //Ajouter la couleur dans le tableau correspondant
            couleur.push("rgba(" + r + "," + g + "," + b + "," + "0.5" + ")")
        }
        //Ajouter un zéro dans les données pour formater le graphe
        badger.push(0)

//Enfin créer le graphe avec les tableau contenant les données
        new Chart(document.getElementById('myChart4').getContext('2d'), {
            type: 'bar',
            data: {
                labels: cadenas,
                datasets: [{
                    label: 'Nombre d\'ouverture',
                    data: badger,
                    backgroundColor: couleur,
                    borderColor: couleur,
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    })
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Requête pour récupérer les passage par mois
    var request = $.ajax({
        url: "http://51.210.151.13/btssnir/projets2022/fablab/api/log/recuperer.php",
        method: "GET",
        data: {mois: 1},
        dataType: "json"
    });

    request.done(function (msg) {
        //Créer des tableaux pour contenir la réponse et les réutiliser pour les graphiques
        let passage = [msg.Janvier, msg.Février, msg.Mars, msg.Avril, msg.Mai, msg.Juin, msg.Juillet, msg.Août, msg.Septembre, msg.Octobre, msg.Novembre, msg.Décembre];
        let mois = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
        let couleur = []

        //Générer aléatoirement une couleur
        for (let i = 0; i < 13; i++) {
            var r = Math.floor(Math.random() * 255);
            var g = Math.floor(Math.random() * 255);
            var b = Math.floor(Math.random() * 255);


            //Ajouter la couleur dans le tableau correspondant
            couleur.push("rgba(" + r + "," + g + "," + b + "," + "0.5" + ")")
        }
        //Ajouter un zéro dans les données pour formater le graphe
        passage.push(0)

        //Enfin créer le graphe avec les tableau contenant les données
        new Chart(document.getElementById('myChart3').getContext('2d'), {
            type: 'bar',
            data: {
                labels: mois,
                datasets: [{
                    label: 'Nombre de passage',
                    data: passage,
                    backgroundColor: couleur,
                    borderColor: couleur,
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    })
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Requête pour récupérer les passage par jour
    var request = $.ajax({
        url: "http://51.210.151.13/btssnir/projets2022/fablab/api/log/recuperer.php",
        method: "GET",
        data: {jour: 1},
        dataType: "json"
    });

    request.done(function (msg) {
        //Créer des tableaux pour contenir la réponse et les réutiliser pour les graphiques
        let passage = [msg.Lundi, msg.Mardi, msg.Mercredi, msg.Jeudi, msg.Vendredi];
        let jour = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi"];
        let couleur = [];


        for (let i = 0; i < 6; i++) {
            //Générer aléatoirement une couleur
            var r = Math.floor(Math.random() * 255);
            var g = Math.floor(Math.random() * 255);
            var b = Math.floor(Math.random() * 255);

            //Ajouter la couleur dans le tableau correspondant
            couleur.push("rgba(" + r + "," + g + "," + b + "," + "0.5" + ")")
        }

        //Ajouter un zéro dans les données pour formater le graphe
        passage.push(0)

        //Enfin créer le graphe avec les tableau contenant les données
        new Chart(document.getElementById('myChart2').getContext('2d'), {
            type: 'bar',
            data: {
                labels: jour,
                datasets: [{
                    label: 'Nombre de passage',
                    data: passage,
                    backgroundColor: couleur,
                    borderColor: couleur,
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    })
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Requête pour récupérer les passage par heures
    var request = $.ajax({
        url: "http://51.210.151.13/btssnir/projets2022/fablab/api/log/recuperer.php",
        method: "GET",
        data: {heures: 1},
        dataType: "json"
    });

    request.done(function (msg) {
        //Créer des tableaux pour contenir la réponse et les réutiliser pour les graphique
        let passage = [msg["8h"], msg["9h"], msg["10h"], msg["11h"], msg["12h"], msg["13h"], msg["14h"], msg["15h"], msg["16h"], msg["17h"], msg["18h"]];
        let mois = ["8h", "9h", "10h", "11h", "12h", "13h", "14h", "15h", "16h", "17h", "18h"];
        let couleur = []


        for (let i = 0; i < 13; i++) {
            //Générer aléatoirement une couleur
            var r = Math.floor(Math.random() * 255);
            var g = Math.floor(Math.random() * 255);
            var b = Math.floor(Math.random() * 255);
            //Ajouter la couleur dans le tableau correspondant
            couleur.push("rgba(" + r + "," + g + "," + b + "," + "0.5" + ")")
        }

//Ajouter un zéro dans les données pour formater le graphe
        passage.push(0)
//Enfin créer le graphe avec les tableau contenant les données
        new Chart(document.getElementById('myChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: mois,
                datasets: [{
                    label: 'Nombre de passage',
                    data: passage,
                    backgroundColor: couleur,
                    borderColor: couleur,
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    })
})