<div class="page-nav page-nav-blog">
    <?php echo paginate_links(array(
									'type'=>'plain',
									'prev_text'          => wp_kses(__('<i class="fa fa-angle-left"></i>', 'injob'), inwave_allow_tags('i')),
									'next_text'          => wp_kses(__('<i class="fa fa-angle-right"></i>', 'injob'), inwave_allow_tags('i'))
								)
							) ?>
	<div class="clearfix"></div>
</div>