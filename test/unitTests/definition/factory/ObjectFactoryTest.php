<?php

/**
 * WP-DI: A lightweight dependency injection container for WordPress.
 * Copyright (C) 2017 Benjamin Hofmann
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software Foundation,
 * Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301  USA
 */

namespace derbenni\wp\di\test\unitTests\definition\factory;

use \derbenni\wp\di\definition\factory\ObjectFactory;
use \derbenni\wp\di\definition\ObjectDefinition;
use \derbenni\wp\di\test\TestCase;
use \InvalidArgumentException;
use \stdClass;

/**
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
class ObjectFactoryTest extends TestCase {

  /**
   *
   * @var ObjectFactory
   */
  private $sut = null;

  protected function setUp() {
    parent::setUp();

    $this->sut = new ObjectFactory();
  }

  /**
   *
   * @covers \derbenni\wp\di\definition\factory\ObjectFactory::make
   */
  public function testMake_CanCreateDefinition() {
    self::assertInstanceOf(ObjectDefinition::class, $this->sut->make([stdClass::class]));
  }

  /**
   *
   * @covers \derbenni\wp\di\definition\factory\ObjectFactory::make
   *
   * @expectedException InvalidArgumentException
   * @expectedExceptionMessage The given classname is not a string.
   */
  public function testMake_ThrowsExceptionIfNoStringAsFirstParameterGiven() {
    $this->sut->make([123]);
  }

  /**
   *
   * @covers \derbenni\wp\di\definition\factory\ObjectFactory::make
   *
   * @expectedException InvalidArgumentException
   * @expectedExceptionMessage The given classname "FooBarBazClass" is unknown.
   */
  public function testMake_ThrowsExceptionIfNoKnownClassnameAsFirstParameterGiven() {
    $this->sut->make(['FooBarBazClass']);
  }
}
