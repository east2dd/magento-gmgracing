/*
 * refine vehicle script
 */
function init_vehicle_search(){
  jQuery('.link-refine-vehicle').on("click", function(){
        jQuery('#form-refine-vehicle').toggle();
    });
    
    jQuery('.make-selector').on('change', function(){
        jQuery('.model-selector').addClass('hidden');
        jQuery('#make-' + jQuery(this).val() + '-models').removeClass('hidden');
    });
    
    jQuery('#form-refine-vehicle form').on('submit', function(e){
        e.preventDefault();
        
        var make = jQuery('.make-selector').val();
        var model = jQuery('#make-' + make + '-models').val();
        
        var term = "";
        if(make!="")
            term=make;
        if(model!= "")
            term=model;
        if(term)
        {
            window.location = "/?project-model="+term;
        }else
        {
            return;
        }
            
    });
}

/*
 * partner logos
 */
function init_partners(){
  jQuery('#our-partners a img').hover(
      function()
      {
        var src = jQuery(this).attr('src');
        var src1 = jQuery(this).attr('src').replace(".png", "") + "-on.png";
        jQuery(this).attr('src', src1);
      }, function(){
        var src = jQuery(this).attr('src');
        var src1 = jQuery(this).attr('src').replace("-on.png", "") + ".png";
        jQuery(this).attr('src', src1);
      }
  );
}

jQuery(function($){
  init_vehicle_search();
  init_partners();
});

//# sourceMappingURL=cms.js.map