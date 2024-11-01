<?php

/**
 * Class SupsysticSlider_Ui_BackendStylesheet
 *
 * Loads the stylesheet to backend.
 */
class SupsysticSlider_Ui_BackendStylesheet extends SupsysticSlider_Ui_Stylesheet
{
    /**
     * {@inheritdoc}
     */
    public function load()
    {
        $request = RscSsl_Http_Request::create();
        $str = !empty($request->query->get('page')) ? $request->query->get('page') : '';
        if (false !== strpos((string)$str, 'supsystic-slider')) {
            $this->register('admin_print_styles');
        }
    }
}
