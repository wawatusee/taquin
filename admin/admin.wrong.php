<?php 
session_start();
if(!isset($_SESSION['user'])){
    header("location: login.php"); exit();
}
if(isset($_GET['logout'])){
    unset($_SESSION['user']);
    header("location: login.php"); exit();
}

require "../config.php";
$dos = "../".$dirImages;
$dir = opendir($dos);
$largeurMiniature = 200;
$largeurImageTaquin = 400;

$jsonImageTaquin = json_decode(file_get_contents("../js/image-taquin.json"));
$selectedImage = isset($jsonImageTaquin) ? $jsonImageTaquin->image_taquin : "default-taquin-image.jpg";
$nameSelected = substr($selectedImage, 0, -4);

$message = "";
$messageType = "";

// Traitement upload
$uploadErrors = [
    1 => "Le fichier dépasse la limite autorisée par le serveur (upload_max_filesize).",
    2 => "Le fichier est trop volumineux.",
    3 => "Le fichier n'a été que partiellement envoyé.",
    4 => "Aucun fichier sélectionné.",
    6 => "Dossier temporaire manquant sur le serveur.",
    7 => "Échec de l'écriture sur le disque.",
];
if(!empty($_FILES) && isset($_FILES['img'])){
    $errorCode = $_FILES['img']['error'];
    if($errorCode !== 0 && $errorCode !== 4){
        $message = isset($uploadErrors[$errorCode]) ? $uploadErrors[$errorCode] : "Erreur d'upload inconnue (code $errorCode).";
        $messageType = "error";
    }
}
if(!empty($_FILES) && isset($_FILES['img']) && $_FILES['img']['error'] === 0){
    require_once("imgClass.php");
    $img = $_FILES['img'];
    $nomDuFichier = $img["name"];
    $parts = explode(".", $nomDuFichier);
    $incoming_format = strtolower(end($parts));
    $allowed_format = array("jpeg","jpg","png","gif");

    if(in_array($incoming_format, $allowed_format)){
        $dimensionImage = getimagesize($img['tmp_name']);
        $ratio = $dimensionImage[0] / $dimensionImage[1];
        $hauteurMiniature = $largeurMiniature / $ratio;
        $hauteurImageTaquin = $largeurImageTaquin / $ratio;
        move_uploaded_file($img['tmp_name'], $dos."/".$img['name']);
        Img::creerMin($dos.$img['name'], $dos."/min", $img['name'], $largeurMiniature, $hauteurMiniature);
        Img::creerMin($dos.$img['name'], $dos."/", $img['name'], $largeurImageTaquin, $hauteurImageTaquin);
        $message = "Image «&nbsp;".$img['name']."&nbsp;» ajoutée.";
        $messageType = "success";
    } else {
        $message = "Format non accepté. Utilisez jpg, png ou gif.";
        $messageType = "error";
    }
}

