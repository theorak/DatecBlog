README for DatecBlog
====================

This is the manual file (DE) for 'datec_blog' authored by Philipp Roensch.

------------------------

Mindestanforderungen:
- TYPO3 CMS ab Version 6.0
- jQuery + jQuery Validation Plugin

Installation:

1) Entpacken Sie den Inhalt des Verzeichnisses nach "typo3conf/" in Ihrer Typo3 Instanz.
2) Legen Sie eine neue Seite vom Typ "Systemordner" f�r die gesammelten Kommentare und Kommentarverfasser an, 
	notieren Sie sich die Seiten-ID
	Hinweis: der Speicherordner der Bloginhalte (Beitr�ge, Kategorien, Schl�sselw�rter) ist irrelevant, 
	wir empfehlen dennoch, der �bersichtlichkeit halber, getrennte Systemordner zu verwenden 
3) Binden Sie das statische Konfigurationstemplate f�r Datec Blog in Ihr ROOT Page-Template ein
	- Gehen Sie hierzu auf die Wurzelseite Ihrer Homepage und �ffnen das Modul Web > Template
	- Benutzen sie die Ansicht "Info/Editieren" um die Option "Gesamten Template-Datensatz bearbeiten" zu �ffnen
	- G�gen Sie nun unter "Enth�lt" das Statische Template "Datec Blog" hinzu
4) Legen Sie eine TypoScript-Konfiguration an und konfigurieren Sie mindestens: (siehe "Konfigurationsm�glichkeiten")
	- plugin.tx_datecblog_blog.settings.commentsStoragePid = <Ihr Systemordner f�r Kommentare>
5) Binden Sie das Plugin auf einer beliebigen Seite ein
	- W�hlen Sie hierzu den Inhaltselementtyp "Plugin" und w�hlen Sie "Datec Blog" aus
	- Speichern Sie den Inhalt und �ffnen Sie die Plugin-Auswahl erneut
	- Sie k�nnen nun noch die Oberfl�che "Blog" (mit Blogeintr�gen und Kommentaren), "Kategorien", "Archiv" oder "Schl�sselw�rter" ausw�hlen
	Hinweis: Alle Bestandteile des Blogs sollten sich auf der gleichen Seite befinden, Ihre Position ist aber frei w�hlbar
6) FERTIG!

Konfigurationsm�glichkeiten:

Diese Optionen richten sich an Experten welche Datec-Blog noch weiter anpassen m�chten. Sie m�ssen diese Werte als TypoScript-Konfiguration hinterlegen.

plugin.tx_datecblog_blog {
	settings {
	    commentsStoragePid = <Ihr Systemordner f�r Kommentare und Kommentarverfasser>
	    mail {
	    	internMailFrom = <E-Mail Adresse, Benutzer und Admin erhalten Nachrichten VON dieser Adresse>
	    	internMailFromName = <E-Mail Adresse, VON - Anzeigename>
	    }
	    maxFileSize = <Zahl Bytes, maximale Gr��e pro hochgeladener Datei f�r Kommentare, default:4000000>
	    allowedFileTypes = <Dateiendungen kommasepariert, erlaubte Dateitypen f�r Kommtentare, default:"pdf,zip,png,jpg,jpeg,gif,txt,doc,docx">
		display {
        	dateFormat = <Datums-Format zur Anzeige, kompatibel zu PHP date()-Funktion, default:"d.m.Y">
       		showDefaultHeaders = <Boolean, sollen �berschrifen zu den Plugins gerendert werden?, default:1>
       		keywords {
        		limit = <Zahl, Limit f�r Ergebnismenge der Schl�sselw�rter, default:0 (disabled)>
        		order = <Datenbankspalte, Schl�sselw�rter werden nach dieser Spalte sortiert, default:"usage">
        		visual = <Entweder "cloud" oder "list", Anzeigeform der Schl�sselw�rter, default:"cloud">
        	}
        	posts {
        		dateFormat = <Datums-Format zur Anzeige, wie zuvor, f�r Blogeintr�ge, default:"d.m.Y - H:i">
        		teaserTextLength = <Zahl, L�nge nach welcher Texte ohne Teaser in der Vorschau abgeschnitten werden, default:40>
        		sorting = <Datenbankspalte, nach dieser wird sortiert, default:"crdate">
        		sortingDirection = <Sortierichtung, default:"DESC">
			}
	        	comments {
	        		dateFormat = <Datums-Format zur Anzeige, wie zuvor, f�r Kommentare, default:"d.m.Y - H:i">
	        		sorting = <Datenbankspalte, nach dieser wird sortiert, default:"crdate">
	        		sortingDirection = <Sortierichtung, default:"DESC">
			}
			feUser {
				nameFormat = <Anzeigeformat f�r Benutzernamen, "username" oder "firstname_lastname" oder "firstname" oder "lastname">
			}
		}
	    cssClasses {
				<Setzen Sie hier die CSS-Klassen die f�r Formulare verwendet m�chten, Sie sehen hier die Standartwerte f�r Bootstap 3 CSS-Framework>
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

Bei Fragen oder Hinweisen kontaktieren Sie bitte: Philipp R�nsch - p.roensch@datec-schmidt.de