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

namespace derbenni\wp\di\test\unitTests\definition\resolver;

use \derbenni\wp\di\Container;
use \derbenni\wp\di\definition\iDefinition;
use \derbenni\wp\di\definition\resolver\ObjectResolver;
use \derbenni\wp\di\test\TestCase;
use \stdClass;

/**
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
class ObjectResolverTest extends TestCase {

  /**
   *
   * @covers \derbenni\wp\di\definition\resolver\ObjectResolver::can
   */
  public function testCan_ReturnsTrueIfAvailableClassNameGiven() {
    self::assertTrue((new ObjectResolver())->can(stdClass::class));
  }

  /**
   *
   * @covers \derbenni\wp\di\definition\resolver\ObjectResolver::can
   */
  public function testCan_ReturnsFalseIfNoStringGiven() {
    self::assertFalse((new ObjectResolver())->can(123));
    self::assertFalse((new ObjectResolver())->can(123.456));
    self::assertFalse((new ObjectResolver())->can(true));
    self::assertFalse((new ObjectResolver())->can(null));
    self::assertFalse((new ObjectResolver())->can('foo'));
    self::assertFalse((new ObjectResolver())->can(new stdClass()));
  }

  /**
   *
   * @covers \derbenni\wp\di\definition\resolver\ObjectResolver::can
   */
  public function testCan_ReturnsFalseIfNotAvailableClassNameGiven() {
    self::assertFalse((new ObjectResolver())->can(FooBarBaz::class));
  }

  /**
   *
   * @covers \derbenni\wp\di\definition\resolver\ObjectResolver::resolve
   * @expectedException InvalidArgumentException
   */
  public function testResolve_CanThrowInvalidArgumentExceptionIfInvalidValueGiven() {
    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();

    (new ObjectResolver())->resolve(123, $container);
  }

  /**
   *
   * @covers \derbenni\wp\di\definition\resolver\ObjectResolver::resolve
   */
  public function testResolve_CanReturnObjectIfNoDependenciesFound() {
    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();

    self::assertInstanceOf(stdClass::class, (new ObjectResolver())->resolve(stdClass::class, $container));
  }
}
