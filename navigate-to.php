<?php
$title = 'Navigeer naar';
require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/includes/header.php';
?>
<!-- Address input -->
<section class="row" id="address">
	<div class="col col-xs-12 col-sm-12">
        <div class="colored-background sc-card sc-card-supporting">
            <h2>Navigeren</h2>
            <?php
            $form = new Form($db);

			if( $_POST ) {
			    $validate = $form->check($_POST, array(
			        'from' => array(
			            'required' => true,
                        'name' => 'Van'
                    ),
                    'fromLat' => array(
                        'required' => true,
                        'name' => 'Van latitude'
                    ),
					'fromLng' => array(
						'required' => true,
						'name' => 'Van longitude'
					),
					'to' => array(
						'required' => true,
						'name' => 'Naar'
					),
					'toLat' => array(
						'required' => true,
						'name' => 'Naar latitude'
					),
					'toLng' => array(
						'required' => true,
						'name' => 'Naar longitude'
					)
                ));

			    if( empty( $form->errors ) ) {
			        // Create usable array for insert
			        $data = array(
			            'address' => $validate['to'],
                        'latitude' => $validate['toLat'],
                        'longitude' => $validate['toLng']
                    );

			        // Add address to database
			        if( $address->add($data) ) {
						$user->to( 'navigation-confirm.php?from='.$validate['from'].'&fromLat='.$validate['fromLat'].'&fromLng='.$validate['fromLng'].'&to='.$validate['to'].'&toLat='.$validate['toLat'].'&toLng='.$validate['toLng'] );
					} else {
			            echo '<div class="error sc-card sc-card-supporting-additional">Er is iets mis gegaan met het toevoegen van het adres</div>';
                    }
                } else {
			        echo $form->outputErrors();
                }
			}
            ?>
		<form action="" method="post">
			<div class="row">
				<div class="col col-xs-2 col-sm-2">
					<label for="from">Van</label>
				</div>

				<div class="col col-xs-10 col-sm-10">
					<input type="text" name="from" id="from" required placeholder="Huidige locatie">
                    <input type="text" name="fromLat" id="fromLat" class="captcha">
                    <input type="text" name="fromLng" id="fromLng" class="captcha">
				</div>
			</div>

			<div class="row">
				<div class="col col-xs-2 col-sm-2">
					<label for="to">Naar</label>
				</div>

				<div class="col col-xs-10 col-sm-10">
					<input type="text" name="to" id="to" required placeholder="Bestemming kiezen">
                    <input type="text" name="toLat" id="toLat" class="captcha">
                    <input type="text" name="toLng" id="toLng" class="captcha">
				</div>
			</div>

            <div class="sc-card-actions">
                <button class="sc-raised-button"><i class="material-icons">directions</i> Oke</button>
            </div>
		</form>
        </div>
	</div>
</section>

<!-- History -->
<section class="row" id="history">
    <div class="col col-xs-12 col-sm-12">
        <div class="sc-card sc-card-supporting">
            <h2>Geschiedenis</h2>
            <?php
            $data = $address->data();

            if( empty( $data ) ) {
                echo '  <a href="#" class="row divided">
                            <div class="col col-xs-2">
                                <i class="material-icons">clear</i>
                            </div>
            
                            <div class="col col-xs-10 col-sm-10">
                                Geen resultaten gevonden
                            </div>
                        </a>';
            } else {
                foreach( $data as $row => $field ) {
					echo '  <div class="row divided">
                                <div class="col col-xs-2">
                                    <i class="material-icons">'.$field['icon'].'</i>
                                </div>
                
                                <a href="navigation-confirm.php?from=fAddress&fromLat=fLat&fromLng=fLng&to='.$field['address'].'&toLat='.$field['latitude'].'&toLng='.$field['longitude'].'" class="col col-xs-7 address">
                                    '.( !empty( $field['name'] ) ? $field['name'] : $field['address'] ).'
                                </a>
                                
                                <div class="col col-xs-3">
                                    <div class="col col-xs-6">
                                        <a href="address-edit.php?id='.base64_encode($field['id']).'"><i class="material-icons">edit</i></a>
                                    </div>
                                    
                                    <div class="col col-xs-6">
                                        <a href="address-delete.php?id='.base64_encode($field['id']).'" onClick="return confirm(\'Weet je zeker dat je dit adres wilt verwijderen?\')"><i class="material-icons">delete</i></a>
                                    </div>
                                </div>
                            </div>';
                }
            }
            ?>
        </div>
    </div>
</section>

<!-- Favorites -->
<section class="row" id="favorites">
    <div class="col col-xs-12 col-sm-12">
        <div class="sc-card sc-card-supporting">
            <h2>Favorieten</h2>
			<?php
			$data = $address->favorites();

			if( empty( $data ) ) {
				echo '  <a href="#" class="row divided">
                            <div class="col col-xs-2">
                                <i class="material-icons">clear</i>
                            </div>
            
                            <div class="col col-xs-10 col-sm-10">
                                Geen resultaten gevonden
                            </div>
                        </a>';
			} else {
				foreach( $data as $row => $field ) {
					echo '  <div class="row divided">
                                <div class="col col-xs-2">
                                    <i class="material-icons">'.$field['icon'].'</i>
                                </div>
                
                                <a href="navigation-confirm.php?from=fAddress&fromLat=fLat&fromLng=fLng&to='.$field['address'].'&toLat='.$field['latitude'].'&toLng='.$field['longitude'].'" class="col col-xs-7 address">
                                    '.( !empty( $field['name'] ) ? $field['name'] : $field['address'] ).'
                                </a>
                                
                                <div class="col col-xs-3">
                                    <div class="col col-xs-6">
                                        <a href="address-edit.php?id='.base64_encode($field['id']).'"><i class="material-icons">edit</i></a>
                                    </div>
                                    
                                    <div class="col col-xs-6">
                                        <a href="address-delete.php?id='.base64_encode($field['id']).'"><i class="material-icons">delete</i></a>
                                    </div>
                                </div>
                            </div>';
				}
			}
			?>
        </div>
    </div>
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
require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/includes/footer.php';