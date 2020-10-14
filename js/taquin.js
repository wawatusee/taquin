function taquin() {
    var lesPieces = document.getElementsByClassName("piece");
    //var ordrePiece = pieceOrdonne[0].style.order = "-1";
    var i = 0;
    for (var i = 0; i < lesPieces.length; i++) {
        var chaquePiece = lesPieces[i];
        var sonStyle = getComputedStyle(chaquePiece);
        console.log(chaquePiece);
        console.log(sonStyle.order);
    };
};
function pieceCliquable(pieceInvisible, pieceAtester, largueurTaquin = 4) {
    var cliquable = (pieceAtester == (pieceInvisible - 1) && !(pieceInvisible % 4 == 1)) || (pieceAtester == (pieceInvisible + 1) && !(pieceInvisible % 4 == 0)) || (pieceAtester == (pieceInvisible + 4)) || (pieceAtester == (pieceInvisible - 4));
    /*Enlever la piece invisible des test*/
    return cliquable;
}
