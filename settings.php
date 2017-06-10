<?php
$title = 'Settings';
require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/includes/header.php';
?>

    <div class="row">
        <div class="col-sm-12 return">
            <a href="/climate-canary/index.php" class="return"><i class="material-icons">arrow_back</i> Terug</a>
        </div>

        <div class="col-sm-12 content">
            <?php
            $form = new Form($db);

            if( $session->exists('settings') ) {
                $settings = $session->get('settings');
            }

            if( $_POST ) {
                $validation = $form->check($_POST, array(
                    'snelheid' => array(
                        'required' => true,
                        'maxLength' => 4,
                        'name' => 'Snelheid'
                    ),
                    'graden' => array(
						'required' => true,
						'maxLength' => 10,
						'name' => 'Temperatuur'
					),
                    'notification-receive' => array(
                        'required' => true,
                        'maxLenght' => 3,
                        'name' => 'Notificatie ontvangen'
                    ),
                    'notification-pause' => array(
                        'name' => 'Notificatie pauze'
                    ),
                    'time' => array(
                        'time' => true,
                        'name' => 'Notificatie tijd'
                    ),
                    'notification-type' => array(
                        'maxLength' => 9,
                        'name' => 'Notificatie type'
                    )
                ));

                if( empty( $form->errors ) ) {
                    $session->set('settings', $validation);

                    if( $session->exists('settings') ) {
                        $user->to('settings.php?message=Settings bijgewerkt');
                    }
                } else {
                    echo $form->outputErrors();
                }
            }
            ?>

            <form action="" method="post">
                <h1>Instellingen</h1>

                <?php
                if( !empty( $_GET['message'] ) ) {
                    $message = $db->sanitize($_GET['message']);
					echo '<div class="success sc-card sc-card-supporting" role="alert">'.$message.'</div>';
                }
                ?>

                <div class="settings-eenheden">
                    <h2>Eenheden</h2>
                    <p>Snelheid:</p>
                    <select name="snelheid" class="sc-select">
                        <?php
                        echo '<option value="'.( $session->exists('settings') ? $session->get('settings')['snelheid'] : 'KM/H').'">'.( $session->exists('settings') ? $session->get('settings')['snelheid'] : 'KM/H').'</option>';
                        ?>
                        <option value="KM/H">KM/H</option>
                        <option value="MPH">MPH</option>
                    </select>

                    <p>Temperatuur:</p>
                    <select name="graden" class="sc-select">
						<?php
						echo '<option value="'.( $session->exists('settings') ? $session->get('settings')['graden'] : 'Celsius').'">'.( $session->exists('settings') ? $session->get('settings')['graden'] : 'Celsius').'</option>';
						?>
                        <option value="Celsius">Celsius</option>
                        <option value="Fahrenheit">Fahrenheit</option>
                    </select>
                </div>

                <hr>

                <div class="settings-thuis">
                    <h2>Thuisadres</h2>
                    <?php
                    $data = $address->data();

                    foreach( $data as $row => $field ) {
                        if( $field['icons_id'] == 1 ) {
                            echo '  <p>'.$field['address'].'</p>
                                    <a href="/climate-canary/address-edit.php?id='.base64_encode( $field['id'] ).'">Wijzig</a>';
                        }
                        if(in_array(1,$data)) {
                            echo 'Voeg een thuisadres toe';
                        }
                    }
                    ?>
                </div>

                <hr>

                <div class="settings-notificaties">
                    <h2>Notificaties</h2>

                    <input type="radio" id="yes" name="notification-receive" value="yes" class="sc-radio" <?php echo ( !empty( $settings ) && $settings['notification-receive'] == 'yes' ? 'checked' : ( isset($settings) && $settings['notification-receive'] == 'no' ? '' : 'checked')); ?>>
                    <label for="yes">Ik wil notificaties ontvangen</label>
                    <br>
                    <input type="radio" id="no" name="notification-receive" value="no" class="sc-radio" <?php echo ( !empty( $settings ) && $settings['notification-receive'] == 'no' ? 'checked' : ''); ?>>
                    <label for="no">Ik wil geen notificaties ontvangen</label>
                    <br />
                    <p>Geluid bij meldingen:</p>
                    <input type="radio" id="standaard" name="notification-type" value="standaard" class="sc-radio" <?php echo ( !empty( $settings ) && $settings['notification-type'] == 'standaard' ? 'checked' : ''); ?> <?php echo ( !empty( $settings ) && $settings['notification-receive'] == 'no' ? 'disabled' : ''); ?>>
                    <label for="standaard">Standaard</label>
                    <br />
                    <input type="radio" id="trillen" name="notification-type" value="trillen" class="sc-radio" <?php echo ( !empty( $settings ) && $settings['notification-type'] == 'trillen' ? 'checked' : ''); ?> <?php echo ( !empty( $settings ) && $settings['notification-receive'] == 'no' ? 'disabled' : ''); ?>>
                    <label for="trillen">Trillen</label>
                    <br />
                    <input type="radio" id="stil" name="notification-type" value="stil" class="sc-radio" <?php echo ( !empty( $settings ) && $settings['notification-type'] == 'stil' ? 'checked' : ''); ?> <?php echo ( !empty( $settings ) && $settings['notification-receive'] == 'no' ? 'disabled' : ''); ?>>
                    <label for="stil">Stil</label>
                    <br>
                    <div class="sc-floating-input">
                        <input type="number" name="notification-pause" <?php echo ( !empty( $settings ) && $settings['notification-receive'] == 'no' ? 'disabled' : ''); ?>>
                        <label for="pause">Stuur een notificatie om de .. minuten</label>
                    </div>
                    <!--<select name="notification-pause" class="sc-select" <?php /*echo ( !empty( $settings ) && $settings['notification-receive'] == 'no' ? 'disabled' : ''); */?>>
						<?php
/*						echo '<option value="'.( $session->exists('settings') ? $session->get('notification-pause')['graden'] : '5 minuten').'">'.( $session->exists('notification-pause') ? $session->get('settings')['graden'] : '5 minuten').'</option>';
						*/?>
                        <option value="5 minuten">5 minuten</option>
                        <option value="15 minuten">15 minuten</option>
                        <option value="30 minuten">30 minuten</option>
                        <option value="1 uur">1 uur</option>
                        <option value="Stuur geen notificaties">Stuur geen notificaties</option>
                    </select>-->
                    <br />
                    <p>Geen meldingen ontvangen tot</p>

                    <div class="sc-floating-input">
                        <input type="text" pattern="[0-2][0-3]:[0-5][0-9]:[0-5][0-9]" name="time" id="time" step="1" value="<?php echo ( !empty( $settings ) ? $settings['time'] : ''); ?>" <?php echo ( !empty( $settings ) && $settings['notification-receive'] == 'no' ? 'disabled' : ''); ?>>
                        <label for="time">Tijd <em><?php echo '(uu:mm:ss)'; ?></em></label>
                    </div>
                    <br />

                </div>
                <hr>

                <button class="sc-raised-button"><i class="material-icons">save</i> Opslaan</button>
            </form>
        </div>
    </div>

<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/includes/footer.php';