<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class Twig_supTwgSsl_Node_Expression_Test extends Twig_supTwgSsl_Node_Expression_Call
{
    public function __construct(Twig_supTwgSsl_NodeInterface $node, $name, Twig_supTwgSsl_NodeInterface $arguments = null, $lineno)
    {
        $nodes = array('node' => $node);
        if (null !== $arguments) {
            $nodes['arguments'] = $arguments;
        }

        parent::__construct($nodes, array('name' => $name), $lineno);
    }

    public function compile(Twig_supTwgSsl_Compiler $compiler)
    {
        $name = $this->getAttribute('name');
        $test = $compiler->getEnvironment()->getTest($name);

        $this->setAttribute('name', $name);
        $this->setAttribute('type', 'test');
        $this->setAttribute('thing', $test);
        if ($test instanceof Twig_supTwgSsl_TestCallableInterface || $test instanceof Twig_supTwgSsl_SimpleTest) {
            $this->setAttribute('callable', $test->getCallable());
        }
        if ($test instanceof Twig_supTwgSsl_SimpleTest) {
            $this->setAttribute('is_variadic', $test->isVariadic());
        }

        $this->compileCallable($compiler);
    }
}
