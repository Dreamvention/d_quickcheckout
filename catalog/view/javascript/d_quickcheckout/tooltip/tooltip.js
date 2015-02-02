
var dTooltip = {

	init : function() {

		jQuery(document).on('mouseover', '[rel="tooltip"]', function() {

			dTooltip.open(this);
		});

		jQuery(document).on('mouseout', '[rel="tooltip"]', function() {
			dTooltip.close();
		});
	},

	open : function(el) {

		// Create tooltip
		jQuery('body').prepend( jQuery('<div>', { 'class' : 'qc qc-tooltip'})
			.append( jQuery('<div>', { 'class' : 'inner' }))
			.append( jQuery('<span>') )
		);
        
		// Get tooltip
		var tooltip = jQuery('.qc.qc-tooltip');

            if( $( window ).width() < tooltip.outerWidth() * 1.5 )
                tooltip.css( 'max-width', $( window ).width() / 2 );
            else
                tooltip.css( 'max-width', 340 );
 
            var pos_left = jQuery(el).offset().left + ( jQuery(el).outerWidth() / 2 ) - ( tooltip.outerWidth() / 2 ),
                pos_top  = jQuery(el).offset().top - tooltip.outerHeight() - 23;
 
          if( pos_left < 0 )
            {
                pos_left =  jQuery(el).offset().left +  jQuery(el).outerWidth() / 2 - 20;
                tooltip.addClass( 'left' );
            }
            else
                tooltip.removeClass( 'left' );
 
            if( pos_left + tooltip.outerWidth() > $( window ).width() )
            {
                pos_left =  jQuery(el).offset().left - tooltip.outerWidth() +  jQuery(el).outerWidth() / 2 + 20;
                tooltip.addClass( 'right' );
            }
            else
                tooltip.removeClass( 'right' );
 
            if( pos_top < 0 )
            {
                var pos_top  =  jQuery(el).offset().top +  jQuery(el).outerHeight();
                tooltip.addClass( 'top' );
            }
            else
                tooltip.removeClass( 'top' );

			 tooltip.css( { left: pos_left, top: pos_top } )
                   .animate( { top: '+=0', opacity: 1 }, 50 );

		// Set tooltip text
		tooltip.find('.inner').html( jQuery(el).attr('data-help'));
	},

	close : function() {
		jQuery('.qc.qc-tooltip').remove();
		
	}
};
dTooltip.init();

var dpopup = {

    init : function() {

        jQuery(document).on('mouseover', '[rel="qc-popup"]', function() {

            dpopup.open(this);
        });

        jQuery(document).on('mouseout', '[rel="qc-popup"]', function() {
            dpopup.close();
        });
    },

    open : function(el) {

        // Create popup
        jQuery('body').prepend( jQuery('<div>', { 'class' : 'qc qc-popup'})
            .append( jQuery('<div>', { 'class' : 'inner' }))
            .append( jQuery('<span>') )
        );
        
        // Get popup
        var popup = jQuery('.qc-popup');

            if( $( window ).width() < popup.outerWidth() * 1.5 )
                popup.css( 'max-width', $( window ).width() / 2 );
            else
                popup.css( 'max-width', 340 );
 
            var pos_left = jQuery(el).offset().left + ( jQuery(el).outerWidth() / 2 ) - ( popup.outerWidth() / 2 ),
                pos_top  = jQuery(el).offset().top - popup.outerHeight() - 38;
 
          if( pos_left < 0 )
            {
                pos_left =  jQuery(el).offset().left +  jQuery(el).outerWidth() / 2 - 20;
                popup.addClass( 'left' );
            }
            else
                popup.removeClass( 'left' );
 
            if( pos_left + popup.outerWidth() > $( window ).width() )
            {
                pos_left =  jQuery(el).offset().left - popup.outerWidth() +  jQuery(el).outerWidth() / 2 + 20;
                popup.addClass( 'right' );
            }
            else
                popup.removeClass( 'right' );
 
            if( pos_top < 0 )
            {
                var pos_top  =  jQuery(el).offset().top +  jQuery(el).outerHeight();
                popup.addClass( 'top' );
            }
            else
                popup.removeClass( 'top' );

             popup.css( { left: pos_left, top: pos_top } )
                   .animate( { top: '+=0', opacity: 1 }, 50 );

        // Set popup text
        popup.find('.inner').html( jQuery(el).attr('data-help'));
    },

    close : function() {
        jQuery('.qc.qc-popup').remove();
        
    }
};
dpopup.init();