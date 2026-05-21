<?php 
require_once "config.php";
$jsonImageTaquin=json_decode(file_get_contents("js/image-taquin.json"));

// Image : GET prime sur le json, avec validation stricte
if(isset($_GET["image"])){
    $imageCandidate = basename($_GET["image"]); // basename() empêche toute traversée de répertoire
    // On vérifie que le fichier existe réellement dans le dossier images
    if(file_exists($dirImages.$imageCandidate)){
        $nomImage = $imageCandidate;
    } else {
        $nomImage = $jsonImageTaquin->image_taquin; // fallback si fichier inconnu
    }
} else {
    $nomImage = $jsonImageTaquin->image_taquin;
}

$urlImage=$dirImages.$nomImage;
if (isset($_GET["nbrPieces"])){
    $nbrPiecesPerLine=sqrt($_GET["nbrPieces"]);
}else{
    $nbrPiecesPerLine=$jsonImageTaquin->nbrPiecesPerLine;
}
$largeurPiece=round($sizeTaquin/$nbrPiecesPerLine);
?>
<!DOCTYPE html>
<html lang="fr">
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
    $dimensionsImage = @getimagesize($urlImage);
    $largeurImage  = ($dimensionsImage && $dimensionsImage[0] > 0) ? $dimensionsImage[0] : 400;
    $hauteurImage  = ($dimensionsImage && $dimensionsImage[1] > 0) ? $dimensionsImage[1] : 300;
    $ratioImage    = $hauteurImage / $largeurImage;
    ?>
    <style>:root{
        --image-taquin: url('<?php echo "../".$urlImage ?>');
        --ratioImage: <?php echo $ratioImage ?>;
        --nbrPiecesPerLine: <?php echo $nbrPiecesPerLine ?>
    }</style>
    <script>
        const nomImage = "<?php echo addslashes($nomImage); ?>";
        const nbrPiecesTotal = <?php echo (int)($nbrPiecesPerLine * $nbrPiecesPerLine); ?>;
    </script>
</head>
<body>
    <div class="c1">

        <header>
            <h1><?php echo $titrePage ?></h1>
            <a href="admin/admin.php" class="admin-link" target="_self">
                <svg viewBox="0 0 16 16">
                    <circle cx="8" cy="8" r="2.5"/>
                    <path d="M8 1v2M8 13v2M1 8h2M13 8h2M3.05 3.05l1.41 1.41M11.54 11.54l1.41 1.41M3.05 12.95l1.41-1.41M11.54 4.46l1.41-1.41"/>
                </svg>
                Admin
            </a>
        </header>

        <section id="scene">
            <!--Le taquin est généré par JavaScript-->
        </section>

        <footer>
            <div class="controls">
                <a class="ctrl-btn" href="images/<?php echo $nomImage; ?>" rel="zoombox[galerie]" title="Voir l'image">
                    <svg viewBox="0 0 16 16">
                        <circle cx="8" cy="8" r="6"/>
                        <line x1="8" y1="5" x2="8" y2="8"/>
                        <circle cx="8" cy="10.5" r="0.75" fill="<?php echo 'rgb(190,119,119)' ?>" stroke="none"/>
                    </svg>
                </a>
                <button class="ctrl-btn" id="numeroButton" onclick="displayPiecesNumber();" title="Afficher les numéros">1</button>
                <button class="ctrl-btn" id="autoButton" onclick="toggleAuto();" title="Mode automatique">Auto</button>
                <button class="ctrl-btn" id="shareButton" onclick="shareGame();" title="Partager ce taquin" style="display:none">
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="3" r="1.5"/><circle cx="12" cy="13" r="1.5"/><circle cx="3" cy="8" r="1.5"/>
                        <line x1="10.5" y1="3.75" x2="4.5" y2="7.25"/><line x1="10.5" y1="12.25" x2="4.5" y2="8.75"/>
                    </svg>
                </button>

                <div class="ctrl-spacer"></div>
                <div class="ctrl-separator"></div>

                <form action="" method="get" id="nbrPieces">
                    <div class="select-wrap">
                        <select name="nbrPieces" onchange="this.form.submit()">
                            <?php
                            $nbrPiecesTaquin = $nbrPiecesPerLine * $nbrPiecesPerLine;
                            $optionsNbrPieces = [9, 16, 25];
                            foreach($optionsNbrPieces as $option){
                                $selected = ($option == $nbrPiecesTaquin) ? 'selected' : '';
                                echo "<option value=\"$option\" $selected>$option</option>";
                            }
                            ?>
                        </select>
                    </div>
                </form>
            </div>
        </footer>

    </div>
    <script type="text/javascript" src="js/taquin.js"></script>
</body>
</html>
