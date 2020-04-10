(function ($) {
	"use strict";
	$(window).load(function () {

		var $container = $('#isotope-main').isotope({
			itemSelector: '.element-item'
			//	layoutMode:'masonry',
			//	resizesContainer: true,
			//	resizable: true,  
		});
		$container.imagesLoaded().progress( function() {
			$container.isotope('layout');
		});
			
		$('#filters button').click(function () {
			var selector = $(this).attr('data-filter');
			$('#filters button').removeClass('is-checked');
			$(this).addClass('is-checked');
			$container.isotope({filter: selector});
			return false;
		});
		$('#load-more-class').click(function () {
			var itemTarget = $(this);
			if ($(itemTarget).hasClass('all-loaded')) {
				return;
			}
			var pages = $('.post-pagination a');
			var listPageUrl = new Array(), endPage = false;
			pages.each(function () {
				if ($(this).hasClass('loaded')) {
				} else {
					listPageUrl.push($(this));
				}
			});
			if (listPageUrl.length === 1) {
				endPage = true;
			}
			var pageLoad = listPageUrl[0];
			$.ajax({
				type: "GET",
				url: $(pageLoad).attr('href'),
				cache: false,
				beforeSend: function (xhr) {
					itemTarget.addClass('loading');
				},
				success: function (transport) {
					var html = $(transport).find('div .element-item');
					if (itemTarget.hasClass('load-posts')) {
						  $container.isotope()
							.append( html )
							.isotope( 'appended', html );
							//.isotope( 'layout' );
							html.imagesLoaded().progress( function() {
								$container.isotope('layout');
							});
						 
					}
					$(pageLoad).addClass('loaded');
					if (endPage) {
					//	itemTarget.addClass('all-loaded').html('All Loaded');
						itemTarget.remove();
					}
					itemTarget.removeClass('loading');
				}
			});
		});
	})
})(jQuery);