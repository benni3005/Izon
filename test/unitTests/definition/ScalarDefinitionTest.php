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
use \derbenni\izon\definition\ScalarDefinition;
use \derbenni\izon\test\TestCase;
use \InvalidArgumentException;
use \stdClass;

/**
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
class ScalarDefinitionTest extends TestCase {

  /**
   *
   * @covers \derbenni\izon\definition\ScalarDefinition::__construct
   *
   * @expectedException InvalidArgumentException
   * @expectedExceptionMessage of the type "NULL"
   */
  public function testConstruct_CanThrowExceptionIfValueIsNull() {
    new ScalarDefinition(null);
  }

  /**
   *
   * @covers \derbenni\izon\definition\ScalarDefinition::__construct
   *
   * @expectedException InvalidArgumentException
   * @expectedExceptionMessage of the type "array"
   */
  public function testConstruct_CanThrowExceptionIfValueIsArray() {
    new ScalarDefinition([]);
  }

  /**
   *
   * @covers \derbenni\izon\definition\ScalarDefinition::__construct
   *
   * @expectedException InvalidArgumentException
   * @expectedExceptionMessage of the type "object"
   */
  public function testConstruct_CanThrowExceptionIfValueIsObject() {
    new ScalarDefinition(new stdClass());
  }

  /**
   *
   * @covers \derbenni\izon\definition\ScalarDefinition::__construct
   * @covers \derbenni\izon\definition\ScalarDefinition::define
   */
  public function testDefine_CanSetAndDefineValue() {
    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();
    self::assertEquals('bar', (new ScalarDefinition('bar'))->define($container));
  }
}
