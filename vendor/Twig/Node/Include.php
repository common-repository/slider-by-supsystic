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

/**
 * Represents an include node.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class Twig_supTwgSsl_Node_Include extends Twig_supTwgSsl_Node implements Twig_supTwgSsl_NodeOutputInterface
{
    public function __construct(Twig_supTwgSsl_Node_Expression $expr, Twig_supTwgSsl_Node_Expression $variables = null, $only = false, $ignoreMissing = false, $lineno = null, $tag = null)
    {
        $nodes = array('expr' => $expr);
        if (null !== $variables) {
            $nodes['variables'] = $variables;
        }

        parent::__construct($nodes, array('only' => (bool) $only, 'ignore_missing' => (bool) $ignoreMissing), $lineno, $tag);
    }

    public function compile(Twig_supTwgSsl_Compiler $compiler)
    {
        $compiler->addDebugInfo($this);

        if ($this->getAttribute('ignore_missing')) {
            $compiler
                ->write("try {\n")
                ->indent()
            ;
        }

        $this->addGetTemplate($compiler);

        $compiler->raw('->display(');

        $this->addTemplateArguments($compiler);

        $compiler->raw(");\n");

        if ($this->getAttribute('ignore_missing')) {
            $compiler
                ->outdent()
                ->write("} catch (Twig_supTwgSsl_Error_Loader \$e) {\n")
                ->indent()
                ->write("// ignore missing template\n")
                ->outdent()
                ->write("}\n\n")
            ;
        }
    }

    protected function addGetTemplate(Twig_supTwgSsl_Compiler $compiler)
    {
        $compiler
             ->write('$this->loadTemplate(')
             ->subcompile($this->getNode('expr'))
             ->raw(', ')
             ->repr($this->getTemplateName())
             ->raw(', ')
             ->repr($this->getTemplateLine())
             ->raw(')')
         ;
    }

    protected function addTemplateArguments(Twig_supTwgSsl_Compiler $compiler)
    {
        if (!$this->hasNode('variables')) {
            $compiler->raw(false === $this->getAttribute('only') ? '$context' : 'array()');
        } elseif (false === $this->getAttribute('only')) {
            $compiler
                ->raw('array_merge($context, ')
                ->subcompile($this->getNode('variables'))
                ->raw(')')
            ;
        } else {
            $compiler->subcompile($this->getNode('variables'));
        }
    }
}
