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

use Adventcalendar\Infra\CsrfProtector;
use Adventcalendar\Infra\DoorDrawer;
use Adventcalendar\Infra\Repository;
use Adventcalendar\Infra\Request;
use Adventcalendar\Infra\Shuffler;
use Adventcalendar\Infra\View;
use Adventcalendar\Value\Url;
use ApprovalTests\Approvals;
use PHPUnit\Framework\TestCase;

class MainAdminControllerTest extends TestCase
{
    public function testRendersOverview(): void
    {
        $sut = $this->sut();
        $response = $sut($this->request(), "");
        Approvals::verifyHtml($response->output());
    }

    public function testPreparesCover(): void
    {
        $_POST = ["adventcalendar_name" => "2023"];
        $sut = $this->sut(["check" => true, "shuffle" => true, "saveCover" => true, "saveDoors" => true]);
        $response = $sut($this->request(), "prepare");
        $this->assertEquals(
            "http://example.com/?adventcalendar&admin=plugin_main&action=view&adventcalendar_name=2023",
            $response->location()
        );
    }

    public function testReportsMissingImage(): void
    {
        $_POST = ["adventcalendar_name" => "2023"];
        $sut = $this->sut(["findImage" => null, "check" => true]);
        $response = $sut($this->request(), "prepare");
        Approvals::verifyHtml($response->output());
    }

    public function testReportsFailureToSaveCover(): void
    {
        $_POST = ["adventcalendar_name" => "2023"];
        $sut = $this->sut(["check" => true, "shuffle" => true, "saveCover" => true, "saveCoverRes" => false]);
        $response = $sut($this->request(), "prepare");
        Approvals::verifyHtml($response->output());
    }

    public function testReportsFailureToSaveDoors(): void
    {
        $_POST = ["adventcalendar_name" => "2023"];
        $sut = $this->sut(["check" => true, "shuffle" => true, "saveCover" => true, "saveDoors" => true, "saveDoorsRes" => false]);
        $response = $sut($this->request(), "prepare");
        Approvals::verifyHtml($response->output());
    }

    public function testShowsPreparedCover(): void
    {
        $sut = $this->sut(["findCover" => "./plugins/adventcalendar/data/2023+.jpg"]);
        $response = $sut($this->request(), "view");
        Approvals::verifyHtml($response->output());
    }

    public function testReportsFailureToShowPreparedCover(): void
    {
        $sut = $this->sut();
        $response = $sut($this->request(), "view");
        Approvals::verifyHtml($response->output());
    }

    private function sut(array $opts = [])
    {
        $opts += [
            "findImage" => [400, 300, "image data"],
            "check" => false,
            "saveCover" => false,
            "saveCoverRes" => true,
            "saveDoors" => false,
            "saveDoorsRes" => true,
            "findCover" => null,
            "shuffle" => false,
        ];
        $conf = XH_includeVar("./config/config.php", "plugin_cf")["adventcalendar"];
        $csrfProtector = $this->createStub(CsrfProtector::class);
        $csrfProtector->expects($opts["check"] ? $this->once() : $this->never())->method("check");
        $csrfProtector->method("token")->willReturn("0ecaa0f044230af99f72073f6a7aa4ab");
        $repository = $this->createMock(Repository::class);
        $repository->method("dataFolder")->willReturn("./plugins/adventcalendar/data/");
        $repository->method("findCalendars")->willReturn(["2022", "2023"]);
        $repository->method("findImage")->willReturn($opts["findImage"]);
        $repository->expects($opts["saveCover"] ? $this->once() : $this->never())->method("saveCover")
            ->with("2023", "modified image data")
            ->willReturn($opts["saveCoverRes"]);
        $repository->expects($opts["saveDoors"] ? $this->once(): $this->never())->method("saveDoors")
            ->with("2023")
            ->willReturn($opts["saveDoorsRes"]);
        $repository->method("findCover")->willReturn($opts["findCover"]);
        $shuffler = $this->createMock(Shuffler::class);
        $shuffler->expects($opts["shuffle"] ? $this->once() : $this->never())->method("shuffle")
            ->willReturnArgument(0);
        $image = $this->createMock(DoorDrawer::class);
        $image->method("drawDoors")->willReturn("modified image data");
        $view = new View("./views/", XH_includeVar("./languages/en.php", "plugin_tx")["adventcalendar"]);
        return new MainAdminController($conf, $csrfProtector, $repository, $shuffler, $image, $view);
    }

    private function request()
    {
        $request = $this->createMock(Request::class);
        $request->method("url")->willReturn(
            new Url(CMSIMPLE_URL, "/", "adventcalendar", "adventcalendar&adventcalendar_name=2023")
        );
        return $request;
    }
}
