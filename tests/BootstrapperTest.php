<?php

namespace Somnambulist\Tests\Doctrine;

use Somnambulist\Doctrine\Bootstrapper;
use PHPUnit\Framework\TestCase;

/**
 * Class BootstrapperTest
 *
 * @package    Somnambulist\Tests\Doctrine
 * @subpackage Somnambulist\Tests\Doctrine\BootstrapperTest
 */
class BootstrapperTest extends TestCase
{

    public function testCanCallBootstrapRegisterTypesMultipleTimes()
    {
        Bootstrapper::registerTypes();
        Bootstrapper::registerTypes();
        Bootstrapper::registerTypes();
        Bootstrapper::registerTypes();
        $this->assertTrue(true);
    }
}
