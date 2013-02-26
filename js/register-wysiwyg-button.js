// closure to avoid namespace collision
(function(){

  // Create Plugin
  tinymce.create('tinymce.plugins.wppsnButtonPlugin', {
    init : function(ed, url) {

      // Find best modal size
      var wWidth = jQuery(window).width() - 40;
      var wHeight = jQuery(window).height() - 40;
      var defaultWidth = 700;
      var defaultHeight = 600;
      var W = ( defaultWidth < wWidth ) ? defaultWidth : wWidth - 40;
      var H = ( defaultHeight < wHeight ) ? defaultHeight : wHeight - 40;

      // Register commands
      ed.addCommand('mcewppsn', function() {
        ed.windowManager.open(
          {
            file : wppsnGlobalVars.wysiwygButtonModalContentFileUrl, // content of the modal window (php file here)
            width : W,
            height : H,
            inline : 1
          },
          {
            plugin_url : url
          }
        );
      });

      // Register buttons
      ed.addButton('wppsn_phraseanet_button', {title : wppsnGlobalVars.wysiwygButtonText, cmd : 'mcewppsn', image: wppsnGlobalVars.wysiwygButtonUrl });
    }
  });

  // Register plugin
  // First parameter is the button ID and must match ID in the tinymce ed.addButton function
  // Second parameter must match the first parameter of the tinymce.create() function above
  tinymce.PluginManager.add('wppsn_phraseanet_button', tinymce.plugins.wppsnButtonPlugin);

})();