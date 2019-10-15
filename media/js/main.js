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
    
    var phImageZoomTrigger = document.querySelector(".phImageFullHref");

    new Drift(phImageZoomTrigger, {
    paneContainer: document.querySelector(".phItemPricePanel"),
        inlinePane: 767,
        inlineOffsetY: -10,
        containInline: true,
        sourceAttribute: "href",
        hoverBoundingBox: true
    });

    new Luminous(phImageZoomTrigger);

    jQuery(".phImageAdditional").click(function(e){
	    e.preventDefault();
        var srcL = jQuery(this).attr("data-image-large");
        var srcO = jQuery(this).attr("data-image-original");

        if (srcO === undefined) {
            srcO = srcL;
        }

        jQuery(".phImageAdditional").removeClass("active");
        jQuery(this).addClass("active");

        jQuery(".phImageFull").attr("src", srcL);
        jQuery(".phImageFullHref").attr("href", srcO);
    });
});