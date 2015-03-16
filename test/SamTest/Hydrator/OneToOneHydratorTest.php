<?php
/**
 * @author Manuel Stosic <manuel.stosic@krankikom.de>
 */

namespace SamTest\Hydrator;

use Sam\Hydrator\OneToOneHydrator;
use SamTest\DummyEntity\Bar;
use SamTest\DummyEntity\Dummy;
use SamTest\DummyEntity\Foo;
use Zend\Stdlib\Hydrator\Aggregate\AggregateHydrator;
use Zend\Stdlib\Hydrator\ClassMethods;

class OneToOneHydratorTest extends \PHPUnit_Framework_TestCase
{
    public function testUnderscoreSeparatedKeysAreSetProperly()
    {
        $uskTrue  = new OneToOneHydrator('foo', 'setFoo', new \stdClass(), true);
        $uskFalse = new OneToOneHydrator('foo', 'setFoo', new \stdClass(), false);

        $this->assertTrue($uskTrue->getUnderscoreSeparatedKeys());
        $this->assertFalse($uskFalse->getUnderscoreSeparatedKeys());
    }

    /**
     * TestCase covers the how-to use-case on how this validator is supposed to be used
     * in the context of an AggregateHydrator
     */
    public function testHydrationHydratesProperlyOnDataMatches()
    {
        $data = [
            'foo_one' => 'TEST FOO ONE',
            'foo_two' => 'TEST FOO TWO',
            'bar_one' => 'BAR FIGHT ONE',
            'bar_two' => 'BAR FIGHT TWO',
            'baz'     => 'LONELY BAZ'
        ];

        $aggregateHydrator = new AggregateHydrator();
        $aggregateHydrator->add(new ClassMethods());
        $aggregateHydrator->add(new OneToOneHydrator('foo_', 'setFoo', new Foo()));
        $aggregateHydrator->add(new OneToOneHydrator('bar_', 'setBar', new Bar()));

        $dummy = $aggregateHydrator->hydrate($data, new Dummy());

        $this->assertInstanceOf('SamTest\\DummyEntity\\Dummy', $dummy);
        $this->assertInstanceOf('SamTest\\DummyEntity\\Foo', $dummy->getFoo());
        $this->assertInstanceOf('SamTest\\DummyEntity\\Bar', $dummy->getBar());

        $this->assertEquals('LONELY BAZ', $dummy->getBaz());

        $this->assertEquals('TEST FOO ONE', $dummy->getFoo()->getOne());
        $this->assertEquals('TEST FOO TWO', $dummy->getFoo()->getTwo());

        $this->assertEquals('BAR FIGHT ONE', $dummy->getBar()->getOne());
        $this->assertEquals('BAR FIGHT TWO', $dummy->getBar()->getTwo());
    }

    public function testHydrationDoesntHappenWhenNoKeysAreFound()
    {
        $data = [
            'baz'     => 'LONELY BAZ'
        ];

        $aggregateHydrator = new AggregateHydrator();
        $aggregateHydrator->add(new ClassMethods());
        $aggregateHydrator->add(new OneToOneHydrator('foo_', 'setFoo', new Foo()));
        $aggregateHydrator->add(new OneToOneHydrator('bar_', 'setBar', new Bar()));

        $dummy = $aggregateHydrator->hydrate($data, new Dummy());

        $this->assertInstanceOf('SamTest\\DummyEntity\\Dummy', $dummy);

        $this->assertEquals('LONELY BAZ', $dummy->getBaz());

        $this->assertNull($dummy->getFoo());
        $this->assertNull($dummy->getBar());
    }
}
