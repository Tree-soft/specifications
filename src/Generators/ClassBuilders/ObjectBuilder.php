<?php

namespace Mildberry\Specifications\Generators\ClassBuilders;

use Mildberry\Specifications\Generators\PhpDocGenerators\PhpDocClass;
use Mildberry\Specifications\Generators\PhpDocGenerators\PhpDocFunction;
use Mildberry\Specifications\Generators\PhpDocGenerators\PhpDocProperty;
use Mildberry\Tests\Specifications\Support\Printer;
use PhpParser\Builder\Class_;
use PhpParser\Builder\Property;
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

    public function __construct(BuilderFactory $factory)
    {
        $this->factory = $factory;
    }

    public function build(): string
    {
        $parts = $this->getNamespace($this->generator->getClassName($this->schema));

        $class = $parts['class'];
        $namespace = $parts['namespace'];

        $classStmt = $this->makeClass($class);

        $node = $this->factory
            ->namespace(new Name($namespace))
            ->addStmt($classStmt)
            ->getNode();

        $printer = new Printer();

        return $printer->prettyPrintFile([$node]);
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

        $properties = (array) ($this->schema->properties ?? []);

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

        $phpDoc
            ->setType($schema->type);

        return $this->factory
            ->property($name)
            ->makePrivate()
            ->setDocComment((string) $phpDoc);
    }

    protected function makeGetter(string $name, $schema)
    {
        /**
         * @var PhpDocFunction $phpDoc
         */
        $phpDoc = $this->container->make(PhpDocFunction::class);

        $phpDoc
            ->setReturnType($schema->type);

        $return = new Return_(new PropertyFetch(new Expr\Variable('this'), $name));

        return $this->factory
            ->method('get' . ucfirst($name))
            ->makePublic()
            ->setReturnType($phpDoc->getReturnType())
            ->setDocComment((string) $phpDoc)
            ->addStmt($return);
    }

    protected function makeSetter(string $name, $schema)
    {
        /**
         * @var PhpDocFunction $phpDoc
         */
        $phpDoc = $this->container->make(PhpDocFunction::class);

        $phpDoc
            ->setParams([
                $name => $schema->type,
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
}
