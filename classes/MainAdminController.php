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

use Adventcalendar\Infra\Image;
use Adventcalendar\Infra\Repository;
use Adventcalendar\Infra\View;
use Adventcalendar\Value\Html;

class MainAdminController
{
    /** @var Repository */
    private $repository;

    /** @var View */
    private $view;

    public function __construct(Repository $repository, View $view)
    {
        $this->repository = $repository;
        $this->view = $view;
    }

    /**
     * @return void
     */
    public function defaultAction()
    {
        global $sn, $_XH_csrfProtection;

        echo $this->view->render("admin", [
            'url' => "$sn?adventcalendar&admin=plugin_main&action=prepare",
            'csrfTokenInput' => Html::of($_XH_csrfProtection->tokenInput()),
            'calendars' => Calendar::getAll($this->repository->dataFolder())
        ]);
    }

    /**
     * @return void
     */
    public function prepareAction()
    {
        global $_XH_csrfProtection, $plugin_cf;

        $_XH_csrfProtection->check();
        $cal = $_POST['adventcalendar_name'];
        $dn = $this->repository->dataFolder();
        $calendar = Calendar::findByName($cal, $this->repository->dataFolder());
        $im = $calendar->getImage();
        if (!$im) {
            echo $this->view->error("error_read", "$dn$cal.jpg");
            return;
        }
        $calendar->calculateDoors(imagesx($im), imagesy($im));
        $image = new Image(
            $im,
            $plugin_cf["adventcalendar"]["color_door"],
            $plugin_cf["adventcalendar"]["color_font"],
            $plugin_cf["adventcalendar"]["color_fringe"],
        );
        $image->drawDoors($calendar->getDoors());

        if (!imagejpeg($im, "$dn$cal+.jpg")) {
            echo $this->view->error("error_save", "$dn$cal+.jpg");
            return;
        }
        if (!$calendar->save()) {
            echo $this->view->error("error_save", "$dn$cal.dat");
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
        $dn = $this->repository->dataFolder();
        $cal = $_GET['adventcalendar_name'];
        echo $this->view->render("view", [
            'src' => "$dn$cal+.jpg",
        ]);
    }
}
