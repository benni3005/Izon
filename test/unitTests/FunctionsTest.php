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
use \derbenni\izon\definition\ArrayDefinition;
use \derbenni\izon\definition\EntryReferenceDefinition;
use \derbenni\izon\definition\ExpressionDefinition;
use \derbenni\izon\definition\FactoryDefinition;
use \derbenni\izon\definition\ObjectDefinition;
use \derbenni\izon\definition\ScalarDefinition;
use \derbenni\izon\test\TestCase;
use \stdClass;
use function \derbenni\izon\expression;
use function \derbenni\izon\factory;
use function \derbenni\izon\get;
use function \derbenni\izon\object;
use function \derbenni\izon\value;

/**
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
class FunctionsTest extends TestCase {

  /**
   *
   * @covers ::derbenni\izon\value
   */
  public function testValue_CanReturnCorrectDefinitionIfScalarValueIsGiven() {
    self::assertInstanceOf(ScalarDefinition::class, value('foo'));
  }

  /**
   *
   * @covers ::derbenni\izon\value
   */
  public function testValue_CanReturnCorrectDefinitionIfArrayIsGiven() {
    self::assertInstanceOf(ArrayDefinition::class, value([]));
  }

  /**
   *
   * @covers ::derbenni\izon\expression
   */
  public function testExpression_CanReturnCorrectDefinition() {
    self::assertInstanceOf(ExpressionDefinition::class, expression('foo'));
  }

  /**
   *
   * @covers ::derbenni\izon\object
   */
  public function testObject_CanReturnCorrectDefinition() {
    self::assertInstanceOf(ObjectDefinition::class, object(stdClass::class));
  }

  /**
   *
   * @covers ::derbenni\izon\factory
   */
  public function testFactory_CanReturnCorrectDefinition() {
    self::assertInstanceOf(FactoryDefinition::class, factory(function(Container $container) {
        return null;
      }));
  }

  /**
   *
   * @covers ::derbenni\izon\get
   */
  public function testGet_CanReturnCorrectDefinition() {
    self::assertInstanceOf(EntryReferenceDefinition::class, get('foo'));
  }
}
