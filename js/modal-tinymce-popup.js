/**
 * Register the tinyMCE action for inserting content in wysiwyg and close the popup
 */
var wppsnDialog = {
    local_ed : 'ed',
    init : function( ed ) {
        wppsnDialog.local_ed = ed;
        tinyMCEPopup.resizeToInnerSize();
    },
    insert : function insertButton( ed, output ) {

        // Try and remove existing style / blockquote
        tinyMCEPopup.execCommand( 'mceRemoveNode', false, null );

        // Insert output in editor
        tinyMCEPopup.execCommand( 'mceReplaceContent', false, output );

        // Close popup
        tinyMCEPopup.close();
    }
};

tinyMCEPopup.onInit.add( wppsnDialog.init, wppsnDialog );