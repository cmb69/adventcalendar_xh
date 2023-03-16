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
use stdClass;

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

    public function defaultAction(): string
    {
        return $this->view->render("info", [
            'logo' => $this->pluginFolder . "adventcalendar.png",
            'version' => ADVENTCALENDAR_VERSION,
            'checks' => $this->checks(),
        ]);
    }

    /** @return list<stdClass> */
    private function checks(): array
    {
        return [
            $this->checkPhpVersion("5.4.0"),
            $this->checkExtension("gd"),
            $this->checkXhVersion("1.6.3"),
            $this->checkPlugin("jquery"),
            $this->checkWritability($this->pluginFolder . "config/"),
            $this->checkWritability($this->pluginFolder . "css/"),
            $this->checkWritability($this->pluginFolder . "languages/"),
            $this->checkWritability($this->repository->dataFolder()),
        ];
    }

    private function checkPhpVersion(string $version): stdClass
    {
        global $plugin_tx;

        $state = $this->systemChecker->checkVersion(PHP_VERSION, $version) ? "success" : "fail";
        return (object) [
            "state" => $state,
            "label" => sprintf($plugin_tx["adventcalendar"]["syscheck_phpversion"], $version),
            "stateLabel" => $plugin_tx["adventcalendar"]["syscheck_$state"],
        ];
    }

    private function checkExtension(string $name): stdClass
    {
        global $plugin_tx;

        $state = $this->systemChecker->checkExtension($name) ? "success" : "fail";
        return (object) [
            "state" => $state,
            "label" => sprintf($plugin_tx["adventcalendar"]["syscheck_extension"], $name),
            "stateLabel" => $plugin_tx["adventcalendar"]["syscheck_$state"],
        ];
    }

    private function checkXhVersion(string $version): stdClass
    {
        global $plugin_tx;

        $state = $this->systemChecker->checkVersion(CMSIMPLE_XH_VERSION, "CMSimple_XH $version") ? "success" : "fail";
        return (object) [
            "state" => $state,
            "label" => sprintf($plugin_tx["adventcalendar"]["syscheck_xhversion"], $version),
            "stateLabel" => $plugin_tx["adventcalendar"]["syscheck_$state"],
        ];
    }

    private function checkPlugin(string $name): stdClass
    {
        global $plugin_tx;

        $state = $this->systemChecker->checkPlugin($name) ? "success" : "fail";
        return (object) [
            "state" => $state,
            "label" => sprintf($plugin_tx["adventcalendar"]["syscheck_plugin"], $name),
            "stateLabel" => $plugin_tx["adventcalendar"]["syscheck_$state"],
        ];
    }

    private function checkWritability(string $folder): stdClass
    {
        global $plugin_tx;

        $state = $this->systemChecker->checkWritability($folder) ? "success" : "fail";
        return (object) [
            "state" => $state,
            "label" => sprintf($plugin_tx["adventcalendar"]["syscheck_writable"], $folder),
            "stateLabel" => $plugin_tx["adventcalendar"]["syscheck_$state"],
        ];
    }
}
