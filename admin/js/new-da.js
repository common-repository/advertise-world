/**
 * Hides and shows different options based on selections
 * Add's and Deletes widgets for sidebars
 *
 * @summary   Hides and shows elements of the user interface
 *
 * @link      https://www.advertiseworld.com
 * @since     1.0.0
 * @requires jQuery.js
 */


// Store the widget instance we created last
var advertise_world_current_widget = null;

// Remove unwanted components of drag and drop widget UI
jQuery('.widget-title-action').attr("style", "display: none !important");
jQuery('.sidebar-name-arrow').attr("style", "display: none !important");


/**
 * Updates new-ad UI to show options for selected type.
 *
 * @since 1.0.0
 *
 * @param elem selected
 */
function advertise_world_type_updated(selected) {

    // Hide all other type placement options
    var container = document.getElementById('advertise-world-wp-options-new-ad-section-placement-container');
    [].forEach.call(container.childNodes, function(child) {
        if('DIV' === child.tagName) {
            child.style.display = 'none';
        }
    });

    // unselect all radio boxes
    var options = document.getElementsByClassName('advertise-world-wp-options-new-ad-section-placement');
    [].forEach.call(options, function(item) {
        item.checked = false;
    });

    // remove all added widgets for this id
    if(advertise_world_current_widget != null) {
        wpWidgets.save( advertise_world_current_widget, 1, 0, 0 );
        advertise_world_current_widget = null;
    }

    // clear which sidebar the widget will be added to
    document.getElementsByClassName('widgets-chooser-actions')[0].innerHTML = '';

    // show selected type's placement options
    document.getElementById('advertise-world-wp-options-new-ad-section-placement-' + selected.value).style.display = 'block';

    jQuery('.advertise-world-admin-menu-new-ad-adspace-id').text(jQuery("#advertise-world-wp-options-new-ad-section-title").val());

    // if type is a widget
    if(jQuery('#advertise-world-wp-options-new-ad-section-placement-' + selected.value).find('#widgets-right').length) {

        // set this sidebar as the one a widget will be added too
        document.getElementsByClassName('widgets-chooser-actions')[0].innerHTML = '<ul class="widgets-chooser-sidebars"><li class="widgets-chooser-selected">' + selected.parentElement.innerText + '</li></ul>';

        // assign the sidebars ID to the widget selector
        var sidebarId = jQuery('#advertise-world-wp-options-new-ad-section-placement-' + selected.value).find( '.widgets-sortables' ).attr( 'id' );
        jQuery('.widgets-chooser-selected').data('sidebarId', sidebarId);

        // Add widget too sidebar
        var chooser = jQuery('.widgets-chooser');
        wpWidgets.addWidget(chooser);

        advertise_world_current_widget = jQuery('#advertise-world-wp-options-new-ad-section-placement-' + selected.value).find( '.widgets-sortables' ).children().last();

        // wpWidgets.save(advertise_world_current_widget, 0, 0, 1);

        // make sure that widget attributes do not show up on our page as we have already set them.
        jQuery('.widget-inside').queue(function() {
            // slide back up after sliding down. Not sure why this stops the animations completely but hey thats good i guess
            jQuery( this ).slideUp('fast', function() {
                widget.attr( 'style', '' );
                widget.removeClass( 'open' );
            });
        });

        // set background color of widget in question
        advertise_world_current_widget.find('.widget-top').css("background-color", "#3399ff")
        
        // we should preset the name and adspace here
        advertise_world_update_widget_form();
        
        // highlight which ad we created this instance

        // save the widget ( we do this here so we dont have to overide the other save button )

    }

}


/**
 * Updates Widget form with entered data
 *
 * @since 1.0.0
 */
function advertise_world_update_widget_form() {

    if(advertise_world_current_widget) {

        var options = advertise_world_current_widget.find('.widget-content');

        options.children().first().val(jQuery('#advertise-world-wp-options-new-ad-section-id').val());
        options.children().eq(1).find('input').val(jQuery('#advertise-world-wp-options-new-ad-section-name').val());
        options.children().eq(2).find('input').val(jQuery('#advertise-world-wp-options-new-ad-section-title').val());

    }
}

// Add listeners to update widget form when data is modified
jQuery('#advertise-world-wp-options-new-ad-section-name').change(advertise_world_update_widget_form);
jQuery('#advertise-world-wp-options-new-ad-section-title').change(advertise_world_update_widget_form);


// make sure that the placement is shown for the selected ad type when editing an advert
jQuery(function() {
    var selected_radio = jQuery("input[type='radio']:checked")[0];
    if ( selected_radio ) {
        document.getElementById('advertise-world-wp-options-new-ad-section-placement-' + selected_radio.value).style.display = 'block';
    }
});