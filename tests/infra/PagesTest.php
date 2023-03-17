<?php

/*
 * Copyright 2023 Christoph M. Becker
 *
 * This file is part of Adventcalendar_XH.
 *
 * Adventcalendar_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Adventcalendar_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Adventcalendar_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Adventcalendar\Infra;

use PHPUnit\Framework\TestCase;

class PagesTest extends TestCase
{
    public function testFindsByHeading(): void
    {
        global $h;

        $h = ["Foo", "Bar", "Baz"];
        $sut = new Pages;
        $page = $sut->findByHeading("Baz");
        $this->assertEquals(2, $page);
    }

    public function testFindsChildrenOf(): void
    {
        global $cl, $l;

        $l = [1, 2, 3, 2, 1];
        $cl = count($l);
        $sut = new Pages;
        $children = $sut->childrenOf(0);
        $this->assertEquals([1, 3], $children);
    }
}
