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

namespace Adventcalendar;

use Adventcalendar\Infra\Jquery;
use Adventcalendar\Infra\Pages;
use Adventcalendar\Infra\Repository;
use Adventcalendar\Infra\RequestStub;
use Adventcalendar\Infra\View;
use ApprovalTests\Approvals;
use PHPUnit\Framework\TestCase;

class MainControllerTest extends TestCase
{
    public function testFailsOnMissingDoors(): void
    {
        $sut = $this->sut(["findDoors" => null]);
        $request = new RequestStub();
        $response = $sut($request, "winter");
        $this->assertStringContainsString("The cover of 'winter' is not yet prepared!", $response->output());
    }

    public function testFailsOnMissingCover(): void
    {
        $sut = $this->sut(["findCover" => null]);
        $request = new RequestStub();
        $response = $sut($request, "winter");
        $this->assertStringContainsString("The cover of 'winter' is not yet prepared!", $response->output());
    }

    public function testFailsOnMissingPage(): void
    {
        $sut = $this->sut(["findByHeading" => -1]);
        $request = new RequestStub();
        $response = $sut($request, "winter");
        $this->assertStringContainsString("The page &quot;winter&quot; does not exist!", $response->output());
    }

    public function testRendersCalendar(): void
    {
        $sut = $this->sut();
        $request = new RequestStub(["time" => strtotime("2014-12-02")]);
        $response = $sut($request, "winter");
        Approvals::verifyHtml($response->output());
    }

    public function testAdminSeesAllDoors(): void
    {
        $sut = $this->sut();
        $request = new RequestStub(["adm" => true, "time" => strtotime("2014-12-02")]);
        $response = $sut($request, "winter");
        Approvals::verifyHtml($response->output());
    }

    private function sut(array $options = [])
    {
        $options += [
            "findByHeading" => 16,
            "findCover" => "winter+.jpg",
            "findDoors" => [[0, 0, 20, 20], [30, 0, 20, 20], [60, 0, 20, 20]]
        ];
        $conf = XH_includeVar("./config/config.php", "plugin_cf")["adventcalendar"];
        $pages = $this->createMock(Pages::class);
        $pages->method("findByHeading")->willReturn($options["findByHeading"]);
        $pages->method("childrenOf")->willReturn([17, 18, 19]);
        $pages->method("urlOf")->willReturnCallback(function (int $page) {
            return "Day" . ($page - 16);
        });
        $repository = $this->createMock(Repository::class);
        $repository->method("findDoors")->willReturn($options["findDoors"]);
        $repository->method("findCover")->willReturn($options["findCover"]);
        $jquery = $this->createMock(Jquery::class);
        $view = new View("./views/", XH_includeVar("./languages/en.php", "plugin_tx")["adventcalendar"]);
        return new MainController("./plugins/adventcalendar/", $conf, $pages, $repository, $jquery, $view);
    }
}
