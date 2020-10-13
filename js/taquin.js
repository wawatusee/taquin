function taquin() {
    console.log("lapin");
    var lesPieces = document.getElementsByClassName("piece");
    var i = 0;
    for (var piece in lesPieces) {
        //le code ma boucle
        var chaquePiece = lesPieces[i];
        console.log(lesPieces[i]);
        window.setComputedStyle(lesPieces[i]);
        i += 1;
    };
};

/*function pieceCliquable(largueurTaquin, pieceInvisible, pieceAtester) {
    var cliquable;
    if (pieceAtester == (pieceInvisible - 1) || pieceAtester == (pieceInvisible + 1) || pieceAtester == (pieceInvisible + 4) || pieceAtester == (pieceInvisible - 4) && !(pieceAtester % largueurTaquin) && pieceAtester % largueurTaquin != 1) {
        cliquable = "vrai";
    } else cliquable = "faux";
    return cliquable;
}
function pieceCliquable(pieceInvisible, pieceAtester, largueurTaquin = 4) {
    var cliquable;
    if (pieceAtester == (pieceInvisible - 1) && !(pieceAtester % largueurTaquin == 1) || pieceAtester == (pieceInvisible + 1) && !(pieceAtester % largueurTaquin) || pieceAtester == (pieceInvisible + 4) || pieceAtester == (pieceInvisible - 4)) {
        cliquable = "vrai";
    } else cliquable = "faux";
    return cliquable;
}*/
function pieceCliquable(pieceInvisible, pieceAtester, largueurTaquin = 4) {
    var cliquable = (pieceAtester == (pieceInvisible - 1) && !(pieceInvisible % 4 == 1)) || (pieceAtester == (pieceInvisible + 1) && !(pieceInvisible % 4 == 0)) || (pieceAtester == (pieceInvisible + 4)) || (pieceAtester == (pieceInvisible - 4));
    /*Enlever la piece invisible des test*/
    return cliquable;
}