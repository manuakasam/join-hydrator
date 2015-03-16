<?php
/**
 * @author Manuel Stosic <manuel.stosic@krankikom.de>
 */

namespace SamTest\Hydrator;

use Sam\Hydrator\AggregateHydrator;
use Sam\Hydrator\OneToOneHydrator;
use SamTest\DummyEntity\Bar;
use SamTest\DummyEntity\Dummy;
use SamTest\DummyEntity\Foo;
use Zend\Stdlib\Hydrator\ClassMethods;

class AggregateHydratorTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorArgumentsAreRequired()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Add at least one instance of an HydratorInterface to the AggregateHydrator.'
        );

        $hydrator = new AggregateHydrator();
    }

    public function testAggregatedHydrationViaConstructorArgumentsWorks()
    {
        $data = [
            'foo_one' => 'TEST FOO ONE',
            'foo_two' => 'TEST FOO TWO',
            'bar_one' => 'BAR FIGHT ONE',
            'bar_two' => 'BAR FIGHT TWO',
            'baz'     => 'LONELY BAZ'
        ];

        $joinedQueryHydrator = new AggregateHydrator(
            new ClassMethods(),
            new OneToOneHydrator('foo_', 'setFoo', new Foo()),
            new OneToOneHydrator('bar_', 'setBar', new Bar())
        );

        $dummy = $joinedQueryHydrator->hydrate($data, new Dummy());

        $this->assertInstanceOf('SamTest\\DummyEntity\\Dummy', $dummy);
        $this->assertInstanceOf('SamTest\\DummyEntity\\Foo', $dummy->getFoo());
        $this->assertInstanceOf('SamTest\\DummyEntity\\Bar', $dummy->getBar());

        $this->assertEquals('LONELY BAZ', $dummy->getBaz());

        $this->assertEquals('TEST FOO ONE', $dummy->getFoo()->getOne());
        $this->assertEquals('TEST FOO TWO', $dummy->getFoo()->getTwo());

        $this->assertEquals('BAR FIGHT ONE', $dummy->getBar()->getOne());
        $this->assertEquals('BAR FIGHT TWO', $dummy->getBar()->getTwo());
    }
}