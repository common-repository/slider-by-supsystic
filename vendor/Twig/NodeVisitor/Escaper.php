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
 * Twig_supTwgSsl_NodeVisitor_Escaper implements output escaping.
 *
 * @final
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class Twig_supTwgSsl_NodeVisitor_Escaper extends Twig_supTwgSsl_BaseNodeVisitor
{
    protected $statusStack = array();
    protected $blocks = array();
    protected $safeAnalysis;
    protected $traverser;
    protected $defaultStrategy = false;
    protected $safeVars = array();

    public function __construct()
    {
        $this->safeAnalysis = new Twig_supTwgSsl_NodeVisitor_SafeAnalysis();
    }

    protected function doEnterNode(Twig_supTwgSsl_Node $node, Twig_supTwgSsl_Environment $env)
    {
        if ($node instanceof Twig_supTwgSsl_Node_Module) {
            if ($env->hasExtension('Twig_supTwgSsl_Extension_Escaper') && $defaultStrategy = $env->getExtension('Twig_supTwgSsl_Extension_Escaper')->getDefaultStrategy($node->getTemplateName())) {
                $this->defaultStrategy = $defaultStrategy;
            }
            $this->safeVars = array();
            $this->blocks = array();
        } elseif ($node instanceof Twig_supTwgSsl_Node_AutoEscape) {
            $this->statusStack[] = $node->getAttribute('value');
        } elseif ($node instanceof Twig_supTwgSsl_Node_Block) {
            $this->statusStack[] = isset($this->blocks[$node->getAttribute('name')]) ? $this->blocks[$node->getAttribute('name')] : $this->needEscaping($env);
        } elseif ($node instanceof Twig_supTwgSsl_Node_Import) {
            $this->safeVars[] = $node->getNode('var')->getAttribute('name');
        }

        return $node;
    }

    protected function doLeaveNode(Twig_supTwgSsl_Node $node, Twig_supTwgSsl_Environment $env)
    {
        if ($node instanceof Twig_supTwgSsl_Node_Module) {
            $this->defaultStrategy = false;
            $this->safeVars = array();
            $this->blocks = array();
        } elseif ($node instanceof Twig_supTwgSsl_Node_Expression_Filter) {
            return $this->preEscapeFilterNode($node, $env);
        } elseif ($node instanceof Twig_supTwgSsl_Node_Print) {
            return $this->escapePrintNode($node, $env, $this->needEscaping($env));
        }

        if ($node instanceof Twig_supTwgSsl_Node_AutoEscape || $node instanceof Twig_supTwgSsl_Node_Block) {
            array_pop($this->statusStack);
        } elseif ($node instanceof Twig_supTwgSsl_Node_BlockReference) {
            $this->blocks[$node->getAttribute('name')] = $this->needEscaping($env);
        }

        return $node;
    }

    protected function escapePrintNode(Twig_supTwgSsl_Node_Print $node, Twig_supTwgSsl_Environment $env, $type)
    {
        if (false === $type) {
            return $node;
        }

        $expression = $node->getNode('expr');

        if ($this->isSafeFor($type, $expression, $env)) {
            return $node;
        }

        $class = get_class($node);

        return new $class(
            $this->getEscaperFilter($type, $expression),
            $node->getTemplateLine()
        );
    }

    protected function preEscapeFilterNode(Twig_supTwgSsl_Node_Expression_Filter $filter, Twig_supTwgSsl_Environment $env)
    {
        $name = $filter->getNode('filter')->getAttribute('value');

        $type = $env->getFilter($name)->getPreEscape();
        if (null === $type) {
            return $filter;
        }

        $node = $filter->getNode('node');
        if ($this->isSafeFor($type, $node, $env)) {
            return $filter;
        }

        $filter->setNode('node', $this->getEscaperFilter($type, $node));

        return $filter;
    }

    protected function isSafeFor($type, Twig_supTwgSsl_NodeInterface $expression, $env)
    {
        $safe = $this->safeAnalysis->getSafe($expression);

        if (null === $safe) {
            if (null === $this->traverser) {
                $this->traverser = new Twig_supTwgSsl_NodeTraverser($env, array($this->safeAnalysis));
            }

            $this->safeAnalysis->setSafeVars($this->safeVars);

            $this->traverser->traverse($expression);
            $safe = $this->safeAnalysis->getSafe($expression);
        }

        return in_array($type, $safe) || in_array('all', $safe);
    }

    protected function needEscaping(Twig_supTwgSsl_Environment $env)
    {
        if (count($this->statusStack)) {
            return $this->statusStack[count($this->statusStack) - 1];
        }

        return $this->defaultStrategy ? $this->defaultStrategy : false;
    }

    protected function getEscaperFilter($type, Twig_supTwgSsl_NodeInterface $node)
    {
        $line = $node->getTemplateLine();
        $name = new Twig_supTwgSsl_Node_Expression_Constant('escape', $line);
        $args = new Twig_supTwgSsl_Node(array(new Twig_supTwgSsl_Node_Expression_Constant((string) $type, $line), new Twig_supTwgSsl_Node_Expression_Constant(null, $line), new Twig_supTwgSsl_Node_Expression_Constant(true, $line)));

        return new Twig_supTwgSsl_Node_Expression_Filter($node, $name, $args, $line);
    }

    public function getPriority()
    {
        return 0;
    }
}
