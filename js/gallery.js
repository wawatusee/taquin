selectRadioButton();
function selectRadioButton(){
var radioButton=document.getElementsByName("defaultTaquin");
/*Listener on each radio button*/
    for (i=0;i<radioButton.length;i++){
        radioButton[i].addEventListener("click",onSelect);
    }
return radioButton[0].value;
}
function onSelect(evt){
    var imageSelected=evt.target.value;
    console.log(imageSelected);
    //let toSend= '"traitementSelection.php?fileName='+imageSelected+'"';
    window.location="traitementSelection.php?fileName="+imageSelected;
}
function onDelete(evt){
    var fileToDelete=evt.target.value;
    console.log(fileToDelete);
    window.location="delete.php?fileName="+fileToDelete;
    
}
