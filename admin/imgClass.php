<?php

class Img{
    static function creerMin($img,$chemin,$nom,$mlargeur=100,$mhauteur=100){
        // On supprime l'extension du nom
        $nom = substr($nom,0,-4);
        // On récupère les dimensions de l'image
        $dimension=getimagesize($img);
        // On crée une image à partir du fichier récupéré
        if(substr(strtolower($img),-4)==".jpg"){$image = imagecreatefromjpeg($img); }
        else if(substr(strtolower($img),-4)==".png"){$image = imagecreatefrompng($img); }
        else if(substr(strtolower($img),-4)==".gif"){$image = imagecreatefromgif($img); }
        else if ( substr(strtolower($img),-5==".jpeg")) {$image = imagecreatefromjpeg($img);}
        // L'image ne peut etre redimensionne
        else{return false; }
        // Création des miniatures
        // On cré une image vide de la largeur et hauteur voulue
        $miniature =imagecreatetruecolor ($mlargeur,$mhauteur); 
        // On va gérer la position et le redimensionnement de la grande image
        if($dimension[0]>($mlargeur/$mhauteur)*$dimension[1] ){ $dimY=$mhauteur; $dimX=$mhauteur*$dimension[0]/$dimension[1]; $decalX=-($dimX-$mlargeur)/2; $decalY=0;}
        if($dimension[0]<($mlargeur/$mhauteur)*$dimension[1]){ $dimX=$mlargeur; $dimY=$mlargeur*$dimension[1]/$dimension[0]; $decalY=-($dimY-$mhauteur)/2; $decalX=0;}
        if($dimension[0]==($mlargeur/$mhauteur)*$dimension[1]){ $dimX=$mlargeur; $dimY=$mhauteur; $decalX=0; $decalY=0;}
        // on modifie l'image crée en y plaçant la grande image redimensionné et décalée
        imagecopyresampled($miniature,$image,$decalX,$decalY,0,0,$dimX,$dimY,$dimension[0],$dimension[1]);
        // On sauvegarde le tout
        imagejpeg($miniature,$chemin."/".$nom.".jpg",90);
        return true;
    }

    // Crée une miniature GIF à partir d'un GIF animé
    // Sauvegarde en .gif dans le dossier min/ — le nom et l'extension sont préservés
    static function creerMinGif($img, $chemin, $nom, $mlargeur=200, $mhauteur=150){
        // Vérifier que le fichier est bien un GIF
        $dimension = getimagesize($img);
        if(!$dimension || $dimension[2] !== IMAGETYPE_GIF){
            return false;
        }
        // Lire la première frame du GIF
        $image = imagecreatefromgif($img);
        if(!$image) return false;

        // Calcul du redimensionnement en conservant le ratio
        $ratioSource = $dimension[0] / $dimension[1];
        $ratioMini   = $mlargeur / $mhauteur;

        if($ratioSource > $ratioMini){
            // Source plus large que la mini : on cale sur la largeur
            $dimX = $mlargeur;
            $dimY = $mlargeur / $ratioSource;
            $decalX = 0;
            $decalY = ($mhauteur - $dimY) / 2;
        } elseif($ratioSource < $ratioMini){
            // Source plus haute que la mini : on cale sur la hauteur
            $dimY = $mhauteur;
            $dimX = $mhauteur * $ratioSource;
            $decalY = 0;
            $decalX = ($mlargeur - $dimX) / 2;
        } else {
            $dimX = $mlargeur;
            $dimY = $mhauteur;
            $decalX = 0;
            $decalY = 0;
        }

        // Créer l'image miniature avec fond transparent
        $miniature = imagecreatetruecolor($mlargeur, $mhauteur);
        $transparent = imagecolorallocate($miniature, 255, 255, 255);
        imagefill($miniature, 0, 0, $transparent);

        imagecopyresampled(
            $miniature, $image,
            (int)$decalX, (int)$decalY, 0, 0,
            (int)$dimX, (int)$dimY,
            $dimension[0], $dimension[1]
        );

        // Sauvegarder en GIF — le nom conserve son extension .gif
        $nomSansExt = substr($nom, 0, -4);
        imagegif($miniature, $chemin."/".$nomSansExt.".gif");

        imagedestroy($image);
        imagedestroy($miniature);
        return true;
    }
}

?>
