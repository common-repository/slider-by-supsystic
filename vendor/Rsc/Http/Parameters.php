<?php


class RscSsl_Http_Parameters extends RscSsl_Common_Collection
{
	public function get_esc_html($key, $default = null)
	{
		$default = parent::get($key, $default);
		$default = esc_html($default);

		return $default;
	}
} 