jQuery(document).ready(function ($) {
  var SMT_Script_Configuration = {
		init : function() {
			// this.add();
			// this.move();
			// this.remove();
			// this.type();
			// this.prices();
			this.files();
			// this.updatePrices();
		},
    files : function() {
			var file_frame;
			window.formfield = '';

			$( document.body ).on('click', '.smartly_upload_file_button', function(e) {
				e.preventDefault();
				var button = $(this);
				window.formfield = $(this).closest('.smartly_attachment_field_containers');

				// If the media frame already exists, reopen it.
				if ( file_frame ) {
					//file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
					file_frame.open();
					return;
				}

				// Create the media frame.
				file_frame = wp.media.frames.file_frame = wp.media( {
					frame: 'post',
					state: 'insert',
					title: button.data( 'uploader-title' ),
					button: {
						text: button.data( 'uploader-button-text' )
					},
          multiple: false
					// multiple: $( this ).data( 'multiple' ) == '0' ? false : true  // Set to true to allow multiple files to be selected
				} );

				file_frame.on( 'menu:render:default', function( view ) {
					// Store our views in an object.
					var views = {};

					// Unset default menu items
					view.unset( 'library-separator' );
					view.unset( 'gallery' );
					view.unset( 'featured-image' );
					view.unset( 'embed' );

					// Initialize the views in our view object.
					view.set( views );
				} );

				// When an image is selected, run a callback.
				file_frame.on( 'insert', function() {

					var selection = file_frame.state().get('selection');

					selection.each( function( attachment, index ) {
						attachment = attachment.toJSON();
            console.log(attachment);

						// var selectedSize                  = 'image' === attachment.type ? $('.attachment-display-settings .size option:selected').val() : false;
            var selectedfilesizeHumanReadable = attachment.filesizeHumanReadable;
            var selectedfilesizeInBytes       = attachment.filesizeInBytes;
						var selectedURL                   = attachment.url;
						var selectedName                  = attachment.title.length > 0 ? attachment.title : attachment.filename;
            var selectedFilename              = attachment.filename;
            var selectedFiletype              = attachment.type;
            console.log(attachment);

            window.formfield.find( '.smt_attachment_id_field' ).val( attachment.id );
            window.formfield.find( '.smt_attachment_name_field' ).val( selectedFilename );
            window.formfield.find( '.smt_attachment_type_field' ).val( selectedFiletype );
            window.formfield.find( '.smt_attachment_size_readable').val( selectedfilesizeHumanReadable );
            window.formfield.find( '.smt_attachment_size_bytes').val( selectedfilesizeInBytes );
            window.formfield.find( '.smartly_catalogue_file_field' ).val( selectedURL );

					});
				});

				// Finally, open the modal
				file_frame.open();
			});


			var file_frame;
			window.formfield = '';

		},

  };

  SMT_Script_Configuration.init();

});
