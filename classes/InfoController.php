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

use Adventcalendar\Infra\View;
use stdClass;

class InfoController extends Controller
{
    /**
     * @return void
     */
    public function defaultAction()
    {
        global $pth, $plugin_tx;

        $view = new View($pth["folder"]["plugins"] . "adventcalendar/views/", $plugin_tx["adventcalendar"]);
        echo $view->render("info", [
            'logo' => "{$pth['folder']['plugins']}adventcalendar/adventcalendar.png",
            'version' => Plugin::VERSION,
            'checks' => $this->checks(),
        ]);
    }

    private function checks(): array
    {
        global $pth;

        return [
            $this->checkPhpVersion("5.4.0"),
            $this->checkExtension("gd"),
            $this->checkXhVersion("1.6.3"),
            $this->checkPlugin("jquery"),
            $this->checkWritability($pth['folder']['plugins'] . "adventcalendar/config/"),
            $this->checkWritability($pth['folder']['plugins'] . "adventcalendar/css/"),
            $this->checkWritability($pth['folder']['plugins'] . "adventcalendar/languages/"),
            $this->checkWritability($this->dataFolder()),
        ];
    }

    private function checkPhpVersion($version): stdClass
    {
        global $plugin_tx;

        $state = version_compare(PHP_VERSION, $version, "ge") ? "success" : "fail";
        return (object) [
            "state" => $state,
            "label" => sprintf($plugin_tx["adventcalendar"]["syscheck_phpversion"], $version),
            "stateLabel" => $plugin_tx["adventcalendar"]["syscheck_$state"],
        ];
    }

    private function checkExtension($name): stdClass
    {
        global $plugin_tx;

        $state = extension_loaded($name) ? "success" : "fail";
        return (object) [
            "state" => $state,
            "label" => sprintf($plugin_tx["adventcalendar"]["syscheck_extension"], $name),
            "stateLabel" => $plugin_tx["adventcalendar"]["syscheck_$state"],
        ];
    }

    private function checkXhVersion($version): stdClass
    {
        global $plugin_tx;

        $state = version_compare(CMSIMPLE_XH_VERSION, "CMSimple_XH $version", "ge") ? "success" : "fail";
        return (object) [
            "state" => $state,
            "label" => sprintf($plugin_tx["adventcalendar"]["syscheck_xhversion"], $version),
            "stateLabel" => $plugin_tx["adventcalendar"]["syscheck_$state"],
        ];
    }

    private function checkPlugin($name): stdClass
    {
        global $pth, $plugin_tx;

        $state = is_dir($pth["folder"]["plugins"] . $name) ? "success" : "fail";
        return (object) [
            "state" => $state,
            "label" => sprintf($plugin_tx["adventcalendar"]["syscheck_plugin"], $name),
            "stateLabel" => $plugin_tx["adventcalendar"]["syscheck_$state"],
        ];
    }

    private function checkWritability($folder): stdClass
    {
        global $plugin_tx;

        $state = is_writeable($folder) ? "success" : "fail";
        return (object) [
            "state" => $state,
            "label" => sprintf($plugin_tx["adventcalendar"]["syscheck_writable"], $folder),
            "stateLabel" => $plugin_tx["adventcalendar"]["syscheck_$state"],
        ];
    }
}
