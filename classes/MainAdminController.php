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
}
