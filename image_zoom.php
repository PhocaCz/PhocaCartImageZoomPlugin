<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die;
jimport( 'joomla.plugin.plugin' );
jimport( 'joomla.filesystem.file');
jimport( 'joomla.html.parameter' );


JLoader::registerPrefix('Phocacart', JPATH_ADMINISTRATOR . '/components/com_phocacart/libraries/phocacart');

class plgPCVImage_Zoom extends JPlugin
{
	function __construct(& $subject, $config) {
		parent :: __construct($subject, $config);
		$this->loadLanguage();
	}


	public function PCVonItemImage($context, $item, $t, $params) {


	    $layoutI	= new JLayoutFile('image', null, array('component' => 'com_phocacart'));

	    $document   = JFactory::getDocument();
	    JHtml::stylesheet('media/plg_pcv_image_zoom/js/drift/drift-basic.min.css');
	    JHtml::stylesheet('media/plg_pcv_image_zoom/js/luminuous/luminous-basic.min.css');

	    JHtml::stylesheet('media/plg_pcv_image_zoom/css/style.css');
	    $document->addScript(JURI::root(true) . '/media/plg_pcv_image_zoom/js/drift/Drift.min.js');
	    $document->addScript(JURI::root(true) . '/media/plg_pcv_image_zoom/js/luminuous/Luminous.min.js');
	    $document->addScript(JURI::root(true) . '/media/plg_pcv_image_zoom/js/main.js');

	    $zoom_image = $this->params->get('zoom_image', 'l');

	    $s      = PhocacartRenderStyle::getStyles();
        $o      = array();
        $x      = $item;
        $idName	= 'VItemP'.(int)$x->id;

        $label = PhocacartRenderFront::getLabel($x->date, $x->sales, $x->featured);

		// IMAGE
		$image = PhocacartImage::getThumbnailName($t['pathitem'], $x->image, 'large');// Image
		$imageL = PhocacartImage::getThumbnailName($t['pathitem'], $x->image, 'large');// Image Link to enlarge


		// Some of the attribute is selected - this attribute include image so the image should be displayed instead of default
		$imageA = PhocaCartImage::getImageChangedByAttributes($t['attr_options'], 'large');
		if ($imageA != '') {
			$image = PhocacartImage::getThumbnailName($t['pathitem'], $imageA, 'large');
			$imageL = PhocacartImage::getThumbnailName($t['pathitem'], $imageA, 'large');
		}

		$link = JURI::base(true) . '/' . $imageL->rel;// Thumbnail - Large Thumbnail as default

		if ($t['display_webp_images'] == 1) {
			$link = JURI::base(true) . '/' . $imageL->rel_webp;
		}

		$linkO = '';
		if ($zoom_image == 'o') {
		    $linkO = JURI::base(true) . '/' . $t['pathitem']['orig_rel_ds'] . $x->image;// Original image
        }




		if (isset($image->rel) && $image->rel != '') {

			$altValue = PhocaCartImage::getAltTitle($x->title, $image->rel);

			$o[] = '<div class="ph-item-image-full-box ' . $label['cssthumbnail'] . '">';
			$o[] = '<div class="ph-label-box">';
			$o[] = $label['new'] . $label['hot'] . $label['feat'];
			if ($t['taglabels_output'] != '') {
				$o[] = $t['taglabels_output'];
			}
			$o[] = '</div>';

			$o[] = '<a href="' . ($linkO != '' ? $linkO : $link) . '" class="' . $t['image_class'] . ' phjProductHref' . $idName . ' phImageFullHref" data-href="' . $link . '">';

			$d = array();
			$d['t'] = $t;
			$d['s'] = $s;
			$d['src'] = JURI::base(true) . '/' . $image->rel;
			$d['srcset-webp'] = JURI::base(true) . '/' . $image->rel_webp;
			$d['data-image'] = JURI::base(true) . '/' . $image->rel;
			$d['data-image-webp'] = JURI::base(true) . '/' . $image->rel_webp;
			$d['alt-value'] = PhocaCartImage::getAltTitle($x->title, $image->rel);
			$d['data-image-large'] = $link;
			$d['data-image-original'] = $linkO;
			$d['class'] = PhocacartRenderFront::completeClass(array($s['c']['img-responsive'], $label['cssthumbnail2'], 'ph-image-full', 'phImageFull', 'phjProductImage' . $idName));
			$d['style'] = '';
			if (isset($t['image_width']) && (int)$t['image_width'] > 0 && isset($t['image_height']) && (int)$t['image_height'] > 0) {
				$d['style'] = 'width:' . $t['image_width'] . 'px;height:' . $t['image_height'] . 'px';
			}
			$o[] = $layoutI->render($d);

			$o[] = '</a>';

			$o[] = '</div>';// end item_row_item_box_full_image
		}


		// Add main images to additional images
		$mainImage = array();
		$mainImage[0] = new stdClass();
		$mainImage[0]->image = $x->image;
		$mainImage[0]->active = true;

        $t['add_images'] = array_merge($mainImage, $t['add_images']);


		// ADDITIONAL IMAGES
		if (!empty($t['add_images'])) {

			$o[] = '<div class="' . $s['c']['row'] . ' ph-item-image-add-box">';

			foreach ($t['add_images'] as $v2) {

				$active = '';
				if (isset($v2->active) && $v2->active) {
					$active = 'active';
				}

				$o[] = '<div class="' . $s['c']['col.xs12.sm4.md4'] . ' ph-item-image-box">';
				$image = PhocacartImage::getThumbnailName($t['pathitem'], $v2->image, 'small');
				$imageL = PhocacartImage::getThumbnailName($t['pathitem'], $v2->image, 'large');

				$link = JURI::base(true) . '/' . $imageL->rel;// Thumbnail - Large Thumbnail as default

				if ($t['display_webp_images'] == 1) {
					$link = JURI::base(true) . '/' . $imageL->rel_webp;
				}

				$linkO = '';
				if ($zoom_image == 'o') {
					$linkO = JURI::base(true) . '/' . $t['pathitem']['orig_rel_ds'] . $v2->image;// Original image
				}

				$altValue = PhocaCartImage::getAltTitle($x->title, $v2->image);

				$o[] = '<a href="' . ($linkO != '' ? $linkO : $link) . '" class="' . $t['image_class'] . ' phImageAdditionalHref">';

				$d = array();
				$d['t'] = $t;
				$d['s'] = $s;
				$d['src'] = JURI::base(true) . '/' . $image->rel;
				$d['srcset-webp'] = JURI::base(true) . '/' . $image->rel_webp;
				$d['alt-value'] = PhocaCartImage::getAltTitle($x->title, $v2->image);
				$d['data-image-large'] = $link;
				$d['data-image-original'] = $linkO;
				$d['class'] = PhocacartRenderFront::completeClass(array($s['c']['img-responsive'], $label['cssthumbnail2'], 'ph-image-full', 'phImageAdditional', $active /*, 'phjProductImage'.$idName*/));
				$o[] = $layoutI->render($d);

				$o[] = '</a>';
				$o[] = '</div>';
			}

			$o[] = '</div>';// end additional images
		}

        return trim(implode("\n", $o));
    }


}
?>
