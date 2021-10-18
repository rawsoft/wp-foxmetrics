(function( $ ) {
	'use strict';

	/**
	 * All of the code for your woocommerce support JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 */

    /* When remove product from cart */
    jQuery( document ).on( 'click', '.woocommerce-cart-form .product-remove > a', function(e) {

        var product_id = jQuery(this).attr('data-product_id');
        jQuery.ajax({
            type: 'POST',
            url: FA_WC_Support_Script.ajax_url,
            data: {
                'action': 'foxmetrics_tracking_cart_remove_item',
                'product_id': product_id,
            }, success: function (result) {
                var json_data = JSON.parse(result);
                if ( json_data.success ) {
                	if ( json_data.event_script ) {
                		jQuery( 'body' ).append( json_data.event_script );
                	}
                }
            },
            error: function () {
            }
        });
    });

    /* When product was add into cart from listing page */
    jQuery( document ).on( 'click', 'ul.products li.product a.add_to_cart_button', function(e) {

        var product_id = jQuery(this).attr('data-product_id');
        var quantity = jQuery(this).attr('data-quantity');
        jQuery.ajax({
            type: 'POST',
            url: FA_WC_Support_Script.ajax_url,
            data: {
                'action': 'foxmetrics_tracking_cart_add_item',
                'product_id': product_id,
                'quantity': quantity,
            }, success: function (result) {
                var json_data = JSON.parse(result);
                if ( json_data.success ) {
                    if ( json_data.event_script ) {
                        jQuery( 'body' ).append( json_data.event_script );
                    }
                }
            },
            error: function () {
            }
        });
    });

    /* When product was add into cart from product single page */
    jQuery( document ).on( 'click', '.single_add_to_cart_button', function(e) {
        
        if ( jQuery('.single-product div.product form.cart .quantity .qty').val() && jQuery('.single-product div.product form.cart .quantity .qty').val().length ) {

            var product_id = FA_WC_Support_Script.product_id;
            var product_name = FA_WC_Support_Script.product_name;
            var product_category_name = FA_WC_Support_Script.product_category_name;
            var product_price = FA_WC_Support_Script.product_price;
            var product_quantity = jQuery('.single-product div.product form.cart .quantity .qty').val();
            /* Prepare the script */

            _fxm.events.push(['_fxm.ecommerce.addcartitem', product_id, product_name, product_category_name, product_quantity, product_price]);
            
        }
    });

    /* When product was add into cart from product single page */
    jQuery( document ).on( 'click', '.single-product form#commentform .form-submit #submit', function(e) {

        if ( jQuery(this).closest('form#commentform').find('#comment_post_ID').length ) {

            var product_id = FA_WC_Support_Script.product_id;
            var product_name = FA_WC_Support_Script.product_name;
            var product_category_name = FA_WC_Support_Script.product_category_name;
            var comment_rating = '';

            /* Check Comment Text Validation */
            if( (jQuery(this).closest('form#commentform').find('#comment').length) && (jQuery(this).closest('form#commentform').find('#comment').val().length) ) {
                /* Check Comment Rating Validation */
                if( (jQuery(this).closest('form#commentform').find('#rating').length) && (jQuery(this).closest('form#commentform').find('#rating').val().length) ) {

                    comment_rating = jQuery(this).closest('form#commentform').find('#rating').val();
                    /* Prepare the script */
                    _fxm.events.push(['_fxm.ecommerce.productreview', product_id, product_name, product_category_name, comment_rating]);
                }
            }
        }
    });

    /** when product updated into cart page. */
    $(document).on("updated_wc_div", function(){
        /* $(".cart_item .product-remove > a").each(function(ind, ele){ */
            /* var product_id = $(ele).attr("data-product_id"); */
            
            jQuery.ajax({
                type: 'POST',
                url: FA_WC_Support_Script.ajax_url,
                data: {
                    'action': 'foxmetrics_tracking_update_cart',
                    /* 'product_id': product_id, */
                }, success: function (result) {
                    var json_data = JSON.parse(result);
                    if ( json_data.success ) {
                        if ( json_data.event_script ) {
                            jQuery( 'body' ).append( json_data.event_script );
                        }
                    }
                },
                error: function () {
                }
            });
        /* }); */
    });


})( jQuery );
