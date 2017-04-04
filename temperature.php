<?php
$title = 'Home';
require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/includes/header.php';
?>

<div class="row">
    <div class="col-sm-12 return">
        <div class="">
            <span>RETURN</span>
        </div>
    </div>

    <div class="col-sm-12 content">
        <h1>TEMPERATUUR</h1>
        <?php
        /*HIER KOMT EEN PHP GEDEELTE WAARIN DE TEMPERATUUR BINNEN DE AUTO WORDT OPGEHAALD*/
        ?>
        <p>in de auto</p>
        <?php
        /*HIER KOMT EEN PHP GEDEELTE WAARIN DE TEMPERATUUR BUITEN DE AUTO WORDT OPGEHAALD*/
        ?>
        <p>buiten de auto</p>
        <p>Een ideale temperatuur in de auto ligt tussen de 18 - 20 graden.</p>
    </div>
</div>

<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/includes/footer.php';