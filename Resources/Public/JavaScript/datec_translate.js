/**
 * Objekt für die Übersetzung der Validierungsmeldungen
 */
var DatecTranslate = {
  	language: 'de',
  	/**
  	 * Setzt den Länder Code
  	 * @param string langs
  	 *		Länder Code
  	 */
  	setLang: function(langCode){
  		DatecTranslate.language = langCode;
  	},
  	/**
  	 * Gibt die ausgewählte Sprache zurück
  	 */
  	getLang: function() {
  		return DatecTranslate.language;
  	},
  	/**
  	 * Gibt einen String in der gewünschten Sprache zurück
  	 * @param string key
  	 *		Key des Strings der zurückgegeben werden soll es können auch platzhalter angegeben werden zb {0}, {1} usw
  	 * @param array value
  	 *		values für die platzhalter
  	 * @return string
  	 *		Gibt den entsprechenden String des angegebenen Keys zurück
  	 */
  	getLanguageString: function(key, value) {
  		//Zuweisen der Sprache
  		langkey = DatecTranslate.language;
  		//Prüfen ob die Sprache unterstützt wird 
  		if(lang.hasOwnProperty(langkey)) {
  			//Prüfen ob es den Key gibt
  			if(lang[langkey].hasOwnProperty(key)) {
  				//Rückgabe des entsprechenden Strings
  				if(value) {
  					str = lang[langkey][key];
  					return str.format(value);
  				} else {
  					return lang[langkey][key];
  				}
  			} else {
  				return "String key not found";
  			}
  		} else {
  			DatecTranslate.setLang("de");
			return DatecTranslate.getLanguageString(key);
  		}
  	}
}

String.prototype.format = function (args) {
	var str = this;
	return str.replace(String.prototype.format.regex, function(item) {
		var intVal = parseInt(item.substring(1, item.length - 1));
		var replace;
		if (intVal >= 0) {
			replace = args[intVal];
		} else if (intVal === -1) {
			replace = "{";
		} else if (intVal === -2) {
			replace = "}";
		} else {
			replace = "";
		}
		return replace;
	});
};
String.prototype.format.regex = new RegExp("{-?[0-9]+}", "g");