$(document).ready(function (){
    var request = $.ajax({
        method: "GET",
        url: "http://51.210.151.13/btssnir/projets2022/fablab/api/log/recuperer.php",
        data: {brut:1},
        dataType: "json"
    });

    request.done(function (msg){
        var nom=[];
        var cadenas=[];
        var date=[];

        for(let i=0;i<msg.length;i++){
            if(!nom.includes(msg[i].Nom+" "+msg[i].Prenom)){
                nom.push(msg[i].Nom+" "+msg[i].Prenom)
            }
            if(!cadenas.includes(msg[i].NomCadenas)){
                cadenas.push(msg[i].NomCadenas)
            }
            if(!date.includes(msg[i].Date)){
                date.push(msg[i].Date)
            }
            $("#logs").append("<ul>"+msg[i].Nom+" "+msg[i].Prenom+" a ouvert "+msg[i].NomCadenas+" le "+msg[i].Date+" à "+msg[i].Horaire+"</ul>")
        }



        for(let i=0;i<nom.length;i++){
            $("#recherche_personne").append(`<option value="${nom[i]}">${nom[i]}</option>`)
        }

        for(let i=0;i<cadenas.length;i++){
            $("#recherche_cadenas").append(`<option value="${cadenas[i]}">${cadenas[i]}</option>`)
        }

        for(let i=0;i<date.length;i++){
            $("#recherche_jour").append(`<option value="${date[i]}">${date[i]}</option>`)
        }

        var collection=$("#logs").find("ul");

        $( "#boutonRecherche").click(function() {



            //$("#recherche_personne").val()
            // var nom=$("#recherche_personne").val();
            // var cadenas=$("#recherche_cadenas").val();
            // var jour=$("#recherche_jour").val();

            // if(nom!=null){
            //     recherche+=nom
            // }
            //
            // if(cadenas!=null){
            //     recherche+=cadenas
            // }

            //var recherche=nom+" a ouvert "+cadenas+" le"+jour


            for(let i=0;i<collection.length;i++){
                var str = collection[i].innerText;
                var nom=str.split("a ouvert")[0]
                var cadenas=str.split("a ouvert")[1].split("le")[0]
                var date=str.split("a ouvert")[1].split("le")[1].split("à")[0]
                // console.log(nom)
                 console.log($("#recherche_personne").val())

               if(nom.includes($("#recherche_personne").val())){
                   collection[i].style.display="block"
               }else{
                   collection[i].style.display="none"
               }
            }
        });
    })

})
