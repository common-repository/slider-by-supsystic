<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//@trigger_error('The Twig_supTwgSsl_Filter_Method class is deprecated since version 1.12 and will be removed in 2.0. Use Twig_supTwgSsl_SimpleFilter instead.', E_USER_DEPRECATED);

/**
 * Represents a method template filter.
 *
 * Use Twig_supTwgSsl_SimpleFilter instead.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @deprecated since 1.12 (to be removed in 2.0)
 */
class Twig_supTwgSsl_Filter_Method extends Twig_supTwgSsl_Filter
{
    protected $extension;
    protected $method;

    public function __construct(Twig_supTwgSsl_ExtensionInterface $extension, $method, array $options = array())
    {
        $options['callable'] = array($extension, $method);

        parent::__construct($options);

        $this->extension = $extension;
        $this->method = $method;
    }

    public function compile()
    {
        return sprintf('$this->env->getExtension(\'%s\')->%s', get_class($this->extension), $this->method);
    }
}
