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
 * Represents an import node.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class Twig_supTwgSsl_Node_Import extends Twig_supTwgSsl_Node
{
    public function __construct(Twig_supTwgSsl_Node_Expression $expr, Twig_supTwgSsl_Node_Expression $var, $lineno, $tag = null)
    {
        parent::__construct(array('expr' => $expr, 'var' => $var), array(), $lineno, $tag);
    }

    public function compile(Twig_supTwgSsl_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write('')
            ->subcompile($this->getNode('var'))
            ->raw(' = ')
        ;

        if ($this->getNode('expr') instanceof Twig_supTwgSsl_Node_Expression_Name && '_self' === $this->getNode('expr')->getAttribute('name')) {
            $compiler->raw('$this');
        } else {
            $compiler
                ->raw('$this->loadTemplate(')
                ->subcompile($this->getNode('expr'))
                ->raw(', ')
                ->repr($this->getTemplateName())
                ->raw(', ')
                ->repr($this->getTemplateLine())
                ->raw(')')
            ;
        }

        $compiler->raw(";\n");
    }
}
