		</main>

		<script src="/climate-canary/js/vendor.min.js"></script>
		<script src="/climate-canary/js/app.min.js"></script>
		<script src="/climate-canary/js/bootstrap.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCRSvUXIT1W0exR_AJavFL7Ag74WbpYN5g&libraries=places"></script>
        <?php
        if( $file == '/climate-canary/navigate-to' ) {
            echo '<script src="/climate-canary/js/navigate-to.js"></script>';
        }
        ?>
	</body>
</html>