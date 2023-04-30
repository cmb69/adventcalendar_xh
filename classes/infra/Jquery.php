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

/** @codeCoverageIgnore */
class Jquery
{
    /** @return void */
    public function include()
    {
        global $pth;
        include_once $pth["folder"]["plugins"] . "jquery/jquery.inc.php";
        include_jQuery();
    }

    /** @return void */
    public function includePlugin(string $name, string $filename)
    {
        global $pth;
        include_once $pth["folder"]["plugins"] . "jquery/jquery.inc.php";
        include_jQueryPlugin($name, $filename);
    }
}
