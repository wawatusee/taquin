<?php 
//Import du fichier json source
$jsonImageTaquin=json_decode(file_get_contents("js/image-taquin.json"));
//Récupération du nom de l'image source
$nomImage=$jsonImageTaquin->image_taquin;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/essai-taille-dynamique.css">
    <title>Taquin</title>
</head>

<body onload="taquin()" ;>
    <div class="c1">
        <header>
            <h1>Raymond Taquin</h1>
        </header>
        <section id="scene">
            <div class="taquin">
                <div class="piece"></div>
                <div class="piece"></div>
                <div class="piece"></div>             
                <div class="piece"></div>             
                <div class="piece"></div>             
                <div class="piece"></div>             
                <div class="piece"></div>             
                <div class="piece"></div>             
                <div class="piece"></div>             
                <div class="piece"></div>             
                <div class="piece"></div>             
                <div class="piece"></div>             
                <div class="piece"></div>             
                <div class="piece"></div>             
                <div class="piece"></div>             
                <div id="pieceInvisible" class="piece"></div>
            </div>
        </section>
        <footer>
            <div>Image du jour</div>
                <section id="planDeSite">
                <?php echo $nomImage ?>
            </section>
        </footer>
    </div>
    <script type="text/javascript" src="js/taquin.js"></script>
</body>
</html>