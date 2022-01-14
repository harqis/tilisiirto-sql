<!-- Tietokantajärjestelmät SQL, syksy 2020
PHP-ohjelmointitehtävä, sivu 1
Tommi Kivinen
tommi.kivinen@tuni.fi -->

<?php

// Aloitetaan sessio.
session_start();

// Luodaan tietokantayhteys ja ilmoitetaan mahdollisesta virheestä.

$y_tiedot= "dbname=tk427989 user=tk427989 password=lRkfOSQSDN5hWRu";

if (!$yhteys= pg_connect($y_tiedot))
   die("Tietokantayhteyden luominen epaonnistui.");
   
if (isset($_POST['tallenna']))
{
   // Tarvittavat, käyttäjän antamat tiedot.
   
   $vanhatili = intval($_POST['vanhatili']);
   $uusitili = intval($_POST['uusitili']);
   $_SESSION['summa'] = intval($_POST['summa']);   
   
   // Selvitetään annettujen tilien omistajat.
   
   $omistajakysely1 = "SELECT omistaja FROM tilit WHERE tilinumero = {$vanhatili}";
   $omistajakysely2 = "SELECT omistaja FROM tilit WHERE tilinumero = {$uusitili}";
   
   $omistaja1 = pg_query($omistajakysely1);
   $omistaja2 = pg_query($omistajakysely2);
   
   $tulos1 = pg_fetch_row($omistaja1);
   $tulos2 = pg_fetch_row($omistaja2);
   
   $_SESSION['vanhaomistaja'] = $tulos1[0];
   $_SESSION['uusiomistaja'] = $tulos2[0];

   // Jos kenttiin on syötetty oikeelliset tiedot, tehdään tilisiirto.
   
   $tiedot_ok = $vanhatili != 0 && $uusitili != 0 && $_SESSION['summa'] > 0;
   if ($tiedot_ok)
   {  
      // Muutokset suorittavat kyselyt.
      $kysely1 = "UPDATE tilit SET saldo = saldo-{$_SESSION['summa']} WHERE tilinumero = {$vanhatili}";     
      $kysely2 = "UPDATE tilit SET saldo = saldo+{$_SESSION['summa']} WHERE tilinumero = {$uusitili}";
      
      // Aloitetaan tilisiirtotapahtuma.
      pg_query('BEGIN');
      
      // Suoritetaan paivitys1.
      $paivitys1 = pg_query($kysely1);
         
      // Asetetaan viesti-muuttuja lisäämisen onnistumisen mukaan.
      // Lisätään virheilmoitukseen myös virheen syy (pg_last_error).
         
      // Yhdelle tilille pitää tapahtua muutos.
      if ($paivitys1 && (pg_affected_rows($paivitys1) > 0)){
         
         // Suoritetaan paivitys2.
         $paivitys2 = pg_query($kysely2);
         
         if ($paivitys2 && (pg_affected_rows($paivitys2) > 0)) {

            // Kaikki onnistui, commit.
            pg_query('COMMIT');
            
            // Siirrytään tulossivulle.
            header('Location: tilisiirto_sivu2.php');
         }
         else {
            // Epäonnistui, rollback.
            pg_query('ROLLBACK');
            
            $viesti = 'Tilisiirto epaonnistui. Tarkista saava tilinumero. ' . pg_last_error($yhteys);
         }
      }
      else {
         // Epäonnistui, rollback.
         pg_query('ROLLBACK');
         
         $viesti = 'Tilisiirto epaonnistui. Tarkista veloitettava tilinumero.' . pg_last_error($yhteys);
      }
      
   }
   else {
      $viesti = 'Annetut tiedot virheelliset -tarkista, ole hyva!';
   }
}

// Suljetaan tietokantayhteys.

pg_close($yhteys);

?>

<html>
 <head>
  <title>Tilisiirto</title>
 </head>

<body>

<!--Lomake lähetetään samalle sivulle.-->
<form action="tilisiirto_sivu1.php" method="post">

<h2>Tilisiirto</h2>

<?php if (isset($viesti)) echo '<p style="color:red">'.$viesti.'</p>'; ?>

   <tableborder="0" cellspacing="0" cellpadding="3">
      <tr>
         <td>Siirrettava summa:</td>
         <td><input type="text" name="summa" value="" /></td>
      </tr>
      <tr>
         <td>Veloitetaan tililta (anna tilinumero):</td>
         <td><input type="text" name="vanhatili" value="" /></td>
      </tr>
      <tr>
         <td>Siirretaan tilille (anna tilinumero):</td>
         <td><input type="text" name="uusitili" value="" /></td>
      </tr>
   </table>
   
   <br/>
   
   <input type="hidden" name="tallenna" value="jep" />
   <input type="submit" value="Tee tilisiirto" />
   </form>
   
</body>
</html>