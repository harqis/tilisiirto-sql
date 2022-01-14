<!-- Tilisiirto, sivu 2
Tommi Kivinen
tommi.kivinen@tuni.fi -->

<html>
   <head>
      <title>Tilisiirto onnistui</title>
   </head>

   <body>
      <h2>Tilisiirto onnistui!</h2>
      
<?php
   
   // Aloitetaan sessio.
   session_start();
   
   echo "{$_SESSION['vanhaomistaja']} on siirtanyt {$_SESSION['summa']} euroa henkilolle {$_SESSION['uusiomistaja']}.";
   
   // Lopetetaan sessio, kun Lopeta-painiketta on painettu.
   if (isset($_POST['lopeta'])) {
      session_destroy();
      // Siirrytään takaisin ekalle sivulle.
      header('Location: tilisiirto_sivu1.php');
    }
?>
      <br>
      Palaa takaisin painamalla Lopeta-painiketta.
      <form method="post" action="tilisiirto_sivu2.php">
         <input type="submit" name ="lopeta" value="Lopeta"/>
      </form>
   </body>
</html>
