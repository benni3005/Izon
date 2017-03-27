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

namespace derbenni\izon\test\unitTests\resolver;

use \derbenni\izon\Container;
use \derbenni\izon\resolver\ArrayResolver;
use \derbenni\izon\test\TestCase;
use \stdClass;

/**
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
class ArrayResolverTest extends TestCase {

  /**
   *
   * @covers \derbenni\izon\resolver\ArrayResolver::can
   */
  public function testCan_ReturnsTrueIfArrayGiven() {
    self::assertTrue((new ArrayResolver())->can([]));
  }

  /**
   *
   * @covers \derbenni\izon\resolver\ArrayResolver::can
   */
  public function testCan_ReturnsFalseIfNoArrayGiven() {
    self::assertFalse((new ArrayResolver())->can(123));
    self::assertFalse((new ArrayResolver())->can(123.456));
    self::assertFalse((new ArrayResolver())->can(true));
    self::assertFalse((new ArrayResolver())->can(null));
    self::assertFalse((new ArrayResolver())->can('foo'));
    self::assertFalse((new ArrayResolver())->can(new stdClass()));
  }

  /**
   *
   * @covers \derbenni\izon\resolver\ArrayResolver::resolve
   * @expectedException InvalidArgumentException
   */
  public function testResolve_CanThrowInvalidArgumentExceptionIfInvalidValueGiven() {
    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();

    (new ArrayResolver())->resolve(123, $container);
  }

  /**
   *
   * @covers \derbenni\izon\resolver\ArrayResolver::resolve
   */
  public function testResolve_CanReturnArrayIfNoDefinitionsFound() {
    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();

    self::assertEquals([1, 2, 3], (new ArrayResolver())->resolve([1, 2, 3], $container));
  }

  /**
   *
   * @covers \derbenni\izon\resolver\ArrayResolver::resolve
   */
  public function testResolve_CanReturnResolvedArrayWhenDefinitionsFound() {
    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();

    $definition = $this->getMockForAbstractClass(\derbenni\izon\definition\iDefinition::class);
    $definition->expects(self::once())
      ->method('define')
      ->with(self::equalTo($container))
      ->willReturn('foo');

    self::assertEquals(['foo'], (new ArrayResolver())->resolve([$definition], $container));
  }
}
