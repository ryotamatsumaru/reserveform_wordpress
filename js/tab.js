function GethashID (hashIDName){
  if(hashIDName){
    jQuery('.tab li').find('a').each(function() 
    { var idName = jQuery(this).attr('href'); 
      if(idName == hashIDName) {
        var parentElm = jQuery(this).parent();
        jQuery('.tab li').removeClass("active");
	jQuery(parentElm).addClass("active"); 
	jQuery(".area").removeClass("is-active"); 
	jQuery(hashIDName).addClass("is-active"); 
	}
    });
  }
}

jQuery('.tab a').on('click', function() {
  var idName = jQuery(this).attr('href');
  GethashID (idName);
  return false; 
});

jQuery(window).on('load', function () {
  jQuery('.tab li:first-of-type').addClass("active"); 
  jQuery('.area:first-of-type').addClass("is-active");
  var hashName = location.hash;
  GethashID (hashName);
});
