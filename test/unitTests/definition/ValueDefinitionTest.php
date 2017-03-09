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

namespace derbenni\wp\di\test\unitTests\definition;

use \derbenni\wp\di\definition\ValueDefinition;
use \derbenni\wp\di\test\TestCase;
use \TypeError;

/**
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
class ValueDefinitionTest extends TestCase {

  /**
   *
   * @covers \derbenni\wp\di\definition\ValueDefinition::__construct
   *
   * @expectedException \InvalidArgumentException
   * @expectedExceptionMessage of the type "NULL"
   */
  public function testConstruct_CanThrowExceptionIfValueIsNull() {
    new ValueDefinition('123', null);
  }

  /**
   *
   * @covers \derbenni\wp\di\definition\ValueDefinition::__construct
   *
   * @expectedException \InvalidArgumentException
   * @expectedExceptionMessage of the type "object"
   */
  public function testConstruct_CanThrowExceptionIfValueIsObject() {
    new ValueDefinition('123', new \stdClass());
  }

  /**
   *
   * @covers \derbenni\wp\di\definition\ValueDefinition::__construct
   * @covers \derbenni\wp\di\definition\ValueDefinition::getId
   */
  public function testConstruct_CanSetAndReturnId() {
    self::assertEquals('foo', (new ValueDefinition('foo', 'bar'))->getId());
  }

  /**
   *
   * @covers \derbenni\wp\di\definition\ValueDefinition::__construct
   * @covers \derbenni\wp\di\definition\ValueDefinition::define
   */
  public function testConstruct_CanSetAndDefineValue() {
    self::assertEquals('bar', (new ValueDefinition('foo', 'bar'))->define());
  }
}
