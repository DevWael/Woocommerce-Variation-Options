<?php


class WCVO_Variation_Options {
	public function variation_options( $loop, $variation_data, $variation ) {
		$variable_data = get_post_meta( $variation->ID, 'wcvo_variation_storage', true );
		?>
        <div class="wcvo-options-area wcvo-options-area-<?php echo esc_attr( $variation->ID ) ?>">
			<?php
			if ( $variable_data ) {
				foreach ( $variable_data as $variable_datum ) {
					$this->cloneable_content( $variation->ID, $variable_datum['title'], $variable_datum['price'], $variable_datum['min'], $variable_datum['max'] );
				}
			} else {
				$this->cloneable_content( $variation->ID );
			}
			?>
        </div>
        <div class="wcvo-clone-button-<?php echo esc_attr( $variation->ID ) ?>">
            <button class="button button-primary button-large"
                    data-variation-id="<?php echo esc_attr( $variation->ID ) ?>" type="button">
                Add Option
            </button>
        </div>
        <input type="hidden" name="var_ids[]" value="<?php echo esc_attr( $variation->ID ); ?>"/>
		<?php
		$this->clone_engine( $variation->ID );
	}

	public function save_values( $post_id ) {
		//todo check nonce & user capability
		$data = array();
		if ( isset( $_POST['var_ids'] ) && ! empty( $_POST['var_ids'] ) && is_array( $_POST['var_ids'] ) ) {
			$var_ids = $_POST['var_ids'];
			foreach ( $var_ids as $var_id ) {
				if ( isset( $_POST['wcvo_title'][ $var_id ] ) ) {
					$titles = $_POST['wcvo_title'][ $var_id ];
					foreach ( $titles as $key => $title ) {
						$data[] = array(
							'title' => sanitize_text_field( $title ),
							'price' => isset( $_POST['wcvo_price'][ $var_id ][ $key ] ) ? wc_format_decimal( sanitize_text_field( $_POST['wcvo_price'][ $var_id ][ $key ] ) ) : '',
							'min'   => isset( $_POST['wcvo_min'][ $var_id ][ $key ] ) ? sanitize_text_field( $_POST['wcvo_min'][ $var_id ][ $key ] ) : '',
							'max'   => isset( $_POST['wcvo_max'][ $var_id ][ $key ] ) ? sanitize_text_field( $_POST['wcvo_max'][ $var_id ][ $key ] ) : '',
						);
					}
				}
				update_post_meta( $var_id, 'wcvo_variation_storage', $data );
				$data = array();
			}
		}
	}

	private function cloneable_content( $variation_id, $name = '', $price = '', $min = '', $max = '' ) {
		?>
        <div class="wcvo-field">
            <p class="form-row form-row-first">
                <label><?php
	                esc_html_e( 'Option Title', 'wcvo' );
					?></label>
                <input type="text" name="wcvo_title[<?php echo esc_attr( $variation_id ); ?>][]" class=""
                       value="<?php echo esc_attr( $name ) ?>">
            </p>
            <p class="form-row form-row-last">
                <label><?php
					printf( esc_html__( 'Price (%s)', 'wcvo' ), esc_html( get_woocommerce_currency_symbol() ) );
					?></label>
                <input type="number" name="wcvo_price[<?php echo esc_attr( $variation_id ); ?>][]"
                       value="<?php echo esc_attr( $price ) ?>"
                       class="wc_input_price"/>
            </p>

            <p class="form-row form-row-first">
                <label><?php
	                esc_html_e( 'Min Quantity', 'wcvo' );
					?></label>
                <input type="number" name="wcvo_min[<?php echo esc_attr( $variation_id ); ?>][]"
                       value="<?php echo esc_attr( $min ) ?>"
                       class=""/>
            </p>
            <p class="form-row form-row-last">
                <label><?php
	                esc_html_e( 'Max Quantity', 'wcvo' );
					?></label>
                <input type="number" name="wcvo_max[<?php echo esc_attr( $variation_id ); ?>][]" class=""
                       value="<?php echo esc_attr( $max ) ?>"/>
            </p>
        </div>
		<?php
	}

	private function clone_engine( $variation_id ) {
		?>
        <script>
            (function ($) {
                var contentTemplate = '<?php
					ob_start();
					$this->cloneable_content( '{variation_id}' );
					$html = ob_get_clean();
					echo str_replace( array( "\n", "\r" ), '', str_replace( "'", '"', $html ) );
					?>';
                $(document).on('click', '.wcvo-clone-button-<?php echo esc_attr( $variation_id ) ?> button', function (e) {
                    let var_id = $(this).data('variation-id');
                    contentTemplate = contentTemplate.replace(/{variation_id}/g, var_id);
                    $('.wcvo-options-area-<?php echo esc_attr( $variation_id ) ?>').append(contentTemplate);
                });
            })(jQuery);
        </script>
		<?php
	}
}