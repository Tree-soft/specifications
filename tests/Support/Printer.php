<?php

namespace Mildberry\Tests\Specifications\Support;

use PhpParser\Node\Expr;
use PhpParser\PrettyPrinter\Standard;
use PhpParser\Node\Stmt;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class Printer extends Standard
{
    protected function pStmt_Namespace(Stmt\Namespace_ $node)
    {
        $namespace = ltrim($this->p($node->name), '\\');

        if ($this->canUseSemicolonNamespaces) {
            return 'namespace ' . $namespace . ';' . "\n" . $this->pStmts($node->stmts, false);
        } else {
            return 'namespace' . (null !== $node->name ? ' ' . $namespace : '')
                . ' {' . $this->pStmts($node->stmts) . "\n" . '}';
        }
    }

    protected function pStmt_ClassMethod(Stmt\ClassMethod $node)
    {
        return $this->pModifiers($node->flags)
            . 'function ' . ($node->byRef ? '&' : '') . $node->name
            . '(' . $this->pCommaSeparated($node->params) . ')'
            . (null !== $node->returnType ? ': ' . $this->pType($node->returnType) : '')
            . (null !== $node->stmts
                ? "\n" . '{' . $this->pStmts($node->stmts) . "\n" . '}'
                : ';');
    }

    protected function pStmts(array $nodes, $indent = true)
    {
        $result = '';

        foreach ($nodes as $id => $node) {
            $comments = $node->getAttribute('comments', array());

            $element = '';

            if ($comments) {
                $element .= "\n" . $this->pComments($comments);
                if ($node instanceof Stmt\Nop) {
                    continue;
                }
            }

            $element .= "\n" . $this->p($node) . ($node instanceof Expr ? ';' : '');

            if (($id > 0) && (count(explode("\n", $element)) > 1)) {
                $element = $this->pNoIndent("\n") . $element;
            }

            $result .= $element;
        }

        if ($indent) {
            return preg_replace('~\n(?!$|' . $this->noIndentToken . ')~', "\n    ", $result);
        } else {
            return $result;
        }
    }

    public function prettyPrintFile(array $stmts)
    {
        $result = parent::prettyPrintFile($stmts);

        if (substr('testers', -1) != "\n") {
            $result .= "\n";
        }

        return $result;
    }
}
