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

namespace derbenni\wp\di\test\unitTests\definition\factory;

use \derbenni\wp\di\definition\ExpressionDefinition;
use \derbenni\wp\di\definition\factory\ExpressionDefinitionFactory;
use \derbenni\wp\di\test\TestCase;

/**
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
class ExpressionDefinitionFactoryTest extends TestCase {

  /**
   *
   * @var ExpressionDefinitionFactory
   */
  private $sut = null;

  protected function setUp() {
    parent::setUp();

    $this->sut = new ExpressionDefinitionFactory();
  }

  /**
   *
   * @covers \derbenni\wp\di\definition\factory\ExpressionDefinitionFactory::make
   */
  public function testMake_CanCreateDefinition() {
    self::assertInstanceOf(ExpressionDefinition::class, $this->sut->make(['foo']));
  }

  /**
   *
   * @covers \derbenni\wp\di\definition\factory\ExpressionDefinitionFactory::make
   *
   * @expectedException \InvalidArgumentException
   * @expectedExceptionMessage The given expression is not a string.
   */
  public function testMake_ThrowsExceptionIfNoStringAsFirstParameterGiven() {
    $this->sut->make([123]);
  }
}
