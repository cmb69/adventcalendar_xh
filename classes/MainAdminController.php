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
use Adventcalendar\Value\Html;

class MainAdminController extends Controller
{
    /**
     * @return void
     */
    public function defaultAction()
    {
        global $sn, $_XH_csrfProtection, $pth, $plugin_tx;

        $view = new View($pth["folder"]["plugins"] . "adventcalendar/views/", $plugin_tx["adventcalendar"]);
        echo $view->render("admin", [
            'url' => "$sn?adventcalendar&admin=plugin_main&action=prepare",
            'csrfTokenInput' => Html::of($_XH_csrfProtection->tokenInput()),
            'calendars' => Calendar::getAll($this->dataFolder())
        ]);
    }

    /**
     * @return void
     */
    public function prepareAction()
    {
        global $_XH_csrfProtection, $plugin_tx;

        $_XH_csrfProtection->check();
        $cal = $_POST['adventcalendar_name'];
        $dn = $this->dataFolder();
        $calendar = Calendar::findByName($cal, $this->dataFolder());
        $im = $calendar->getImage();
        if (!$im) {
            echo XH_message('fail', $plugin_tx['adventcalendar']['error_read'], "$dn$cal.jpg");
            return;
        }
        $calendar->calculateDoors(imagesx($im), imagesy($im));
        $image = new Image($im);
        $image->drawDoors($calendar->getDoors());

        if (!imagejpeg($im, "$dn$cal+.jpg")) {
            echo XH_message('fail', $plugin_tx['adventcalendar']['error_save'], "$dn$cal+.jpg");
            return;
        }
        if (!$calendar->save()) {
            echo XH_message('fail', $plugin_tx['adventcalendar']['error_save'], "$dn$cal.dat");
            return;
        }

        $url = CMSIMPLE_URL . "?adventcalendar&admin=plugin_main&action=view&adventcalendar_name=$cal";
        header("Location: $url", true, 303);
        exit;
    }

    /**
     * @return void
     */
    public function viewAction()
    {
        global $pth, $plugin_tx;

        $dn = $this->dataFolder();
        $cal = $_GET['adventcalendar_name'];
        $view = new View($pth["folder"]["plugins"] . "adventcalendar/views/", $plugin_tx["adventcalendar"]);
        echo $view->render("view", [
            'src' => "$dn$cal+.jpg",
        ]);
    }
}
