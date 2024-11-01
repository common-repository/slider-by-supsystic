<?php

/**
 * Class SupsysticSlider_Ui_BackendJavascript
 */
class SupsysticSlider_Ui_BackendJavascript extends SupsysticSlider_Ui_Javascript
{
    /**
     * {@inheritdoc}
     */
    public function load()
    {
        $request = RscSsl_Http_Request::create();
        $str = !empty($request->query->get('page')) ? $request->query->get('page') : '';
        if (false !== strpos((string)$str, 'supsystic-slider')) {
            $this->register('admin_print_scripts');
        }
    }
}
