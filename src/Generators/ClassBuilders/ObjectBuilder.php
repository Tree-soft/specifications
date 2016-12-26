<?php

namespace Mildberry\Specifications\Generators\ClassBuilders;

use Mildberry\Specifications\Generators\PhpDocGenerators\PhpDocClass;
use Mildberry\Specifications\Generators\PhpDocGenerators\PhpDocFunction;
use Mildberry\Specifications\Generators\PhpDocGenerators\PhpDocProperty;
use Mildberry\Tests\Specifications\Support\Printer;
use PhpParser\Builder\Class_;
use PhpParser\Builder\Method;
use PhpParser\Builder\Property;
use PhpParser\Builder\Use_;
use PhpParser\BuilderFactory;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Return_;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ObjectBuilder extends AbstractBuilder
{
    /**
     * @var BuilderFactory
     */
    private $factory;

    /**
     * @var array
     */
    private $uses = [];

    /**
     * @var string
     */
    private $class;

    /**
     * ObjectBuilder constructor.
     *
     * @param BuilderFactory $factory
     */
    public function __construct(BuilderFactory $factory)
    {
        $this->factory = $factory;
        $this->clear();
    }

    /**
     * @return string
     */
    public function build(): string
    {
        $this->clear();

        $this->class = $this->extractor->extract($this->schema);

        $parts = $this->getNamespace($this->class);

        $class = $parts['class'];
        $namespace = $parts['namespace'];

        $classStmt = $this->makeClass($class);

        $uses = $this->makeUse();

        $node = $this->factory
            ->namespace(new Name($namespace));

        if (!empty($uses)) {
            $node->addStmts($uses);
        }

        $node->addStmt($classStmt);

        $printer = new Printer();

        return $printer->prettyPrintFile([$node->getNode()]);
    }

    protected function clear()
    {
        $this->uses = [];
        $this->class = null;
    }

    /**
     * @param string $class
     *
     * @return array
     */
    protected function getNamespace(string $class): array
    {
        $parts = explode('\\', $class);

        $shortClass = array_pop($parts);

        return [
            'namespace' => implode('\\', $parts),
            'class' => $shortClass,
        ];
    }

    /**
     * @param string $class
     *
     * @return Class_
     */
    protected function makeClass(string $class)
    {
        /**
         * @var PhpDocClass $phpDoc
         */
        $phpDoc = $this->container->make(PhpDocClass::class);

        $classStmt = $this->factory
            ->class($class)
            ->setDocComment((string) $phpDoc);

        $baseClass = $this->getBaseClass();

        if (isset($baseClass)) {
            $classStmt->extend($baseClass);
        }

        $properties = $this->getProperties();

        $fields = [];

        $methods = [];

        foreach ($properties as $property => $schema) {
            $fields[] = $this->makeProperty($property, $schema);

            $methods[] = $this->makeGetter($property, $schema);
            $methods[] = $this->makeSetter($property, $schema);
        }

        $classStmt
            ->addStmts($fields)
            ->addStmts($methods);

        return $classStmt;
    }

    /**
     * @return string
     */
    protected function getBaseClass(): string
    {
        $baseTypes = $this->getBaseTypes();

        $type = array_pop($baseTypes);

        return $this->extractType($type);
    }

    /**
     * @return array
     */
    protected function getProperties(): array
    {
        $properties = (array) ($this->schema->properties ?? []);

        $baseTypes = $this->getBaseTypes();

        foreach ($baseTypes as $type) {
            foreach ($type->properties ?? [] as $key => $value) {
                unset($properties[$key]);
            }
        }

        return $properties;
    }

    /**
     * @return array
     */
    protected function getBaseTypes(): array
    {
        return array_filter($this->schema->allOf ?? [], function ($type) {
            return isset($type->id);
        });
    }

    /**
     * @param string $name
     * @param object $schema
     *
     * @return Property
     */
    protected function makeProperty(string $name, $schema)
    {
        /**
         * @var PhpDocProperty $phpDoc
         */
        $phpDoc = $this->container->make(PhpDocProperty::class);

        $type = $this->extractType($schema);

        $phpDoc
            ->setType($type);

        return $this->factory
            ->property($name)
            ->makePrivate()
            ->setDocComment((string) $phpDoc);
    }

    /**
     * @param string $name
     * @param object $schema
     *
     * @return Method
     */
    protected function makeGetter(string $name, $schema)
    {
        /**
         * @var PhpDocFunction $phpDoc
         */
        $phpDoc = $this->container->make(PhpDocFunction::class);

        $type = $this->extractType($schema);

        $phpDoc
            ->setReturnType($type);

        $return = new Return_(new PropertyFetch(new Expr\Variable('this'), $name));

        return $this->factory
            ->method('get' . ucfirst($name))
            ->makePublic()
            ->setReturnType($phpDoc->getReturnType())
            ->setDocComment((string) $phpDoc)
            ->addStmt($return);
    }

    /**
     * @param string $name
     * @param $schema
     *
     * @return Method
     */
    protected function makeSetter(string $name, $schema)
    {
        /**
         * @var PhpDocFunction $phpDoc
         */
        $phpDoc = $this->container->make(PhpDocFunction::class);

        $type = $this->extractType($schema);

        $phpDoc
            ->setParams([
                $name => $type,
            ])
            ->setReturnType('$this');

        $param = $this->factory
            ->param($name)
            ->setTypeHint($phpDoc->getParams()[$name]);

        $body = [
            new Expr\Assign(
                new PropertyFetch(new Expr\Variable('this'), $name),
                new Expr\Variable($name)
            ),
            new Return_(new Expr\Variable('this')),
        ];

        return $this->factory
            ->method('set' . ucfirst($name))
            ->makePublic()
            ->addParam($param)
            ->setDocComment((string) $phpDoc)
            ->addStmts($body);
    }

    /**
     * @return array|Use_[]
     */
    protected function makeUse()
    {
        return array_map(function (string $alias, string $class) {
            $stmt = $this->factory->use($class)->as($alias);

            return $stmt;
        }, $this->uses, array_keys($this->uses));
    }

    /**
     * @param string|object $schema
     *
     * @return string|string[]
     */
    protected function extractType($schema)
    {
        $types = $this->extractor->extract($schema);

        return
            (is_array($types)) ?
                (array_map(function ($type) {
                    return $this->addUseStatement($type);
                }, $types)) :
                ($this->addUseStatement($types));
    }

    /**
     * @param string $type
     *
     * @return string
     */
    protected function addUseStatement(string $type): string
    {
        if ($type[0] != '\\') {
            return $type;
        } elseif (in_array($type, $this->uses)) {
            return $this->uses[$type];
        }

        $parts = $this->getNamespace($type);

        $sourceParts = $this->getNamespace($this->class);

        $class = $parts['class'];

        if ($parts['namespace'] != $sourceParts['namespace']) {
            if ($parts['class'] == $sourceParts['class']) {
                $class = "Parent{$parts['class']}";
            }

            $this->uses[$type] = $class;
        }

        return $class;
    }
}
