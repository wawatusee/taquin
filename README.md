# Taquin de Raymond

Jeu de taquin en ligne jouant des images et GIFs animés issus des œuvres éphémères de Raymond, artiste en bois flotté.

---

## Structure du projet

```
/
├── index.php               # Page publique du jeu
├── config.php              # Configuration globale
├── js/
│   └── taquin.js           # Logique du jeu
│   └── image-taquin.json   # Image active et nombre de pièces
├── css/
│   └── taquin.css          # Styles du jeu
├── images/                 # Images et GIFs du taquin
│   └── min/                # Miniatures
└── admin/
    ├── admin.php            # Interface d'administration
    ├── admin-gif.php        # Convertisseur vidéo → GIF
    ├── upload-gif.php       # Réception et sauvegarde des GIFs
    ├── delete.php           # Suppression d'images
    ├── imgClass.php         # Classe de redimensionnement d'images
    └── js/
        └── gifshot.min.js  # Librairie de conversion vidéo → GIF
```

---

## Modifications apportées

### `index.php`
- Accepte les paramètres GET `image` et `nbrPieces` pour le partage de taquins spécifiques
- Validation stricte du paramètre `image` via `basename()` et `file_exists()`
- Garde contre la division par zéro sur `getimagesize()` (GIFs animés)
- Injection des variables PHP `$nomImage` et `$nbrPiecesTotal` en JS via `<script>`
- Bouton de partage dans le footer, masqué jusqu'à la victoire
- Interface footer redessinée : suppression du `fieldset`, nouveaux boutons `.ctrl-btn`
- Lien Admin intégré dans le header avec icône SVG engrenage

### `js/taquin.js`
- **Responsive** : positionnement des pièces en pourcentages (`backgroundPositionX/Y`) au lieu de pixels absolus
- **Mélange aléatoire** : algorithme Fisher-Yates remplace les tableaux hardcodés
- **Solubilité garantie** : `isSolvable()` vérifie la parité des inversions sur le tableau visuel reconstitué, avec correction automatique si nécessaire
- `var` remplacés par `let` partout, variables globales déclarées et initialisées en tête de fichier
- Bug `onload` corrigé : `setBoard()` → `setBoard` (sans parenthèses)
- **Mode Auto** : joue aléatoirement parmi les pièces cliquables, bouton Auto/Stop
- **Partage** : `shareGame()` utilise la Web Share API sur mobile, clipboard en fallback sur desktop
- `endOfGame()` unifié : arrêt du mode auto + affichage du bouton partage

### `css/taquin.css`
- **Responsive** : `.taquin` passe à `min(90vmin, 500px)` — s'adapte à tous les écrans sans déborder
- `.piece` utilise `width: calc(100% / var(--nbrPiecesPerLine))` et `aspect-ratio`
- `background-size: calc(var(--nbrPiecesPerLine) * 100%)` — découpage image en CSS pur
- Suppression des variables `--totalLargeur` et `--largeurPiece` devenues inutiles
- Nouveau style `.ctrl-btn`, `.admin-link`, `.select-wrap`, `.ctrl-btn.auto-on`
- Bordure du taquin adoucie avec `border-radius: 10px`

### `admin/admin.php`
- Interface entièrement redessinée : palette rose cohérente avec le front
- Galerie en cards avec actions intégrées (Choisir / Supprimer)
- Sélection d'image en un clic — suppression de `traitementSelection.php`
- Upload avec affichage du nom de fichier et messages success/error explicites
- Gestion des codes d'erreur PHP upload (taille, format, disque…)
- `echo` et `print_r` de debug supprimés
- Extension miniature adaptée selon le format source (`.jpg` pour images fixes, `.gif` pour GIFs)

### `admin/delete.php`
- Page de confirmation redessinée
- Nom du fichier passé en champ `hidden` au lieu d'une `textarea` éditable
- `basename()` ajouté pour sécuriser la suppression
- Session vérifiée en entrée de page

### `admin/imgClass.php`
- Nouvelle méthode statique `creerMinGif()` : crée une miniature GIF (première frame) en conservant l'extension `.gif`
- La méthode `creerMin()` originale est inchangée

### `admin/admin-gif.php` *(nouveau)*
- Interface de conversion vidéo → GIF animé, sans ffmpeg
- Conversion entièrement côté navigateur via `gifshot.min.js`
- Réglages : point de départ, durée, largeur, qualité
- Hauteur calculée automatiquement depuis le ratio réel de la vidéo
- Aperçu du GIF généré avant enregistrement
- Boutons Enregistrer / Recommencer
- Même charte graphique que l'admin

### `admin/upload-gif.php` *(nouveau)*
- Reçoit le GIF en base64, décode et sauvegarde dans `images/` sans transformation
- Crée la miniature GIF via `Img::creerMinGif()`
- Répond en JSON `{success, fileName}`
- Session vérifiée, erreurs explicites

---

## Fonctionnement du partage

À la victoire, un bouton de partage apparaît dans le footer. Il génère une URL de la forme :

```
https://raymondspartacus.fr/index.php?nbrPieces=9&image=mer2.gif
```

- Sur **mobile** : ouvre le menu de partage natif (WhatsApp, SMS, mail…)
- Sur **desktop** : copie le lien dans le presse-papier

---

## Fichier supprimé

- `admin/traitementSelection.php` — rendu inutile, la sélection d'image se fait directement dans `admin.php`
