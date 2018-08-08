<?php
/**
 * Dummy module return as json_encode
 * http://www.aa-team.com
 * =======================
 *
 * @author		Andrei Dinca, AA-Team
 * @version		0.1 - in development mode
 */

global $WooZone;

function WooZone_Bitly_auth_html( $istab = '', $is_subtab='' ) {

	$html = array();

	$login = get_option( 'WooZone_bitly_login', '' );

	$html[] = '
	<div class="panel-body WooZone-panel-body WooZone-form-row   ">
		<label for="redirect_url" class="WooZone-form-label">AUTH</label>
		<div class="WooZone-form-item">
	';
	if ( '' != $login ) {
		$html[] = '
				<p>Congratulations, you are authorized with bitly login <strong>' . $login . '</strong>.</p>
				<p>Click <a class="WooZone-form-button-small WooZone-form-button-primary" id="WooZone-bitly-auth" href="https://bitly.com/oauth/authorize">here</a> to RE-authorize your website using your bitly account!</p>
		';
	}
	else {
		$html[] = '
				<p>Click <a class="WooZone-form-button-small WooZone-form-button-primary" id="WooZone-bitly-auth" href="https://bitly.com/oauth/authorize">here</a> to authorize your website using your bitly account!</p>
		';
	}
	$html[] = '
		</div>
	</div>
	';

	$html[] = '
		<script>
		//jQuery("#client_id").trigger("keyup");
		(function ($) {

			$("document").ready(function() {
			
				$("body").on("click", "#WooZone-bitly-auth", bitly_auth);
			});

			function bitly_popup( url ) {
				var w = window.open(url, "bitlyauth", "width=800, height=600, scrollbars=no");
				return w;
			}

			function bitly_auth(e) {
				e.preventDefault();

				var that = $(this),
					href = that.attr("href"),
					client_id = $("#client_id").val(),
					redirect_url = $("#redirect_url").val(),
					new_url = href + "?client_id=" + client_id + "&redirect_uri=" + redirect_url;

				//window.location = new_url;
				return bitly_popup( new_url);
				}

		})(jQuery);
		</script>
	';

	$html = implode(PHP_EOL, $html);
	return $html;
}

echo json_encode(array(
	$tryed_module['db_alias'] => array(
		
		/* define the form_sizes  box */
		'bitly' => array(
			'title' => 'Amazon settings',
			'icon' => '{plugin_folder_uri}images/amazon.png',
			'size' => 'grid_4', // grid_1|grid_2|grid_3|grid_4
			'header' => true, // true|false
			'toggler' => false, // true|false
			'buttons' => true, // true|false
			'style' => 'panel', // panel|panel-widget
			
			// create the box elements array
			'elements' => array(

				'headeline' => array(
					'type' => 'html',
					'html' => '
						<div class="WooZone-bitly-notice">
							<h4>Getting Started</h4>
							<p>If you\'re looking to create Bitlinks you need to generate API oAuth token <a target="_blank" href="https://bitly.com/a/oauth_apps">here</a>.</p>
						</div>
						'
				),

				'client_id' => array(
					'type' => 'text',
					'std' => '',
					'size' => 'large',
					'force_width' => '350',
					'title' => 'CLIENT ID',
					'desc' => 'Expected format: <code>cece08e89c7f2d0849b21ef444a8b42aa7d8bea4</code>',
				),

				'client_secret' => array(
					'type' => 'text',
					'std' => '',
					'size' => 'large',
					'force_width' => '350',
					'title' => 'CLIENT SECRET',
					'desc' => 'Expected format: <code>e5cf514226dc0d222fa6703e44438c349f920c90</code>',
				),

				'redirect_url' => array(
					'type' => 'text_readonly',
					'std' => admin_url('admin-ajax.php') . '?action=WooZoneBitlyAuth',
					'size' => 'large',
					'title' => 'Redirect URL',
					'desc' => '',
				),

				'auth_btn' => array(
					'type' => 'html',
					'html' => WooZone_Bitly_auth_html()
				)
			)
		)
	)
));