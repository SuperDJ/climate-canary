<?php
if( !empty( $_GET['id'] ) ) {
	$title = 'Adres bewerken';
	require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/includes/header.php';

	if( $db->exists('id', 'address', 'id', base64_decode( $_GET['id'] )) ) {
		$id = $db->sanitize( base64_decode( $_GET['id'] ) );
		$data = $address->data($id)[0];
		$form = new Form($db);

		if( $_POST ) {
			$validation = $form->check($_POST, array(
				'name' => array(
					'required' => true,
					'minLength' => 2,
					'name' => 'Naam'
				),
				'category' => array(
					'required' => true,
					'numeric' => true,
					'name' => 'Categorie'
				)
			), $id);

			if( empty( $form->errors ) ) {
				// Check if certain category isn't taken
				if( $db->exists('icons_id', 'address', 'icons_id', 1) ) {
					echo '<div class="error sc-card sc-card-supporting">Thuis adres is al ingesteld. Wijzig eerst het huidige thuis adres</div>';
				} else {
					if( $address->edit($validation)) {
						$user->to('/climate-canary/navigate-to.php');
					} else {
						echo '<div class="error sc-card sc-card-supporting">Adres kon niet worden gewijzigd</div>';
					}
				}
			} else {
				echo $form->outputErrors();
			}
		}
?>
	 <form action="" method="post" autocomplete="off">
		 <div class="sc-floating-input">
			 <input type="text" name="name" id="name" value="<?php echo ( !empty( $form->input('name') ) ? $form->input('name') : $data['name'] ); ?>">
			 <label for="name">Naam</label>
		 </div>

		 <select name="category" id="category">
			 <?php
			 foreach( $address->categories() as $row => $field ) {
			 	echo '<option value='.$field['id'].'>'.$field['category'].'</option>';
			 }
			 ?>
		 </select>

		 <button class="sc-raised-button">
			 <i class="material-icons">save</i>
			 Opslaan
		 </button>
	 </form>
<?php
	} else {
		$user->to( '/climate-canary/navigate-to.php' );
	}

	require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/includes/footer.php';
} else {
	$user->to('/climate-canary/navigate-to.php');
}