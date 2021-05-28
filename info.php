<?php

require_once("inc/CApp.php");

?>

<?php
$app->renderHeader("Kontakt Information");
?>

<html>
    <body>
        <div class="information">
            <h2>
                Öppetider: 8-17 mån-fre, 10-15 lördag, stängt söndag
            </h2>
             <h2>
                Telefontid: 9-15 mån-fre
            </h2>
            <h2>
                Telefonnummer: ___-_______
            </h2>
            <h2>
                Våran address är Bergsgatan 14B och det kostar 100kr per besök
            </h2>
            <h2>
                <img id="direction" src="images/Sjukhus_plats.png"/>
            </h2>
        </div>
    </body>
</html>

<?php
$app->renderFooter();
?>