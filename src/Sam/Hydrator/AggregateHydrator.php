<?php
/**
 * @author Manuel Stosic <manuel.stosic@krankikom.de>
 */

namespace Sam\Hydrator;

use Zend\Stdlib\Hydrator\Aggregate\AggregateHydrator as BaseAggregateHydrator;

/**
 * Class AggregateHydrator
 *
 * Wrote a simple wrapper to faster initialize an AggregateHydrator because I can!
 *
 * <code>
 * $foo = new \Sam\Hydrator\AggregateHydrator(
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
    /**
     * Constructor is used to pass all relevant parts of the aggregate hydrator
     *
     * @throws \InvalidArgumentException when
     */
    public function __construct()
    {
        if (0 === func_num_args()) {
            throw new \InvalidArgumentException(
                'Add at least one instance of an HydratorInterface to the AggregateHydrator.'
            );
        }

        foreach (func_get_args() as $hydrator) {
            $this->add($hydrator);
        }
    }
}
