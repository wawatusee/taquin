class TaquinImage{
    _htmlPiecesArray=[];
    constructor(image,nombreDePieces,largeurDuTaquin){
        this.image=image;
        if(Number.isInteger(Math.sqrt(nombreDePieces))){
            this.nombreDePieces=nombreDePieces;
            this.nbrPieceParLigne=Math.sqrt(nombreDePieces);
        }
        this.largeurDuTaquin=largeurDuTaquin;
        this.largeurPrCentPiece=100/this.nbrPieceParLigne;
        
        for(i=0;i<this.nombreDePieces;i++){

        /*Instanciation d'un objet que l'on pousse dans le tableau _htmlPiecesArray*/

        /*Attribuer à chacun de ces objets des propriétés qui serviront de style à l'affichage:
        (style.width='${this.largeurDePiece}' et height, style.background = url('${this.image}'), style.backgroundPositionX=) */
        }
        /*Calculer le nombre de pièces par ligne */

    }

}
class PieceTaquin extends HTMLElement {
    constructor(image, largeurPrCentDePiece, x, y) {
      super();
      
      this.style.background = `url("${image}")`;
      this.style.width = `${largeurPrCentDePiece}%`;
      this.style.backgroundPositionX = ``;
      this.style.backgroundPositionY = ``;
      this.onclick = 
    }
  }

