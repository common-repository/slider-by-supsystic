<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @final
 */
class Twig_supTwgSsl_Profiler_Dumper_Html extends Twig_supTwgSsl_Profiler_Dumper_Text
{
    private static $colors = array(
        'block' => '#dfd',
        'macro' => '#ddf',
        'template' => '#ffd',
        'big' => '#d44',
    );

    public function dump(Twig_supTwgSsl_Profiler_Profile $profile)
    {
        return '<pre>'.parent::dump($profile).'</pre>';
    }

    protected function formatTemplate(Twig_supTwgSsl_Profiler_Profile $profile, $prefix)
    {
        return sprintf('%s└ <span style="background-color: %s">%s</span>', $prefix, self::$colors['template'], $profile->getTemplate());
    }

    protected function formatNonTemplate(Twig_supTwgSsl_Profiler_Profile $profile, $prefix)
    {
        return sprintf('%s└ %s::%s(<span style="background-color: %s">%s</span>)', $prefix, $profile->getTemplate(), $profile->getType(), isset(self::$colors[$profile->getType()]) ? self::$colors[$profile->getType()] : 'auto', $profile->getName());
    }

    protected function formatTime(Twig_supTwgSsl_Profiler_Profile $profile, $percent)
    {
        return sprintf('<span style="color: %s">%.2fms/%.0f%%</span>', $percent > 20 ? self::$colors['big'] : 'auto', $profile->getDuration() * 1000, $percent);
    }
}
