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

namespace derbenni\izon\test\unitTests\resolver\resolver;

use \derbenni\izon\Container;
use \derbenni\izon\resolver\parameter\iParameterResolver;
use \derbenni\izon\resolver\parameter\ParameterResolverCollection;
use \derbenni\izon\test\dummy\ParameterResolverTestDummy;
use \derbenni\izon\test\TestCase;
use \ReflectionParameter;

/**
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
class ParameterResolverCollectionTest extends TestCase {

  /**
   *
   * @covers \derbenni\izon\resolver\parameter\ParameterResolverCollection::__construct
   * @covers \derbenni\izon\resolver\parameter\ParameterResolverCollection::add
   */
  public function testConstruct_CanSetParameterResolversInProperty() {
    $sut = new ParameterResolverCollection([
      $this->getMockForAbstractClass(iParameterResolver::class),
    ]);

    $definitions = $this->returnValueOfPrivateProperty($sut, 'parameterResolvers');

    self::assertNotEmpty($definitions);
    self::assertInstanceOf(iParameterResolver::class, $definitions[0]);
  }

  /**
   *
   * @covers \derbenni\izon\resolver\parameter\ParameterResolverCollection::can
   */
  public function testCan_ReturnsTrueIfAnyKindOfParameterIsGiven() {
    self::assertTrue((new ParameterResolverCollection([]))->can(new ReflectionParameter([ParameterResolverTestDummy::class, 'unspecifiedParameter'], 'foo'), []));
  }

  /**
   *
   * @covers \derbenni\izon\resolver\parameter\ParameterResolverCollection::resolve
   */
  public function testResolve_CanIterateThroughParameterResolversAndReturnTheirValue() {
    $parameter = new ReflectionParameter([ParameterResolverTestDummy::class, 'unspecifiedParameter'], 'foo');
    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();

    $parameterResolver = $this->getMockForAbstractClass(iParameterResolver::class);
    $parameterResolver->expects(self::once())
      ->method('can')
      ->with($parameter, ['bar' => 'baz'])
      ->willReturn(true);
    $parameterResolver->expects(self::once())
      ->method('resolve')
      ->with($parameter, $container, ['bar' => 'baz'])
      ->willReturn('foo');

    $sut = new ParameterResolverCollection([$parameterResolver]);
    self::assertEquals('foo', $sut->resolve($parameter, $container, ['bar' => 'baz']));
  }

  /**
   *
   * @covers \derbenni\izon\resolver\parameter\ParameterResolverCollection::resolve
   * @expectedException \derbenni\izon\DependencyException
   */
  public function testResolve_ThrowsDependencyExceptionIfNoParameterResolverCouldResolveTheParameter() {
    $parameter = new ReflectionParameter([ParameterResolverTestDummy::class, 'unspecifiedParameter'], 'foo');
    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();

    $sut = new ParameterResolverCollection([]);
    $sut->resolve($parameter, $container);
  }
}
