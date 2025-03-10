<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 * (c) Armin Ronacher
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
abstract class Twig_supTwgSsl_Node_Expression_Unary extends Twig_supTwgSsl_Node_Expression
{
    public function __construct(Twig_supTwgSsl_NodeInterface $node, $lineno)
    {
        parent::__construct(array('node' => $node), array(), $lineno);
    }

    public function compile(Twig_supTwgSsl_Compiler $compiler)
    {
        $compiler->raw(' ');
        $this->operator($compiler);
        $compiler->subcompile($this->getNode('node'));
    }

    abstract public function operator(Twig_supTwgSsl_Compiler $compiler);
}
