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
use \derbenni\wp\di\definition\iDefinition;
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
  public function testConstruct_CanSetDefinitionsInProperty() {
    $container = new Container([
      'foo' => $this->getMockForAbstractClass(iDefinition::class),
    ]);

    $definitions = $this->returnValueOfPrivateProperty($container, 'definitions');

    self::assertNotEmpty($definitions);
    self::assertInstanceOf(iDefinition::class, $definitions['foo']);
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
    new Container([
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
    new Container([
      'foo' => 'bar',
    ]);
  }

  /**
   *
   * @covers \derbenni\wp\di\Container::has
   */
  public function testHas_CanReturnTrueIfDefinitionWithIdExists() {
    $container = new Container([
      'foo' => $this->getMockForAbstractClass(iDefinition::class),
    ]);

    self::assertTrue($container->has('foo'));
  }

  /**
   *
   * @covers \derbenni\wp\di\Container::has
   */
  public function testHas_CanReturnFalseIfDefinitionWithIdDoesNotExists() {
    self::assertFalse((new Container([]))->has('foo'));
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

    $container = new Container([
      'foo' => $definition,
    ]);

    self::assertEquals('bar', $container->get('foo'));
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

    $container = new Container([
      'foo' => $definition,
    ]);

    self::assertEquals('bar', $container->get('foo'));
    self::assertEquals('bar', $container->get('foo'));
  }

  /**
   *
   * @covers \derbenni\wp\di\Container::get
   *
   * @expectedException \derbenni\wp\di\NotFoundException
   * @expectedExceptionMessage not found in the container
   */
  public function testGet_CanThrowExceptionIfDefinitionWasNotFound() {
    (new Container([]))->get('foo');
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

    $container = new Container([
      'foo' => $definition,
    ]);

    self::assertEquals('bar', $container->make('foo'));
    self::assertEquals('bar', $container->make('foo'));
  }

  /**
   *
   * @covers \derbenni\wp\di\Container::make
   *
   * @expectedException \derbenni\wp\di\NotFoundException
   * @expectedExceptionMessage not found in the container
   */
  public function testMake_CanThrowExceptionIfDefinitionWasNotFound() {
    (new Container([]))->make('foo');
  }
}
