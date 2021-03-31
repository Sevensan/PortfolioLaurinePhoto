$( document ).ready(function() {

$(".fondGrandeImage").hide();


    //si je clique sur une image d'une card, je fais apparaitre l'image qui a comme id le même alt
    $('.imgPublication').click(function(){
        let altPetiteImage = $(this).attr('alt');
        console.log(altPetiteImage);
        //string

        $('.imageGrand').each(function(i){
            let imageGrand = $(this).attr('alt'); 
            console.log(imageGrand);
            if("grand" + altPetiteImage == imageGrand)
            {
                console.log("coucou");
                $(this).closest(".imageGrand").css("display", "block");
                $(".fondGrandeImage").show();
            }

          });
    });


    $('.imageGrand').click(function(){
        $('.imageGrand').hide();
        $(".fondGrandeImage").hide();
    });
    $("#tous").click(function(){
        $(".categorie2").show();
        $(".categorie3").show();
        $(".categorie1").show();
    });
    $("#btncategorie1").click(function(){
        $(".categorie2").hide();
        $(".categorie3").hide();
        $(".categorie1").show();
    });
    $("#btncategorie2").click(function(){
        $(".categorie1").hide();
        $(".categorie3").hide();
        $(".categorie2").show();
    });
    $("#btncategorie3").click(function(){
        $(".categorie2").hide();
        $(".categorie1").hide();
        $(".categorie3").show();
    });
  });

