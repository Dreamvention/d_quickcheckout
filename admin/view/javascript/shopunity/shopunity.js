// JavaScript Document
$(document).ready(function(){
					
	if ($('#header > #menu').length == 0){
       $('#header').prepend('<div id="menu"></div>')	
    }
					   
	$('#header > #menu').prepend('<a id="logo" href="http://www.opencart.com/index.php?route=extension/extension&filter_username=Dreamvention" target="_blank"></a>');
	$('#header > #menu').wrapInner('<div class="layout"><div class="wrap"></div></div>');

	$('#content > .breadcrumb').wrapInner('<div class="layout"><div class="wrap"></div></div>');
	$('#content .box .heading').wrapInner('<div class="layout"><div class="wrap"></div></div>');
	$('#content .content').wrapInner('<div class="layout"><div class="wrap"></div></div>');
	$('#container #content .box .content .layout ').append('<div class="footer-push"></div>');
	$('#footer').html('© 2013 All rights reserved. Developed by <a href="http://www.opencart.com/index.php?route=extension/extension&filter_username=Dreamvention" target="_blank">Dreamvention</a>');
	$('#footer').appendTo('#container #content .box  .content ')
	  
	//Vtabs
	
	$('.vtabs > a, .vtabs > span').wrap('<li></li>');
	$('.vtabs').wrapInner('<ul></ul>');
		//$('#container #content.login .box .content .layout .wrap').prepend('<a id="logo" href="http://www.opencart.com/index.php?route=extension/extension&filter_username=Dreamvention" target="_blank"></a>');
	
	$("input:checkbox, input:radio").uniform();
	$('#content').fadeIn(1000);

	$('.tab-trigger').click(function(){
		$('.vtabs a[href="'+$(this).attr('href')+'"], .htabs a[href="'+$(this).attr('href')+'"]').click();
		return false
	})
	
	$('form thead input[type="checkbox"]').click(function(){	
		$.uniform.update("input:checkbox, input:radio");
	})
})

function saveAndStay(){
    $.ajax( {
      type: "POST",
      url: $('#form').attr( 'action' ) + '&save',
      data: $('#form').serialize(),
	  beforeSend: function() {
		$('#form').fadeTo('slow', 0.5);
		},
	  complete: function() {
		$('#form').fadeTo('slow', 1);		
		},
      success: function( response ) {
        console.log( response );
      }
    } );	
}

function versionCheck(rout, placeholder, token){
	$.ajax( {
      type: "POST",
      url: 'index.php?route=' + rout + '/version_check&token=' + token,
	  dataType: 'json',
	  beforeSend: function() {
		$('#form').fadeTo('slow', 0.5);
		},
	  complete: function() {
		$('#form').fadeTo('slow', 1);		
		},
      success: function( json ) {
        console.log( json );
		if(json['error']){
			$(placeholder).html('<div class="warning">' + json['error'] + '</div>')
		}
		if(json['attention']){
			$html = '';
			if(json['update']){
				 $.each(json['update'] , function(k, v) {
						$html += '<div>Version: ' +k+ '</div><div>'+ v +'</div>';
				 });
			}
			 $(placeholder).html('<div class="attention">' + json['attention'] + $html + '</div>')
		}
		if(json['success']){
			$(placeholder).html('<div class="success">' + json['success'] + '</div>')
		}	
      }
	})
}