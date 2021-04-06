function taquin() {
    /*Sélection de toutes les div identifiées pièces, dans un tableau "lesPieces", on range leurs réfèrences*/
    var lesPieces = document.getElementsByClassName("piece");
    /*Trouver la piece invisible, */
    pieceInvisible = document.querySelector("#pieceInvisible");
    /*stoquer son style dans une variable :*/
    stylePieceInvisible = getComputedStyle(pieceInvisible);
    /*Tableau de positions autres */
    var shuffleArray=[9,11,2,14,15,1,3,8,16,7,4,12,5,13,6,10];
    /*Boucle sur les  */
    for (var i = 0; i < lesPieces.length; i++) {
        let largeurPiece=hauteurPiece=100;
        var chaquePiece = lesPieces[i];
        var sonStyle = getComputedStyle(chaquePiece);
        
        /*Placement de l'image de fond pour chaque piece */
        chaquePiece.style.backgroundPositionX=`${-(i%4)*largeurPiece}px`;
        chaquePiece.style.backgroundPositionY=`${-Math.floor(i/4)*largeurPiece}px`;
        /*Fin du Placement du fond pour chaque piece */
        /*chaquePiece.style.order = i+1;*/
        chaquePiece.style.order =shuffleArray[i];
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

function pieceCliquable(pieceInvisible, pieceAtester, largueurTaquin = 4) {
    pieceInvisible = Number(pieceInvisible);
    pieceAtester = Number(pieceAtester);
    //Cliquable est définit comme true quand l'ordre de la piece testée est égale à l'ordre de la pièce invisible,-1 ou +1 ou -4 ou +4 sauf quand le reste de la division de l'orde de la piece invisible par la largeur du taquin est égal à 1 ou à 0
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
        console.log('Cliquabilité: la pièce invisible est en place ' + stylePieceInvisible.order + " || Et la tienne en " + sonStyle.order);
    };
};

function shuffleTaquin(array){
    var lesPieces = document.getElementsByClassName("piece");
    console.log(lesPieces+"Lapin");
    for (var i = 0; i < lesPieces.length; i++) {
        var chaquePiece = lesPieces[i];
        var sonStyle = getComputedStyle(chaquePiece);
        var nouvelOrdre=array[i];
        chaquePiece.style.order = Number(nouvelOrdre);
        console.log(`Ordre tiré du tableau shuffleTaquin ${nouvelOrdre}`);
        console.log(sonStyle.order);
        console.log(chaquePiece);
    };

}
//shuffleTaquin(shuffleArray);
function taquinAfficheOrder() {
    var lesPieces = document.getElementsByClassName("piece");
    stylePieceInvisible = getComputedStyle(pieceInvisible);
    for (var i = 0; i < lesPieces.length; i++) {
        var chaquePiece = lesPieces[i];
        var sonStyle = getComputedStyle(chaquePiece);
        console.log(sonStyle.order);
    };

};
taquinAfficheOrder();