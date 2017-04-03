<?php
$title = 'Home';
require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/includes/header.php';
?>

<section class="row">
    <div class="col col-xs-6 col-sm-6">
        <a href="#" class="sc-card sc-center home">
            <div class="sc-card-supporting">
                <i class="material-icons">home</i>
                <span>Naar huis</span>
            </div>
        </a>
    </div>


    <div class="col col-xs-6 col-sm-6">
        <a href="/climate-canary/navigate-to.php" class="sc-card sc-center home">
            <div class="sc-card-supporting">
                <i class="material-icons">navigation</i>
                <span>Navigeer naar</span>
            </div>
        </a>
    </div>
</section>

<section class="row">
    <div class="col col-xs-6 col-sm-6">
        <a href="#" class="sc-card sc-center home">
            <div class="sc-card-supporting">
                <i class="material-icons">wb_sunny</i>
                <span>Temperatuur</span>
            </div>
        </a>
    </div>

    <div class="col col-xs-6 col-sm-6">
        <a href="#" class="sc-card sc-center home">
            <div class="sc-card-supporting">
                <i class="material-icons">grain</i>
                <span>CO<sub>2</sub> gehalte</span>
            </div>
        </a>
    </div>
</section>

<section class="row">
    <div class="col col-xs-6 col-sm-6">
        <a href="#" class="sc-card sc-center home">
            <div class="sc-card-supporting">
                <i class="material-icons">cloud</i>
                <span>Luchtvochigheid</span>
            </div>
        </a>
    </div>

    <div class="col col-xs-6 col-sm-6">
        <a href="#" class="sc-card sc-center home">
            <div class="sc-card-supporting">
                <i class="material-icons">settings</i>
                <span>Instellingen</span>
            </div>
        </a>
    </div>
</section>
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/includes/footer.php';