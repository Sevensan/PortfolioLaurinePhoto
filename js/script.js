$( document ).ready(function() {

    $(".imageSlider").hide();
    const images = [];
        for(i = 1;i<7;i++)
        {
            if($("#imageSlider" + i).attr("src") == undefined)
            {
                i++;
            }
            else
            {
                images.push($("#imageSlider" + i).attr("src"));
            }
        }

        // let num_photo_haut = -1;
        // let num_photo_bas = 0;

        // function changephoto(sens)
        // {
        //     num_photo_haut = num_photo_haut + sens;
        //     if (num_photo_haut < 0)
        //         num_photo_haut = images.length;
        //     if (num_photo_haut > images.length)
        //         num_photo_haut = 0;
        //     num_photo_bas = num_photo_bas + sens;
        //     if (num_photo_bas < 0)
        //         num_photo_bas = images.length;
        //     if (num_photo_bas > images.length)
        //         num_photo_bas = 0;
        //     if ($("#slide_haut").css("z-index") == "2")
        //     {
        //         $("#slide_bas").attr("src", images[num_photo_bas]);
        //         $("#slide_haut").fadeOut(500, function () {
        //             $("#slide_haut").attr("src", images[num_photo_haut]);
        //             $("#slide_haut").css("z-index", "1");
        //             $("#slide_haut").fadeIn(100);
        //             $("#slide_bas").css("z-index", "2");
        //         });
        //     } // Si le slide_bas est au dessus
        // else {
        //     $("#slide_haut").attr("src", images[num_photo_bas]);
        //     $("#slide_bas").fadeOut(500, function () {
        //         $("#slide_bas").attr("src", images[num_photo_haut]);
        //         $("#slide_bas").css("z-index", "1");
        //         $("#slide_bas").fadeIn(100);
        //         $("#slide_haut").css("z-index", "2");
        //     });
        //     }
        // }
    
    console.log(images);
    console.log(images.length);
    //     function afficher()
    //     {
    //         for(i = 0; i < images.length; i++)
    //         {
    //             if(i = images.length)
    //             {
    //                 i = 0;
    //             }
    //             $(".slider img").attr("src", images[i])
    //         }
    //     }
    // setInterval(changephoto(1), 500);
    i = 1;
    function slider()
    {
        $("#slide_haut img").attr("src", images[i]);
        if(i < images.length)
        {
            i++
        }
        // else
        // {
        //     i = 0;
        // }
        
    }
    setInterval(slider(), 2000);

  });

