<?php
global $post;
$thispageid = $post->ID;
?>

<?php if ( 'on' == et_get_option( 'divi_back_to_top', 'false' ) ) : ?>

	<span class="et_pb_scroll_top et-pb-icon"></span>

<?php endif;

if ( ! is_page_template( 'page-template-blank.php' ) ) : ?>

			<footer id="main-footer">





				<div id="footer-bottom">
					<div class="container clearfix">




				<div class="footer_row">
					<?php if ( et_get_option( 'divi_footer_creds' ) != "" ) : ?>
						<div class="footer_credits">
							<?php echo et_get_option( 'divi_footer_creds' ) ; ?>
						</div>
					<?php endif; ?>


				</div>


					</div>	<!-- .container -->
				</div>
			</footer> <!-- #main-footer -->
		</div> <!-- #et-main-area -->

<?php endif; // ! is_page_template( 'page-template-blank.php' ) ?>

	</div> <!-- #page-container -->

	<?php wp_footer(); ?>
</body>
</html>
