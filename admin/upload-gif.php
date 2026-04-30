<?php
session_start();
if(!isset($_SESSION['user'])){
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Non autorisé.']);
    exit();
}

header('Content-Type: application/json');

require_once "../config.php";
require_once "imgClass.php";

$dos = "../".$dirImages;
$largeurMiniature = 200;
$largeurImageTaquin = 400;

if(!isset($_POST['gif']) || empty($_POST['gif'])){
    echo json_encode(['success' => false, 'error' => 'Aucune donnée reçue.']);
    exit();
}

$data = $_POST['gif'];

// Retirer l'entête base64
$data = str_replace('data:image/gif;base64,', '', $data);
$data = str_replace(' ', '+', $data);
$fileData = base64_decode($data);

if($fileData === false){
    echo json_encode(['success' => false, 'error' => 'Décodage base64 échoué.']);
    exit();
}

// Nom unique
$fileName = 'gif_' . date('Ymd_His') . '.gif';
$filePath = $dos . $fileName;

// Sauvegarde image pleine taille
if(!file_put_contents($filePath, $fileData)){
    echo json_encode(['success' => false, 'error' => 'Impossible d\'écrire le fichier.']);
    exit();
}

// Calcul ratio pour la miniature
$dimensions = getimagesize($filePath);
if(!$dimensions){
    echo json_encode(['success' => false, 'error' => 'Fichier image invalide.']);
    exit();
}
$ratio = $dimensions[0] / $dimensions[1];
$hauteurMiniature = $largeurMiniature / $ratio;

// Miniature GIF (première frame, extension .gif préservée)
Img::creerMinGif($filePath, $dos."min", $fileName, $largeurMiniature, $hauteurMiniature);

// Le GIF animé est conservé tel quel dans images/ — pas de repassage par imgClass

echo json_encode(['success' => true, 'fileName' => $fileName]);
