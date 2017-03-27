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
use \derbenni\izon\resolver\parameter\ClassNameParameterResolver;
use \derbenni\izon\test\dummy\ParameterResolverTestDummy;
use \derbenni\izon\test\TestCase;
use \Exception;
use \ReflectionParameter;
use \stdClass;

/**
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
class ClassNameParameterResolverTest extends TestCase {

  /**
   *
   * @var ClassNameParameterResolver
   */
  private $sut = null;

  protected function setUp() {
    parent::setUp();

    $this->sut = new ClassNameParameterResolver();
  }

  /**
   *
   * @covers \derbenni\izon\resolver\parameter\ClassNameParameterResolver::can
   */
  public function testCan_ReturnsTrueIfTheGivenParameterIsATypeHint() {
    $parameter = new ReflectionParameter([ParameterResolverTestDummy::class, 'requiredClassParameter'], 'foo');

    self::assertTrue($this->sut->can($parameter, [0 => 'bar']));
  }

  /**
   *
   * @covers \derbenni\izon\resolver\parameter\ClassNameParameterResolver::can
   */
  public function testCan_ReturnsFalseIfGivenParameterIsNotATypeHint() {
    $parameter = new ReflectionParameter([ParameterResolverTestDummy::class, 'unspecifiedParameter'], 'foo');

    self::assertFalse($this->sut->can($parameter, []));
  }

  /**
   *
   * @covers \derbenni\izon\resolver\parameter\ClassNameParameterResolver::resolve
   */
  public function testResolve_CanReturnAnInstanceOfTheTypeHintedClass() {
    $parameter = new ReflectionParameter([ParameterResolverTestDummy::class, 'requiredClassParameter'], 'foo');
    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();
    $container->expects(self::once())
      ->method('get')
      ->with(stdClass::class)
      ->willReturn(new stdClass());

    self::assertInstanceOf(stdClass::class, $this->sut->resolve($parameter, $container, []));
  }

  /**
   *
   * @covers \derbenni\izon\resolver\parameter\ClassNameParameterResolver::resolve
   */
  public function testResolve_CanReturnTheDeafultValueOfATypeHintIfClassCouldNotBeResolved() {
    $parameter = new ReflectionParameter([ParameterResolverTestDummy::class, 'optionalClassParameter'], 'foo');
    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();
    $container->expects(self::once())
      ->method('get')
      ->with(stdClass::class)
      ->willThrowException(new Exception());

    self::assertNull($this->sut->resolve($parameter, $container, []));
  }

  /**
   *
   * @covers \derbenni\izon\resolver\parameter\ClassNameParameterResolver::resolve
   * @expectedException \Exception
   */
  public function testResolve_ThrowsExceptionIftypeHintCouldNotBeResolvedAndNoDefaultValueIsAvailable() {
    $parameter = new ReflectionParameter([ParameterResolverTestDummy::class, 'requiredClassParameter'], 'foo');
    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();
    $container->expects(self::once())
      ->method('get')
      ->with(stdClass::class)
      ->willThrowException(new Exception());

    $this->sut->resolve($parameter, $container, []);
  }
}
