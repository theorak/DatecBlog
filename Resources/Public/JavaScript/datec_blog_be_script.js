window.addEventListener('load', function() {
	if(document.getElementById('tx-datec-blog-postId') && $$('.tx-datec-blog-showBeBlogView') ) {
		if( $('tx-datec-blog-postId').value != "") {
			DatecBeBlog.showSinglePost($('tx-datec-blog-postId').value);
		}else {
			DatecBeBlog.switchFilterDisplay();
			DatecBeBlog.listPosts();
			
		}
	}
	DatecTranslate.setLang($("tx-datec-blog-lang").value);
});

var DatecBeBlog = {
	listPosts: function() {
		if(DatecBeBlog.getToken()) {
			$('tx-datec-blog-ajax-loader').show();
			var formData = $('tx-datec-blog-filterBloglistForm').serialize();
			new Ajax.Updater('tx-datec-blog-posts','mod.php?M=web_DatecBlogDatecblogm1&tx_datecblog_web_datecblogdatecblogm1[action]=listPosts&moduleToken='+ DatecBeBlog.getToken() + '&tx_datecblog_web_datecblogdatecblogm1[controller]=BlogmanagementModule&tx_datecblog_blog[formData]=' + formData , {
				onComplete:function(response) {
					$('tx-datec-blog-ajax-loader').hide();
					$('tx-datec-blog-post-single').update();
				},
				onFailure: function(response) {
					alert(DatecTranslate.getLanguageString("loadError"));
					console.log(response)
				}
			});
		} else {
			alert(DatecTranslate.getLanguageString("invalidToken"));		
		}
	},	
	showSinglePost: function(postId) {
		if(DatecBeBlog.getToken()) {
			$('tx-datec-blog-ajax-loader').show();
			new Ajax.Updater('tx-datec-blog-post-single','mod.php?M=web_DatecBlogDatecblogm1&tx_datecblog_web_datecblogdatecblogm1[action]=showSinglePost&moduleToken=' + DatecBeBlog.getToken() + '&tx_datecblog_web_datecblogdatecblogm1[controller]=BlogmanagementModule&tx_datecblog_web_datecblogdatecblogm1[postId]=' + postId, {
				onComplete: function(response) {
					$('tx-datec-blog-ajax-loader').hide();
					$('tx-datec-blog-posts').hide();
					$('tx-datec-blog-filterBloglistForm').hide();
					DatecBeBlog.listComments(postId);
					
				},
				onFailure: function(response) {
					alert(DatecTranslate.getLanguageString("loadError"));
					console.log(response)
				}
			});
		} else {
			alert(DatecTranslate.getLanguageKey("invalidToken"));
		}
	},
	leaveSinglePost: function() {
		$('tx-datec-blog-info').update();
		$('tx-datec-blog-posts').show();
		$('tx-datec-blog-post-single').update();
	},
	listComments: function(postId) {
		if(DatecBeBlog.getToken()) {
			if(document.getElementById('tx-datec-blog-comments')) {
				$('tx-datec-blog-ajax-loader-comments').show();
				new Ajax.Updater('tx-datec-blog-comments','mod.php?M=web_DatecBlogDatecblogm1&tx_datecblog_web_datecblogdatecblogm1[action]=listBlogComments&moduleToken=' + DatecBeBlog.getToken() + '&tx_datecblog_web_datecblogdatecblogm1[controller]=BlogmanagementModule&tx_datecblog_web_datecblogdatecblogm1[postId]=' + postId, {
					onComplete: function(response) {
						$('tx-datec-blog-ajax-loader-comments').hide();
					},
					onFailure: function(response) {
						alert(DatecTranslate.getLanguageString("loadError"));
						console.log(response);
					}
				});
			}
			
		} else {
			alert(DatecTranslate.getLanguageString("invalidToken"));
		}
		
	},
	switchFilterDisplay: function() {
		if($('tx-datec-blog-filters-hidden').children == "" | $('tx-datec-blog-filters-hidden').children.length == 0) {
			$('tx-datec-blog-filterBloglistForm').hide();
		} else {
			$('tx-datec-blog-filterBloglistForm').show();
		}
	},
	addToFilter: function(filterItem) {
		var filterType = $(filterItem).readAttribute('data-filter-type');
		var filterValue = $(filterItem).readAttribute('data-filter-value');
		var displayName = $(filterItem).innerText;
		
		switch(filterType) {
			case "category": 
				DatecBeBlog.addCategoryToFilter(filterValue, displayName); 
				break;
			case "archivePeriode": 
				DatecBeBlog.addArchivePeriodToFilter(filterValue, displayName);
				break;
			case "keyword": 
				DatecBeBlog.addKeywordToFilter(filterValue,displayName);
				break;
			default: return false;
		}
		
		DatecBeBlog.leaveSinglePost();
		DatecBeBlog.switchFilterDisplay();
		DatecBeBlog.listPosts();
		
	},
	addCategoryToFilter: function(categoryId, filterDisplayName) {
		var counter = 0;
		var categoriesFilter = $$('.tx-datec-blog-bloglistCriteria-categories');
		console.log(categoryId);
		if(categoriesFilter.length > 0) {
			for(var i = 0; i< categoriesFilter.length; i++){
				if(categoriesFilter[i].value == categoryId) {
					
					return false;
				} else {
					break;
				}
			}
			counter = categoriesFilter.length;
		}
		
		var addCategoryHidden = document.createElement('input');
		addCategoryHidden.setAttribute('type','hidden');
		addCategoryHidden.setAttribute('class','tx-datec-blog-bloglistCriteria-categories');
		addCategoryHidden.setAttribute('id','tx-datec-blog-bloglistCriteria-category-'+categoryId);
		addCategoryHidden.setAttribute('name','tx_datecblog_blog[bloglistCriteria][categories]['+counter+']');
		addCategoryHidden.setAttribute('value',categoryId);
		
		var addCategoryFilter = document.createElement('div');
		addCategoryFilter.setAttribute('class','tx-datec-blog-filterItem tx-datec-blog-filterItem-category');
		addCategoryFilter.setAttribute('id','tx-datec-blog-filterItem-category-'+categoryId);
		addCategoryFilter.setAttribute('data-filter-type','category');
		addCategoryFilter.setAttribute('data-filter-value',categoryId);
		addCategoryFilter.setAttribute('onclick', 'DatecBeBlog.removeFromFilter(this)');
		
		var categoryValue = document.createTextNode('Kategorie: '+filterDisplayName)
		addCategoryFilter.appendChild(categoryValue);
		$('tx-datec-blog-filters-hidden').appendChild( addCategoryHidden);
		$('tx-datec-blog-filters').appendChild( addCategoryFilter);
	},
	addArchivePeriodToFilter:function(archivePeriodValue, filterDisplayName) {
		var archivePeriodParts = archivePeriodValue.split('-');
		var archivePeriodFrom = archivePeriodParts[0];
		var archivePeriodTo = archvePeriodParts[1];
		var archivePeriodType = archivePeriodParts[2];
		var archivePeriodHiddenParts = $$('.tx-datec-blog-bloglistCriteria-archivePeriod');
		var archivePeriodFilterPart = $$('.tx-datec-blog-filterItem-archivePeriod');
		
		if(archivePeriodHiddenParts.length > 0) {
			$(archivePeriodParts[0]).setAttribute('value',archivePeriodFrom);
			$(archivePeriodParts[0]).setAttribute('id', 'tx-datec-blog-bloglistCriteria-archivePeriodFrom-'+archivePeriodFrom);
			$(archivePeriodParts[1]).setAttribute('value',archivePeriodTo);
			$(archivePeriodParts[1]).setAttribute('id','tx-datec-blog-bloglistCriteria-archivePeriodTo-'+archivePeriodTo);
			$(archivePeriodParts[2]).setattribute('value', archivePeriodType);
			$(archivePeriodParts[2]).setAttribute('id', 'tx-datec-blog-bloglistCriteria-archivePeriodType-'+archivePeriodType);
		
			$(archivePeriodParts[0]).setAttribute('data-filterValue', archivePeriodValue);
			$(archivePeriodParts[0]).setAttribute('id', 'tx-datec-blog-filterItem-archivePeriod-'+archivePeriodValue)
			$(archivePeriodParts[0]).innerText = 'Archiv-Zeitraum: ' + filterDisplayName;
		} else {
			var addArchivePeriodHidden = '\<input type="hidden" class="tx-datec-blog-bloglistCriteria-archivePeriod" id="tx-datec-blog-bloglistCriteria-archivePeriodFrom-'+archivePeriodFrom+'" name="tx_datecblog_blog[bloglistCriteria][archivePeriod][from]" value="'+archivePeriodFrom+'" /><input type="hidden" class="tx-datec-blog-bloglistCriteria-archivePeriod" id="tx-datec-blog-bloglistCriteria-archivePeriodTo-'+archivePeriodTo+'" name="tx_datecblog_blog[bloglistCriteria][archivePeriod][to]" value="'+archivePeriodTo+'" /><input type="hidden" class="tx-datec-blog-bloglistCriteria-archivePeriod" id="tx-datec-blog-bloglistCriteria-archivePeriodType-'+archivePeriodType+'" name="tx_datecblog_blog[bloglistCriteria][archivePeriod][type]" value="'+archivePeriodType+'" />';
			var addArchivePeriodFilter = '<div class="tx-datec-blog-filterItem tx-datec-blog-filterItem-archivePeriod" id="tx-datec-blog-filterItem-archivePeriod-'+archivePeriodValue+'" data-filter-type="archivePeriod" data-filter-value="'+archivePeriodValue+'" onclick="DatecBlog.removeFromFilter(this)">Archiv-Zeitraum: '+filterDisplayName+'</div>';
			$('tx-datec-blog-filters-hidden').appendChild(addArchivePeriodHidden);
			$('tx-datec-blog-filters').appendChild( addArchivePeriodFilter);
			
		}
		
	},
	addKeywordToFilter: function (keywordId, filterDisplayName) {
		var counter = 0;
		var keywordFilter = $$('.tx-datec-blog-bloglistCriteria-keywords');
		
		if(keywordFilter.length > 0) {
			for(var i = 0; i < keywordFilter.length; i++) {
				if(keywordFilter[i].value == keywordId) {
					return false;
				} else {
					break;
				}
			}
			counter = keywordFilter.length;
		}
		
		var addKeywordHidden = document.createElement('input');
		addKeywordHidden.setAttribute('type','hidden');
		addKeywordHidden.setAttribute('class','tx-datec-blog-bloglistCriteria-keywords');
		addKeywordHidden.setAttribute('id','tx-datec-blog-bloglistCriteria-keyword-'+keywordId);
		addKeywordHidden.setAttribute('name', 'tx_datecblog_blog[bloglistCriteria][keywords]['+counter+']');
		addKeywordHidden.setAttribute('value', keywordId);
		
		var addKeywordFilter = document.createElement('div');
		addKeywordFilter.setAttribute('class', 'tx-datec-blog-filterItem tx-datec-blog-filterItem-keyword');
		addKeywordFilter.setAttribute('id', 'tx-datec-blog-filterItem-keyword-'+keywordId);
		addKeywordFilter.setAttribute('data-filter-type', 'keyword');
		addKeywordFilter.setAttribute('data-filter-value', keywordId);
		addKeywordFilter.setAttribute('onclick','DatecBeBlog.removeFromFilter(this)');
		
		var keywordValue = document.createTextNode('Schlüsselwort: '+filterDisplayName);
		addKeywordFilter.appendChild(keywordValue);
		
		$('tx-datec-blog-filters-hidden').appendChild(addKeywordHidden);
		$('tx-datec-blog-filters').appendChild(addKeywordFilter);
		
	},
	removeFromFilter: function(filterItem) {
		var filterType = $(filterItem).readAttribute('data-filter-type');
		var filterValue = $(filterItem).readAttribute('data-filter-value');
		console.log(filterValue);
		
		switch (filterType) {
			case "category" : DatecBeBlog.removeCategoryFromFilter(filterValue); break;
			case "archivePeriod" : DatecBeBlog.removeArchivePeriodFromFilter(filterValue); break;
			case "keyword" : DatecBeBlog.removeKeywordFormFilter(filterValue); break;
			default : return false;
		}
		
		DatecBeBlog.switchFilterDisplay();
		DatecBeBlog.listPosts();
	},
	removeCategoryFromFilter: function(categoryId) {
		$('tx-datec-blog-bloglistCriteria-category-'+categoryId).remove();
		$('tx-datec-blog-filterItem-category-'+categoryId).remove();
	},
	removeKeywordFormFilter: function(keywordId) {
		$('tx-datec-blog-bloglistCriteria-keyword-'+keywordId).remove();
		$('tx-datec-blog-filterItem-keyword-'+keywordId).remove();
	},
	removeArchivePeriodFromFilter: function(archivePeriodValue) {
		var archivePeriodParts = archivePeriodValue.split('-');
		var archivePeriodFrom = archivePeriodParts[0];
		var archivePeriodTo = archivePeriodParts[1];
		var archivePeriodType = archivePeriodParts[2];
		
		$('tx-datec-blog-bloglistCriteria-archivePeriodFrom-'+archivePeriodFrom).remove();
		$('tx-datec-blog-bloglistCriteria-archivePeriodTo-'+archivePeriodTo).remove();
		$('tx-datec-blog-bloglistCriteria-archivePeriodType-'+archivePeriodType).remove();
		$('tx-datec-blog-filterItem-archivePeriod-'+archivePeriodValue).remove();
	},
	clearFilter: function() {
		$('tx-datec-blog-filters-hidden').update();
		$('tx-datec-blog-filters').update();
		DatecBeBlog.switchFilterDisplay();
		DatecBeBlog.listPosts();
	},
	getToken:function() {
		var url = window.location.href;
		var re1='.*?';	// Non-greedy match on filler
	    var re2='\\d+';	// Uninteresting: int
	    var re3='.*?';	// Non-greedy match on filler
	    var re4='\\d+';	// Uninteresting: int
	    var re5='.*?';	// Non-greedy match on filler
	    var re6='((?:\\d+[a-z][a-z]*[0-9]+[a-z0-9]*))';	// Alphanum 1
	    
	    var p = new RegExp(re1+re2+re3+re4+re5+re6);
	    var m = p.exec(url);
	    if (m != null)
	    {	
	       return m[1];
	    } else {
	    	return false;
	    }
	},
	
} 

var lang = {
		de: {
			loadError: "Es ist ein Fehler aufgetreten!",
			invalidToken:"Es konnte kein gültiger Token ausgelesen werden",
			deleteCommentTree: "Kommentarbaum wirklich l\u00f6schen?",
			deleteComment: "Kommentar wirklich l\u00f6schen?",
			blockCommentCreator: "Kommentarersteller wirklich blockieren?",
			unBlockCommentCreator: "Kommentarersteller wirklich wieder freigeben?",
		}
}