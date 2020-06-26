(function ($) {
    'use strict';

    /**
     * All of the code for your public-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    if ($('div.product').hasClass('product-type-variable')) {
        let allVariationsData = $('.variations_form').data('product_variations');
        let html = '';
        $('input.variation_id').on('change', function () {
            if ('' != $('input.variation_id').val()) {
                let var_id = $('input.variation_id').val();
                $.each(allVariationsData, function (i, v) {
                    if (var_id == v.variation_id) {
                        if (!$.isEmptyObject(v.variation_fields)) {
                            $.each(v.variation_fields, function (i, v) {
                                html +=
                                    '<div class="wcvo-variation-option">' +
                                    '<input type="radio" class="wcvo-option" name="wcvo-option" value="' + v.title + '">' +
                                    '<span class="title"><b>' + v.title + '</b> - ' + currency.currency_symbol + v.price + '</span>' +
                                    '<input type="number" class="wcvo-quantity" name="wcvo-quantity" min="' + v.min + '" max="' + v.max + '" disabled>' +
                                    '</div>';
                            });
                        }
                        $('.wcvo-custom-inputs').html(html);
                        html = '';//empty html variable for other selections
                    }
                });
            } else {
                $('.wcvo-custom-inputs').html(''); //empty div if user decided not to select
            }
        });

        $(document).on('change', '.wcvo-variation-option .wcvo-option', function (e) {
            $('.wcvo-quantity').prop("disabled", true);
            $(this).parent().find('.wcvo-quantity').prop("disabled", false);
        });
    }

})(jQuery);