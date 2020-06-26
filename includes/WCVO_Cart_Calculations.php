<?php


class WCVO_Cart_Calculations {

	public function add_cart_item_data( $cart_item_data, $product_id, $variation_id ) {
		if ( $variation_id ) {
			$variation_options = $this->get_variation_options( $variation_id );
			$option_price      = $total_option_price = '';
			if ( isset( $_POST['wcvo-option'] ) && ! empty( $_POST['wcvo-option'] ) ) {
				if ( $variation_options ) {
					foreach ( $variation_options as $variation_option ) {
						if ( $variation_option['title'] == $_POST['wcvo-option'] ) {
							$option_price = sanitize_text_field( $variation_option['price'] );
						}
					}
				}
			}
			if ( isset( $_POST['wcvo-quantity'] ) && is_numeric( $_POST['wcvo-quantity'] ) ) {
				//todo verify that quantity is not exceeding the max quantity or less that the minimum quantity of the variation option
				$total_option_price = $option_price * sanitize_text_field( $_POST['wcvo-quantity'] );
			}
			//WCVO_Helpers::log( $option_price );
			if ( $total_option_price ) {
				$cart_item_data['wcvo-option-pure-name']         = sanitize_text_field( $_POST['wcvo-option'] );
				$cart_item_data['wcvo-option-pure-price']        = $option_price;
				$cart_item_data['wcvo-option-quantity']          = sanitize_text_field( $_POST['wcvo-quantity'] );
				$cart_item_data['wcvo-option-price']             = $total_option_price;
				$cart_item_data['wcvo-product-and-option-price'] = $this->get_variation_price( $variation_id ) + $total_option_price;
			}
		}

		return $cart_item_data;
	}

	public function calculate_cart_total( $cart_obj ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return; //disable if we are in dashboard
		}
		foreach ( $cart_obj->get_cart() as $key => $value ) {
			if ( isset( $value['wcvo-product-and-option-price'] ) ) {
				$price = $value['wcvo-product-and-option-price'];

				$value['data']->set_price( ( $price ) );
			}
		}
	}

	public function display_cart_data( $item_data, $cart_item_data ) {
		if ( isset( $cart_item_data['wcvo-option-pure-name'] ) ) {
			$item_data[] = array(
				'key'   => __( 'Extras', 'wcvo' ),
				'value' => wc_clean( $cart_item_data['wcvo-option-pure-name'] )
			);
		}

		if ( isset( $cart_item_data['wcvo-option-quantity'] ) ) {
			$item_data[] = array(
				'key'   => __( 'Quantity', 'wcvo' ),
				'value' => wc_clean( $cart_item_data['wcvo-option-quantity'] )
			);
		}

		if ( isset( $cart_item_data['wcvo-option-pure-price'] ) ) {
			$item_data[] = array(
				'key'   => __( 'Price', 'wcvo' ),
				'value' => wc_clean( $cart_item_data['wcvo-option-pure-price'] )
			);
		}

		if ( isset( $cart_item_data['wcvo-option-price'] ) ) {
			$item_data[] = array(
				'key'   => __( 'Total Price', 'wcvo' ),
				'value' => wc_clean( $cart_item_data['wcvo-option-price'] )
			);
		}

		return $item_data;
	}

	/**
	 * @param $variation_id
	 *
	 * @return float|int
	 */
	private function get_variation_price( $variation_id ) {
		$variable_product = wc_get_product( $variation_id );

		return $variable_product->get_price();
	}

	/**
	 * @param $variation_id
	 *
	 * @return Array|False variation options or false if empty
	 */
	private function get_variation_options( $variation_id ) {
		return get_post_meta( $variation_id, 'wcvo_variation_storage', true );
	}
}