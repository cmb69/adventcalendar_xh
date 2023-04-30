# Adventcalendar_XH

Adventcalendar_XH ermöglicht es einen Adventskalender auf Ihrer Website
anzuzeigen. Die Türchen können von den Besuchern nur bis zum aktuellen Datum
geöffnet werden. Die hinter den Türchen versteckten Geheimnisse sind der
Inhalt von CMSimple_XH-Seiten, so dass Sie präsentieren können, was immer
Sie wünschen (Lyrik, Bilder, Videos, Produkte usw.)

- [Voraussetzungen](#voraussetzungen)
- [Download](#download)
- [Installation](#installation)
- [Einstellungen](#einstellungen)
- [Verwendung](#verwendung)
  - [Schnellstartanleitung](#schnellstartanleitung)
  - [Vorbereiten des Umschlags](#vorbereiten-des-umschlags)
  - [Vorbereiten der Geheimnisse](#vorbereiten-der-geheimnisse)
  - [Anzeigen des Kalenders](#anzeigen-des-kalenders)
- [Einschränkungen](#einschränkungen)
- [Problembehebung](#problembehebung)
- [Lizenz](#lizenz)
- [Danksagung](#danksagung)

## Voraussetzungen

Adventcalendar_XH ist ein Plugin für [CMSimple_XH](https://cmsimple-xh.org/de/).
Es benötigt CMSimple_XH ≥ 1.7.0 und PHP ≥ 5.4.0.

## Download

Das [aktuelle Release](https://github.com/cmb69/adventcalendar_xh/releases/latest)
kann von Github herunter geladen werden.

## Installation

Die Installation erfolgt wie bei vielen anderen CMSimple_XH-Plugins auch. Im
[CMSimple_XH Wiki](https://wiki.cmsimple-xh.org/de/?fuer-anwender/arbeiten-mit-dem-cms/plugins)
finden sie ausführliche Hinweise.

1. **Sichern Sie die Daten auf Ihrem Server.**
1. Entpacken Sie die ZIP-Datei auf Ihrem Rechner.
1. Laden Sie den ganzen Ordner `adventcalendar/` auf Ihren
   Server in den `plugins/` Ordner von CMSimple_XH hoch.
1. Vergeben Sie Schreibrechte für die Unterordner `config/`, `css/`, `languages/`
   und den Daten-Ordner des Plugins.
1. Navigieren Sie zu `Plugins` → `Adventcalendar` im Administrationsbereich,
   um zu prüfen, ob alle Voraussetzungen erfüllt sind.

## Einstellungen

Die Plugin-Konfiguration erfolgt wie bei vielen anderen CMSimple_XH-Plugins
auch im Administrationsbereich der Website. Wählen Sie `Plugins` → `Adventcalendar`.

Sie können die Voreinstellungen von Adventcalendar_XH unter
`Konfiguration` ändern. Hinweise zu den Optionen werden beim Überfahren
der Hilfe-Icons mit der Maus angezeigt.

Die Lokalisierung wird unter `Sprache` vorgenommen. Sie können die
Zeichenketten in Ihre eigene Sprache übersetzen (falls keine entsprechende
Sprachdatei zur Verfügung steht), oder sie entsprechend Ihren Anforderungen
anpassen.

Das Aussehen von Adventcalendar_XH kann unter `Stylesheet` angepasst werden.

## Verwendung

Sie können so viele verschiedene Adventskalender in einer
CMSimple_XH-Installation haben wie Sie möchten. Diese werden durch ihren
Namen unterschieden. Der Name darf nur alphanumerische Zeichen enthalten und
er sollte nicht mit der Überschrift irgendeiner Seite übereinstimmen.

Besucher Ihrer Website können keine Türchen von zukünftigen Tagen öffnen,
gemäß der Konfigurationsoption `Date` → `Start`. Wenn Sie als
Administrator eingeloggt sind, können Sie zu Testzwecken alle Türchen
öffnen.

### Schnellstartanleitung

Um schnell eine funktionierende Demo aufzusetzen, führen Sie einfach
folgende Schritte durch:

1. Navigieren Sie zu `Plugins` → `Adventcalendar` → `Administration`,
   wählen Sie das Umschlagbild `winter`, und klicken Sie `Umschlag vorbereiten`.
1. Erzeugen Sie eine neue versteckte CMSimple_XH Seite mit der Überschrift
   `winter` (Groß-/Kleinschreibung ist wichtig) und ohne weiteren Inhalt.
1. Erzeugen Sie einige versteckte Unterseiten dieser Seite mit beliebigen
   Überschriften und Inhalt.
1. Geben Sie folgenden Pluginaufruf auf einer anderen Seite ein:

       {{{adventcalendar('winter')}}}
1. Wechseln Sie in den Ansichtsmodus – viel Spaß mit dem Adventskalender.
   Beachten Sie, dass Sie nur so viele Türchen öffnen können, wie Sie
   Unterseiten der Kalenderseite angelegt haben.

### Vorbereiten des Umschlags

Suchen Sie ein geeignetes Hintergrundbild für Ihren Adventskalender,
skalieren Sie es auf die gewünschte Größe (normalerweise die Breite des
Inhaltsbereichs Ihres Templates), und laden Sie es in den konfigurierten
Datenordner des Plugins hoch. Das hoch geladene Bild muss im JPEG Format
sein, und hat gemäß dem Kalender benannt zu werden; also muss für den
Kalender `winter` der Dateiname `winter.jpg` lauten. Dann
navigieren Sie zu `Plugins` → `Adventcalendar` → `Administration`,
wo Sie das Bild als Umschlag für Ihren Adventskalender vorbereiten können.
`Umschlag vorbereiten` wird die Türchen mit den zugehörigen Tages-Nummern einzeichnen.
Bilder im Landschaftsformat haben 4 Zeilen mit je 6 Türchen; Bilder im Portraitformat
haben 6 Zeilen mit je 4 Türchen. Die Größe der Türchen und die Farben können
in der Pluginkonfiguration angepasst werden. Die Reihenfolge der Türchen
wird zufällig gewählt; wenn Ihnen diese nicht gefällt, dann bereiten Sie den
Umschlag einfach noch einmal vor.

### Vorbereiten der Geheimnisse

Erzeugen Sie eine neue CMSimple_XH-Seite mit dem Namen des Adventskalenders
als Überschrift (die Menüebene der Seite spielt keine Rolle). Erzeugen für
jeden Tag eine Unterseite (die 1. Unterseite ist für den 1. Tag, die 2. für
den 2. Tag usw.) Sie können die Seiten beliebig mit Inhalt füllen; auch
Plugin-Aufrufe sind möglich. Normalerweise werden Sie alle diese Seiten im
Menü verstecken wollen.
Wenn Sie eine bereits existierende Seite als Geheimnis hinter einem
Türchen anzeigen wollen, dann leiten Sie die entsprechende Unterseite
auf die gewünschte Seite weiter und hängen Sie dabei `&print` an die URL an.

### Anzeigen des Kalenders

Zeigen Sie den Adventskalender auf einer CMSimple_XH-Seite durch folgenden
Pluginaufruf an:

    {{{adventcalendar('%KALENDER_NAME%')}}}

Sie müssen `%KALENDER_NAME%` durch den tatsächlichen Namen des Adventskalenders ersetzen.

## Einschränkungen

Die Lightbox erfordert einen zeitgemäßen Browser; in alten Browsern wird der
Inhalt der geheimen Seiten als separate Seite angezeigt.

## Problembehebung

Melden Sie Programmfehler und stellen Sie Supportanfragen entweder auf
[Github](https://github.com/cmb69/adventcalendar_xh/issues)
oder im [CMSimple_XH Forum](https://cmsimpleforum.com/).

## Lizenz

Adventcalendar_XH ist freie Software. Sie können es unter den Bedingungen
der GNU General Public License, wie von der Free Software Foundation
veröffentlicht, weitergeben und/oder modifizieren, entweder gemäß
Version 3 der Lizenz oder (nach Ihrer Option) jeder späteren Version.

Die Veröffentlichung von Adventcalendar_XH erfolgt in der Hoffnung, dass es
Ihnen von Nutzen sein wird, aber *ohne irgendeine Garantie*, sogar ohne
die implizite Garantie der *Marktreife* oder der *Verwendbarkeit für einen
bestimmten Zweck*. Details finden Sie in der GNU General Public License.

Sie sollten ein Exemplar der GNU General Public License zusammen mit
Adventcalendar_XH erhalten haben. Falls nicht, siehe <https://www.gnu.org/licenses/>.

Copyright © 2012-2023 Christoph M. Becker

Russische Übersetzung © 2012 Любомир Кудрай

## Danksagung

Adventcalendar_XH verwendet [Colorbox](https://www.jacklmoore.com/colorbox/).
Vielen Dank an Jack Moore für die Veröffentlichung dieses ausgezeichneten Lightbox-Klons
unter MIT-Lizenz.

Das Pluginlogo wurde von 
[Enhanced Labs Design Studio](https://icon-icons.com/es/users/z3XFBTtNIwiSUFnQ70KGw/icon-sets/)
entworfen. Vielen Dank für die Veröffentlichung dieses Icons unter einer liberalen Lizenz.

Vielen Dank an die Gemeinschaft im [CMSimple_XH Forum](https://cmsimpleforum.com/)
für Tipps, Vorschläge und das Testen.
Mein besonderer Dank geht an *Korvell*, der mich angespornt hat 1beta5 gerade
noch rechtzeitig vor dem Dezember 2013 zu veröffentlichen.

Und zu guter letzt vielen Dank an [Peter Harteg](http://www.harteg.dk/),
den „Vater“ von CMSimple, und allen Entwicklern von
[CMSimple_XH](https://www.cmsimple-xh.org/de/) ohne die es dieses
phantastische CMS nicht gäbe.
