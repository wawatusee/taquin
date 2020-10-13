# taquin


## Taquin de raymond-version html-js-css


Commande de Raymond :

### taquin jouable.

Le taquin est une figure divisée en 16 morceaux de dimensions similaires(hauteur largeur).  
Les 16 morceaux sont disposés sur une grille de 16 emplacements adjacents(4 colonnes, 4 rangées).  
Le 16eme morceau est invisible.  
Si un des 15 morceaux visibles est voisin du morceau invisible, il devient cliquable. Au clic sur un morceau visible, il échange sa place avec le morceau invisible.  

## Comment qu'on fait

### Structure  

Les 16 div sont contenues par une boite flex. La largeur de la dite boite Flex correspond à 4 fois la largeur de chaque morceau. 
Nous attribuons un comportement wrap à cette boite.  
La mise en tableau des pièces dépend de ce comportement.  
Une propriété order pour chaque pièce du taquin lui donne sa position dans le tableau :  



