<?php 
//Rapatrier les infos nécessaires, ex: $dirImages
require_once "config.php";
//Import du fichier json source
$jsonImageTaquin=json_decode(file_get_contents("js/image-taquin.json"));
//Récupération du nom de l'image source
$nomImage=$jsonImageTaquin->image_taquin;
$urlImage=$dirImages.$nomImage;
header("refresh:4,index.php")
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/imageDuJour.css">
    <title>Taquin</title>
    <!--Page for show the image of the day on FaceBook via a link-->
</head>
<body>
    <header>
        <h1>Image du jour</h1>
    </header>
    <main>
        <section class="imageDuJour">
           <img src="<?php echo $urlImage?>" alt="Image du jour">
        </section>
    </main>
</body>
</html>