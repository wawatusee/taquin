function taquin() {
    var lesPieces = document.getElementsByClassName("piece");
    /*Attribuer un ordre à un élément Flex :
    /*Trouver la piece invisible, stoquer son ordre dans une variable :*/
    pieceInvisible = document.querySelector("#pieceInvisible");
    console.log(pieceInvisible);
    stylePieceInvisible = getComputedStyle(pieceInvisible);
    for (var i = 0; i < lesPieces.length; i++) {
        let largeurPiece=hauteurPiece=100;
        var chaquePiece = lesPieces[i];
        chaquePiece.style.order = i + 1;
        var sonStyle = getComputedStyle(chaquePiece);
        /*Placement du fond pour chaque piece */
        /*ICI `${(i%4)*40;} ${couleurJouee}` */
        chaquePiece.style.backgroundPositionX=`${-(i%4)*largeurPiece}px`;
        chaquePiece.style.backgroundPositionY=`${-Math.floor(i/4)*largeurPiece}px`;
        /*Fin du Placement du fond pour chaque piece */
        chaquePiece.addEventListener("click", joue);
        console.log(chaquePiece);
        console.log(sonStyle.order);
    };
};
/*Dans chessGame on a utilisé cette réplique :
emplacement.style.backgroundPosition = `${pieces[figure][1]} ${couleurJouee}`;
Ca marchait donc on va utiliser la même.
Pour l'appliquer :
Largeur de l'image (toujours la même)
Hauteur de l'image largeur multipliée par ratio d'image

 */
function shufflePieces(){
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