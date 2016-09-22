.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


Zielgruppe: **Administratoren**


.. only:: html

	.. contents:: Within this page
		:local:
		:depth: 3

.. _configurationDE:

Konfiguration
=============

Dieses Kapitel beschreipt alle Konfigurationsoptionen für Datec Blog, verfügbar über TypoScript. Um diese Optionen zu ändern, fügen Sie ein Erweiterungstemplate zu Ihrem ROOT-Template hinzu.

.. _configuration-typoscriptDE:

Minimale Konfiguration
----------------------

Bei installion, fügen Sie bitte das statische Template 'Datec Blog' zu Ihrem ROOT-Template hinzu (Web > Template > Bearbeiten des ROOT-Templates > Enthält > Statisches Template von Erweiterung auswählen) and setzten Sie mindestens folgende Optionen:

.. code-block:: ts

	# PID des Systemordners, in welchem Kommentare und Kommentarersteller gespeichert werden sollen
	plugin.tx_datecblog_blog.settings.commentsStoragePid = 123

	# Gültige E-Mail Adresse zum Versand automatischer Benachrichten (VON)
	plugin.tx_datecblog_blog.settings.mail.internMailFrom = blog@no-reply.com


Allgemeine Konfiguration
------------------------

plugin.tx_datecblog_blog.

.. container:: ts-properties

	================================================    =============   ==============================================================================  ===========
	Eigenschaft                                         Datentyp        Beschreibung                                                                    Standard
	================================================    =============   ==============================================================================  ===========
	view.templateRootPath                               string          Konstante, Pfad zu den Template Dateien, ändern um eigene zu verwenden.         EXT:datec_blog/Resources/Private/Templates/
	view.partialRootPath                                string          Konstante, Pfad zu den Teil-Template Dateien, ändern um eigene zu verwenden.    EXT:datec_blog/Resources/Private/Partials/
	view.layoutRootPath                                 string          Konstante, Pfad zu den Layout Dateien, ändern um eigene zu verwenden.           EXT:datec_blog/Resources/Private/Layouts/
	settings.mail.internMailFrom                        string          E-Mail Adresse zum Versand automatischer Benachrichten (VON).                   blog@no-reply.com
	settings.mail.internMailFromName                    string          E-Mail Adresse zum Versand automatischer Benachrichten (VON-NAME).              Datec Blog
	settings.maxFileSize                                string          Maximale Dateigröße in bytes für Kommentare mit Dateianhängen.                  4000000
	settings.allowedFileTypes                           string          Erlaubte Dateitypen, kommaseparierte Liste, für Kommentare mit Dateianhängen.   pdf,zip,png,jpg,jpeg,gif,txt,doc,docx
	settings.comments.storagePid                        int             System Ordner für Kommentare und Kommentarersteller.
	settings.posts.storagePid                           int             System Ordner für Blog-Einträge.
	settings.categories.storagePid                      int             System Ordner für Kategories.
	settings.keywords.storagePid                        int             System Ordner für Schlüsselwörter.
	settings.display.dateFormat                         string          Datmsformat zur Datumsanzeige, kompatiebel zu date() PHP Funktion.              d.m.Y
	settings.display.showDefaultHeaders                 boolean         Zeige Standard-Titel über jedem Plugin an (e.g. 'Kategorien').                  1
	settings.display.keywords.limit                     string          Limitiere Schlüsselwörter Liste, wähle 0 zum deaktivieren.                      0
	settings.display.keywords.order                     string          Ordne Schlüsselw. nach 'date' (Erscheinen), 'usage' (Clicks) oder 'sorting'.    usage
	settings.display.keywords.visual                    string          Zeige Schlüsselwörter als 'cloud' (Wolke) oder 'list' (Liste).                  cloud
	settings.display.posts.dateFormat                   string          Wie 'settings.display.dateFormat' nur für Blogeinträge.                         d.m.Y - H:i
	settings.display.posts.teaserTextLength             string          Blogeinträge (ohne Teasertext) werden in der Liste an dieser Länge gekürzt.     40
	settings.display.posts.sorting                      string          Spaltenname für SQL-Anfrage zu Sortierung der Bogeinträge.			             crdate
	settings.display.posts.sortingDirection             string          Sortier-Richtung SQL-Anfrage zu Sortierung der Bogeinträge.                     DESC
	settings.display.posts.pagination.enable            boolean         Seitenbildung aktivieren.                                                       1
	settings.display.posts.pagination.itemsPerPage      int             Blogeinträge pro Seite.                                                         15
	settings.display.posts.pagination.maxPages          int             Maximale Seitenanzahl, 0 ist unbeschränkt. (reduziert sichtbaren Inhalt!)       0
	settings.display.posts.pagination.top               boolean         Seitennavigation über der Listenansicht anzeigen.                               0
	settings.display.posts.pagination.bottom            boolean         Seitennavigation unter der Listenansicht anzeigen.                              1
	settings.display.comments.dateFormat                string          Wie 'settings.display.dateFormat' nur für Kommentare.                           d.m.Y - H:i
	settings.display.comments.sorting                   string          Spaltenname für SQL-Anfrage zu Sortierung der Kommentare.                       crdate
	settings.display.comments.sortingDirection          string          Sortier-Richtung SQL-Anfrage zu Sortierung der Kommentare.                      DESC
	settings.display.feUser.nameFormat                  string          Benutzer mit 'username', 'firstname_lastname', 'firstname' oder 'lastname'.     username
	settings.cssClasses.form                            string          CSS Klasse für Formulare. (All CSS-settings are suggested Bootstrap default)    form form-inline
	settings.cssClasses.formLabel                       string          CSS Klasse für Formular Labels.                                                 control-label
	settings.cssClasses.formField                       string          CSS Klasse für Formular Felder.                                                 form-control
	settings.cssClasses.formFieldWrap                   string          CSS Klasse zum umschließen von Feld + Label.                                    form-group
	settings.cssClasses.formButton                      string          CSS Klasse für Formular Schaltflächen.                                          btn btn-default
	settings.cssClasses.listGroup                       string          CSS Klasse für Listen-Gruppe ('ul').
	settings.cssClasses.listItem                        string          CSS Klasse für Lsiten- Item ('li').
	================================================    =============   ==============================================================================  ===========
