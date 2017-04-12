<?php
if( empty( $_GET['type'] ) ) {
    $user->to('/climate-canary/');
} else {
    $type = $_GET['type'];

    switch($type) {
        case 'temperature':
            $title = 'Temperatuur';
            break;
        case 'carbon-dioxide':
            $title = 'Co2 gehalte';
            break;
        case 'humidity':
            $title =  'Luctvochtigheid';
            break;
    }
}
require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/includes/header.php';
?>

	<div class="row">
		<div class="col-sm-12 return">
			<a href="<?php echo ( !empty( $previous ) ? $previous : '/climate-canary/' ); ?>" class="return"><i class="material-icons">arrow_back</i> Terug</a>
		</div>

		<div class="col-sm-12 content">
			<h1><?php echo $title; ?></h1>
			<p id="value"></p>
			<p>in de auto</p>
			<p>Een ideale temperatuur in de auto ligt tussen de 18 - 20 graden.</p>
		</div>
	</div>

<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/includes/footer.php';