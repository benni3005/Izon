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
use \InvalidArgumentException;

/**
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
class ContainerTest extends TestCase {

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
   * @expectedException InvalidArgumentException
   * @expectedExceptionMessage Only strings are allowed
   */
  public function testConstruct_CanThrowExceptionIfInvalidIdIsGiven() {
    new Container([
      123 => 'bar',
    ]);
  }

  /**
   *
   * @expectedException InvalidArgumentException
   * @expectedExceptionMessage is not a valid definition
   */
  public function testConstruct_CanThrowExceptionIfInvalidDefinitionIsGiven() {
    new Container([
      'foo' => 'bar',
    ]);
  }

  public function testHas_CanReturnTrueIfDefinitionWithIdExists() {
    $container = new Container([
      'foo' => $this->getMockForAbstractClass(iDefinition::class),
    ]);

    self::assertTrue($container->has('foo'));
  }

  public function testHas_CanReturnFalseIfDefinitionWithIdDoesNotExists() {
    self::assertFalse((new Container([]))->has('foo'));
  }

  public function testGet_CanReturnValueFromDefinitionIfDefinitionExists() {
    $definition = $this->getMockForAbstractClass(iDefinition::class);
    $definition->expects(self::once())
      ->method('getValue')
      ->willReturn('bar');

    $container = new Container([
      'foo' => $definition,
    ]);

    self::assertEquals('bar', $container->get('foo'));
  }

  /**
   *
   * @expectedException \derbenni\wp\di\NotFoundException
   * @expectedExceptionMessage not found in the container
   */
  public function testGet_CanThrowExceptionIfDefinitionWasNotFound() {
    (new Container([]))->get('foo');
  }
}
