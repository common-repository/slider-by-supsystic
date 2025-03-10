<?php

/**
 * Twig Extension for working with attachments.
 */
class SupsysticSlider_Slider_Twig_supTwgSsl_Attachment extends Twig_supTwgSsl_Extension
{

    /**
     * @var SupsysticSlider_Slider_Attachment
     */
    protected $helper;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->helper = new SupsysticSlider_Slider_Attachment();
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'SupsysticSlider_Slider_Twig_supTwgSsl_Attachment';
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        return array(
            new Twig_supTwgSsl_SimpleFilter('image_size', array(
                $this,
                'getImageSizeFilter'
            )),
            new Twig_supTwgSsl_SimpleFilter('attachment_size', array(
                $this->helper,
                'getSize'
            )),
        );
    }

    /**
     * Returns requested size for the specified image.
     *
     * @param array|object $image Image entity.
     * @param int $width Requested width.
     * @param int $height Requested height.
     * @return string
     */
    public function getImageSizeFilter($image, $width, $height = null, $cropPosition = null)
    {

        if (is_array($image) && isset($image['attachment'])) {
            return $this->helper->getSize(
                $image['attachment']['id'],
                $width,
                $height,
                $cropPosition
            );
        }

        if (is_object($image) && property_exists($image, 'attachment')) {
			if(property_exists($image, 'isNotRealAttachment') && $image->isNotRealAttachment) {
				$url = $this->helper->getCropedFileUrl($image->attachment, $width, $height, $cropPosition);
				return $url;
			}

            return $this->helper->getSize(
                $image->attachment['id'],
                $width,
                $height,
                $cropPosition
            );
        }

        return $this->helper->getSize(null, $width, $height);
    }
}
