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
class Twig_supTwgSsl_Profiler_NodeVisitor_Profiler extends Twig_supTwgSsl_BaseNodeVisitor
{
    private $extensionName;

    public function __construct($extensionName)
    {
        $this->extensionName = $extensionName;
    }

    protected function doEnterNode(Twig_supTwgSsl_Node $node, Twig_supTwgSsl_Environment $env)
    {
        return $node;
    }

    protected function doLeaveNode(Twig_supTwgSsl_Node $node, Twig_supTwgSsl_Environment $env)
    {
        if ($node instanceof Twig_supTwgSsl_Node_Module) {
            $varName = $this->getVarName();
            $node->setNode('display_start', new Twig_supTwgSsl_Node(array(new Twig_supTwgSsl_Profiler_Node_EnterProfile($this->extensionName, Twig_supTwgSsl_Profiler_Profile::TEMPLATE, $node->getTemplateName(), $varName), $node->getNode('display_start'))));
            $node->setNode('display_end', new Twig_supTwgSsl_Node(array(new Twig_supTwgSsl_Profiler_Node_LeaveProfile($varName), $node->getNode('display_end'))));
        } elseif ($node instanceof Twig_supTwgSsl_Node_Block) {
            $varName = $this->getVarName();
            $node->setNode('body', new Twig_supTwgSsl_Node_Body(array(
                new Twig_supTwgSsl_Profiler_Node_EnterProfile($this->extensionName, Twig_supTwgSsl_Profiler_Profile::BLOCK, $node->getAttribute('name'), $varName),
                $node->getNode('body'),
                new Twig_supTwgSsl_Profiler_Node_LeaveProfile($varName),
            )));
        } elseif ($node instanceof Twig_supTwgSsl_Node_Macro) {
            $varName = $this->getVarName();
            $node->setNode('body', new Twig_supTwgSsl_Node_Body(array(
                new Twig_supTwgSsl_Profiler_Node_EnterProfile($this->extensionName, Twig_supTwgSsl_Profiler_Profile::MACRO, $node->getAttribute('name'), $varName),
                $node->getNode('body'),
                new Twig_supTwgSsl_Profiler_Node_LeaveProfile($varName),
            )));
        }

        return $node;
    }

    private function getVarName()
    {
        return sprintf('__internal_%s', hash('sha256', uniqid(mt_rand(), true), false));
    }

    public function getPriority()
    {
        return 0;
    }
}
