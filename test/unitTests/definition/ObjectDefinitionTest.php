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

namespace derbenni\izon\test\unitTests\definition;

use \derbenni\izon\Container;
use \derbenni\izon\definition\ObjectDefinition;
use \derbenni\izon\resolver\iMethodResolver;
use \derbenni\izon\resolver\iObjectResolver;
use \derbenni\izon\test\dummy\ObjectDefinitionTestDummy;
use \derbenni\izon\test\TestCase;
use \ReflectionMethod;

/**
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
class ObjectDefinitionTest extends TestCase {

  /**
   *
   * @var ObjectDefinition
   */
  private $sut = null;

  protected function setUp() {
    parent::setUp();

    $objectResolver = $this->getMockForAbstractClass(iObjectResolver::class);
    $methodResolver = $this->getMockForAbstractClass(iMethodResolver::class);

    $this->sut = new ObjectDefinition('', $objectResolver, $methodResolver);
  }

  /**
   *
   * @covers \derbenni\izon\definition\ObjectDefinition::constructor
   */
  public function testConstructor_CanSetConstructorParameters() {
    $this->sut->constructor('foo', 'bar');

    self::assertEquals(['foo', 'bar'], $this->returnValueOfPrivateProperty($this->sut, 'constructor'));
  }

  /**
   *
   * @covers \derbenni\izon\definition\ObjectDefinition::constructorParameter
   */
  public function testConstructorParameter_CanSetParameters() {
    $this->sut->constructorParameter('foo', 1);
    $this->sut->constructorParameter('bar', 2);
    $this->sut->constructorParameter('bar', 3);

    $expected = [
      'foo' => 1,
      'bar' => 3,
    ];

    self::assertEquals($expected, $this->returnValueOfPrivateProperty($this->sut, 'constructor'));
  }

  /**
   *
   * @covers \derbenni\izon\definition\ObjectDefinition::method
   */
  public function testMethod_CanSetParameters() {
    $this->sut->method('method', 'foo', 'bar');
    $this->sut->method('method', 1, 2, 3);

    $expected = [
      'method' => [
        0 => ['foo', 'bar'],
        1 => [1, 2, 3],
      ],
    ];

    self::assertEquals($expected, $this->returnValueOfPrivateProperty($this->sut, 'methods'));
  }

  /**
   *
   * @covers \derbenni\izon\definition\ObjectDefinition::method
   * @covers \derbenni\izon\definition\ObjectDefinition::constructor
   */
  public function testMethod_CanSetConstructorParameters() {
    $this->sut->method('__construct', 'foo', 'bar');

    self::assertEquals([], $this->returnValueOfPrivateProperty($this->sut, 'methods'));
    self::assertEquals(['foo', 'bar'], $this->returnValueOfPrivateProperty($this->sut, 'constructor'));
  }

  /**
   *
   * @covers \derbenni\izon\definition\ObjectDefinition::methodParameter
   */
  public function testMethodParameter_CanSetParameters() {
    $this->sut->methodParameter('method', 'foo', 1);
    $this->sut->methodParameter('method', 'bar', 2);
    $this->sut->methodParameter('method', 'bar', 3);

    $expected = [
      'method' => [
        0 => [
          'foo' => 1,
          'bar' => 3,
        ],
      ],
    ];

    self::assertEquals($expected, $this->returnValueOfPrivateProperty($this->sut, 'methods'));
  }

  /**
   *
   * @covers \derbenni\izon\definition\ObjectDefinition::methodParameter
   */
  public function testMethodParameter_CanSetConstructorParameters() {
    $this->sut->methodParameter('__construct', 'foo', 1);
    $this->sut->methodParameter('__construct', 'bar', 2);
    $this->sut->methodParameter('__construct', 'bar', 3);

    $expected = [
      'foo' => 1,
      'bar' => 3,
    ];

    self::assertEquals([], $this->returnValueOfPrivateProperty($this->sut, 'methods'));
    self::assertEquals($expected, $this->returnValueOfPrivateProperty($this->sut, 'constructor'));
  }

  /**
   *
   * @covers \derbenni\izon\definition\ObjectDefinition::__construct
   * @covers \derbenni\izon\definition\ObjectDefinition::define
   */
  public function testDefine_CanReturnInstanceOfTheDesiredObject() {
    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();

    $objectResolver = $this->getMockForAbstractClass(iObjectResolver::class);
    $objectResolver->expects(self::once())
      ->method('resolve')
      ->with(self::equalTo(ObjectDefinitionTestDummy::class), self::equalTo($container))
      ->willReturn(new ObjectDefinitionTestDummy());

    $methodResolver = $this->getMockForAbstractClass(iMethodResolver::class);
    $methodResolver->expects(self::once())
      ->method('resolve')
      ->with(self::isInstanceOf(ReflectionMethod::class), self::equalTo($container), self::equalTo([1, 2]))
      ->willReturn([1, 2]);

    $sut = new ObjectDefinition(ObjectDefinitionTestDummy::class, $objectResolver, $methodResolver);
    $sut->method('method', 1, 2);

    self::assertInstanceOf(ObjectDefinitionTestDummy::class, $sut->define($container));
  }
}
