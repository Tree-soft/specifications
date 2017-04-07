<?php

namespace TreeSoft\Specifications\Support;

use PhpParser\Node\Expr;
use PhpParser\PrettyPrinter\Standard;
use PhpParser\Node\Stmt;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class Printer extends Standard
{
    /**
     * @param Stmt\Namespace_ $node
     *
     * @return string
     */
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

    /**
     * @param Stmt\UseUse $node
     *
     * @return string
     */
    protected function pStmt_UseUse(Stmt\UseUse $node)
    {
        $namespace = ltrim($this->p($node->name), '\\');

        return $this->pUseType($node->type) . $namespace
            . ($node->name->getLast() !== $node->alias ? ' as ' . $node->alias : '');
    }

    /**
     * @param $type
     *
     * @return string
     */
    protected function pUseType($type)
    {
        return $type === Stmt\Use_::TYPE_FUNCTION ? 'function '
            : ($type === Stmt\Use_::TYPE_CONSTANT ? 'const ' : '');
    }

    /**
     * @param Stmt\ClassMethod $node
     *
     * @return string
     */
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

    /**
     * @param array $nodes
     * @param bool $indent
     *
     * @return mixed|string
     */
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

    /**
     * @param array $stmts
     *
     * @return string
     */
    public function prettyPrintFile(array $stmts)
    {
        $result = parent::prettyPrintFile($stmts);

        if (substr('testers', -1) != "\n") {
            $result .= "\n";
        }

        return $result;
    }
}
