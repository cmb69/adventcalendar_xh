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

use Pfw\SystemCheckService;
use Pfw\View\View;

class InfoController extends Controller
{
    /**
     * @return void
     */
    public function defaultAction()
    {
        global $pth;

        (new View('adventcalendar'))
            ->template('info')
            ->data([
                'logo' => "{$pth['folder']['plugins']}adventcalendar/adventcalendar.png",
                'version' => Plugin::VERSION,
                'checks' => (new SystemCheckService)
                    ->minPhpVersion('5.4.0')
                    ->extension('gd')
                    ->minXhVersion('1.6.3')
                    ->plugin('pfw')
                    ->plugin('jquery')
                    ->writable("{$pth['folder']['plugins']}adventcalendar/config/")
                    ->writable("{$pth['folder']['plugins']}adventcalendar/css/")
                    ->writable("{$pth['folder']['plugins']}adventcalendar/languages/")
                    ->writable($this->dataFolder())
                    ->getChecks()
            ])
            ->render();
    }
}
