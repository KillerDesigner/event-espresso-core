<div id="espresso-thank-you-page-overview-dv" class="width-100" >

	<div class="ee-attention">
		<div class="extra-padding-sides jst-left">
			<p class="bigger-text">
			<?php 
			echo apply_filters( 
				'FHEE__thank_you_page_overview_template__order_conf_desc', 
				sprintf( 
					__( '%sCongratulations%sYou\'re registration has been successfully processed. Check your email for your registration confirmation or click the button below to view / download / print a full description of your purchases and registration information.', 'event_espresso' ),
					'<h2 class="">',
					'</h2>'
				)
			)
			?>				
			</p>
			<div class="jst-rght">
				<a class="ee-button ee-roundish indented-text big-text" href="<?php echo $TXN_receipt_url;?>"><span class="ee-icon ee-icon-PDF-file-type"></span><?php _e( 'View Full Order Confirmation Receipt', 'event_espresso' );?></a>
			</div>
		</div>
	</div>
	<br/>
	<br/>

	<?php  do_action( 'AHEE__thank_you_page_overview_template__content' ); ?>

</div>
<!-- end of espresso-thank-you-page-overview-dv -->