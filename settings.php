<?php
$title = 'Home';
require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/includes/header.php';
?>

    <div class="row">
        <div class="col col-xs-12">
            <h1>Instellingen</h1>
            <div class="settings-eenheden">
                <h2>Eenheden</h2>
                <p>Snelheid:</p>
                <select name="snelheid">
                    <option value="KM/H">KM/H</option>
                    <option value="MPH">MPH</option>
                </select>
                <br />
                <p>Temperatuur:</p>
                <select name="graden">
                    <option value="Celsius">Celsius</option>
                    <option value="Fahrenheit">Fahrenheit</option>
                </select>
            </div>
            <hr>
            <div class="settings-thuis">
                <h2>Thuisadres</h2>
                <p>Grote Beer 2, Groningen</p>
                <a href="#">Wijzig</a>
            </div>
            <hr>
            <div class="settings-notificaties">
                <h2>Notificaties</h2>
                <form>
                    <input type="radio" name="accepteren" value="ontvangen" checked> Ik wil notificaties ontvangen<br>
                    <input type="radio" name="weigeren" value="niet ontvangen"> Ik wil geen notificaties ontvangen<br> <!-- Als deze optie wordt gekozen kunnen de overige opties worden bevroren -->
                </form>

                <p>Stuur een notificatie om de</p>
                <select name="notificaties">
                    <option value="5 minuten">5 minuten</option>
                    <option value="15 minuten">15 minuten</option>
                    <option value="30 minuten">30 minuten</option>
                    <option value="1 uur">1 uur</option>
                    <option value="Geen">Stuur geen notificaties</option>
                </select>

                <p>Geen meldingen ontvangen tot</p>
                <form>
                    <input type="text" name="hour">:<input type="text" name="minute">
                </form>

                <p>Geluid bij meldingen:</p>
                <form>
                    <input type="radio" name="standaard" value="standaard" checked> Standaard<br>
                    <input type="radio" name="trillen" value="trillen"> Trillen<br>
                    <input type="radio" name="stil" value="stil"> Stil<br>
                </form>
            </div>
            <hr>
            <button type="button">Opslaan</button>
        </div>
    </div>

<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/includes/footer.php';