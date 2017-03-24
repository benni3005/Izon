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

namespace derbenni\wp\di\test\unitTests;

use \derbenni\wp\di\Container;
use \derbenni\wp\di\definition\factory\iObjectFactory;
use \derbenni\wp\di\definition\iDefinition;
use \derbenni\wp\di\test\dummy\ContainerTestDummy;
use \derbenni\wp\di\test\TestCase;
use \TypeError;

/**
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
class ContainerTest extends TestCase {

  /**
   *
   * @covers \derbenni\wp\di\Container::__construct
   * @covers \derbenni\wp\di\Container::add
   */
  public function testConstruct_CanSetDepenciesInProperties() {
    $sut = new Container($this->getMockForAbstractClass(iObjectFactory::class), [
      'foo' => $this->getMockForAbstractClass(iDefinition::class),
    ]);

    $definitions = $this->returnValueOfPrivateProperty($sut, 'definitions');
    $objectFactory = $this->returnValueOfPrivateProperty($sut, 'objectFactory');

    self::assertNotEmpty($definitions);
    self::assertInstanceOf(iDefinition::class, $definitions['foo']);
    self::assertInstanceOf(iObjectFactory::class, $objectFactory);
  }

  /**
   *
   * @covers \derbenni\wp\di\Container::__construct
   * @covers \derbenni\wp\di\Container::add
   *
   * @expectedException TypeError
   * @expectedExceptionMessage must be of the type string
   */
  public function testConstruct_CanThrowErrorIfInvalidIdIsGiven() {
    new Container($this->getMockForAbstractClass(iObjectFactory::class), [
      123 => $this->getMockForAbstractClass(iDefinition::class),
    ]);
  }

  /**
   *
   * @covers \derbenni\wp\di\Container::__construct
   * @covers \derbenni\wp\di\Container::add
   *
   * @expectedException TypeError
   * @expectedExceptionMessage must implement interface
   */
  public function testConstruct_CanThrowErrorIfInvalidDefinitionIsGiven() {
    new Container($this->getMockForAbstractClass(iObjectFactory::class), [
      'foo' => 'bar',
    ]);
  }

  /**
   *
   * @covers \derbenni\wp\di\Container::has
   */
  public function testHas_CanReturnTrueIfDefinitionWithIdExists() {
    $sut = new Container($this->getMockForAbstractClass(iObjectFactory::class), [
      'foo' => $this->getMockForAbstractClass(iDefinition::class),
    ]);

    self::assertTrue($sut->has('foo'));
  }

  /**
   *
   * @covers \derbenni\wp\di\Container::has
   */
  public function testHas_CanReturnFalseIfDefinitionWithIdDoesNotExists() {
    self::assertFalse((new Container($this->getMockForAbstractClass(iObjectFactory::class), []))->has('foo'));
  }

  /**
   *
   * @covers \derbenni\wp\di\Container::has
   */
  public function testHas_CanCaReturnsTrueIfUndefinedClassNameWasRequested() {
    $objectDefinition = $this->getMockForAbstractClass(iDefinition::class);

    $objectFactory = $this->getMockForAbstractClass(iObjectFactory::class);
    $objectFactory->expects(self::once())
      ->method('make')
      ->with([ContainerTestDummy::class])
      ->willReturn($objectDefinition);

    $sut = new Container($objectFactory, []);

    self::assertTrue($sut->has(ContainerTestDummy::class));
  }

  /**
   *
   * @covers \derbenni\wp\di\Container::get
   */
  public function testGet_CanReturnValueFromDefinitionIfDefinitionExists() {
    $definition = $this->getMockForAbstractClass(iDefinition::class);
    $definition->expects(self::once())
      ->method('define')
      ->willReturn('bar');

    $sut = new Container($this->getMockForAbstractClass(iObjectFactory::class), [
      'foo' => $definition,
    ]);

    self::assertEquals('bar', $sut->get('foo'));
  }

  /**
   *
   * @covers \derbenni\wp\di\Container::get
   */
  public function testGet_CanReturnValueFromCacheIfDefinitionWasBuiltBefore() {
    $definition = $this->getMockForAbstractClass(iDefinition::class);
    $definition->expects(self::once())
      ->method('define')
      ->willReturn('bar');

    $sut = new Container($this->getMockForAbstractClass(iObjectFactory::class), [
      'foo' => $definition,
    ]);

    self::assertEquals('bar', $sut->get('foo'));
    self::assertEquals('bar', $sut->get('foo'));
  }

  /**
   *
   * @covers \derbenni\wp\di\Container::get
   *
   * @expectedException \derbenni\wp\di\NotFoundException
   * @expectedExceptionMessage not found in the container
   */
  public function testGet_CanThrowExceptionIfDefinitionWasNotFound() {
    (new Container($this->getMockForAbstractClass(iObjectFactory::class), []))->get('foo');
  }

  /**
   *
   * @covers \derbenni\wp\di\Container::get
   */
  public function testGet_CanCanReturnInstanceOfUnconfiguredClassIfResolvable() {
    $objectDefinition = $this->getMockForAbstractClass(iDefinition::class);
    $objectDefinition->expects(self::once())
      ->method('define')
      ->willReturn(new ContainerTestDummy());

    $objectFactory = $this->getMockForAbstractClass(iObjectFactory::class);
    $objectFactory->expects(self::once())
      ->method('make')
      ->with([ContainerTestDummy::class])
      ->willReturn($objectDefinition);

    $sut = new Container($objectFactory, []);

    self::assertInstanceOf(ContainerTestDummy::class, $sut->get(ContainerTestDummy::class));
  }

  /**
   *
   * @covers \derbenni\wp\di\Container::make
   */
  public function testMake_CanReturnValueFromDefinitionEveryTimeItIsRequested() {
    $definition = $this->getMockForAbstractClass(iDefinition::class);
    $definition->expects(self::exactly(2))
      ->method('define')
      ->willReturn('bar');

    $sut = new Container($this->getMockForAbstractClass(iObjectFactory::class), [
      'foo' => $definition,
    ]);

    self::assertEquals('bar', $sut->make('foo'));
    self::assertEquals('bar', $sut->make('foo'));
  }

  /**
   *
   * @covers \derbenni\wp\di\Container::make
   *
   * @expectedException \derbenni\wp\di\NotFoundException
   * @expectedExceptionMessage not found in the container
   */
  public function testMake_CanThrowExceptionIfDefinitionWasNotFound() {
    (new Container($this->getMockForAbstractClass(iObjectFactory::class), []))->make('foo');
  }
}
