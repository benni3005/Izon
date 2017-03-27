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
use \derbenni\izon\resolver\MethodResolver;
use \derbenni\izon\resolver\parameter\iParameterResolver;
use \derbenni\izon\test\dummy\MethodResolverTestDummy;
use \derbenni\izon\test\TestCase;
use \InvalidArgumentException;
use \ReflectionMethod;
use \ReflectionParameter;
use \stdClass;

/**
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
class MethodResolverTest extends TestCase {

  /**
   *
   * @var MethodResolver
   */
  private $sut = null;

  protected function setUp() {
    parent::setUp();

    $this->sut = new MethodResolver($this->getMockForAbstractClass(iParameterResolver::class));
  }

  /**
   *
   * @covers \derbenni\izon\resolver\MethodResolver::__construct
   */
  public function testConstruct_CanSetDefinitionsInProperty() {
    $sut = new MethodResolver($this->getMockForAbstractClass(iParameterResolver::class));

    self::assertInstanceOf(iParameterResolver::class, $this->returnValueOfPrivateProperty($sut, 'parameterResolver'));
  }

  /**
   *
   * @covers \derbenni\izon\resolver\MethodResolver::can
   */
  public function testCan_ReturnsTrueIfReflectionParameterGiven() {
    self::assertTrue($this->sut->can(new ReflectionMethod(MethodResolverTestDummy::class, 'withoutParameters')));
  }

  /**
   *
   * @covers \derbenni\izon\resolver\MethodResolver::can
   */
  public function testCan_ReturnsFalseIfNoReflectionParameterGiven() {
    self::assertFalse($this->sut->can(123));
    self::assertFalse($this->sut->can(123.456));
    self::assertFalse($this->sut->can(true));
    self::assertFalse($this->sut->can(null));
    self::assertFalse($this->sut->can('foo'));
    self::assertFalse($this->sut->can(new stdClass()));
  }

  /**
   *
   * @covers \derbenni\izon\resolver\MethodResolver::resolve
   * @expectedException InvalidArgumentException
   */
  public function testResolve_CanThrowInvalidArgumentExceptionIfInvalidValueGiven() {
    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();

    $this->sut->resolve(123, $container);
  }

  /**
   *
   * @covers \derbenni\izon\resolver\MethodResolver::resolve
   */
  public function testResolve_CanReturnArgumentsResolvedByParameterResolver() {
    $method = new ReflectionMethod(MethodResolverTestDummy::class, 'withParameters');
    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();

    $parameterResolver = $this->getMockForAbstractClass(iParameterResolver::class);
    $parameterResolver->expects(self::exactly(2))
      ->method('resolve')
      ->with(self::isInstanceOf(ReflectionParameter::class), self::identicalTo($container), self::equalTo(['foo' => 'bar']))
      ->willReturnOnConsecutiveCalls('lorem', 'ipsum');

    $sut = new MethodResolver($parameterResolver);

    self::assertEquals(['lorem', 'ipsum'], $sut->resolve($method, $container, [
        'foo' => 'bar',
    ]));
  }
}
