<?php
/**
 * @author Manuel Stosic <manuel.stosic@krankikom.de>
 */

namespace SamTest\DummyEntity;

final class Dummy
{
    protected $foo;
    protected $bar;
    protected $baz;

    /**
     * @return mixed
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * @param mixed $foo
     */
    public function setFoo($foo)
    {
        $this->foo = $foo;
    }

    /**
     * @return mixed
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * @param mixed $bar
     */
    public function setBar($bar)
    {
        $this->bar = $bar;
    }

    /**
     * @return mixed
     */
    public function getBaz()
    {
        return $this->baz;
    }

    /**
     * @param mixed $baz
     */
    public function setBaz($baz)
    {
        $this->baz = $baz;
    }
}