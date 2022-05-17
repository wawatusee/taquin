var nomImageTaquin=getNomImage();
document.documentElement.style.setProperty('--image-taquin','url("../img/'+nomImageTaquin+'")');
function getNomImage(){
    var jsonImageTaquin = document.getElementById("planDeSite");
    var nomImageTaquin=jsonImageTaquin.textContent;
    return nomImageTaquin;
}