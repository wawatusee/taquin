function taquin() {
    var lesPieces = document.getElementsByClassName("piece");
    /*Attribuer un ordre à un élément Flex :
    /*Trouver la piece invisible, stoquer son ordre dans une variable :*/
    pieceInvisible = document.querySelector("#pieceInvisible");
    console.log(pieceInvisible);
    stylePieceInvisible = getComputedStyle(pieceInvisible);
    for (var i = 0; i < lesPieces.length; i++) {
        var chaquePiece = lesPieces[i];
        chaquePiece.style.order = i + 1;
        var sonStyle = getComputedStyle(chaquePiece);

        chaquePiece.addEventListener("click", joue);
        console.log(chaquePiece);
        console.log(sonStyle.order);
    };
};

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
let pieces = ["Piece1", "Piece2", "Piece3", "Piece4", "Piece5", "Piece6"];

function randomPieces(tableauPieces) {
    let availablePieces = Array(...tableauPieces);
    let randomOrderPieces = [];
        // Crée une variable contenant la longueur initiale de `availablePieces` pour éviter qu'elle ne soit réevaluée à chaque tour de boucle
        let initialLength = availablePieces.length;

    for (let i = 0; i < initialLength; i++) {
                        // Choisi un index pour une pièce restante dans `availablePieces`
                        let randomIndex = Math.floor(Math.random() * availablePieces.length);

                        // Fait sauter une pièce à l'index précisé par `randomIndex` et renvoie un array contenant cette pièce
                        let deletedPieceArray = availablePieces.splice(randomIndex, 1);

            randomOrderPieces.push(...deletedPieceArray);
        }

    return randomOrderPieces;
}