README for DatecBlog
====================

This is the manual file (DE) for 'datec_blog' authored by Philipp Roensch.

------------------------

Mindestanforderungen:
- TYPO3 CMS ab Version 6.0
- jQuery + jQuery Validation Plugin

Installation:

1) Entpacken Sie den Inhalt des Verzeichnisses nach "typo3conf/" in Ihrer Typo3 Instanz.
2) Legen Sie eine neue Seite vom Typ "Systemordner" für die gesammelten Kommentare und Kommentarverfasser an, 
	notieren Sie sich die Seiten-ID
	Hinweis: der Speicherordner der Bloginhalte (Beiträge, Kategorien, Schlüsselwörter) ist irrelevant, 
	wir empfehlen dennoch, der Übersichtlichkeit halber, getrennte Systemordner zu verwenden 
3) Binden Sie das statische Konfigurationstemplate für Datec Blog in Ihr ROOT Page-Template ein
	- Gehen Sie hierzu auf die Wurzelseite Ihrer Homepage und öffnen das Modul Web > Template
	- Benutzen sie die Ansicht "Info/Editieren" um die Option "Gesamten Template-Datensatz bearbeiten" zu öffnen
	- Gügen Sie nun unter "Enthält" das Statische Template "Datec Blog" hinzu
4) Legen Sie eine TypoScript-Konfiguration an und konfigurieren Sie mindestens: (siehe "Konfigurationsmöglichkeiten")
	- plugin.tx_datecblog_blog.settings.commentsStoragePid = <Ihr Systemordner für Kommentare>
5) Binden Sie das Plugin auf einer beliebigen Seite ein
	- Wählen Sie hierzu den Inhaltselementtyp "Plugin" und wählen Sie "Datec Blog" aus
	- Speichern Sie den Inhalt und öffnen Sie die Plugin-Auswahl erneut
	- Sie können nun noch die Oberfläche "Blog" (mit Blogeinträgen und Kommentaren), "Kategorien", "Archiv" oder "Schlüsselwörter" auswählen
	Hinweis: Alle Bestandteile des Blogs sollten sich auf der gleichen Seite befinden, Ihre Position ist aber frei wählbar
6) FERTIG!

Konfigurationsmöglichkeiten:

Diese Optionen richten sich an Experten welche Datec-Blog noch weiter anpassen möchten. Sie müssen diese Werte als TypoScript-Konfiguration hinterlegen.

plugin.tx_datecblog_blog {
	settings {
	    commentsStoragePid = <Ihr Systemordner für Kommentare und Kommentarverfasser>
	    mail {
	    	internMailFrom = <E-Mail Adresse, Benutzer und Admin erhalten Nachrichten VON dieser Adresse>
	    	internMailFromName = <E-Mail Adresse, VON - Anzeigename>
	    }
	    maxFileSize = <Zahl Bytes, maximale Größe pro hochgeladener Datei für Kommentare, default:4000000>
	    allowedFileTypes = <Dateiendungen kommasepariert, erlaubte Dateitypen für Kommtentare, default:"pdf,zip,png,jpg,jpeg,gif,txt,doc,docx">
		display {
        	dateFormat = <Datums-Format zur Anzeige, kompatibel zu PHP date()-Funktion, default:"d.m.Y">
       		showDefaultHeaders = <Boolean, sollen Überschrifen zu den Plugins gerendert werden?, default:1>
       		keywords {
        		limit = <Zahl, Limit für Ergebnismenge der Schlüsselwörter, default:0 (disabled)>
        		order = <Datenbankspalte, Schlüsselwörter werden nach dieser Spalte sortiert, default:"usage">
        		visual = <Entweder "cloud" oder "list", Anzeigeform der Schlüsselwörter, default:"cloud">
        	}
        	posts {
        		dateFormat = <Datums-Format zur Anzeige, wie zuvor, für Blogeinträge, default:"d.m.Y - H:i">
        		teaserTextLength = <Zahl, Länge nach welcher Texte ohne Teaser in der Vorschau abgeschnitten werden, default:40>
        		sorting = <Datenbankspalte, nach dieser wird sortiert, default:"crdate">
        		sortingDirection = <Sortierichtung, default:"DESC">
			}
	        	comments {
	        		dateFormat = <Datums-Format zur Anzeige, wie zuvor, für Kommentare, default:"d.m.Y - H:i">
	        		sorting = <Datenbankspalte, nach dieser wird sortiert, default:"crdate">
	        		sortingDirection = <Sortierichtung, default:"DESC">
			}
			feUser {
				nameFormat = <Anzeigeformat für Benutzernamen, "username" oder "firstname_lastname" oder "firstname" oder "lastname">
			}
		}
	    cssClasses {
				<Setzen Sie hier die CSS-Klassen die für Formulare verwendet möchten, Sie sehen hier die Standartwerte für Bootstap 3 CSS-Framework>
	        	form = form form-horizontal
	        	formLabel = col-sm-4 control-label        	
	        	formField = form-control
	        	formFieldWrap = form-group
	        	formFieldInnerWrap = col-sm-8
				formButton = btn btn-default
				formSubmitWrap = col-sm-offset-4 col-sm-5
	        }
	    }
	}
}

Bei Fragen oder Hinweisen kontaktieren Sie bitte: Philipp Rönsch - p.roensch@datec-schmidt.de