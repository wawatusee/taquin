<?php
session_start();
if(!isset($_SESSION['user'])){
    header("location: login.php"); exit();
}

$url = "../images/";
$deleted = false;
$fileToDelete = "";

if(isset($_POST["fileToDelete"])){
    $fileToDelete = basename($_POST["fileToDelete"]); // basename() empêche toute traversée de répertoire
    if(isset($_POST["confirmed"])){
        // Suppression confirmée
        if(file_exists($url.$fileToDelete)) unlink($url.$fileToDelete);
        if(file_exists($url."min/".$fileToDelete)) unlink($url."min/".$fileToDelete);
        $deleted = true;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer — Taquin</title>
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
        .page {
            max-width: 480px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 1.25rem;
            border-bottom: 0.5px solid var(--border);
            margin-bottom: 2.5rem;
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
        }
        h2 {
            font-size: 0.7rem;
            font-weight: normal;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 1.25rem;
        }
        .preview {
            border: 0.5px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            background: white;
            margin-bottom: 1.5rem;
            max-width: 200px;
        }
        .preview img {
            width: 100%;
            display: block;
        }
        .preview-name {
            padding: 8px 10px;
            font-size: 12px;
            color: var(--text-muted);
            border-top: 0.5px solid var(--border);
        }
        .actions {
            display: flex;
            gap: 10px;
            margin-top: 1rem;
        }
        .btn-cancel {
            padding: 9px 20px;
            font-size: 13px;
            border: 0.5px solid var(--border);
            border-radius: var(--radius);
            background: transparent;
            color: var(--text-muted);
            text-decoration: none;
            cursor: pointer;
            transition: background 0.15s;
        }
        .btn-cancel:hover { background: var(--rose-light); }
        .btn-confirm {
            padding: 9px 20px;
            font-size: 13px;
            border: none;
            border-radius: var(--radius);
            background: #c0392b;
            color: white;
            cursor: pointer;
            transition: opacity 0.15s;
        }
        .btn-confirm:hover { opacity: 0.85; }
        .message {
            padding: 10px 16px;
            border-radius: var(--radius);
            font-size: 13px;
            background: var(--rose-light);
            color: var(--rose);
            border: 0.5px solid var(--rose-muted);
        }
    </style>
</head>
<body>
<div class="page">
    <header>
        <a href="admin.php" class="back-link">← Admin</a>
        <h1>Supprimer</h1>
    </header>

    <?php if($deleted): ?>
        <div class="message">«&nbsp;<?php echo htmlspecialchars($fileToDelete); ?>&nbsp;» a été supprimé.</div>
        <div style="margin-top:1.5rem">
            <a href="admin.php" class="btn-cancel">Retour à la galerie</a>
        </div>

    <?php elseif($fileToDelete): ?>
        <h2>Confirmer la suppression</h2>
        <div class="preview">
            <img src="<?php echo $url.'min/'.htmlspecialchars($fileToDelete); ?>" alt=""/>
            <div class="preview-name"><?php echo htmlspecialchars($fileToDelete); ?></div>
        </div>
        <form method="post" action="">
            <input type="hidden" name="fileToDelete" value="<?php echo htmlspecialchars($fileToDelete); ?>"/>
            <input type="hidden" name="confirmed" value="1"/>
            <div class="actions">
                <a href="admin.php" class="btn-cancel">Annuler</a>
                <button type="submit" class="btn-confirm">Supprimer définitivement</button>
            </div>
        </form>

    <?php else: ?>
        <div class="message">Aucun fichier spécifié.</div>
        <div style="margin-top:1.5rem">
            <a href="admin.php" class="btn-cancel">Retour à la galerie</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
