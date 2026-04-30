# crud_galerie-php-css-js
Gallery with small crud with html css js json for the data files
## Done
 - add an image on a directory from user terminal
 - display thumbs images(flex wrap)
 - on click thumb use of a zoombox to display big image
 - add a checkbox to use it as a selector backend
 - use the ratio of the background image to fill the taquin with a non square image
 - Use video to fill the taquin(just have use a GIF, thats perfect)
  - make an event at the issue of the taquin
   - delete a thumb an the linked big image
   - Create an help with a zoomBox wich show the image of the 15n puzzle
   - Create an help service wich show the original number of each case
   - on change the default image, go to the public page directly
   - less corner for each piece of the taquin
   - Change the background to grey
   - change the number of pieces 
   - make a taquin with only 9 pieces
  - build the taquin in JS instead of hard html
## To do

For Raymond

 - access to the admin with a password

 The other are for this site and the fork origin to
 
 - rule errors: admin,on upload, rule imagesize error; if the default image(json)doesn't exist; if an image extension is jpeg and not jpg.
 - modify the name of a thumb and the big image linked to it


 ## Parameters image taquin and explain
 The aspect of each piece come from the backround image of his div, the url of that imagage come from a file json("image-taquin.json") with the property "image_taquin";
  Read by php in index php:" $jsonImageTaquin=json_decode(file_get_contents("js/image-taquin.json"));
                //Récupération du nom de l'image source
                $nomImage=$jsonImageTaquin->image_taquin;
                $urlImage=$dirImages.$nomImage;"

And transformed in variable css in a style tag : "<style>:root{--image-taquin:url('<?php echo "../".$urlImage ?>');</style>"
At end the real impact is in CSS with the use of this variable for the background of each piece:
".piece {background: var(--image-taquin);"

May be I could do everything in JS but I like the mixed CSS/JS to, thats make the reread easier.
 
