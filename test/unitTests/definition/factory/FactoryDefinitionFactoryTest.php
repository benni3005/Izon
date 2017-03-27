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

use \derbenni\izon\Container;
use \derbenni\izon\definition\factory\FactoryDefinitionFactory;
use \derbenni\izon\definition\FactoryDefinition;
use \derbenni\izon\test\TestCase;
use \InvalidArgumentException;

/**
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
class FactoryDefinitionFactoryTest extends TestCase {

  /**
   *
   * @var FactoryDefinitionFactory
   */
  private $sut = null;

  protected function setUp() {
    parent::setUp();

    $this->sut = new FactoryDefinitionFactory();
  }

  /**
   *
   * @covers \derbenni\izon\definition\factory\FactoryDefinitionFactory::make
   */
  public function testMake_CanCreateDefinition() {
    self::assertInstanceOf(FactoryDefinition::class, $this->sut->make([function(Container $container) {
          return null;
        }]));
  }

  /**
   *
   * @covers \derbenni\izon\definition\factory\FactoryDefinitionFactory::make
   *
   * @expectedException InvalidArgumentException
   * @expectedExceptionMessage The given factory is not a callable.
   */
  public function testMake_ThrowsExceptionIfNoCallableAsFirstParameterGiven() {
    $this->sut->make([123]);
  }
}
