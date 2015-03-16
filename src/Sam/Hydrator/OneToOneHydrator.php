<?php
/**
 * @author Manuel Stosic <manuel.stosic@krankikom.de>
 */

namespace Sam\Hydrator;

use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Class OneToOneHydrator
 *
 * Basic Hydrator to properly hydrate OneToOne tables
 *
 * <code>
 * $data = [
 *   'foo_one' => 'FOO_1',
 *   'foo_two' => 'FOO_2',
 *   'bar_one' => 'BAR_1',
 *   'bar_two' => 'BAR_2Ä
 * ];
 *
 * $hydrator = new \Zend\Stdlib\AggregateHydrator();
 * $hydrator->add(new OneToOneHydrator('foo_', 'setFoo', new Foo()));
 * $hydrator->add(new OneToOneHydrator('bar_', 'setBar', new Bar()));
 *
 * $object = $hydrator->hydrate($data, new Dummy());
 *
 * var_dump($object);
 *
 * object(Dummy)#38 (5) {
 *   ["foo":protected]=>
 *   object(Foo)#24 (2) {
 *     ["one":protected]=>
 *       string(5) "FOO_1"
 *     ["two":protected]=>
 *       string(5) "FOO_2"
 *   }
 *   ["bar":protected]=>
 *   object(Bar)#24 (2) {
 *     ["one":protected]=>
 *       string(5) "BAR_1"
 *     ["two":protected]=>
 *       string(5) "BAR_2"
 *   }
 * }
 * </code>
 *
 * @author  Manuel Stosic <manuel.stosic@krankikom.de>
 * @package Zf2demo\Hydrator
 */
class OneToOneHydrator extends ClassMethods
{
    protected $needle;
    protected $setter;
    protected $prototype;

    /**
     * @param string $needle          What substring identifies the related object
     * @param string $setter          What function to call to populate the object
     * @param mixed  $objectPrototype Prototype Object to be used for the Hydration
     * @param bool   $underscoreSeparatedKeys
     */
    public function __construct($needle, $setter, $objectPrototype, $underscoreSeparatedKeys = true)
    {
        parent::__construct($underscoreSeparatedKeys);

        $this->needle    = $needle;
        $this->setter    = $setter;
        $this->prototype = $objectPrototype;
    }

    /**
     * @param array  $data
     * @param object $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $toHydrate = [];
        $lenNeedle = strlen($this->needle);

        foreach ($data as $key => $value) {
            if ($this->needle === substr($key, 0, $lenNeedle)) {
                $toHydrate[substr($key, $lenNeedle)] = $value;
            }
        }

        if (count($toHydrate) > 0) {
            $aggregatedObject = parent::hydrate($toHydrate, $this->prototype);

            $object->{$this->setter}($aggregatedObject);
        }

        return $object;
    }
}
