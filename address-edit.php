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
				'icons_id' => array(
					'required' => true,
					'numeric' => true,
					'unique' => 'address',
					'name' => 'Categorie'
				)
			), $id);

			if( empty( $form->errors ) ) {
                if( $address->edit($validation)) {
                    $user->to('/climate-canary/navigate-to.php');
                } else {
                    echo '<div class="error sc-card sc-card-supporting">Adres kon niet worden gewijzigd</div>';
                }
			} else {
				echo $form->outputErrors();
			}
		}
?>
     <div class="row">
         <div class="col col-xs-12">
             <form action="" method="post" autocomplete="off">
                 <h2><?php echo $db->detail('address', 'address', 'id', $id); ?></h2>
                 <div class="sc-floating-input">
                     <input type="text" name="name" id="name" value="<?php echo ( !empty( $form->input('name') ) ? $form->input('name') : $data['name'] ); ?>">
                     <label for="name">Naam</label>
                 </div>

                 <select name="icons_id" id="category" class="sc-select">
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
         </div>
     </div>
<?php
	} else {
		$user->to( '/climate-canary/navigate-to.php' );
	}

	require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/includes/footer.php';
} else {
	$user->to('/climate-canary/navigate-to.php');
}