// Traitement sélection image active
if(isset($_POST['defaultTaquin'])){
    $newImage = basename($_POST['defaultTaquin']);
    $jsonImageTaquin->image_taquin = $newImage;
    file_put_contents("../js/image-taquin.json", json_encode($jsonImageTaquin));
    $selectedImage = $newImage;
    $nameSelected = substr($selectedImage, 0, -4);
    $message = "Image active mise à jour.";
    $messageType = "success";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../zoombox/jquery.js"></script>
    <script src="../zoombox/zoombox.js"></script>
    <link rel="stylesheet" href="../zoombox/zoombox.css" media="screen"/>
    <title>Admin — Taquin</title>
    <style>
        :root {
            --rose: rgb(190, 119, 119);
            --rose-muted: rgb(212, 160, 160);
            --rose-light: rgb(245, 234, 234);
            --rose-bg: rgb(250, 244, 244);
            --text: rgb(80, 55, 55);
            --text-muted: rgb(160, 130, 130);
            --border: rgba(190, 119, 119, 0.25);
            --radius: 8px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Georgia', serif;
            background-color: var(--rose-bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* LAYOUT */
        .page {
            max-width: 860px;
            margin: 0 auto;
            padding: 2rem 1.5rem 4rem;
        }

        /* HEADER */
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 1.25rem;
            border-bottom: 0.5px solid var(--border);
            margin-bottom: 2.5rem;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .back-link {
            font-size: 12px;
            color: var(--text-muted);
            text-decoration: none;
            border: 0.5px solid var(--border);
            border-radius: 6px;
            padding: 5px 12px;
            transition: background 0.15s;
        }
        .back-link:hover { background: var(--rose-light); }
        header h1 {
            font-size: 1.1rem;
            font-weight: normal;
            color: var(--rose);
            letter-spacing: 0.02em;
        }
        .logout-link {
            font-size: 12px;
            color: var(--text-muted);
            text-decoration: none;
            border: 0.5px solid var(--border);
            border-radius: 6px;
            padding: 5px 12px;
            transition: background 0.15s;
        }
        .logout-link:hover { background: var(--rose-light); }

        /* SECTIONS */
        section {
            margin-bottom: 2.5rem;
        }
        h2 {
            font-size: 0.7rem;
            font-weight: normal;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }

        /* MESSAGE */
        .message {
            padding: 10px 16px;
            border-radius: var(--radius);
            font-size: 13px;
            margin-bottom: 1.5rem;
        }
        .message.success { background: var(--rose-light); color: var(--rose); border: 0.5px solid var(--rose-muted); }
        .message.error { background: #fdecea; color: #c0392b; border: 0.5px solid #e8b4b0; }

        /* UPLOAD */
        .upload-zone {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .file-input-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border: 0.5px solid var(--border);
            border-radius: var(--radius);
            font-size: 13px;
            color: var(--text-muted);
            cursor: pointer;
            background: white;
            transition: background 0.15s;
        }
        .file-input-label:hover { background: var(--rose-light); }
        .file-input-label input[type="file"] { display: none; }
        .file-name {
            font-size: 12px;
            color: var(--text-muted);
            font-style: italic;
        }
        .btn-upload {
            padding: 8px 20px;
            background: var(--rose);
            color: white;
            border: none;
            border-radius: var(--radius);
            font-size: 13px;
            cursor: pointer;
            transition: opacity 0.15s;
        }
        .btn-upload:hover { opacity: 0.85; }

        /* GALLERY */
        .gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }
        .thumb {
            position: relative;
            width: 140px;
            border: 0.5px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            background: white;
            transition: box-shadow 0.15s;
        }
        .thumb:hover { box-shadow: 0 4px 16px rgba(190,119,119,0.15); }
        .thumb.active {
            border-color: var(--rose);
            box-shadow: 0 0 0 2px var(--rose-muted);
        }
        .thumb img {
            width: 100%;
            height: 100px;
            object-fit: cover;
            display: block;
        }
        .thumb-info {
            padding: 6px 8px;
            font-size: 11px;
            color: var(--text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            border-top: 0.5px solid var(--border);
        }
        .thumb-actions {
            display: flex;
            gap: 6px;
            padding: 6px 8px;
        }
        .btn-select {
            flex: 1;
            padding: 5px 0;
            font-size: 11px;
            border: 0.5px solid var(--rose-muted);
            border-radius: 5px;
            background: transparent;
            color: var(--rose);
            cursor: pointer;
            transition: background 0.15s;
        }
        .btn-select:hover { background: var(--rose-light); }
        .btn-select.selected {
            background: var(--rose);
            color: white;
            border-color: var(--rose);
            pointer-events: none;
        }
        .btn-delete {
            padding: 5px 8px;
            font-size: 11px;
            border: 0.5px solid rgba(192,57,43,0.3);
            border-radius: 5px;
            background: transparent;
            color: #c0392b;
            cursor: pointer;
            transition: background 0.15s;
        }
        .btn-delete:hover { background: #fdecea; }
        .zoom-link { display: block; }
    </style>
</head>
<body>
<div class="page">

    <header>
        <div class="header-left">
            <a href="../index.php" class="back-link">← Taquin</a>
            <h1>Administration</h1>
        </div>
        <a href="?logout" class="logout-link">Déconnexion</a>
    </header>

    <?php if($message): ?>
    <div class="message <?php echo $messageType; ?>"><?php echo $message; ?></div>
    <?php endif; ?>

    <section>
        <h2>Ajouter une image</h2>
        <form method="post" action="" enctype="multipart/form-data">
            <div class="upload-zone">
                <label class="file-input-label">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M8 2v9M4 6l4-4 4 4"/><path d="M2 13h12"/>
                    </svg>
                    Choisir un fichier
                    <input type="file" name="img" id="fileInput" onchange="updateFileName(this)"/>
                </label>
                <span class="file-name" id="fileName">jpg, png, gif</span>
                <button type="submit" class="btn-upload">Envoyer</button>
            </div>
        </form>
    </section>

    <section>
        <h2>Galerie</h2>
        <div class="gallery">
        <?php
        while($file = readdir($dir)){
            $allowed_format = array("peg","jpg","gif","png");
            $incoming_format = strtolower(substr($file, -3));
            if(!in_array($incoming_format, $allowed_format)) continue;
            $imageName = substr($file, 0, -4);
            $isActive = ($imageName === $nameSelected);
        ?>
            <div class="thumb <?php echo $isActive ? 'active' : ''; ?>">
                <a class="zoom-link" href="<?php echo $dos.$file; ?>" rel="zoombox[galerie]">
                    <img src="<?php echo $dos.'min/'.$imageName.'.jpg'; ?>" alt="<?php echo $imageName; ?>"/>
                </a>
                <div class="thumb-info"><?php echo $imageName; ?></div>
                <div class="thumb-actions">
                    <form method="post" action="" style="flex:1">
                        <input type="hidden" name="defaultTaquin" value="<?php echo $file; ?>"/>
                        <button type="submit" class="btn-select <?php echo $isActive ? 'selected' : ''; ?>">
                            <?php echo $isActive ? '✓ Active' : 'Choisir'; ?>
                        </button>
                    </form>
                    <?php if(!$isActive && $imageName !== 'default-taquin-image'): ?>
                    <form method="post" action="delete.php">
                        <input type="hidden" name="fileToDelete" value="<?php echo $file; ?>"/>
                        <button type="submit" class="btn-delete">✕</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php } ?>
        </div>
    </section>

</div>
<script>
function updateFileName(input){
    document.getElementById('fileName').textContent = input.files[0] ? input.files[0].name : 'jpg, png, gif';
}
</script>
</body>
</html>
