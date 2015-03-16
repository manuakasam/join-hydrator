<?php
/**
 * @author Manuel Stosic <manuel.stosic@krankikom.de>
 */

namespace Sam\Hydrator;

use Zend\Stdlib\Hydrator\Aggregate\AggregateHydrator as BaseAggregateHydrator;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Class AggregateHydrator
 *
 * Wrote a simple wrapper to faster initialize an AggregateHydrator because I can!
 *
 * <code>
 * $foo = new AggregateHydrator(
 *     new ClassMethods(),
 *     new OneToOneHydrator('foo_', 'setFoo', new Foo()),
 *     new OneToOneHydrator('bar_', 'setBar', new Bar())
 * );
 * </code>
 *
 * @author  Manuel Stosic <manuel.stosic@krankikom.de>
 * @package Zf2demo\Hydrator
 */
final class AggregateHydrator extends BaseAggregateHydrator
{
    protected $hydratorChain = [];

    /**
     * Constructor is used to pass all relevant parts of the aggregate hydrator 
     *
     * @throws \InvalidArgumentException when
     */
    public function __construct()
    {
        $passedHydrators = func_get_args();

        for ($i = 0; $i < func_num_args(); $i++) {
            $this->hydratorChain[$i] = $passedHydrators[$i];
        }

        if (0 === count($passedHydrators)) {
            throw new \InvalidArgumentException(
                'Add at least one instance of an HydratorInterface to the AggregateHydrator.'
            );
        }
    }

    /**
     * Run the aggregated hydrator to receive a nice awesome object
     *
     * @param array  $data
     * @param object $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $baseHydrator = new BaseAggregateHydrator();

        foreach ($this->hydratorChain as $hydrator) {
            $baseHydrator->add($hydrator);
        }

        return $baseHydrator->hydrate($data, $object);
    }
}