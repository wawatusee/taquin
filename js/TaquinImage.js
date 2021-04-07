class TaquinImage{
    _htmlPiecesArray=[];
    constructor(image,nombreDePieces){
        this.image=image;
        this.nombreDePieces=nombreDePieces;
        for(i=0;i<this.nombreDePieces;i++){
        /*Instancier chaque pièce du taquin, et les stoquer dans le tableau _htmlPiecesArray */
        }
        /*Calculer le nombre de pièces par ligne */
    }
    set nombreDePieces(nombreDePieces){
        if(Number.isInteger(Math.sqrt(nombreDePieces))){
            this._nombreDePieces=nombreDePieces;
        }
    }
    get nombreDePieces(){
        return this._nombreDePieces;
    }
    set image(image){
        this._image=image;
    }
    get image(){
        return this._image;
    }

}