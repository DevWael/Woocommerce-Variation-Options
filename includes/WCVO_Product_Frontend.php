<?php


class WCVO_Product_Frontend {

	public function load_variation_settings( $variations ) {
		$variations['variation_fields'] = get_post_meta( $variations['variation_id'], 'wcvo_variation_storage', true );

		return $variations;
	}

	public function template() {
		?>
        <div class="wcvo-custom-inputs"></div>
		<?php
	}
}