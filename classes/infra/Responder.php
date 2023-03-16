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

use Adventcalendar\Value\Response;

class Responder
{
    public static function respond(Response $response): string
    {
        if ($response->javascript()) {
            self::emitJs();
        }
        return $response->output();
    }

    /**
     * @return void
     */
    private static function emitJs()
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
