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

namespace derbenni\izon\test\unitTests;

use \derbenni\izon\Container;
use \derbenni\izon\definition\factory\iObjectDefinitionFactory;
use \derbenni\izon\definition\iDefinition;
use \derbenni\izon\test\dummy\CircularDependencyOne;
use \derbenni\izon\test\dummy\CircularDependencyTwo;
use \derbenni\izon\test\dummy\ContainerTestDummy;
use \derbenni\izon\test\TestCase;
use \TypeError;
use function \derbenni\izon\object;

/**
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
class ContainerTest extends TestCase {

  /**
   *
   * @covers \derbenni\izon\Container::__construct
   * @covers \derbenni\izon\Container::add
   */
  public function testConstruct_CanSetDepenciesInProperties() {
    $sut = new Container($this->getMockForAbstractClass(iObjectDefinitionFactory::class), [
      'foo' => $this->getMockForAbstractClass(iDefinition::class),
    ]);

    $definitions = $this->returnValueOfPrivateProperty($sut, 'definitions');
    $objectFactory = $this->returnValueOfPrivateProperty($sut, 'objectFactory');

    self::assertNotEmpty($definitions);
    self::assertInstanceOf(iDefinition::class, $definitions['foo']);
    self::assertInstanceOf(iObjectDefinitionFactory::class, $objectFactory);
  }

  /**
   *
   * @covers \derbenni\izon\Container::__construct
   * @covers \derbenni\izon\Container::add
   *
   * @expectedException TypeError
   * @expectedExceptionMessage must be of the type string
   */
  public function testConstruct_CanThrowErrorIfInvalidIdIsGiven() {
    new Container($this->getMockForAbstractClass(iObjectDefinitionFactory::class), [
      123 => $this->getMockForAbstractClass(iDefinition::class),
    ]);
  }

  /**
   *
   * @covers \derbenni\izon\Container::__construct
   * @covers \derbenni\izon\Container::add
   *
   * @expectedException TypeError
   * @expectedExceptionMessage must implement interface
   */
  public function testConstruct_CanThrowErrorIfInvalidDefinitionIsGiven() {
    new Container($this->getMockForAbstractClass(iObjectDefinitionFactory::class), [
      'foo' => 'bar',
    ]);
  }

  /**
   *
   * @covers \derbenni\izon\Container::has
   */
  public function testHas_CanReturnTrueIfDefinitionWithIdExists() {
    $sut = new Container($this->getMockForAbstractClass(iObjectDefinitionFactory::class), [
      'foo' => $this->getMockForAbstractClass(iDefinition::class),
    ]);

    self::assertTrue($sut->has('foo'));
  }

  /**
   *
   * @covers \derbenni\izon\Container::has
   */
  public function testHas_CanReturnFalseIfDefinitionWithIdDoesNotExists() {
    self::assertFalse((new Container($this->getMockForAbstractClass(iObjectDefinitionFactory::class), []))->has('foo'));
  }

  /**
   *
   * @covers \derbenni\izon\Container::has
   */
  public function testHas_CanCaReturnsTrueIfUndefinedClassNameWasRequested() {
    $objectDefinition = $this->getMockForAbstractClass(iDefinition::class);

    $objectFactory = $this->getMockForAbstractClass(iObjectDefinitionFactory::class);
    $objectFactory->expects(self::once())
      ->method('make')
      ->with([ContainerTestDummy::class])
      ->willReturn($objectDefinition);

    $sut = new Container($objectFactory, []);

    self::assertTrue($sut->has(ContainerTestDummy::class));
  }

  /**
   *
   * @covers \derbenni\izon\Container::get
   * @covers \derbenni\izon\Container::resolveDefinition
   */
  public function testGet_CanReturnValueFromDefinitionIfDefinitionExists() {
    $definition = $this->getMockForAbstractClass(iDefinition::class);
    $definition->expects(self::once())
      ->method('define')
      ->willReturn('bar');

    $sut = new Container($this->getMockForAbstractClass(iObjectDefinitionFactory::class), [
      'foo' => $definition,
    ]);

    self::assertEquals('bar', $sut->get('foo'));
  }

  /**
   *
   * @covers \derbenni\izon\Container::get
   * @covers \derbenni\izon\Container::resolveDefinition
   */
  public function testGet_CanReturnValueFromCacheIfDefinitionWasBuiltBefore() {
    $definition = $this->getMockForAbstractClass(iDefinition::class);
    $definition->expects(self::once())
      ->method('define')
      ->willReturn('bar');

    $sut = new Container($this->getMockForAbstractClass(iObjectDefinitionFactory::class), [
      'foo' => $definition,
    ]);

    self::assertEquals('bar', $sut->get('foo'));
    self::assertEquals('bar', $sut->get('foo'));
  }

  /**
   *
   * @covers \derbenni\izon\Container::get
   * @covers \derbenni\izon\Container::resolveDefinition
   *
   * @expectedException \derbenni\izon\NotFoundException
   * @expectedExceptionMessage not found in the container
   */
  public function testGet_CanThrowExceptionIfDefinitionWasNotFound() {
    (new Container($this->getMockForAbstractClass(iObjectDefinitionFactory::class), []))->get('foo');
  }

  /**
   *
   * @covers \derbenni\izon\Container::get
   * @covers \derbenni\izon\Container::resolveDefinition
   */
  public function testGet_CanCanReturnInstanceOfUnconfiguredClassIfResolvable() {
    $objectDefinition = $this->getMockForAbstractClass(iDefinition::class);
    $objectDefinition->expects(self::once())
      ->method('define')
      ->willReturn(new ContainerTestDummy());

    $objectFactory = $this->getMockForAbstractClass(iObjectDefinitionFactory::class);
    $objectFactory->expects(self::once())
      ->method('make')
      ->with([ContainerTestDummy::class])
      ->willReturn($objectDefinition);

    $sut = new Container($objectFactory, []);

    self::assertInstanceOf(ContainerTestDummy::class, $sut->get(ContainerTestDummy::class));
  }

  /**
   *
   * @covers \derbenni\izon\Container::make
   * @covers \derbenni\izon\Container::resolveDefinition
   */
  public function testMake_CanReturnValueFromDefinitionEveryTimeItIsRequested() {
    $definition = $this->getMockForAbstractClass(iDefinition::class);
    $definition->expects(self::exactly(2))
      ->method('define')
      ->willReturn('bar');

    $sut = new Container($this->getMockForAbstractClass(iObjectDefinitionFactory::class), [
      'foo' => $definition,
    ]);

    self::assertEquals('bar', $sut->make('foo'));
    self::assertEquals('bar', $sut->make('foo'));
  }

  /**
   *
   * @covers \derbenni\izon\Container::make
   * @covers \derbenni\izon\Container::resolveDefinition
   *
   * @expectedException \derbenni\izon\NotFoundException
   * @expectedExceptionMessage not found in the container
   */
  public function testMake_CanThrowExceptionIfDefinitionWasNotFound() {
    (new Container($this->getMockForAbstractClass(iObjectDefinitionFactory::class), []))->make('foo');
  }

  /**
   *
   * @covers \derbenni\izon\Container::resolveDefinition
   *
   * @expectedException \derbenni\izon\DependencyException
   * @expectedExceptionMessage Circular dependency detected
   */
  public function testGet_CanThrowExceptionIfCircularDependencyDetected() {
    $sut = new Container($this->getMockForAbstractClass(iObjectDefinitionFactory::class), [
      CircularDependencyOne::class => object(CircularDependencyOne::class),
      CircularDependencyTwo::class => object(CircularDependencyTwo::class),
    ]);
    $sut->get(CircularDependencyOne::class);
  }
}
