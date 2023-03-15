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

use Adventcalendar\Infra\Pages;
use Adventcalendar\Infra\Repository;
use Adventcalendar\Infra\View;

class MainController
{
    /** @var Pages */
    private $pages;

    /** @var Repository */
    private $repository;

    /** @var View */
    private $view;

    /**
     * @var string
     */
    private $calendarName;

    /**
     * @param string $cal
     */
    public function __construct(Pages $pages, Repository $repository, View $view, $cal)
    {
        $this->pages = $pages;
        $this->repository = $repository;
        $this->view = $view;
        $this->calendarName = (string) $cal;
    }

    /**
     * @return void
     */
    public function defaultAction()
    {
        global $pth, $plugin_cf, $plugin_tx;

        $ptx = $plugin_tx['adventcalendar'];
        $calendar = Calendar::findByName($this->calendarName, $this->repository->dataFolder());
        $data = $calendar->getDoors();
        if (!isset($data)) {
            echo $this->view->error("error_read", $this->repository->dataFolder() . $this->calendarName . '.dat');
            return;
        }
        $src = $this->repository->dataFolder() . $this->calendarName . '+.jpg';
        if (!file_exists($src)) {
            echo $this->view->error("error_read", $src);
            return;
        }
        $page = $this->pages->findByHeading($this->calendarName);
        if ($page < 0) {
            echo $this->view->error("message_missing_page", $this->calendarName);
            return;
        }
        $this->emitJs();
        $doors = [];
        foreach ($this->pages->childrenOf($page) as $i => $page) {
            if ($i >= $this->getCurrentDay()) {
                break;
            }
            $coords = implode(',', $data[$i]);
            $href = '?' . $this->pages->urlOf($page) . '&print';
            $doors[$i + 1] = (object) compact('coords', 'href');
        }
        echo $this->view->render("main", [
            "src" => $src,
            "doors" => $doors,
        ]);
    }

    /**
     * @return int
     */
    private function getCurrentDay()
    {
        global $plugin_cf;

        if (defined("XH_ADM") && XH_ADM) {
            return 24;
        } else {
            $start = strtotime($plugin_cf['adventcalendar']['date_start']);
            return (int) floor((time() - $start) / 86400) + 1;
        }
    }

    /**
     * @return void
     */
    private function emitJs()
    {
        global $pth, $plugin_cf, $hjs;
        static $again = false;

        if ($again) {
            return;
        }
        $again = true;
        $pcf = $plugin_cf['adventcalendar'];
        include_once $pth['folder']['plugins'] . 'jquery/jquery.inc.php';
        include_jQuery();
        $filename = $pth['folder']['plugins']
            . 'adventcalendar/colorbox/jquery.colorbox-min.js';
        include_jQueryPlugin('colorbox', $filename);
        $width = $pcf['lightbox_width'];
        $height = $pcf['lightbox_height'];
        $hjs .= <<<EOS
            <script type="text/javascript">/* <![CDATA[ */
            jQuery(function () {
                jQuery("area.adventcalendar").click(function (event) {
                        jQuery.colorbox({
                            iframe: true, href: this.href,
                            maxWidth: "100%", maxHeight: "100%",
                            innerWidth: "$width", innerHeight: "$height"
                        });
                        event.preventDefault();
                });
            });
            /* ]]> */</script>

EOS;
    }
}
