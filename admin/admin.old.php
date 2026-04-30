
<?php 
session_start();
if(!isset($_SESSION['user'])){
    header("location: login.php");	exit();
}

if(isset($_GET['logout'])){
    unset($_SESSION['user']);
    header("location: login.php");	exit();
}
?>
<?php
//Prendre dans config.php le chemin des miniatures
require "../config.php";
$dos="../".$dirImages;
$dir=opendir($dos."/min/");//Ouvrir le répertoire des miniatures
$largeurMiniature=200;
$largeurImageTaquin=400;
?>
<?php 
//Import du fichier json source
$jsonImageTaquin=json_decode(file_get_contents("../js/image-taquin.json"));
//Récupération du nom de l'image source
if(!isset($jsonImageTaquin)){
    $selectedImage="default-taquin-image.jpg";
}else {
    $selectedImage=$jsonImageTaquin->image_taquin;
}
?>
<?php
//Traitement upload image,création thumb et image plein format 
$nameSelected=substr($selectedImage,0,-4);
    if(!empty($_FILES)){
        require_once("imgClass.php");//J'importe la class image
        print_r($_FILES['img']);
        $img=$_FILES['img'];//stock the image in $img
        //Quest for the extension of the file
        echo "Extension de fichier entrant:";
        $nomDuFichier=$img["name"];
        $incoming_format=getExtension($nomDuFichier);
       //end of Quest for the extension of the file
        //$incoming_format=strtolower(substr($img['name'], -3));//transforme en minuscule l'extension des fichiers récupérée grace à la méthode substr
        $allowed_format=array("jpeg","jpg","png","gif");//Lister dans un tableau les formats d'images acceptés
        echo "'extension du fichier entrant '.$incoming_format".'<br>';
        if(in_array($incoming_format,$allowed_format)){//Si le format de l'image fait partie des formats tolérés 
            echo "Format accepté";
         //Récupérer le tableau de tailles de l'image
        $dimensionImage=getimagesize($img['tmp_name']);
        $ratio=$dimensionImage[0]/$dimensionImage[1];
        $hauteurMiniature=$largeurMiniature/$ratio;
        $hauteurImageTaquin=$largeurImageTaquin/$ratio;
        echo "Largeur: ".$dimensionImage[0].",Hauteur image :".$dimensionImage[1].",Ratio image : ".$ratio;
 //Création de la miniature et de l'image au format taquin avec les méthodes de la classe imgClass
        move_uploaded_file($img['tmp_name'],$dos."/".$img['name']);//Bouger l'image dans le répertoire prévu avec son nom initial
        Img::creerMin($dos.$img['name'],$dos."/"."/min",$img['name'],$largeurMiniature,$hauteurMiniature);//Avec une méthode de la classe image de Grafikart créer une miniature stoquée dans le répertoire désiré 
        Img::creerMin($dos.$img['name'],$dos."/",$img['name'],$largeurImageTaquin,$hauteurImageTaquin);//Avec une méthode de la classe image de Grafikart créer une l'image du taquin stoquée dans le répertoire désiré 
        }
        else {
            //TESTS
            echo "Ce fichier n'est pas au format accepté.";//Sinon on prévient le client que le format de l'image n'était pas bon
        }
    }
?>
<?php
function getExtension( $fileTested){
    $arrayFromtheFileExplode=explode(".",$fileTested);
    $extensionFile=end($arrayFromtheFileExplode);
   return $extensionFile;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script type="text/javascript" src="../zoombox/jquery.js"></script>
    <script type="text/javascript" src="../zoombox/zoombox.js"></script> 
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud images-selection unique</title>
    <link rel="stylesheet" type="text/css" media="screen" href="../css/screen.css" />
    <link href="../zoombox/zoombox.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
    <header>
        <h1><a href="../index.php" target="_self" rel="noopener noreferrer">Taquin</a> But before :<a href="?logout">Log out</a>	</h1>
    </header>
    <article>
        <h2>Images taquin-Réglages</h2>
        <section>
            <?php
            if(isset($erreur)){
                echo $erreur;
            }
            ?>
<!--Upload Form-->
            <form method="post" action="" enctype ="multipart/form-data">
                <fieldset>
                    <legend>Nouvelle image</legend>
                    <input class="addFile" type="file" name="img"/>
                    <input class="submit" type="submit" name="Upload">
                </fieldset>
            </form>
<!--Upload Form_end-->
            </section>
            <section >
<!--GALLERY with radio and delete Buttons Forms for each-->
                <p>Choisir/supprimer :</p>
                <div class="gallery">
                <?php 
                while($file=readdir($dir)){//Pour chacune des images du dossier
                    $imageName=substr($file,0,-4);
                    $allowed_format=array("peg","jpg","gif","png");//Définir les formats d'images acceptés
                    $incoming_format=strtolower(substr($file,-3));//Convertir l'extension de l'image en minuscules
                    if(in_array($incoming_format,$allowed_format)){//Si les formats du fichier sont acceptés
                    ?>
    <!--THUMBAIL-->
                    <figure class="min">
        <!--OPEN zoombox on click thumbail -->
                        <a href="<?php echo $dos.$file; ?>" rel="zoombox[galerie]">
                            <img src="<?php echo $dos."min/".$file; ?>"/>
                        </a>
        <!--Image vignette--->                  
                        <div class="titleFigcaption">
                            <label for="<?php  echo $imageName ?>"><?php  echo $imageName ?></label>
                        </div>
                        <div class="actionButtons">
            <!--Bouton de selection d'image-->        
                                <form action="" method="GET">
                                    <input type="radio" id="<?php  echo $imageName?>" class="selectaButton" name="defaultTaquin" value="<?php  echo $file ?>"<?php echo $imageName===$nameSelected? "checked>":">"?>
                                </form>
            <!--DELETE thumbail button-->
                    <?php if($imageName!=$nameSelected&&$imageName!="default-taquin-image"): ?>
                            <form action="delete.php" method="post">
                                <button type="submit" name="chooseToDelete" value="<?php  echo $file ?>"><img src="../css/images/deleteButton.png" alt=""></button>
                            </form>
                    <?php endif;?>
                        </div>
                    </figure>
                    <?php
                        }
                    }
                    ?>
                </div>
<!--GALLERY with Button radio Form for each_end-->
            </section>
    </article>
    <script type="text/javascript" src="../js/gallery.js"></script>
</body>
</html>