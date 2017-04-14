		</main>

		<script src="/climate-canary/js/vendor.min.js"></script>
		<script src="/climate-canary/js/app.min.js"></script>
		<script src="/climate-canary/js/bootstrap.min.js"></script>
        <script src="/climate-canary/js/senz2.js"></script>
        <?php
        if( !in_array( $file, array( 'navigation-confirm', 'navigate' ) ) ) {
			echo '  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCRSvUXIT1W0exR_AJavFL7Ag74WbpYN5g&libraries=places"></script>
                    <script src="/climate-canary/js/navigate-to.js"></script>';
		}
        ?>
	</body>
</html>