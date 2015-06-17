<?php

class wpSearchShortcode{

	public function __construct(){

		add_shortcode('wp_live_search',		array($this,'shortcode'));
	}

	public function shortcode( $atts, $content = null ) {

		$defaults = array(
			'type'	 		=> 'posts', // 'posts', 'pages', 'books'
			'multi'			=> false,
			'placeholder'	=> __('Search...','wp-live-search'),
			'results' 		=> __('entries found','wp-live-search'),
			'target'		=> ''
		);
		$atts = shortcode_atts( $defaults, $atts );

		$results_text = $atts['results'] ? $atts['results'] : false;
		$target       = $atts['target'] ? sprintf( 'data-target=%s', trim( $atts['target'] ) ) : false;

		if ( true == $atts['multi'] ) {

			$chunks = self::return_chunks( $atts['type'] );

			$type = $atts['type'] ? sprintf('posts?%s', $chunks ) : false;

		} else {

			$type = 'posts' == $atts['type'] || 'pages' == $atts['type'] ? sprintf('%s?', trim( $atts['type'] ) ) : sprintf('posts?type=%s&', trim( $atts['type'] ) );
		}

		ob_start();

		?>
		<div id="wpls" class="wpls" itemprop="potentialAction" itemscope itemtype="http://schema.org/SearchAction">

			<div class="wpls--results-wrap">
				<span id="wpls--results"></span>
				<span><?php echo esc_html( $results_text );?></span>
			</div>

			<div id="wpls--input-wrap">
				<input itemprop="query-input" type="text" data-object-type="<?php echo esc_attr( $type );?>" id="wpls--input" placeholder="<?php echo esc_attr( $atts['placeholder'] );?>">
				<div id="wpls--loading" class="wpls--loading"><div class="wpls--loader"></div></div>
			</div>

			<ul itemprop="target" id="wpls--post-list" <?php echo esc_attr( $target );?>></ul>

		</div>

		<?php

		return ob_get_clean();
	}

	/**
	*	Return a search filter paramater based on the number of types a user passes
	*
	*	@since 0.7
	*	@access private
	*/
	private static function return_chunks( $types ){

		if ( empty( $types ) ) {
			return;
		}

		$out = '';

		$types = explode(',', $types );

		if ( $types ):

			foreach ( (array) $types as $type ) {
				$out .= sprintf('type[]=%s&', $type);
			}

		endif;

		return $out;

	}

}

new wpSearchShortcode();