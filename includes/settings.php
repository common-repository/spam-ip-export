<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class CommentsSPAMIPExporter {
	private $block_spam_comment_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'block_spam_comment_add_plugin_page' ) );
	}

	public function block_spam_comment_add_plugin_page() {
		add_management_page(
			'Block Spam Comments', // page_title
			'Block Spam Comments', // menu_title
			'manage_options', // capability
			'block-spam-comments', // menu_slug
			array( $this, 'block_spam_comment_create_admin_page' ) // function
		);
	}

	public function block_spam_comment_create_admin_page() { ?>
		<div class="wrap">
			<h2>Block Spam Comments by Iweblab</h2>
			<p style="font-size: 15px;">Ciao, questo plugin ti aiuterà a segnalare a noi i commenti spazzatura che arrivano sul tuo sito.<br>
				Non dovrai far altro che contrassegnare questi commenti come spam e al resto penserà il plugin.<br>
				Ogni notte, verranno recuperate le tue segnalazioni e in seguito rimosse per tenere pulito il tuo database.<br>
				I nostri sistemi bloccheranno in modo definitivo l'ip del commento spam. Questo si riperquote su tutta la nostra struttura.
			</p>
			<p style="font-size: 15px;">Per maggiorni informazioni o per assistenza <a target=”_blank” href="https://iweblab.it">iweblab.it</a> oppure <a href="mailto:supporto@iweblab.it">supporto@iweblab.it</a>
			</p>
		</div>
	<?php }

}
if ( is_admin() )
	$block_spam_comment = new CommentsSPAMIPExporter();