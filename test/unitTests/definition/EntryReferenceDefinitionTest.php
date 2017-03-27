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
use \derbenni\izon\definition\EntryReferenceDefinition;
use \derbenni\izon\test\TestCase;

/**
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
class EntryReferenceDefinitionTest extends TestCase {

  /**
   *
   * @covers \derbenni\izon\definition\EntryReferenceDefinition::__construct
   * @covers \derbenni\izon\definition\EntryReferenceDefinition::define
   */
  public function testDefine_CanSetAndDefineValue() {
    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();
    $container->expects(self::once())
      ->method('get')
      ->with(self::equalTo('bar'))
      ->will(self::returnValue('baz'));

    self::assertEquals('baz', (new EntryReferenceDefinition('bar'))->define($container));
  }
}
