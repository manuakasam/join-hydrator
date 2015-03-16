<?php
/**
 * @author Manuel Stosic <manuel.stosic@krankikom.de>
 */

namespace SamTest\DummyEntity;

final class Bar
{
    protected $one;
    protected $two;

    /**
     * @return mixed
     */
    public function getOne()
    {
        return $this->one;
    }

    /**
     * @param mixed $one
     */
    public function setOne($one)
    {
        $this->one = $one;
    }

    /**
     * @return mixed
     */
    public function getTwo()
    {
        return $this->two;
    }

    /**
     * @param mixed $two
     */
    public function setTwo($two)
    {
        $this->two = $two;
    }
}