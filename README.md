# Adventcalendar_XH

Adventcalendar_XH facilitates to display an advent
calendar on your site. The little doors can only be opened by visitors up to the
current date. The secrets behind the doors are the contents of CMSimple_XH
pages, so it is possible to present whatever you like (poetry, images, videos,
products etc.)

- [Requirements](#requirements)
- [Download](#download)
- [Installation](#installation)
- [Settings](#settings)
- [Usage](#usage)
  - [Quickstart Guide](#quickstart-guide)
  - [Prepare the cover](#prepare-the-cover)
  - [Prepare the secrets](#prepare-the-secrets)
  - [Display the Calendar](#display-the-calendar)
- [Limitations](#limitations)
- [Troubleshooting](#troubleshooting)
- [License](#license)
- [Credits](#credits)

## Requirements

Adventcalendar_XH is a plugin for [CMSimple_XH](https://cmsimple-xh.org/).
It requires CMSimple_XH ≥ 1.7.0, and PHP ≥ 7.1.0 with the gd Extension.

## Download

The [lastest release](https://github.com/cmb69/adventcalendar_xh/releases/latest)
is available for download on Github.

## Installation

The installation is done as with many other CMSimple_XH plugins. See the
[CMSimple_XH Wiki](https://wiki.cmsimple-xh.org/?for-users/working-with-the-cms/plugins)
for further details.

1. **Backup the data on your server.**
1. Unzip the distribution on your computer.
1. Upload the whole folder `adventcalendar/` to your server into
   the `plugins/` folder of CMSimple_XH.
1. Set write permissions for the subfolders `config/`, `css/`, `languages/`
   and the data folder of the plugin.
1. Navigate to `Plugins` → `Adventcalendar` in the back-end to check
   if all requirements are fulfilled.

## Settings

The configuration of the plugin is done as with many other CMSimple_XH plugins in
the back-end of the Website. Select `Plugins` → `Adventcalendar`.

You can change the default settings of Adventcalendar_XH under
`Config`. Hints for the options will be displayed when hovering over
the help icons with your mouse.

Localization is done under `Language`. You can translate the character
strings to your own language, if there is no appropriate language file
available, or customize them according to your needs.

The look of the Adventcalendar_XH can be customized under `Stylesheet`.

## Usage

You can have as much separate advent calendars in a CMSimple_XH installation
as you like. These are distinguished by a name. The name may contain
alphanumeric characters only and it should not be the same as the heading of
any already existing page.

Visitors of your site will not be able to open doors of future days,
according to the configuration option `Date` → `Start`. When
you are logged in as administrator you can access all doors for testing
purposes.

### Quickstart Guide

To quickly set up a working demo, just follow these steps:

1. Navigate to `Plugins` → `Adventcalendar` → `Administration`, select the cover
   image `winter`, and press `Prepare Cover`.
1. Create a new hidden CMSimple_XH page with the heading `winter` (letter case
   is important) and without content.
1. Create some hidden subpages of this page with arbitrary headings and
   content.
1. Enter the following plugin call on another page:

       {{{adventcalendar('winter')}}}

1. Switch to view mode and enjoy the advent calendar. Note, that you can only
   open as much doors, as you have created subpages of the calendar page.

### Prepare the Cover

Find an appropriate background image for your advent calendar, resize it to
the desired size (typically the width of the contents area of your template)
and upload it to the configured data folder of the plugin. The uploaded
image has to be in JPEG format and has to be named like the calendar; so for
the calendar `winter` the filename has to be `winter.jpg`. Then
browse to `Plugins` → `Adventcalendar` → `Administration`,
where you can prepare the image to be the cover of your advent calendar.
Pressing `Prepare Cover` will draw the little doors with the according
day numbers. For images in landscape format there will be 4 rows with 6
doors each; for images in portrait format there will be 6 rows with 4 doors
each. The size of the doors and the colors can be customized in the
configuration of the plugin. The order of the doors is chosen randomly;
if you do not like the chosen order, just prepare the image again.

### Prepare the Secrets

Create a new CMSimple_XH page with the name of the advent calendar as
heading (the menu level of the page does not matter). Create a sub page for
each day (the 1st sub page is for day 1, the 2nd for day 2 etc.) You can
fill the pages with any contents you like, even plugin calls are possible.
Typically you will want to hide all these pages from the menu.
If you want to use an already existing page as secret behind a door, redirect
the respective sub page to the existing one and append `&print` to the URL.

### Display the Calendar

Display the advent calendar on any CMSimple_XH page by calling the plugin:

    {{{adventcalendar('%CALENDAR_NAME%')}}}

`%CALENDAR_NAME%` must be replaced with the actual name of your advent calendar.

## Limitations

The lightbox requires a contemporary browser; on old browsers, the contents of
the secret pages will be shown as separate page.

## Troubleshooting

Report bugs and ask for support either on
[Github](https://github.com/cmb69/adventcalendar_xh/issues)
or in the [CMSimple_XH Forum](https://cmsimpleforum.com/).

## License

Adventcalendar_XH is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Adventcalendar_XH is distributed in the hope that it will be useful,
but *without any warranty*; without even the implied warranty of
*merchantibility* or *fitness for a particular purpose*. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Adventcalendar_XH.  If not, see <https://www.gnu.org/licenses/>.

Copyright © 2012-2023 Christoph M. Becker


Russian translation © 2012 Любомир Кудрай

## Credits

Adventcalendar_XH uses [Colorbox](https://www.jacklmoore.com/colorbox/).
Many thanks to Jack Moore for publishing this fine lightbox clone under MIT license.

The plugin logo is designed by
[Enhanced Labs Design Studio](https://icon-icons.com/es/users/z3XFBTtNIwiSUFnQ70KGw/icon-sets/).
Many thanks for publishing this icon under a liberal license.

Many thanks to the community at the [CMSimple_XH Forum](https://cmsimpleforum.com/)
for tips, suggestions and testing.
Especially I want to thank *Korvell* for pushing 1beta5 just in time before December 2013.

And last but not least many thanks to [Peter Harteg](https://www.harteg.dk/),
the “father” of CMSimple, and all developers of [CMSimple_XH](https://www.cmsimple-xh.org/)
without whom this amazing CMS would not exist.
