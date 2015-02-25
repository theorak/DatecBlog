$().ready(function() {
	if (typeof DatecBlog.pageId != "undefined") {
		if ($('#tx-datec-blog-postId').val() != "") {
			DatecBlog.showSinglePost($('#tx-datec-blog-postId').val(), false);
		}
		DatecBlog.listPosts();
		DatecBlog.switchFilterDisplay();
	}
	if ($('#tx-datec-blog-keyword-cloud').length > 0) {
		DatecBlog.initializeKeywordCloud();
	}
});

var DatecBlog = {
	path: $(location).attr('href').split('?')[0], // base path for ajax requests
	pagetype: '1660', // pageNum for ajax requests
	pageId: $('#tx-datec-blog-pageId').val(), // pageId for ajax requests
	listPosts: function() {  
		$('#tx-datec-blog-ajax-loader').show();
		var formData = $('#tx-datec-blog-filterBloglistForm').serialize();
		var paginationCurrent = ($('#tx-datec-blog-paginationCurrent').val() == 'undefined') ? 0 : $('#tx-datec-blog-paginationCurrent').val();
		$.ajax({
			async: true,
		    url: DatecBlog.path + '?id= ' + DatecBlog.pageId + '&tx_datecblog_blog[controller]=Blog&tx_datecblog_blog[action]=listPosts&type=' + DatecBlog.pagetype + '&tx_datecblog_blog[formData]=' + formData + '&tx_datecblog_blog[paginationCurrent]=' + paginationCurrent,
		    success: function(result,status,xhr) {
			    $('#tx-datec-blog-posts').html(result);
		        $('#tx-datec-blog-ajax-loader').hide();
		        DatecBlog.initializeLightboxLinks();
		    },
		    error: function(error) {
		        console.log(error);
		        alert('Es ist ein Fehler aufgetreten!');
		    }
		});
	},
	listPostsOnPage: function(pageNumber) {
		$('#tx-datec-blog-paginationCurrent').val(pageNumber);
		DatecBlog.listPosts();
	},
	updateClickCount: function(keywordId) {
		$.ajax({
			async: true,
		    url: DatecBlog.path + '?id= ' + DatecBlog.pageId + '&tx_datecblog_blog[controller]=Keywords&tx_datecblog_blog[action]=updateClickCount&type=' + DatecBlog.pagetype + '&tx_datecblog_blog[keywordId]=' + keywordId,
		    success: function(result,status,xhr) {
		    	$('#tx-datec-blog-keywords').append(result);
		    },
		    error: function(error) {
		        console.log(error);
		        alert('Es ist ein Fehler aufgetreten!');
		    }
		});
	},
	showSinglePost: function(postId, focusComments) {    
		$('#tx-datec-blog-ajax-loader').show();
		var commentText = $('#tx-datec-blog-commentText').val(); // prefilled comment
		$.ajax({
			async: true,
		    url: DatecBlog.path + '?id= ' + DatecBlog.pageId + '&tx_datecblog_blog[controller]=Blog&tx_datecblog_blog[action]=showSinglePost&type=' + DatecBlog.pagetype + '&tx_datecblog_blog[postId]=' + postId + '&tx_datecblog_blog[commentText]=' + commentText,
		    success: function(result,status,xhr) {
		    	$('#tx-datec-blog-post-single').html(result);
		    	$('#tx-datec-blog-filterBloglistForm').hide();
		    	$('#tx-datec-blog-posts').hide();    	
		        $('#tx-datec-blog-ajax-loader').hide();
		        DatecBlog.initializeLightboxLinks();
			    DatecBlog.listComments(postId);
		        DatecBlog.validateCommentForm();
		        if (focusComments) {
		        	$('html, body').animate({
		                scrollTop: $("#tx-datec-blog-comments").offset().top
		            }, 2000);	        	
		        }
		    },
		    error: function(error) {
		        console.log(error);
		        alert('Es ist ein Fehler aufgetreten!');
		    }
		});
	},
	listComments: function(postId) {  
		if ($('#tx-datec-blog-comments').length) {
			$('#tx-datec-blog-ajax-loader-comments').show();
			$.ajax({
				async: true,
			    url: DatecBlog.path + '?id= ' + DatecBlog.pageId + '&tx_datecblog_blog[controller]=Blog&tx_datecblog_blog[action]=listComments&type=' + DatecBlog.pagetype + '&tx_datecblog_blog[postId]=' + postId,
			    success: function(result,status,xhr) {
			    	$('#tx-datec-blog-comments').html(result);
			    	$('#tx-datec-blog-ajax-loader-comments').hide(postId);
			    },
			    error: function(error) {
			        console.log(error);
			        alert('Es ist ein Fehler aufgetreten!');
			    }
			});
		}
	},
	createComment: function () {
		var valid = $("#tx-datec-blog-commentForm").valid();
	    if (valid) {
	    	$('#tx-datec-blog-ajax-loader-comments').show();
			$('#tx-datec-blog-duplicant2').attr('value', $('#tx-datec-blog-duplicant1').val());
	    	$("#tx-datec-blog-commentForm").submit();
	    }
	},
	leaveSinglePost: function() {
		DatecBlog.switchFilterDisplay();
		$('#tx-datec-blog-info').empty();
		$('#tx-datec-blog-posts').show();
		$('#tx-datec-blog-post-single').empty();
	},
	replyToComment: function(parentId, creatorName) {
		$('html, body').animate({
		  scrollTop: $("#tx-datec-blog-commentForm").offset().top
		}, 500);
		$('#tx-datec-blog-comment-info').html("Ihre Antwort auf "+creatorName+"'s Kommentar: <span style='cursor:pointer;' onclick='DatecBlog.dontReplyToComment("+parentId+");'>(Nicht darauf Antworten)</span>");
		$('#tx-datec-blog-comment-parent').attr('value', parentId);		
	},
	dontReplyToComment: function(parentId) {
		$('#tx-datec-blog-comment-info').empty();
		$('#tx-datec-blog-comment-parent').attr('value', 0);
	},
	switchFilterDisplay: function() {
		if ($('#tx-datec-blog-filters-hidden').html() == "") {
			$('#tx-datec-blog-filterBloglistForm').hide();
		} else {
			$('#tx-datec-blog-filterBloglistForm').show();
		}
	},
	clearFilter: function() {
		$('#tx-datec-blog-filters-hidden').empty();
		$('#tx-datec-blog-filters').empty();
		DatecBlog.switchFilterDisplay();
		DatecBlog.listPosts();
	},
	addToFilter: function(filterItem) {
		var filterType = $(filterItem).data('filterType');
		var filterValue = $(filterItem).data('filterValue');
		var filterDisplayName = $(filterItem).html();
		
		switch (filterType) {
			case "category" : DatecBlog.addCategoryToFilter(filterValue, filterDisplayName); break;
			case "archivePeriod" : DatecBlog.addArchivePeriodToFilter(filterValue, filterDisplayName); break;
			case "keyword" : DatecBlog.addKeywordToFilter(filterValue, filterDisplayName); break;
			default : return false;
		}
		
		DatecBlog.leaveSinglePost();
		DatecBlog.switchFilterDisplay();
		DatecBlog.listPosts();	
	},
	addCategoryToFilter: function(categoryId, filterDisplayName) {
		var counter = 0;
		var categoriesFilter = $('.tx-datec-blog-bloglistCriteria-categories');
		
		if (categoriesFilter.length > 0) {
			for (var i = 0; i < categoriesFilter.length; i++) { // check if category not already is used in filter
			    if ($(categoriesFilter[i]).val() != categoryId) {
			    	continue;
			    } else {
			    	return false;
			    }
			}
			counter = categoriesFilter.length;
		}
		var addCategoryHidden = '<input type="hidden" class="tx-datec-blog-bloglistCriteria-categories" id="tx-datec-blog-bloglistCriteria-category-'+categoryId+'" name="tx_datecblog_blog[bloglistCriteria][categories]['+counter+']" value="'+categoryId+'" />';
		var addCategoryFilter = '<div class="tx-datec-blog-filterItem tx-datec-blog-filterItem-category" id="tx-datec-blog-filterItem-category-'+categoryId+'" data-filter-type="category" data-filter-value="'+categoryId+'" onclick="DatecBlog.removeFromFilter(this)">Kategorie: '+filterDisplayName+'</div>';
		$('#tx-datec-blog-filters-hidden').append(addCategoryHidden);
		$('#tx-datec-blog-filters').append(addCategoryFilter);	
	},
	addArchivePeriodToFilter: function(archivePeriodValue, filterDisplayName) {
		var archivePeriodParts = archivePeriodValue.split('-');
		var archivePeriodFrom = archivePeriodParts[0];
		var archivePeriodTo = archivePeriodParts[1];
		var archivePeriodType = archivePeriodParts[2];
		var archivePeriodHiddenParts = $('.tx-datec-blog-bloglistCriteria-archivePeriod');
		var archivePeriodFilterPart = $('.tx-datec-blog-filterItem-archivePeriod');
		
		if (archivePeriodHiddenParts.length > 0) { // is there already a archivePeriod in the filter? update it
			$(archivePeriodHiddenParts[0]).attr('value', archivePeriodFrom);
			$(archivePeriodHiddenParts[0]).attr('id', 'tx-datec-blog-bloglistCriteria-archivePeriodFrom-'+archivePeriodFrom);
			$(archivePeriodHiddenParts[1]).attr('value', archivePeriodTo);
			$(archivePeriodHiddenParts[1]).attr('id', 'tx-datec-blog-bloglistCriteria-archivePeriodTo-'+archivePeriodTo);
			$(archivePeriodHiddenParts[2]).attr('value', archivePeriodType);
			$(archivePeriodHiddenParts[2]).attr('id', 'tx-datec-blog-bloglistCriteria-archivePeriodType-'+archivePeriodType);			
			
			$(archivePeriodFilterPart[0]).data('filterValue', archivePeriodValue);
			$(archivePeriodFilterPart[0]).html('Archiv-Zeitraum: '+filterDisplayName);
			$(archivePeriodFilterPart[0]).attr('id', 'tx-datec-blog-filterItem-archivePeriod-'+archivePeriodValue);			
		} else {		
			var addArchivePeriodHidden = '\<input type="hidden" class="tx-datec-blog-bloglistCriteria-archivePeriod" id="tx-datec-blog-bloglistCriteria-archivePeriodFrom-'+archivePeriodFrom+'" name="tx_datecblog_blog[bloglistCriteria][archivePeriod][from]" value="'+archivePeriodFrom+'" /><input type="hidden" class="tx-datec-blog-bloglistCriteria-archivePeriod" id="tx-datec-blog-bloglistCriteria-archivePeriodTo-'+archivePeriodTo+'" name="tx_datecblog_blog[bloglistCriteria][archivePeriod][to]" value="'+archivePeriodTo+'" /><input type="hidden" class="tx-datec-blog-bloglistCriteria-archivePeriod" id="tx-datec-blog-bloglistCriteria-archivePeriodType-'+archivePeriodType+'" name="tx_datecblog_blog[bloglistCriteria][archivePeriod][type]" value="'+archivePeriodType+'" />';
			var addArchivePeriodFilter = '<div class="tx-datec-blog-filterItem tx-datec-blog-filterItem-archivePeriod" id="tx-datec-blog-filterItem-archivePeriod-'+archivePeriodValue+'" data-filter-type="archivePeriod" data-filter-value="'+archivePeriodValue+'" onclick="DatecBlog.removeFromFilter(this)">Archiv-Zeitraum: '+filterDisplayName+'</div>';
			$('#tx-datec-blog-filters-hidden').append(addArchivePeriodHidden);
			$('#tx-datec-blog-filters').append(addArchivePeriodFilter);
		}
	},
	addKeywordToFilter: function(keywordId, filterDisplayName) {
		var counter = 0;
		var keywordsFilter = $('.tx-datec-blog-bloglistCriteria-keywords');
		
		if (keywordsFilter.length > 0) {
			for (var i = 0; i < keywordsFilter.length; i++) { // check if keyqord not already is used in filter
			    if ($(keywordsFilter[i]).val() != keywordId) {
			    	continue;
			    } else {
			    	return false;
			    }
			}
			counter = keywordsFilter.length;
		}
		DatecBlog.updateClickCount(keywordId);
		var addKeywordHidden = '<input type="hidden" class="tx-datec-blog-bloglistCriteria-keywords" id="tx-datec-blog-bloglistCriteria-keyword-'+keywordId+'" name="tx_datecblog_blog[bloglistCriteria][keywords]['+counter+']" value="'+keywordId+'" />';
		var addKeywordFilter = '<div class="tx-datec-blog-filterItem tx-datec-blog-filterItem-keyword" id="tx-datec-blog-filterItem-keyword-'+keywordId+'" data-filter-type="keyword" data-filter-value="'+keywordId+'" onclick="DatecBlog.removeFromFilter(this)">Schl&uuml;sselwort: '+filterDisplayName+'</div>';
		$('#tx-datec-blog-filters-hidden').append(addKeywordHidden);
		$('#tx-datec-blog-filters').append(addKeywordFilter);
	},
	removeFromFilter: function(filterItem) {
		var filterType = $(filterItem).data('filterType');
		var filterValue = $(filterItem).data('filterValue');		
		
		switch (filterType) {
			case "category" : DatecBlog.removeCategoryFromFilter(filterValue); break;
			case "archivePeriod" : DatecBlog.removeArchivePeriodFromFilter(filterValue); break;
			case "keyword" : DatecBlog.removeKeywordFromFilter(filterValue); break;
			default : return false;
		}
		
		DatecBlog.switchFilterDisplay();
		DatecBlog.listPosts();
	},
	removeCategoryFromFilter: function(categoryId) {		
		$('#tx-datec-blog-bloglistCriteria-category-'+categoryId).remove();
		$('#tx-datec-blog-filterItem-category-'+categoryId).remove();
	},
	removeArchivePeriodFromFilter: function(archivePeriodValue) {		
		var archivePeriodParts = archivePeriodValue.split('-');
		var archivePeriodFrom = archivePeriodParts[0];
		var archivePeriodTo = archivePeriodParts[1];
		var archivePeriodType = archivePeriodParts[2];
		
		$('#tx-datec-blog-bloglistCriteria-archivePeriodFrom-'+archivePeriodFrom).remove();
		$('#tx-datec-blog-bloglistCriteria-archivePeriodTo-'+archivePeriodTo).remove();
		$('#tx-datec-blog-bloglistCriteria-archivePeriodType-'+archivePeriodType).remove();
		$('#tx-datec-blog-filterItem-archivePeriod-'+archivePeriodValue).remove();
	},
	removeKeywordFromFilter: function(keywordId) {
		$('#tx-datec-blog-bloglistCriteria-keyword-'+keywordId).remove();
		$('#tx-datec-blog-filterItem-keyword-'+keywordId).remove();
	},
	initializeKeywordCloud: function() {
		var cloudItems = $('.tx-datec-blog-cloud-item');
		
		cloudItems.each(function(i, v) {
			var itemClasses = $(cloudItems[i]).attr('class').split(' ');
			var sizingClassParts = itemClasses[1].split('-');
			var newSizingClass;
		  
			if (sizingClassParts[1] > 500) {
			  newSizingClass = "size-xlarge";
			} else if (sizingClassParts[1] > 100) {
			  newSizingClass = "size-large";
			} else if (sizingClassParts[1] > 50) {
			  newSizingClass = "size-medium";
			} else if (sizingClassParts[1] > 20) {
			  newSizingClass = "size-normal";
			} else {
			  newSizingClass = "size-small";
			}
			
			$(cloudItems[i]).addClass(newSizingClass);
		});
	},
	switchTreeLevel: function(treeSwitcher) {
		var treeType = $(treeSwitcher).data('treeType');
		var treeLevel = $(treeSwitcher).data('treeLevel');
		var treeContent = $('.tx-datec-blog-tree-items[data-tree-type="'+treeType+'"][data-tree-level="'+treeLevel+'"]');
		
		if (treeContent.css('display') == "block") {
			treeContent.hide();
			$(treeSwitcher).removeClass("open");
		} else {
			treeContent.show();
			$(treeSwitcher).addClass("open");
		}
	},
	validateCommentForm: function() {
		if ($("#tx-datec-blog-commentForm").length) {
			$("#tx-datec-blog-commentForm").validate();
			
			if ($("#tx-datec-blog-comment-text").length) {
			    $("#tx-datec-blog-comment-text").rules("add", {
			        required: true,
			        messages: {
			            required: "Bitte geben Sie einen Kommentartext an",
			        }
			    });
			}			
			if ($("#tx-datec-blog-comment-creator-email").length) {
			    $("#tx-datec-blog-comment-creator-email").rules("add", {
			        required: true,
			        email: true,
			        messages: {
			            required: "Bitte geben Ihre E-Mail Adresse an",
			            email: "Bitte geben sie eine g&uuml;ltige E-Mail Adresse an",
			        }
			    });
			}
			if ($("#tx-datec-blog-comment-creator-username").length) {
			    $("#tx-datec-blog-comment-creator-username").rules("add", {
			        required: true,
			        messages: {
			            required: "Bitte geben Sie Ihren Benutzernamen an",
			        }
			    });
			}
		}
	},
	initializeLightboxLinks: function() {
		var $lightboxModal = "\
            <div class='modal fade' id='lightbox' tabindex='-1' role='dialog' aria-hidden='true'>\
                <div class='modal-dialog modal-lightbox'>\
                    <div class='modal-content'>\
                        <div class='modal-body'></div>\
                    </div>\
                </div>\
            </div>\
        ";
        $('body').append($lightboxModal);
        $('.lightbox').click(function(event){
            event.preventDefault();
            var $lightbox = $('#lightbox');
            var $modalBody = $lightbox.find('.modal-body');
            var $modalDialog = $lightbox.find('.modal-dialog');
            $modalBody.empty();
            $modalBody.append('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>');
            var $src = $(this).attr("href");
            var $image = "<img class=\"img-responsive\" src=\"" + $src + "\">";

            // FIX IMAGEWIDTH
            var img = new Image();
            img.onload = function(){
                $modalDialog.width(img.width);
                $modalDialog.css({ "max-width": '95%' });
            };
            img.src = $src;

            $modalBody.append($image);
            var $title = $(this).attr("title");
            var $text = $(this).parent().find('.caption').html();
            if($title || $text){
                $modalBody.append('<div class="modal-caption"></div>');
                if($title){
                    $modalBody.find('.modal-caption').append("<span class=\"modal-caption-title\">" + $title + "</span>");
                }
                if($text){
                    $modalBody.find('.modal-caption').append($text);
                }
            }
            $('#lightbox').modal({show:true});
        });
	}
}