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

use Adventcalendar\Value\Url;

class Request
{
    /** @codeCoverageIgnore */
    public static function current(): self
    {
        return new self;
    }

    /** @codeCoverageIgnore */
    public function url(): Url
    {
        global $sn, $su;
        return new Url(CMSIMPLE_URL, $sn, $su, $_SERVER["QUERY_STRING"]);
    }

    public function action(): string
    {
        $action = $this->url()->param("action");
        if (!is_string($action)) {
            $action = "";
        }
        if (!strncmp($action, "do_", strlen("do_"))) {
            $action = "";
        }
        if (isset($_POST["adventcalendar_do"])) {
            $action = "do_" . $action;
        }
        return $action;
    }

    /** @codeCoverageIgnore */
    public function adm(): bool
    {
        return defined("XH_ADM") && XH_ADM;
    }

    /** @codeCoverageIgnore */
    public function time(): int
    {
        return (int) $_SERVER["REQUEST_TIME"];
    }
}
