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

use \derbenni\wp\di\Container;
use \derbenni\wp\di\definition\ArrayDefinition;
use \derbenni\wp\di\definition\iDefinition;
use \derbenni\wp\di\definition\resolver\iResolver;
use \derbenni\wp\di\test\TestCase;

/**
 *
 * @author Benjamin Hofmann <benni@derbenni.rocks>
 */
class ArrayDefinitionTest extends TestCase {

  /**
   *
   * @covers \derbenni\wp\di\definition\ArrayDefinition::__construct
   * @covers \derbenni\wp\di\definition\ArrayDefinition::define
   */
  public function testDefine_CanSetAndDefineValueIfNoSubDefinitionsGiven() {
    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();

    $resolver = $this->getMockForAbstractClass(iResolver::class);
    $resolver->expects(self::once())
      ->method('resolve')
      ->with(self::equalTo([1, 2, 3]))
      ->willReturn([1, 2, 3]);

    self::assertEquals([1, 2, 3], (new ArrayDefinition([1, 2, 3], $resolver))->define($container));
  }

  /**
   *
   * @covers \derbenni\wp\di\definition\ArrayDefinition::__construct
   * @covers \derbenni\wp\di\definition\ArrayDefinition::define
   */
  public function testDefine_CanSetAndDefineValueIfSubDefinitionsGiven() {
    $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();

    $definition = $this->getMockForAbstractClass(iDefinition::class);

    $resolver = $this->getMockForAbstractClass(iResolver::class);
    $resolver->expects(self::once())
      ->method('resolve')
      ->with(self::equalTo([$definition]))
      ->willReturn(['ipsum']);

    self::assertEquals(['ipsum'], (new ArrayDefinition([$definition], $resolver))->define($container));
  }
}
