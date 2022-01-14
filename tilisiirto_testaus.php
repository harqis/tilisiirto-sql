<!-- Tilisiirto, tietokannan tiedot näyttävä sivu
Tommi Kivinen
tommi.kivinen@tuni.fi -->

<html>
 <head>
  <title>Tietokannan tila</title>
 </head>
 <body>
    <h2>Tietokannassa olevat tilit (tilinumero, tilin omistaja ja tilin saldo)</h2>

<?php

$y_tiedot = "dbname=tk427989 user=tk427989 password=lRkfOSQSDN5hWRu";

if (!$yhteys = pg_connect($y_tiedot))
   die("Tietokantayhteyden luominen epäonnistui.");


$tulos = pg_query("SELECT tilinumero, omistaja, saldo FROM tilit ORDER BY tilinumero");
if (!$tulos) {
  echo "Virhe kyselyssä.\n";
  exit;
}

while ($rivi = pg_fetch_row($tulos)) {
  echo " $rivi[0] $rivi[1] $rivi[2]";
  echo "<br/>\n";
}

pg_close($yhteys);

?>

 </body>
</html>
