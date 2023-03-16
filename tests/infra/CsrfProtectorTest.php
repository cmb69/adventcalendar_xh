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

class CsrfProtectorTest extends TestCase
{
    public function testRetrievesToken(): void
    {
        $sut = $this->sut();
        $sut->method("tokenInput")->willReturn(
            "<input type=\"hidden\" name=\"xh_csrf_token\" value=\"0ecaa0f044230af99f72073f6a7aa4ab\">"
        );
        $token = $sut->token();
        $this->assertEquals("0ecaa0f044230af99f72073f6a7aa4ab", $token);
    }

    public function testFailsToRetrieveToken(): void
    {
        $sut = $this->sut();
        $sut->method("tokenInput")->willReturn("");
        $this->expectExceptionMessage("CSRF protection is broken!");
        $token = $sut->token();
    } 

    private function sut()
    {
        return $this->getMockBuilder(CsrfProtector::class)
        ->disableOriginalConstructor()
        ->disableOriginalClone()
        ->disableArgumentCloning()
        ->disallowMockingUnknownTypes()
        ->onlyMethods(["tokenInput"])
        ->getMock();
    }
}
