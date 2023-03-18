<?php

/*
 * Copyright 2012-2017 Christoph M. Becker
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

use Adventcalendar\Infra\Repository;
use Adventcalendar\Infra\SystemChecker;
use Adventcalendar\Infra\View;
use Adventcalendar\Value\Response;

class InfoController
{
    /** @var string */
    private $pluginFolder;

    /** @var Repository */
    private $repository;

    /** @var SystemChecker */
    private $systemChecker;

    /** @var View */
    private $view;

    public function __construct(string $pluginFolder, Repository $repository, SystemChecker $systemChecker, View $view)
    {
        $this->pluginFolder = $pluginFolder;
        $this->repository = $repository;
        $this->systemChecker = $systemChecker;
        $this->view = $view;
    }

    public function __invoke(): Response
    {
        return Response::create($this->view->render("info", [
            "version" => ADVENTCALENDAR_VERSION,
            "checks" => [
                $this->checkPhpVersion("5.4.0"),
                $this->checkExtension("gd"),
                $this->checkXhVersion("1.6.3"),
                $this->checkPlugin("jquery"),
                $this->checkWritability($this->pluginFolder . "config/"),
                $this->checkWritability($this->pluginFolder . "css/"),
                $this->checkWritability($this->pluginFolder . "languages/"),
                $this->checkWritability($this->repository->dataFolder()),
            ],
        ]))->withTitle("Adventcalendar 1.0beta6");
    }

    /** @return array{class:string,key:string,arg:string,statekey:string} */
    private function checkPhpVersion(string $version): array
    {
        $state = $this->systemChecker->checkVersion(PHP_VERSION, $version) ? "success" : "fail";
        return [
            "class" => "xh_$state",
            "key" => "syscheck_phpversion",
            "arg" => $version,
            "statekey" => "syscheck_$state",
        ];
    }

    /** @return array{class:string,key:string,arg:string,statekey:string} */
    private function checkExtension(string $name): array
    {
        $state = $this->systemChecker->checkExtension($name) ? "success" : "fail";
        return [
            "class" => "xh_$state",
            "key" => "syscheck_extension",
            "arg" => $name,
            "statekey" => "syscheck_$state",
        ];
    }

    /** @return array{class:string,key:string,arg:string,statekey:string} */
    private function checkXhVersion(string $version): array
    {
        $state = $this->systemChecker->checkVersion(CMSIMPLE_XH_VERSION, "CMSimple_XH $version") ? "success" : "fail";
        return [
            "class" => "xh_$state",
            "key" => "syscheck_xhversion",
            "arg" => $version,
            "statekey" => "syscheck_$state",
        ];
    }

    /** @return array{class:string,key:string,arg:string,statekey:string} */
    private function checkPlugin(string $name): array
    {
        $state = $this->systemChecker->checkPlugin($name) ? "success" : "fail";
        return [
            "class" => "xh_$state",
            "key" => "syscheck_plugin",
            "arg" => $name,
            "statekey" => "syscheck_$state",
        ];
    }

    /** @return array{class:string,key:string,arg:string,statekey:string} */
    private function checkWritability(string $folder): array
    {
        $state = $this->systemChecker->checkWritability($folder) ? "success" : "warning";
        return [
            "class" => "xh_$state",
            "key" => "syscheck_writable",
            "arg" => $folder,
            "statekey" => "syscheck_$state",
        ];
    }
}
