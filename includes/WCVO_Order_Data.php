<?php

class WCVO_Order_Data {
	/**
	 * display custom options data on order details
	 */
	public function order_data( $item, $cart_item_key, $values, $order ) {
		if ( isset( $values['wcvo-option-pure-name'] ) ) {
			$item->add_meta_data(
				__( 'Extras', 'wcvo' ),
				$values['wcvo-option-pure-name'],
				true
			);
		}
		if ( isset( $values['wcvo-option-quantity'] ) ) {
			$item->add_meta_data(
				__( 'Quantity', 'wcvo' ),
				$values['wcvo-option-quantity'],
				true
			);
		}
		if ( isset( $values['wcvo-option-pure-price'] ) ) {
			$item->add_meta_data(
				__( 'Price', 'wcvo' ),
				$values['wcvo-option-pure-price'],
				true
			);
		}
		if ( isset( $values['wcvo-option-price'] ) ) {
			$item->add_meta_data(
				__( 'Extras Total Price', 'wcvo' ),
				$values['wcvo-option-price'],
				true
			);
		}
	}

	/**
	 * display custom options data on order email
	 */
	public function order_email_data( $product_name, $item ) {
		if ( isset( $item['wcvo-option-pure-name'] ) ) {
			$product_name .= sprintf(
				'<ul><li>%s: %s</li></ul>',
				__( 'Extras', 'wcvo' ),
				esc_html( $item['wcvo-option-pure-name'] )
			);
		}

		if ( isset( $item['wcvo-option-quantity'] ) ) {
			$product_name .= sprintf(
				'<ul><li>%s: %s</li></ul>',
				__( 'Quantity', 'wcvo' ),
				esc_html( $item['wcvo-option-quantity'] )
			);
		}

		if ( isset( $item['wcvo-option-pure-price'] ) ) {
			$product_name .= sprintf(
				'<ul><li>%s: %s</li></ul>',
				__( 'Price', 'wcvo' ),
				esc_html( $item['wcvo-option-pure-price'] )
			);
		}

		if ( isset( $item['wcvo-option-price'] ) ) {
			$product_name .= sprintf(
				'<ul><li>%s: %s</li></ul>',
				__( 'Extras Total Price', 'wcvo' ),
				esc_html( $item['wcvo-option-price'] )
			);
		}

		return $product_name;
	}

}