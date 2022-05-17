<?php 
//Rapatrier les infos nécessaires, ex: $dirImages
require_once "config.php";
//Import du fichier json source
$jsonImageTaquin=json_decode(file_get_contents("js/image-taquin.json"));
//Récupération du nom de l'image source
$nomImage=$jsonImageTaquin->image_taquin;
$urlImage=$dirImages.$nomImage;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--<script type="text/javascript" src="js/init.js"></script>-->
    <link rel="stylesheet" href="css/taquin.css">
    <title>Taquin</title>
    <style>:root{--image-taquin:url('<?php echo $urlImage ?>');}</style>
</head>
<body onload="taquin()">
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
            <div>Plan de site</div>
            <section id="planDeSite">
                <nav><!--MENU--></nav>
            </section>
        </footer>
    </div>
    <script type="text/javascript" src="js/taquin.js"></script>
</body>
</html>