<?php 
//Rapatrier les infos nécessaires, ex: $dirImages,$sizeTaquin
require_once "config.php";
//Import du fichier json source
$jsonImageTaquin=json_decode(file_get_contents("js/image-taquin.json"));
//Récupération du nom de l'image source
$nomImage=$jsonImageTaquin->image_taquin;
$urlImage=$dirImages.$nomImage;
//Si GET contient le nombre de pieces, c'est cette valeur qui prime, sinon on se fie au fichier json
if (isset($_GET["nbrPieces"])){
    $nbrPiecesPerLine=sqrt($_GET["nbrPieces"]);
}else{
    $nbrPiecesPerLine=$jsonImageTaquin->nbrPiecesPerLine;
}


//Division de la taille du taquin par le nombre de pièces dans une ligne
$largeurPiece=round($sizeTaquin/$nbrPiecesPerLine);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="zoombox/jquery.js"></script>
    <script type="text/javascript" src="zoombox/zoombox.js"></script> 
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/taquin.css">
    <link href="zoombox/zoombox.css" rel="stylesheet" type="text/css" media="screen" />
    <title>Taquin</title>
    <?php
    //Valeurs pour ratio image dans la balise style
    //Ratio est utilisé pour le positionnement de l'image de fond de chaque pièce et pour la taille du taquin
    $dimensionsImage=getimagesize($urlImage);
            $largeurImage=$dimensionsImage[0];
            $hauteurImage=$dimensionsImage[1];
            $ratioImage=$hauteurImage/$largeurImage;
    ?>
    <!--Déclaration de la variable qui sera employée pour l'arrière plan de chaque pièce. Attention au slash ajouté avant le nom du dossier-->
   <!--Utilisé pour le style de class piece dans taquin.css pour le background--> 
   <style>:root{--image-taquin:url('<?php echo "../".$urlImage ?>');
                --totalLargeur:<?php echo $sizeTaquin.'px'?>;
                --ratioImage:<?php echo $ratioImage ?>;
                --largeurPiece:<?php echo $largeurPiece ?>;
                --nbrPiecesPerLine:<?php echo $nbrPiecesPerLine?>
            }
    </style>
</head>
<body>
    <div class="c1">
        <header>
            <h1><?php echo $titrePage ?><a href="admin/admin.php" target="_self" rel="noopener noreferrer"><img src="css/images/engrenages.png" alt="Gestion taquin"></a></h1>
        </header>
        <section id="scene">
            <!--Don't panic the Taquin will be created with Java Script-->
        </section>
        <footer>
            <section id="planDeSite">
                <div class="help">
                     <fieldset>
                        <legend>Help</legend>
                        <button><a href="images/<?php echo $nomImage; ?>" rel="zoombox[galerie]">?</a></button>
                        <button id="numeroButton"><a  href="#" onclick=displayPiecesNumber();>1</a></button>
                        <!--Creation of a selector of number of pieces-->
                        <form action="" method="get" id="nbrPieces">
                            <select form="nbrPieces" name="nbrPieces" onchange="this.form.submit()">
                                <?php $nbrPiecesTaquin=$nbrPiecesPerLine*$nbrPiecesPerLine;
                                $optionsNbrPieces=[9,16,25];
                                ?>
                                <?php foreach($optionsNbrPieces as $option){
                                    if($option==$nbrPiecesTaquin){
                                    echo "<option selected value=".$option.">".$option."</option>";    
                                    }else echo "<option value=".$option.">".$option."</option>";
                                }?>
                            </select>
                        </form>
                     </fieldset>
                </div>
            </section>
        </footer>
    </div>
    <script type="text/javascript" src="js/taquin.js"></script>
</body>
</html>