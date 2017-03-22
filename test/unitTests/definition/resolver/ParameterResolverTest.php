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
use \derbenni\wp\di\definition\resolver\ParameterResolver;
use \derbenni\wp\di\test\TestCase;
use \derbenni\wp\di\test\unitTests\definition\resolver\resolverDummy\ParameterResolverTestDummy;
use \Exception;
use \InvalidArgumentException;
use \ReflectionParameter;
use \stdClass;

/**
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
class ParameterResolverTest extends TestCase {

  /**
   *
   * @var ParameterResolver
   */
  private $sut = null;

  protected function setUp() {
    parent::setUp();

    $this->sut = new ParameterResolver();
  }

  /**
   *
   * @covers \derbenni\wp\di\definition\resolver\ParameterResolver::can
   */
  public function testCan_ReturnsTrueIfReflectionParameterGiven() {
    self::assertTrue($this->sut->can(new ReflectionParameter('strpos', 'haystack')));
  }

  /**
   *
   * @covers \derbenni\wp\di\definition\resolver\ParameterResolver::can
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
   * @covers \derbenni\wp\di\definition\resolver\ParameterResolver::resolve
   * @expectedException InvalidArgumentException
   */
  public function testResolve_CanThrowInvalidArgumentExceptionIfInvalidValueGiven() {
    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();

    $this->sut->resolve(123, $container);
  }

  /**
   *
   * @covers \derbenni\wp\di\definition\resolver\ParameterResolver::resolve
   */
  public function testResolve_CanReturnValueByProvidingNamedArgumentsArray() {
    $parameter = new ReflectionParameter([ParameterResolverTestDummy::class, 'unspecifiedParameter'], 'foo');
    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();

    self::assertEquals('bar', $this->sut->resolve($parameter, $container, [
        'foo' => 'bar',
    ]));
  }

  /**
   *
   * @covers \derbenni\wp\di\definition\resolver\ParameterResolver::resolve
   */
  public function testResolve_CanReturnValueByProvidingNumberedArgumentsArray() {
    $parameter = new ReflectionParameter([ParameterResolverTestDummy::class, 'unspecifiedParameter'], 'foo');
    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();

    self::assertEquals('bar', $this->sut->resolve($parameter, $container, [
        0 => 'bar',
    ]));
  }

  /**
   *
   * @covers \derbenni\wp\di\definition\resolver\ParameterResolver::resolve
   * @expectedException \derbenni\wp\di\DependencyException
   * @expectedExceptionMessage Dependency of class
   */
  public function testResolve_ThrowsExceptionIfRequiredClassValueCanNotBeResolved() {
    $parameter = new ReflectionParameter([ParameterResolverTestDummy::class, 'requiredClassParameter'], 'foo');

    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();
    $container->expects(self::once())
      ->method('get')
      ->with(self::equalTo(stdClass::class))
      ->willThrowException(new Exception());

    $this->sut->resolve($parameter, $container);
  }

  /**
   *
   * @covers \derbenni\wp\di\definition\resolver\ParameterResolver::resolve
   */
  public function testResolve_CanReturnClassValueIfRequired() {
    $parameter = new ReflectionParameter([ParameterResolverTestDummy::class, 'requiredClassParameter'], 'foo');

    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();
    $container->expects(self::once())
      ->method('get')
      ->with(self::equalTo(stdClass::class))
      ->willReturn(new stdClass());

    self::assertInstanceOf(stdClass::class, $this->sut->resolve($parameter, $container));
  }

  /**
   *
   * @covers \derbenni\wp\di\definition\resolver\ParameterResolver::resolve
   */
  public function testResolve_CanReturnClassValueIfOptional() {
    $parameter = new ReflectionParameter([ParameterResolverTestDummy::class, 'optionalClassParameter'], 'foo');

    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();
    $container->expects(self::once())
      ->method('get')
      ->with(self::equalTo(stdClass::class))
      ->willThrowException(new Exception());

    self::assertEquals(null, $this->sut->resolve($parameter, $container));
  }

  /**
   *
   * @covers \derbenni\wp\di\definition\resolver\ParameterResolver::resolve
   */
  public function testResolve_CanReturnDefaultValueIfNoClassHasToBeResolved() {
    $parameter = new ReflectionParameter([ParameterResolverTestDummy::class, 'defaultValueParameter'], 'foo');
    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();

    self::assertEquals('bar', $this->sut->resolve($parameter, $container));
  }

  /**
   *
   * @covers \derbenni\wp\di\definition\resolver\ParameterResolver::resolve
   * @expectedException \derbenni\wp\di\DependencyException
   * @expectedExceptionMessage Parameter "foo" of method "unspecifiedParameter"
   */
  public function testResolve_ThrowsExceptionIfResolvingWasNotPossible() {
    $parameter = new ReflectionParameter([ParameterResolverTestDummy::class, 'unspecifiedParameter'], 'foo');
    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();

    $this->sut->resolve($parameter, $container);
  }

  /**
   *
   * @covers \derbenni\wp\di\definition\resolver\ParameterResolver::resolve
   */
  public function testResolve_CanReturnValueProvidedByConfiguredDefinitionArgument() {
    $parameter = new ReflectionParameter([ParameterResolverTestDummy::class, 'unspecifiedParameter'], 'foo');
    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();

    $definition = $this->getMockForAbstractClass(\derbenni\wp\di\definition\iDefinition::class);
    $definition->expects(self::once())
      ->method('define')
      ->with(self::identicalTo($container))
      ->willReturn('bar');

    self::assertEquals('bar', $this->sut->resolve($parameter, $container, [
        0 => $definition,
    ]));
  }
}
