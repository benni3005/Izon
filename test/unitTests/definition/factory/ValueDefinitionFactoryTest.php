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

namespace derbenni\izon\test\unitTests\definition\factory;

use \derbenni\izon\definition\ArrayDefinition;
use \derbenni\izon\definition\factory\ValueDefinitionFactory;
use \derbenni\izon\definition\ScalarDefinition;
use \derbenni\izon\test\TestCase;
use \InvalidArgumentException;
use \stdClass;

/**
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
class ValueDefinitionFactoryTest extends TestCase {

  /**
   *
   * @var ValueDefinitionFactory
   */
  private $sut = null;

  protected function setUp() {
    parent::setUp();

    $this->sut = new ValueDefinitionFactory();
  }

  /**
   *
   * @covers \derbenni\izon\definition\factory\ValueDefinitionFactory::make
   */
  public function testMake_CanCreateScalarDefinition() {
    self::assertInstanceOf(ScalarDefinition::class, $this->sut->make(['foo']));
  }

  /**
   *
   * @covers \derbenni\izon\definition\factory\ValueDefinitionFactory::make
   */
  public function testMake_CanCreateArrayDefinition() {
    self::assertInstanceOf(ArrayDefinition::class, $this->sut->make([[]]));
  }

  /**
   *
   * @covers \derbenni\izon\definition\factory\ValueDefinitionFactory::make
   *
   * @expectedException InvalidArgumentException
   * @expectedExceptionMessage The given value is neither scalar nor an array.
   */
  public function testMake_ThrowsExceptionIfNoStringOrArrayAsFirstParameterGiven() {
    $this->sut->make([new stdClass()]);
  }
}
