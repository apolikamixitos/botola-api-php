<?php

////////////////////////////////////////////////////////////////////////////////
///
///Coded By : Ayoub DARDORY (Apolikamixitos)
///Email : AYOUBUTO@Gmail.com
///Description : Une Classe PHP pour avoir les résultats du championnat Marocain via <http://www.arryadia.com>
///Follow me : http://www.twitter.com/Apolikamixitos
///GitHub: http://github.com/apolikamixitos
//
////////////////////////////////////////////////////////////////////////////////

require("botola.class.php");

// Vous pouvez changer le nombre 2 par le numero du groupe pour avoir les resultats
// Tapez 1 pour avoir les résultats de la GNF1 et 2 pour la GNF2
$NGNF = 1;
$GNF = new GNF($NGNF);

echo "Liste des équipes GNF1 :";
var_dump($GNF->getEquipes()); //Liste des équipes

$Classement = 1;
$Equipe = $GNF->GNFEquipe($Classement); //Avoir la 1ère équipe au classement (GNF1)

echo "La 1ère équipe au classement GNF1 :";
var_dump($Equipe);

// Dans le premier parametre vous mettez le groupe et dans le 2eme le numero de la journée désirée
//Exemple : La 12ème journée du GNF1
$Matches = $GNF->GNFMatches(12);

echo "Matches de la 12ème journée GNF1 :";
var_dump($Matches);
?>