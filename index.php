<?php
$title = 'Home';
require_once $_SERVER['DOCUMENT_ROOT'].'/includes/header.php';
?>

<section class="container">
    <div class="row">
        <div class="col .col-xs-4 .col-sm-6">
            <a href="#" class="card center home">
                <div class="card-supporting">
                    <i class="material-icons">home</i>
                    <span>Naar huis</span>
                </div>
            </a>
        </div>
    </div>


    <div class="row">
        <div class="col .col-xs-4 .col-sm-6"">
            <a href="#" class="card center home">
                <div class="card-supporting">
                    <i class="material-icons">navigation</i>
                    <span>Navigeer naar</span>
                </div>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col .col-xs-4 .col-sm-6"">
            <a href="#" class="card center home">
                <div class="card-supporting">
                    <i class="material-icons">wb_sunny</i>
                    <span>Temperatuur</span>
                </div>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col .col-xs-4 .col-sm-6"">
            <a href="#" class="card center home">
                <div class="card-supporting">
                    <i class="material-icons">grain</i>
                    <span>CO<sub>2</sub> gehalte</span>
                </div>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col .col-xs-4 .col-sm-6"">
            <a href="#" class="card center home">
                <div class="card-supporting">
                    <i class="material-icons">cloud</i>
                    <span>Luchtvochigheid</span>
                </div>
            </a>
        </div>
    </div>

    <div class="sc-col sc-xs2 sc-s4">
        <a href="#" class="sc-card sc-center home">
            <div class="sc-card-supporting">
                <i class="material-icons">settings</i>
                <span>Instellingen</span>
            </div>
        </a>
    </div>
</section>
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/includes/footer.php';