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
 * Represents a spaceless node.
 *
 * It removes spaces between HTML tags.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class Twig_supTwgSsl_Node_Spaceless extends Twig_supTwgSsl_Node
{
    public function __construct(Twig_supTwgSsl_NodeInterface $body, $lineno, $tag = 'spaceless')
    {
        parent::__construct(array('body' => $body), array(), $lineno, $tag);
    }

    public function compile(Twig_supTwgSsl_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write("ob_start();\n")
            ->subcompile($this->getNode('body'))
            ->write("echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));\n")
        ;
    }
}
