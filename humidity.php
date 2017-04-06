<?php
$title = 'Home';
require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/includes/header.php';
?>

<div class="row">
    <div class="col-sm-12 return">
        <div class="">
            <a href="index.php">Terug</a>
        </div>
    </div>

    <div class="col-sm-12 content">
        <h1>LUCHTVOCHTIGHEID</h1>
        <?php
        /*HIER KOMT EEN PHP GEDEELTE WAARIN DE TEMPERATUUR BINNEN DE AUTO WORDT OPGEHAALD*/
        ?>
        <p>luchtvochtigheid in de auto</p>

        <p>De luchtvochtigheid is <?php /* HAALT DE LUCHTVOCHTIGHEID OP EN GEEFT TE HOOG/GOED/TE LAAG WEER */ ?> !</p>
        <p>Een ideale temperatuur in de auto ligt tussen de 18 - 20 graden.</p>
    </div>
</div>

<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/includes/footer.php';