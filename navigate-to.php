<?php
$title = 'Navigeer naar';
require_once $_SERVER['DOCUMENT_ROOT'].'/includes/header.php';
?>
<!-- Address input -->
<section class="row" id="address">
	<div class="col col-xs-12 col-sm-12">
        <div class="colored-background sc-card sc-card-supporting">
		<form action="" method="post">
			<div class="row">
				<div class="col col-xs-2 col-sm-2">
					<label for="from">Van</label>
				</div>

				<div class="col col-xs-10 col-sm-10">
					<input type="text" name="from" id="from" required placeholder="Huidige locatie">
				</div>
			</div>

			<div class="row">
				<div class="col col-xs-2 col-sm-2">
					<label for="to">Naar</label>
				</div>

				<div class="col col-xs-10 col-sm-10">
					<input type="text" name="to" id="to" required placeholder="Bestemming kiezen">
				</div>
			</div>

            <div class="sc-card-actions">
                <button class="sc-raised-button"><i class="material-icons">directions</i> Navigeren</button>
            </div>
		</form>
        </div>
	</div>
</section>

<!-- History -->
<section class="row" id="history">
    <div class="col col-xs-12 col-sm-12">
        <div class="sc-card sc-card-supporting">
            <a href="#" class="row divided">
                <div class="col col-xs-2 col-sm-2">
                    <i class="material-icons">access_time</i>
                </div>

                <div class="col col-xs-10 col-sm-10">
                    McDonalds Schulpen Plein
                </div>
            </a>

            <a href="#" class="row divided">
                <div class="col col-xs-2 col-sm-2">
                    <i class="material-icons">access_time</i>
                </div>

                <div class="col col-xs-10 col-sm-10">
                    Zernikeplein 11
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Favorites -->
<section class="row" id="favorites">

</section>

<!-- Navigation button -->
<div class="sc-floating-button sc-trigger quick-nav" data-sc-trigger="options">
    <i class="material-icons">menu</i>

    <ul id="options">
        <li><a href="#address"><i class="material-icons">place</i></a></li>
        <li><a href="#history"><i class="material-icons">access_time</i></a></li>
        <li><a href="#favorites"><i class="material-icons">star</i></a></li>
    </ul>
</div>
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/includes/footer.php';