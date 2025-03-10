<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//@trigger_error('The Twig_supTwgSsl_Filter_Node class is deprecated since version 1.12 and will be removed in 2.0. Use Twig_supTwgSsl_SimpleFilter instead.', E_USER_DEPRECATED);

/**
 * Represents a template filter as a node.
 *
 * Use Twig_supTwgSsl_SimpleFilter instead.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @deprecated since 1.12 (to be removed in 2.0)
 */
class Twig_supTwgSsl_Filter_Node extends Twig_supTwgSsl_Filter
{
    protected $class;

    public function __construct($class, array $options = array())
    {
        parent::__construct($options);

        $this->class = $class;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function compile()
    {
    }
}
