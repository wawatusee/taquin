class TaquinImage {
    /*Tableau qui stoquera les pièces du taquin */
    _htmlPiecesArray=[];

    constructor(image,nombreDePieces,largeurTaquin){
        /*Checks the arguments*/
        /*The image must be an url, so a string */
        if (typeof image === "string") {
            this.image = image;
        }
        /*The nombreDePièce must be a number */
        if ( Number.isInteger(Math.sqrt(nombreDePieces)) ){
            this.nombreDePieces = nombreDePieces;
            this.nbrPieceParLigne = Math.sqrt(nombreDePieces);
        }

        if (typeof largeurTaquin === "number") {
            this.largeurTaquin = largeurTaquin;
        }

        this.largeurPrCentPiece = 100 / this.nbrPieceParLigne;
        
        for (let i = 0; i < this.nombreDePieces; i++){
            let x = i % this.nbrPieceParLigne;
            let y = Math.floor(i / this.nbrPieceParLigne);
            let nouvellePiece = new PieceTaquin(this.image, this.largeurPrCentPiece, x, y);
            this._htmlPiecesArray.push(nouvellePiece);
        }
    }

    renduTaquin(container) {
        let taquin = document.createElement('div');
        taquin.style.width = `${this.largeurTaquin}px`;
        taquin.style.height = `${this.largeurTaquin}px`;
        taquin.style.display = "flex";
        taquin.style.flexDirection = "row";
        taquin.style.flexWrap = "wrap";

        for (let piece of this._htmlPiecesArray) {
            taquin.insertAdjacentElement('beforeend', piece);
        }

        container.insertAdjacentElement('beforeend', taquin);
    }
}

class PieceTaquin extends HTMLElement {
  constructor(image, largeurPrCentPiece, x, y) {
    super();
    let decalageX = -(x * 100);
    let decalageY = -(y * 100);

    this.style.background = `url("${image}")`;
    this.style.width = `${largeurPrCentPiece}%`;
    this.style.backgroundPositionX = `${decalageX}%`;
    this.style.backgroundPositionY = `${decalageY}%`;
    this.onclick = function () {
        console.log("Aie je suis cliqué");
    };
  }
}

customElements.define('piece-taquin', PieceTaquin);
let monTaquin= new TaquinImage("D:/Projets/taquin/img/imgtaquinnumero.png",16, 400 ); 