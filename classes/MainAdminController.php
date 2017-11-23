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

use Pfw\Url;
use Pfw\View\HtmlString;
use Pfw\View\View;

class MainAdminController
{
    /**
     * @return void
     */
    public function defaultAction()
    {
        global $_XH_csrfProtection;

        (new View('adventcalendar'))
            ->template('admin')
            ->data([
                'url' => Url::getCurrent()->with('action', 'prepare'),
                'csrfTokenInput' => new HtmlString($_XH_csrfProtection->tokenInput()),
                'calendars' => Calendar::getAll()
            ])
            ->render();
    }

    /**
     * @return void
     */
    public function prepareAction()
    {
        global $_XH_csrfProtection, $plugin_tx;

        $_XH_csrfProtection->check();
        $cal = $_POST['adventcalendar_name'];
        $dn = Plugin::dataFolder();
        $calendar = Calendar::findByName($cal);
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

        $url = Url::getCurrent()->with('action', 'view')->with('adventcalendar_name', $cal);
        header("Location: {$url->getAbsolute()}", true, 303);
        exit;
    }

    /**
     * @return void
     */
    public function viewAction()
    {
        $dn = Plugin::dataFolder();
        $cal = $_GET['adventcalendar_name'];
        (new View('adventcalendar'))
            ->template('view')
            ->data(['src' => "$dn$cal+.jpg"])
            ->render();
    }
}
