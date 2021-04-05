function taquin() {
    /*Sélection de toutes les div identifiées pièces, dans un tableau "lesPieces", on range leurs réfèrences*/
    var lesPieces = document.getElementsByClassName("piece");
    /*Trouver la piece invisible, */
    pieceInvisible = document.querySelector("#pieceInvisible");
    /*stoquer son style dans une variable :*/
    stylePieceInvisible = getComputedStyle(pieceInvisible);
/*Boucle sur les  */
    for (var i = 0; i < lesPieces.length; i++) {
        let largeurPiece=hauteurPiece=100;
        var chaquePiece = lesPieces[i];
        var sonStyle = getComputedStyle(chaquePiece);
        chaquePiece.style.order = i+1;
        /*Placement de l'image de fond pour chaque piece */
        chaquePiece.style.backgroundPositionX=`${-(i%4)*largeurPiece}px`;
        chaquePiece.style.backgroundPositionY=`${-Math.floor(i/4)*largeurPiece}px`;
        /*Fin du Placement du fond pour chaque piece */
        /*Mise en place des écouteurs sur chaque pièce */
        chaquePiece.addEventListener("click", joue);
    };
};
/*Dans chessGame on a utilisé cette réplique :
emplacement.style.backgroundPosition = `${pieces[figure][1]} ${couleurJouee}`;
Ca marchait donc on va utiliser la même.
Pour l'appliquer :
Largeur de l'image (toujours la même)
Hauteur de l'image largeur multipliée par ratio d'image

 */
function shufflePieces(piecesToShuffle){
    /*Ici le code de Nanard */
}

function pieceCliquable(pieceInvisible, pieceAtester, largueurTaquin = 4) {
    pieceInvisible = Number(pieceInvisible);
    pieceAtester = Number(pieceAtester);
    //Cliquable est définit comme true quand l'ordre de la piece testéé est égale à l'ordre de la pièce invisible,-1 ou +1 ou -4 ou +4 sauf quand le reste de la division de l'orde de la piece invisible par la largeur du taquin est égal à 1 ou à 0
    var jouable = (pieceAtester == (pieceInvisible - 1) && (pieceInvisible % largueurTaquin != 1)) // Vérifie si déplaçable vers la gauche
        ||
        (pieceAtester == (pieceInvisible + 1) && (pieceInvisible % largueurTaquin != 0)) // Vérifie si déplaçable vers la droite
        ||
        (pieceAtester == (pieceInvisible + largueurTaquin)) // Vérifie si déplaçable vers le bas
        ||
        (pieceAtester == (pieceInvisible - largueurTaquin)); // Vérifie si déplaçable vers le haut
    return jouable;
}

function joue(evt) {
    //var nouvelleOrdrePieceCliquee=ordrePieceInvisible;
    var sonStyle = getComputedStyle(evt.target);
    if (pieceCliquable(stylePieceInvisible.order, sonStyle.order)) {
        // Echange l'order entre la pièce invisible et la pièce cliquée
        let temporaryOrder = pieceInvisible.style.order;
        pieceInvisible.style.order = sonStyle.order;
        evt.target.style.order = temporaryOrder;
        console.log('La pièce invisible qui est en position ' + stylePieceInvisible.order + ' prend la position de la piece cliquée ' + sonStyle.order);
    } else {
        console.log(evt);

        console.log(sonStyle.order);
        console.log('Pièce cliquable: ' + stylePieceInvisible.order + " || " + sonStyle.order);
    };
};