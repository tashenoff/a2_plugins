;(function($) {

$( document ).ready(function() {
   
	
            jQuery('.wpgs img').removeAttr('srcset');
    if (wpgs_var.wcaption == 'true') {var wpgs_wcaption = 'title';} else {var wpgs_wcaption = ' ';}

   // var jarrow = wpgsData.warrows;
 // alert(wpgsData.warrows)
 $('.venobox').venobox({
 	 framewidth: wpgs_var.wLightboxframewidth+'px',  
 	 titleattr:wpgs_wcaption ,
 	 numerationPosition: 'bottom',
 	 numeratio:'true',
 	 titlePosition:'bottom'
 	 //
 });  // lightbox

 	

   
    // 
    	$('.woocommerce-product-gallery__image img').load(function() {

	    var imageObj = $('.woocommerce-product-gallery__image img');


	    if (!(imageObj.width() == 1 && imageObj.height() == 1)) {
	    	//alert(imageObj.attr('src'));
	    //	$('.attachment-shop_thumbnail').attr('src', imageObj.attr('src'));
	    	$('.attachment-shop_thumbnail').trigger('click');
	    	
	   			
	    }
	});


});


     


})( jQuery );