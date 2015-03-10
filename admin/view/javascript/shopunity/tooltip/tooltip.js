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
		jQuery('body').prepend( jQuery('<div>', { 'class' : 'tooltip'})
			.append( jQuery('<div>', { 'class' : 'inner' }))
			.append( jQuery('<span>') )
		);
        
		// Get tooltip
		var tooltip = jQuery('.tooltip');
		// Set tooltip text
		tooltip.find('.inner').text( jQuery(el).attr('data-help'));

            if( $( window ).width() < tooltip.outerWidth() * 1.5 )
                tooltip.css( 'max-width', $( window ).width() / 2 );
            else
                tooltip.css( 'max-width', 340 );
 
            var pos_left = jQuery(el).offset().left + ( jQuery(el).outerWidth() / 2 ) - ( tooltip.outerWidth() / 2 ),
                pos_top  = jQuery(el).offset().top - tooltip.outerHeight() - 10;

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

		
	},

	close : function() {
		jQuery('.tooltip').remove();
		
	}
};
dTooltip.init();