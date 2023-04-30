<!--
 * Copyright (c) 2012 Lybomyr Kydray aka Old
 *
 * Translation are licensed under the GNU General Public License, version 2 or
 * later.
 -->

# Adventcalendar_XH

Adventcalendar_XH позволяет легко разместить календарь рождественского поста
на Вашем сайте. Небольшие двери могут быть открыты посетителями только при
наступлении определенной даты. Хранящиеся за дверью тайны - это содержимое
страниц CMSimple_XH, таким образом, Вы можете разместить на них все, что
пожелаете (стихи, изображения, видео, произведения и пр.)

- [Требования](#требования)
- [Download](#download)
- [Установка](#установка)
- [Настройки](#настройки)
- [Использование](#использование)
  - [Quickstart Guide](#quickstart-guide)
  - [Подготовка обложки](#подготовка-обложки)
  - [Подготовка "тайн"](#подготовка-тайн)
  - [Отображение календаря](#отображение-календаря)
- [Limitations](#limitations)
- [Troubleshooting](#troubleshooting)
- [Лицензия](#лицензия)
- [Благодарности](#благодарности)

## Требования

Adventcalendar_XH is a plugin for [CMSimple_XH](https://cmsimple-xh.org/).
It requires CMSimple_XH ≥ 1.7.0 and PHP ≥ 5.4.0.

## Download

The [lastest release](https://github.com/cmb69/adventcalendar_xh/releases/latest)
is available for download on Github.

## Установка

The installation is done as with many other CMSimple_XH plugins. See the
[CMSimple_XH Wiki](https://wiki.cmsimple-xh.org/?for-users/working-with-the-cms/plugins)
for further details.

1. **Создайте резервную копию вашего сайта.**
1. Разархивируйте дистрибутив в папку на вашем компьютере.
1. Загрузите каталог `adventcalendar/` на серевер в директорию
   плагинов CMSimple_XH.
1. Укажите права доступа для подкаталогов `data/`,
   `config/`, `css/` и `languages/`.
1. Перейдите в режим администрирования `Плагины` → `Adventcalendar`
   для проверки соответствия требованиям.

## Настройки

Конфигурирование плагина в режиме администратора не отличается от конфигурирования
большинства других плагинов CMSimple_XH.
Выберите `Adventcalendar` в меню `Плагины`.

Вы можете изменить настройки Adventcalendar_XH  на вкладке
`Конфигурация`. Поместив курсор мыши на иконке помощи, можно посмотреть
подсказки для соотвествующей опции.

Локализация доступна на вкладке `Язык`. Вы можете перевести языковые
строки на ваш язык, или изменить строки в соответствии с вашими
потребностями.

Настройки стиля плагина Adventcalendar_XH доступны на вкладке `Стиль`.

## Использование

Вы можете разместить неограниченное количество рождественских календарей для
одной установленной CMSimple_XH. Но для каждого рождественского календаря
необходимо указать уникальное имя. Имя должно содержать исключительно
латинские символы и цифры, и **не должно повторять** заголовок любой из
страниц.

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

### Подготовка обложки

Найдите соответствующее фоновое изображение для Вашего рождественского
календаря, при необходимости, измените его размеры (по умолчанию, ширина
изображения должна соответствовать ширине блока контента вашего шаблона), и
загрузите изображение в папку данных плагина (data/). После этого перейдите
на страницу `Плагины` → `Adventcalendar` → `Управление`, где
Вы можете подготовить изображение для использования в качестве обложки
рождественского календаря. После нажатия кнопки `Подготовить обложку`
будут созданы небольшие двери с сответствующими датами. Для изображения,
расположенного горизонтально, будут созданы 4 строки по 6 дверей в каждой,
для изображения, расположенного вертикально, будет создано 6 строк по 4
двери в каждой. Размер дверей и желаемые цвета можно указать в настройках
плагина. Порядок дверей определяется в случайном порядке, если данный
порядок вас не устраивает, процедуру подготовки изображения следует
повторить.

### Подготовка "тайн"

Создайте новую страницу CMSimple_XH с именем рождественского календаря в
качестве заголовка (уровень меню страницы не имеет значения). Создайте
страницу уровнем ниже (суб-страницу) для каждого дня (первая суб-страница -
для дня №1, вторая для дня №2 и т.д.).Вы можете заполнить страницу любым
содержимым в соответствии с вашими предпочтениями, использовав, в том числе,
и вызовы плагинов. В общем случае, Вы пожелаете скрыть
эти страницы в меню. При желании, Вы можете использовать существующую
страницу в качестве хранящейся за дверью "тайны", для этого достаточно
использовать перенаправление
соответствующей суб-страницы на существующую страницу, и добавить
`&print` к URL.

### Отображение календаря

Для отображения рождественского календаря на любой из страниц достаточно
вызвать плагин:

    {{{adventcalendar('%CALENDAR_NAME%')}}}

Естественно, `%CALENDAR_NAME%` необходимо изменить на текущее имя
Вашего рождественского календаря.

## Limitations

The lightbox requires a contemporary browser; on old browsers, the contents of
the secret pages will be shown as separate page.

## Troubleshooting

Report bugs and ask for support either on
[Github](https://github.com/cmb69/adventcalendar_xh/issues)
or in the [CMSimple_XH Forum](https://cmsimpleforum.com/).

## Лицензия

Adventcalendar_XH является свободным
программным обеспечением: Вы можете распространять ее и (или) изменять, соблюдая
условия Генеральной публичной лицензии GNU, опубликованной Фондом свободного
программного обеспечения; либо редакции 3 Лицензии, либо (на Ваше усмотрение)
любой редакции, выпущенной позже.

Adventcalendar_XH распространяется в расчете на то, что она окажется полезной, но
БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, включая подразумеваемую гарантию КАЧЕСТВА либо
ПРИГОДНОСТИ ДЛЯ ОПРЕДЕЛЕННЫХ ЦЕЛЕЙ. Ознакомьтесь с Генеральной публичной
лицензией GNU для получения более подробной информации.

Вы должны были получить копию Генеральной публичной лицензии GNU вместе с Adventcalendar_XH.
Если Вы ее не получили, то перейдите по адресу:
<https://www.gnu.org/licenses/>. 

Copyright © 2012 Christoph M. Becker

Russian translation © 2012 Любомир Кудрай

## Благодарности

Adventcalendar_XH использует [Colorbox](https://www.jacklmoore.com/colorbox/).
Выражаем благодарность Jack Moore за предоставление великолепного клона лайтбокса по
лицензии MIT.

Иконка плагина создана [Enhanced Labs Design Studio](https://icon-icons.com/es/users/z3XFBTtNIwiSUFnQ70KGw/icon-sets/).
Выражаем благодарность за публикацию иконки по либеральной лицензии.

Особая благодарность сообществу [CMSimple_XH форума](https://www.cmsimpleforum.com/)
за советы,предложения и тестирование.
Especially I want to thank *Korvell* for pushing 1beta5 just in time before December 2013.

И последняя, но не менее искренняя благодарность [Peter Harteg](https://www.harteg.dk/),
"отцу" CMSimple, и всем разработчикам [CMSimple_XH](https://www.cmsimple-xh.org),
без которых эта удивительная CMS не могла бы существовать.
