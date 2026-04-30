<?php
$url="../images/";
$supprOk=false;
if(isset($_POST["chooseToDelete"])){
    echo "Fichier à détruire :";
    $fileToDelete=$_POST["chooseToDelete"];
    var_dump($_POST["chooseToDelete"]);
}elseif(isset($_POST["fileToDelete"])){
    $fileToDelete=$_POST["fileToDelete"];
    destroyImages($fileToDelete, $url);
    $supprOk=true;
}
function destroyImages($fileName,$repertory){
    unlink($repertory."min/".$fileName);
    unlink($repertory.$fileName);
    $supprOk=true;
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/screen.css" />
    <title>Destruction fichier</title>
</head>
<body>
<header>
    <h1><a href="../index.php" target="_self" rel="noopener noreferrer">Taquin</a></h1>
    </header>
    <article>
        <h2>Administration-Supprimer fichier</h2>
        <?php if(!$supprOk): ?>
        <section class="gallery">
            <figure class="min">
                <img src="<?php echo $url;?>min/<?php echo $fileToDelete; ?>" title="Image à supprimer"/>
                <figcaption>
                    <div class="titleFigcaption">Fichier à supprimer :</div>
                    <div></div><?php echo $fileToDelete; ?>
                    </div>
                </figcaption>
            </figure>
        </section>
    <!--Formulaire de validation de nouvelle image-->
        <form action="" method="POST">
                <fieldset>
                <legend>Voulez vous vraiment supprimer?</legend>
                <textarea name="fileToDelete" id="" ><?php echo $fileToDelete ?></textarea>
                <button type="submit">Détruire</button>
            </fieldset>
        </form>
        <?php endif;?>
    </article>
</body>
</html>

