// closure to avoid namespace collision
(function(){

  // Create Plugin
  tinymce.create('tinymce.plugins.wppsnButtonPlugin', {
    init : function(ed, url) {

      // Find best modal size
      var wWidth = jQuery(window).width();
      var wHeight = jQuery(window).height();
      var minModalWidth = 820;
      var maxModalWidth = 1120;
      var minModalHeight = 600;
      var maxModalHeight = 850;
      var modalW = ( wWidth < minModalWidth ) ? minModalWidth : wWidth - 40;
      var modalH = ( wHeight < minModalHeight ) ? minModalHeight : wHeight - 80;
      var modalW = ( wWidth > maxModalWidth ) ? maxModalWidth : wWidth - 40;
      var modalH = ( wHeight > maxModalHeight ) ? maxModalHeight : wHeight - 80;

      // Register commands
      ed.addCommand('mcewppsn', function() {
        ed.windowManager.open(
          {
            file : wppsnInfosWysiwyg.buttonModalContentFileUrl, // content of the modal window (php file here)
            width : modalW,
            height : modalH,
            inline : 1
          },
          {
            plugin_url : url
          }
        );
      });

      // Register buttons
      ed.addButton('wppsn_phraseanet_button', {title : wppsnInfosWysiwyg.buttonText, cmd : 'mcewppsn', image: wppsnInfosWysiwyg.buttonUrl });
    }
  });

  // Register plugin
  // First parameter is the button ID and must match ID in the tinymce ed.addButton function
  // Second parameter must match the first parameter of the tinymce.create() function above
  tinymce.PluginManager.add('wppsn_phraseanet_button', tinymce.plugins.wppsnButtonPlugin);

})();