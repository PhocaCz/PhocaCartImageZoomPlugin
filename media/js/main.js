/*
 * jQuery Phoca Image Zoom
 * https://www.phoca.cz
 *
 * Copyright (C) 2016 Jan Pavelka www.phoca.cz
 *
 * License http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * Drift.min.js licensed under BSD 2-Clause "Simplified" License
 * see: media/plg_pcv_image_zoom/js/drift/LICENSE.md
 * Luminous.min.js licensed under BSD 2-Clause "Simplified" License
 * see: media/plg_pcv_image_zoom/js/luminuous/Luminous.min.js
 */

jQuery(document).ready(function(){
    
    var phImageZoomTriggerGallery   = document.querySelectorAll(".phImageGalleryHref");// Luminous Gallery (popup all images except active copy)
    var phImageZoomTrigger          = document.querySelector(".phImageFullHref");// Drift (zoom effect)

    var phParams 	        = Joomla.getOptions('phParamsPlgImageZoom');
    var displayNavigation   = phParams['displayNavigation'];
    

    new Drift(phImageZoomTrigger, {
    paneContainer: document.querySelector(".phItemPricePanel"),
        inlinePane: 767,
        inlineOffsetY: -10,
        containInline: true,
        sourceAttribute: "href",
        hoverBoundingBox: true
    });

    if (displayNavigation == 1) {
        // Gallery with navigation
        var gallery = new LuminousGallery(phImageZoomTriggerGallery, {}, {showCloseButton: true});
    } else {
        // No gallery - only full image as popup
        var gallery = new Luminous(phImageZoomTrigger);
    }
    

    jQuery(".phImageAdditional").click(function(e){
	    e.preventDefault();
        var srcL = jQuery(this).attr("data-image-large");
        var srcO = jQuery(this).attr("data-image-original");

        if (srcO === undefined) {
            srcO = srcL;
        }

        // Set current image as active
        jQuery(".phImageAdditional").removeClass("active");// Remove active from all
        jQuery(this).addClass("active");// Add it to current active only

        if (displayNavigation == 1) {
            // Full image and active image will be displayed twice in slideshow
            // We can prevent from displaying active image in slideshow by server side
            // But when active image will be changed, we need to rebuild image and a classes
            // and we need to destroy old gallery and run new one with updated images for the slideshow
            jQuery(".phImageAdditionalHref").removeClass("phImageGalleryHref");// Remove image gallery class from all images - from a tag
            jQuery(".phImageAdditionalHref").addClass("phImageGalleryHref");// Add it to all (no duplicity = remove from all - add to all)
            jQuery(".phImageAdditional").removeClass("phImageGallery");// The same for images inside a - from image tag
            jQuery(".phImageAdditional").addClass("phImageGallery");
            jQuery(this).removeClass("phImageGallery");// Remove the image gallery class from active only - from image tag
            jQuery(this).parent().removeClass("phImageGalleryHref");// Remove the image gallery class from a tag

            gallery.destroy();// Destroy old gallery to be ready for newly added classes
            phImageZoomTriggerGallery   = document.querySelectorAll(".phImageGalleryHref");// Run the gallery newly because of changed classes
        
            gallery = new LuminousGallery(phImageZoomTriggerGallery, {}, {showCloseButton: true});
        }

        jQuery(".phImageFull").attr("src", srcL);
        jQuery(".phImageFullHref").attr("href", srcO);

        // There are two events: 1) switch image 2) modal popup - stop the second event on small images
        return false;// Stop the LuminousGallery event - displaying modal. Start modal only on large image.
    });
});