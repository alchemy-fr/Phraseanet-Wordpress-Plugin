/**
 * All the modal behavior
 */

async function addFacet(e) {
  let facets_list = await localStorage.getItem("facets");

  if (facets_list == undefined || facets_list.length < 3) {
    var obj = [{ id: this.id, name: this.name, parent: this.rel }];

    await localStorage.setItem("facets", JSON.stringify(obj));
  } else {
    let json_to_array = JSON.parse(facets_list);

    for (let i = 0; i < json_to_array.length; i++) {
      let id = json_to_array[i].id;

      if (id == this.id) {
        json_to_array.splice(i, 1);
        await json_to_array.push({
          id: this.id,
          name: this.name,
          parent: this.rel,
        });
      } else {
        await json_to_array.push({
          id: this.id,
          name: this.name,
          parent: this.rel,
        });
        console.log(this.id);
        console.log(id);
      }
    }

    localStorage.setItem("facets", JSON.stringify(json_to_array));
  }
}

function toggle(e) {
  let st = document.getElementById(this.id + "_view").style;
  st.display = st.display == "none" ? "block" : "none";
}

function removeFacet(e) {
  console.log("yo");
  console.log(this.name);

  let facets_list = localStorage.getItem("facets");

  if (facets_list != undefined) {
    let json_to_array = JSON.parse(facets_list);

    for (let i = 0; i < json_to_array.length; i++) {
      let id = json_to_array[i].id;

      if (id == this.name) {
        json_to_array.splice(i, 1);
      }
    }
    localStorage.setItem("facets", JSON.stringify(json_to_array));
  }
}
async function initFacets() {
  let location = window.location.href;
  let location_index = location.indexOf("/wp-admin");
  let loc = location.substring(0, location_index);
  await fetch(loc + "/wp-admin/admin-ajax.php?action=get_facets_list")
    .then((data) => {
      data
        .json()
        .then((json) => {
          var output = document.createElement("UL");
          output.className = "listBox";
          //output.setAttribute(class,"list-group")
          for (var k in json) {
            var li = document.createElement("li");

            var a = document.createElement("p");
            var linkText = document.createTextNode(k);
            a.appendChild(linkText);
            a.id = k;
            a.addEventListener("click", toggle);
            a.style =
              "background-color: #5ea0ba;color: white;font-size: 16px;padding-left: 7px;padding-top: 7px;padding-bottom: 7px;cursor: pointer;border-radius: 2%;";

            li.appendChild(a);
            li.className = "list-item";

            let subListWraper = document.createElement("div");
            subListWraper.id = k + "_view";
            subListWraper.style =
              "display:none;text-align: left;margin-left: -27%;";

            for (let i in json[k]) {
              if (json[k][i][0] != undefined) {
                var ul = document.createElement("ul");

                var li2 = document.createElement("li");
                li2.style = "list-style-type: none;";
                var a = document.createElement("a");
                var linkText = document.createTextNode(json[k][i][0]);
                a.appendChild(linkText);
                a.title = json[k][i][0];
                a.name = json[k][i][0];
                a.id = json[k][i][1];
                a.href = "";
                a.rel = k;
                a.style = "font-size:11px;";
                a.addEventListener("click", addFacet);
                li2.appendChild(a);
                li2.setAttribute("id", json[k][i][1]);
                li2.setAttribute("name", json[k][i][0]);

                var icon = document.createElement("a");
                icon.className = "btnClose";
                icon.style = "display:none";
                icon.id = "btn_" + json[k][i][1];
                icon.name = json[k][i][1];
                icon.appendChild(document.createTextNode("X"));
                icon.href = "";

                icon.addEventListener("click", removeFacet);
                li2.appendChild(icon);
                ul.appendChild(li2);

                subListWraper.appendChild(ul);
                li.appendChild(subListWraper);
              }
            }

            output.appendChild(li);
          }

          document.getElementById("loading").style.display = "none";
          document.getElementById("load_facets").appendChild(output);

          let facets_list = localStorage.getItem("facets");

          if (facets_list != undefined) {
            let json_to_array = JSON.parse(facets_list);

            for (let i = 0; i < json_to_array.length; i++) {
              let id = json_to_array[i].id;

              let btn_id = document.getElementById("btn_" + id);

              if (btn_id != null) {
                btn_id.style = "display:block";

                document.getElementById(
                  json_to_array[i].parent + "_view"
                ).style.display = "block";
              }
            }
          }
        })
        .catch((err) => {
          console.log(err);
        });
    })
    .catch((err) => {
      console.log(err);
    });
}

/**
 * Global var used for storing constant values
 */
var wppsnGlobals = {
  ajaxUrl: "../../../../../wp-admin/admin-ajax.php",
};

/**
 * Define the plugin
 */
(function ($, window, document, undefined) {
  // Default vars
  var pluginName = "wppsnInterface";

  /**
   * Create the Modal Main Object
   */
  function WppsnModal(element, options) {
    this.bodyElt = jQuery(element);
    this.imgGallerySelectedImgIds = new Array(); // Images Gallery : selected images IDs
    this.videoPlaylistSelectedVideoIds = new Array(); // Video Playlist : selected videos IDs
    this.init();
  }

  /**
   * Modal Initialisation
   */
  WppsnModal.prototype.init = function () {
    // Get initial HTML content
    jQuery.ajax({
      url: wppsnGlobals.ajaxUrl,
      data: { action: "wppsn-get-modal-initial-content" },
      context: this,
      success: function (resp) {
        this.bodyElt.html(resp);

        // If plugin is completely configured (the interface is shown, else an error paragraph is shown)
        if (this.bodyElt.find("#wppsn-sidebar").length > 0) {
          this.prepareInitialContent();
          initFacets();
        }
      },
      dataType: "html",
    });
  };

  /**
   * Prepare initial Content
   */
  WppsnModal.prototype.prepareInitialContent = function () {
    // Put in cache some DOM elements
    this.domPanSidebar = jQuery("#wppsn-sidebar");
    this.domMenu = this.domPanSidebar.find("#wppsn-menu");

    this.domMainPans = jQuery(".wppsn-main-pan");

    this.domPanSingleMedia = jQuery("#wppsn-pan-medias");
    this.domSingleMediaTabs = this.domPanSingleMedia.find(
      ".wppsn-main-pan-tabs"
    );
    this.domSingleMediaListMediasWrapper = this.domPanSingleMedia.find(
      ".wppsn-media-list-wrapper"
    );
    this.domSingleMediaListMediasHeader = this.domPanSingleMedia.find(
      ".wppsn-media-list-header"
    );
    this.domSingleMediaSearchInput = this.domSingleMediaListMediasHeader.find(
      ".wppsn-search-field"
    );
    this.domSingleMediaSearchType = this.domSingleMediaListMediasHeader.find(
      ".wppsn-search-type"
    );
    this.domSingleMediaRecordType = this.domSingleMediaListMediasHeader.find(
      ".wppsn-record-type"
    );
    this.domSingleMediaCounter = this.domSingleMediaListMediasHeader.find(
      ".wppsn-media-counter strong"
    );
    this.domSingleMediaListMedias = this.domPanSingleMedia.find(
      ".wppsn-media-list"
    );
    this.domSingleMediaListPagination = this.domPanSingleMedia.find(
      ".wppsn-media-list-pagination"
    );
    this.domPanSingleMediaInsert = this.domPanSingleMedia.find(
      "#wppsn-single-media-insert-wrapper"
    );
    this.domSingleMediaInsertPans = this.domPanSingleMediaInsert.find(
      ".wppsn-single-media-insert-pan"
    );
    this.domSingleMediaInsertButtons = this.domPanSingleMediaInsert.find(
      "#wppsn-single-media-insert-buttons"
    );
    this.domPanSingleMediaPreview = this.domPanSingleMedia.find(
      ".wppsn-media-preview-wrapper"
    );
    this.domSingleMediaPreviewPans = this.domPanSingleMediaPreview.find(
      ".wppsn-single-media-preview-pan"
    );
    this.domSingleMediaBasketsWrapper = this.domPanSingleMedia.find(
      ".wppsn-baskets-wrapper"
    );

    this.domPanImgGallery = jQuery("#wppsn-pan-images");
    this.domImgGalleryTabs = this.domPanImgGallery.find(".wppsn-main-pan-tabs");
    this.domImgGalleryListMediasWrapper = this.domPanImgGallery.find(
      ".wppsn-media-list-wrapper"
    );
    this.domImgGalleryListMediasHeader = this.domPanImgGallery.find(
      ".wppsn-media-list-header"
    );
    this.domImgGallerySearchInput = this.domImgGalleryListMediasHeader.find(
      ".wppsn-search-field"
    );
    this.domImgGallerySearchType = this.domImgGalleryListMediasHeader.find(
      ".wppsn-search-type"
    );
    this.domImgGalleryCounter = this.domImgGalleryListMediasHeader.find(
      ".wppsn-media-counter strong"
    );
    this.domImgGalleryListMedias = this.domPanImgGallery.find(
      ".wppsn-media-list"
    );
    this.domImgGalleryListPagination = this.domPanImgGallery.find(
      ".wppsn-media-list-pagination"
    );
    this.domImgGallerySelectedListWrapper = this.domPanImgGallery.find(
      ".wppsn-media-selected-list-wrapper"
    );
    this.domImgGallerySelectedList = this.domImgGallerySelectedListWrapper.find(
      ".wppsn-selected-media-list"
    );
    this.domImgGallerySelectedListButtons = this.domImgGallerySelectedListWrapper.find(
      ".wppsn-selected-media-list-buttons"
    );
    this.domPanImgGalleryPreview = this.domPanImgGallery.find(
      ".wppsn-media-preview-wrapper"
    );
    this.domPanImgGalleryCreateStep1 = this.domPanImgGallery.find(
      "#wppsn-img-gallery-create-gallery-step1"
    );
    this.domImgGalleryCreateStep1MediaList = this.domPanImgGalleryCreateStep1.find(
      "#wppsn-img-gallery-list-media-fields"
    );
    this.domPanImgGalleryCreateStep2 = this.domPanImgGallery.find(
      "#wppsn-img-gallery-create-gallery-step2"
    );
    this.domImgGalleryBasketsWrapper = this.domPanImgGallery.find(
      ".wppsn-baskets-wrapper"
    );

    this.domPanVideoPlaylist = jQuery("#wppsn-pan-videos");
    this.domVideoPlaylistTabs = this.domPanVideoPlaylist.find(
      ".wppsn-main-pan-tabs"
    );
    this.domVideoPlaylistListMediasWrapper = this.domPanVideoPlaylist.find(
      ".wppsn-media-list-wrapper"
    );
    this.domVideoPlaylistListMediasHeader = this.domPanVideoPlaylist.find(
      ".wppsn-media-list-header"
    );
    this.domVideoPlaylistSearchInput = this.domVideoPlaylistListMediasHeader.find(
      ".wppsn-search-field"
    );
    this.domVideoPlaylistSearchType = this.domVideoPlaylistListMediasHeader.find(
      ".wppsn-search-type"
    );
    this.domVideoPlaylistCounter = this.domVideoPlaylistListMediasHeader.find(
      ".wppsn-media-counter strong"
    );
    this.domVideoPlaylistListMedias = this.domPanVideoPlaylist.find(
      ".wppsn-media-list"
    );
    this.domVideoPlaylistListPagination = this.domPanVideoPlaylist.find(
      ".wppsn-media-list-pagination"
    );
    this.domVideoPlaylistSelectedListWrapper = this.domPanVideoPlaylist.find(
      ".wppsn-media-selected-list-wrapper"
    );
    this.domVideoPlaylistSelectedList = this.domVideoPlaylistSelectedListWrapper.find(
      ".wppsn-selected-media-list"
    );
    this.domVideoPlaylistSelectedListButtons = this.domVideoPlaylistSelectedListWrapper.find(
      ".wppsn-selected-media-list-buttons"
    );
    this.domPanVideoPlaylistPreview = this.domPanVideoPlaylist.find(
      ".wppsn-media-preview-wrapper"
    );
    this.domPanVideoPlaylistCreateStep1 = this.domPanVideoPlaylist.find(
      "#wppsn-video-playlist-create-playlist-step1"
    );
    this.domVideoPlaylistCreateStep1MediaList = this.domPanVideoPlaylistCreateStep1.find(
      "#wppsn-video-playlist-list-media-fields"
    );
    this.domVideoPlaylistBasketsWrapper = this.domPanVideoPlaylist.find(
      ".wppsn-baskets-wrapper"
    );

    this.domClonableElements = jQuery("#wppsn-clonable-elements");

    // Hide what should be hidden at first load
    this.domSingleMediaTabs.hide();
    this.domSingleMediaBasketsWrapper.hide();
    this.domSingleMediaInsertPans.hide();
    this.domSingleMediaInsertButtons.hide();
    this.domPanSingleMediaPreview.hide();
    this.domSingleMediaPreviewPans.hide();

    this.domPanImgGallery.hide();
    this.domImgGalleryTabs.hide();
    this.domImgGalleryBasketsWrapper.hide();
    this.domImgGallerySelectedListButtons.hide();
    this.domPanImgGalleryPreview.hide();
    this.domPanImgGalleryCreateStep1.hide();
    this.domPanImgGalleryCreateStep2.hide();

    this.domPanVideoPlaylist.hide();
    this.domVideoPlaylistTabs.hide();
    this.domVideoPlaylistBasketsWrapper.hide();
    this.domVideoPlaylistSelectedListButtons.hide();
    this.domPanVideoPlaylistPreview.hide();
    this.domPanVideoPlaylistCreateStep1.hide();

    // Init various events (buttons, search fields...)
    this.initMainMenuEvents();
    this.initSingleMediaPanEvents();
    this.initImgGalleryPanEvents();
    this.initVideoPlaylistPanEvents();

    // Load the Single Media List
    this.getMediaList();
  };

  /**
   * Init the Main menu links
   */
  WppsnModal.prototype.initMainMenuEvents = function () {
    var _this = this;

    // [click] Main Menu Links
    this.domMenu.on("click", "a", function (e) {
      var curLink = jQuery(this);
      var requestedPan = jQuery(
        "#wppsn-pan-" + curLink.attr("id").split("-")[2]
      );

      // Display Pan if not visible
      if (!curLink.hasClass("current-menu")) {
        _this.domMainPans.hide();
        _this.domMenu.find("a").removeClass("current-menu");
        requestedPan.show();
        curLink.addClass("current-menu");

        // Get Media List
        _this.getMediaList();
      }
      e.preventDefault();
    });
  };

  /**
   * Init the Single Media Pan events (buttons, search field...)
   */
  WppsnModal.prototype.initSingleMediaPanEvents = function () {
    var _this = this;

    // [press ENTER] Search Field
    this.domSingleMediaSearchInput.keypress(function (e) {
      var keycode = e.keyCode ? e.keyCode : e.which;
      if (keycode == "13") {
        _this.getMediaList();
      }
    });

    // [click] Search Type Radio Buttons : Documents OR Coverages
    this.domSingleMediaSearchType.on("change", function (e) {
      _this.getMediaList();
      e.preventDefault();
    });

    // [click] Record Type dropdown list
    this.domSingleMediaRecordType.on("change", function (e) {
      _this.getMediaList();
      e.preventDefault();
    });

    // [click] List Pagination
    this.domSingleMediaListPagination.on("click", "a", function (e) {
      _this.getMediaList(jQuery(this).text());
      e.preventDefault();
    });

    // [click] Insert Pan "Details" button
    this.domPanSingleMediaInsert
      .find(".media-details-button")
      .on("click", function (e) {
        if (!_this.domPanSingleMediaPreview.is(":visible")) {
          _this.showSingleMediaPreviewPan(
            jQuery(this)
              .parents(".wppsn-single-media-insert-pan")
              .data("mediaInfos")
          );
        }
        e.preventDefault();
      });

    // [click] Preview Pan Close button
    this.domPanSingleMediaPreview
      .find(".media-preview-close")
      .on("click", function (e) {
        _this.domPanSingleMediaPreview.hide();
        _this.domSingleMediaPreviewPans.hide();
        // Stop video if any
        _this.domPanSingleMediaPreview
          .find("#wppsn-single-media-preview-video-player-wrapper")
          .empty();
        e.preventDefault();
      });

    // [click] Use image as Featured Image
    this.domPanSingleMediaInsert
      .find(".wppsn-set-featured-image")
      .on("click", function (e) {
        var curLink = jQuery(this);
        var mediaInfos = curLink
          .parents(".wppsn-single-media-insert-pan")
          .data("mediaInfos");
        var container = curLink.parent("div");

        _this.setFeaturedImage(container, mediaInfos);

        e.preventDefault();
      });

    // [click] Insert button
    this.domSingleMediaInsertButtons.find("a").on("click", function (e) {
      _this.insertSingleMedia();
      e.preventDefault();
    });
  };

  /**
   * Init the Img Gallery Pan events (buttons, search field...)
   */
  WppsnModal.prototype.initImgGalleryPanEvents = function () {
    var _this = this;

    // [press ENTER] Search Field
    this.domImgGallerySearchInput.keypress(function (e) {
      var keycode = e.keyCode ? e.keyCode : e.which;
      if (keycode == "13") {
        _this.getMediaList();
      }
    });

    // [click] Search Type Radio Buttons : Documents OR Coverages
    this.domImgGallerySearchType.on("change", function (e) {
      _this.getMediaList();
      e.preventDefault();
    });

    // [click] List Pagination
    this.domImgGalleryListPagination.on("click", "a", function (e) {
      _this.getMediaList(jQuery(this).text());
      e.preventDefault();
    });

    // [click] Preview Pan Close button
    this.domPanImgGalleryPreview
      .find(".media-preview-close")
      .on("click", function (e) {
        _this.domPanImgGalleryPreview.hide();
        e.preventDefault();
      });

    // [click] Selected Images list delete all
    this.domImgGallerySelectedListWrapper
      .find(".wppsn-selected-media-list-delete-all a")
      .on("click", function (e) {
        _this.domImgGallerySelectedList
          .find(".selected-delete")
          .each(function () {
            jQuery(this).trigger("click");
          });
        e.preventDefault();
      });

    // [click] Create Image Gallery Step 1 button
    this.domImgGallerySelectedListButtons.find("a").on("click", function (e) {
      _this.createImgGalleryStep1();
      e.preventDefault();
    });

    // [click] Create Image Gallery Step 1 Close Button
    this.domPanImgGalleryCreateStep1
      .find(".create-gallery-step1-close")
      .on("click", function (e) {
        _this.domPanImgGalleryCreateStep1.hide();
        e.preventDefault();
      });

    // [click] Create Image Gallery Step 1 to step 2 button
    this.domPanImgGalleryCreateStep1
      .find(".create-gallery-step1-next")
      .on("click", function (e) {
        _this.createImgGalleryStep2();
        e.preventDefault();
      });

    // [click] Create Image Gallery Step 2 Close Button
    this.domPanImgGalleryCreateStep2
      .find(".create-gallery-step2-close")
      .on("click", function (e) {
        _this.domPanImgGalleryCreateStep2.hide();
        e.preventDefault();
      });

    // [click] Insert Image Gallery Buttons
    this.domPanImgGalleryCreateStep2.on(
      "click",
      ".button-primary",
      function (e) {
        _this.insertImgGallery(
          jQuery(this).parents("li").attr("id").split("-")[4]
        );
        e.preventDefault();
      }
    );
  };

  /**
   * Init the Video Playlist Pan events (buttons, search field...)
   */
  WppsnModal.prototype.initVideoPlaylistPanEvents = function () {
    var _this = this;

    // [press ENTER] Search Field
    this.domVideoPlaylistSearchInput.keypress(function (e) {
      var keycode = e.keyCode ? e.keyCode : e.which;
      if (keycode == "13") {
        _this.getMediaList();
      }
    });

    // [click] Search Type Radio Buttons : Documents OR Coverages
    this.domVideoPlaylistSearchType.on("change", function (e) {
      _this.getMediaList();
      e.preventDefault();
    });

    // [click] List Pagination
    this.domVideoPlaylistListPagination.on("click", "a", function (e) {
      _this.getMediaList(jQuery(this).text());
      e.preventDefault();
    });

    // [click] Preview Pan Close button
    this.domPanVideoPlaylistPreview
      .find(".media-preview-close")
      .on("click", function (e) {
        _this.domPanVideoPlaylistPreview
          .hide()
          .find(".wppsn-media-preview-video-player-wrapper")
          .empty();
        e.preventDefault();
      });

    // [click] Selected Videos list delete all
    this.domVideoPlaylistSelectedListWrapper
      .find(".wppsn-selected-media-list-delete-all a")
      .on("click", function (e) {
        _this.domVideoPlaylistSelectedList
          .find(".selected-delete")
          .each(function () {
            jQuery(this).trigger("click");
          });
        e.preventDefault();
      });

    // [click] Create Videos Playlist Step 1 button
    this.domVideoPlaylistSelectedListButtons
      .find("a")
      .on("click", function (e) {
        _this.createVideoPlaylistStep1();
        e.preventDefault();
      });

    // [click] Create Videos Playlist Step 1 Close Button
    this.domPanVideoPlaylistCreateStep1
      .find(".create-playlist-step1-close")
      .on("click", function (e) {
        _this.domPanVideoPlaylistCreateStep1.hide();
        e.preventDefault();
      });

    // [click] Insert Video Playlist Button
    this.domPanVideoPlaylistCreateStep1.on(
      "click",
      ".button-primary",
      function (e) {
        _this.insertVideoPlaylist();
        e.preventDefault();
      }
    );
  };

  /**
   * Get the Media List regarding current filters
   * @param {int} pageNb Page number of pagination
   */
  WppsnModal.prototype.getMediaList = function (pageNb) {
    // Get current workflow pan
    var currentMainPan = jQuery(".wppsn-main-pan:visible");
    var currentWorkflow = currentMainPan.attr("id").split("-")[2];
    var currentMediaList = currentMainPan.find(".wppsn-media-list");
    var currentRecordType = "all";

    // If not already loading
    if (!currentMediaList.hasClass("list-loading")) {
      // Loading animation
      currentMediaList.empty().addClass("list-loading");

      // Switch between workflows
      switch (currentWorkflow) {
        // Single Media
        case "medias":
          // Hide Insert and Preview Pans if opened
          this.domSingleMediaInsertPans.hide();
          this.domSingleMediaInsertButtons.hide();
          this.domPanSingleMediaPreview.hide();
          this.domSingleMediaPreviewPans.hide();

          // Record Type
          currentRecordType = this.domSingleMediaRecordType.val() || "all";

          // Disable search types radio buttons and record types dropdown list
          this.domSingleMediaSearchType.attr("disabled", "disabled");
          this.domSingleMediaRecordType.attr("disabled", "disabled");

          break;

        // Images Gallery
        case "images":
          // Hide Pans : preview, create gallery step 1 and 2
          this.domPanImgGalleryPreview.hide();
          this.domPanImgGalleryCreateStep1.hide();
          this.domPanImgGalleryCreateStep2.hide();

          // Record Type
          currentRecordType = "image";

          // Disable search types radio buttons
          this.domImgGallerySearchType.attr("disabled", "disabled");

          break;

        // Videos Playlist
        case "videos":
          // Hide Pans : preview and create playlist step 1
          this.domPanVideoPlaylistPreview.hide();
          this.domPanVideoPlaylistCreateStep1.hide();

          // Record Type
          currentRecordType = "video";

          // Disable search types radio buttons
          this.domVideoPlaylistSearchType.attr("disabled", "disabled");

          break;
      }

      facets = localStorage.getItem("facets");
      facets = JSON.parse(facets);

      let search = currentMainPan.find(".wppsn-search-field").val() || "";

      if (facets != undefined || facets != null) {
        let c = 0;
        let l = facets.length;

        console.log(typeof facets);
        console.log(facets);
        console.log(l);

        for (let i = 0; i < l; i++) {
          console.log(facets[i].id);

          if (i == 0) {
            if (search == "") {
              search = facets[i].id;
            } else {
              search += " AND " + facets[i].id;
            }
          } else {
            search += " AND " + facets[i].id;
          }
        }
      }

      console.log(search);

      // Current Filters (search query, search type, record type and page of pagination)
      var mediaFilters = {
        searchQuery: search,

        searchType:
          currentMainPan.find(".wppsn-search-type:checked").val() || 0,
        recordType: currentRecordType,
        pageNb: typeof pageNb == "undefined" ? 1 : pageNb,
      };

      // Request Media List Infos
      jQuery.ajax({
        url: wppsnGlobals.ajaxUrl,
        data: { action: "wppsn-get-media-list", params: mediaFilters },
        context: this,
        success: function (resp) {
          // Save response
          currentMediaList.data("mediaListInfos", resp);

          // Prepare the list
          switch (currentWorkflow) {
            case "medias":
              this.prepareSingleMediaList();
              break;

            case "images":
              this.prepareImgGalleryMediaList();
              break;

            case "videos":
              this.prepareVideoPlaylistMediaList();
              break;
          }
        },
        dataType: "json",
      });
    }
  };

  /**
   * Prepare the Single Media List
   */
  WppsnModal.prototype.prepareSingleMediaList = function () {
    var mediaListInfos = this.domSingleMediaListMedias.data("mediaListInfos");

    // Remove pagination
    this.domSingleMediaListPagination.empty();

    // No results
    if (mediaListInfos.s == "no-results") {
      this.domSingleMediaListMedias
        .removeClass("list-loading")
        .append('<p class="wppsn-no-results">' + mediaListInfos.sMsg + "</p>");
      this.domSingleMediaCounter.text("0");
    }
    // Error
    else if (mediaListInfos.s == "error") {
      this.domSingleMediaListMedias
        .removeClass("list-loading")
        .append('<p class="wppsn-error">' + mediaListInfos.sMsg + "</p>");
      this.domSingleMediaCounter.text("0");
    }
    // Success
    else if (mediaListInfos.s == "success") {
      var _this = this;

      // Update Media Counter
      this.domSingleMediaCounter.text(mediaListInfos.total);

      // Create the HTML Media List and add it to DOM
      var mediaList = mediaListInfos.mediaList;
      var mediaListElt = jQuery("<ul></ul>");

      for (var i in mediaList) {
        var mediaEltInfos = mediaList[i];

        // Media Element "Details" button
        var mediaEltDetailsButton = jQuery(
          '<a href="" class="media-details-button button">' +
            wppsnModali18n.buttonDetails +
            "</a>"
        ).on("click", function () {
          // Click on "details" button open the sidebar insert Pan and the preview Pan
          var liElt = jQuery(this).parents("li");
          var mInfos = liElt.data("mediaInfos");
          _this.domSingleMediaListMedias
            .find("li")
            .removeClass("current-selected");
          liElt.addClass("current-selected");
          _this.showSingleMediaInsertInfos(mInfos);
          _this.showSingleMediaPreviewPan(mInfos);
          return false;
        });

        // Media Element HTML appended to list element
        jQuery("<li></li>")
          .data("mediaInfos", mediaEltInfos)
          .append(
            jQuery('<div class="media-item"></div>')
              .append(
                '<div class="media-thumb"><img src="' +
                  mediaEltInfos.thumb +
                  '"></div>'
              )
              .append('<p class="media-title">' + mediaEltInfos.title + "</p>")
              .append(
                jQuery('<p class="media-buttons"></p>').append(
                  mediaEltDetailsButton
                )
              )
              .append(
                '<span class="media-type media-type-' +
                  mediaEltInfos.phraseaType +
                  '"></span>'
              )
          )
          .on("click", function (e) {
            // Click anywhere in the media item <li> open only the sidebar
            _this.domSingleMediaListMedias
              .find("li")
              .removeClass("current-selected");
            jQuery(this).addClass("current-selected");
            _this.showSingleMediaInsertInfos(jQuery(this).data("mediaInfos"));
            e.preventDefault();
          })
          .appendTo(mediaListElt);
      }

      // Remove old Media List and append the new one
      this.domSingleMediaListMedias
        .removeClass("list-loading")
        .empty()
        .append(mediaListElt);

      // Append Pagination
      this.domSingleMediaListPagination.append(mediaListInfos.pagination);
    }

    // Re-enable search type radio buttons and record types dropdown list
    this.domSingleMediaSearchType.removeAttr("disabled");
    this.domSingleMediaRecordType.removeAttr("disabled");
  };

  /**
   * Prepare the Img Gallery Media List
   */
  WppsnModal.prototype.prepareImgGalleryMediaList = function () {
    var mediaListInfos = this.domImgGalleryListMedias.data("mediaListInfos");

    // Remove pagination
    this.domImgGalleryListPagination.empty();

    // No results
    if (mediaListInfos.s == "no-results") {
      this.domImgGalleryListMedias
        .removeClass("list-loading")
        .append('<p class="wppsn-no-results">' + mediaListInfos.sMsg + "</p>");
      this.domImgGalleryCounter.text("0");
    }
    // Error
    else if (mediaListInfos.s == "error") {
      this.domImgGalleryListMedias
        .removeClass("list-loading")
        .append('<p class="wppsn-error">' + mediaListInfos.sMsg + "</p>");
      this.domImgGalleryCounter.text("0");
    }
    // Success
    else if (mediaListInfos.s == "success") {
      var _this = this;

      // Update Media Counter
      this.domImgGalleryCounter.text(mediaListInfos.total);

      // Create the HTML Media List and add it to DOM
      var mediaList = mediaListInfos.mediaList;
      var mediaListElt = jQuery("<ul></ul>");

      for (var i in mediaList) {
        var mediaEltInfos = mediaList[i];
        var mediaIsSelected =
          jQuery.inArray(mediaEltInfos.id, this.imgGallerySelectedImgIds) != -1
            ? true
            : false;

        var mediaEltDetailsButton = jQuery(
          '<a href="" class="media-details-button button">' +
            wppsnModali18n.buttonDetails +
            "</a>"
        ).on("click", function () {
          // Click on "details" button open the the preview Pan
          _this.showImgGalleryPreviewPan(
            jQuery(this).parents("li").data("mediaInfos")
          );
          return false;
        });

        var mediaEltSelectButton = jQuery(
          '<a href="" class="media-select-button button-primary">' +
            wppsnModali18n.buttonSelect +
            "</a> "
        ).on("click", function () {
          // Add this img to the selection
          _this.addToImgGallerySelection(
            jQuery(this).parents("li").data("mediaInfos")
          );
          return false;
        });

        if (mediaIsSelected) {
          mediaEltSelectButton.css("display", "none");
        }

        var mediaEltUnselectButton = jQuery(
          '<a href="" class="media-unselect-button button-primary">' +
            wppsnModali18n.buttonUnselect +
            "</a> "
        ).on("click", function () {
          // Remove this img from the selection
          _this.deleteFromImgGallerySelection(
            jQuery(this).parents("li").data("mediaInfos")
          );
          return false;
        });

        if (!mediaIsSelected) {
          mediaEltUnselectButton.css("display", "none");
        }

        // Media Element HTML
        jQuery('<li class="media-item-' + mediaEltInfos.id + '"></li>')
          .data("mediaInfos", mediaEltInfos)
          .append(
            jQuery('<div class="media-item"></div>')
              .append(
                '<div class="media-thumb"><img src="' +
                  mediaEltInfos.thumb +
                  '"></div>'
              )
              .append('<p class="media-title">' + mediaEltInfos.title + "</p>")
              .append(
                jQuery('<p class="media-buttons"></p>')
                  .append(mediaEltDetailsButton)
                  .append(mediaEltSelectButton)
                  .append(mediaEltUnselectButton)
              )
              .append(
                '<span class="media-type media-type-' +
                  mediaEltInfos.phraseaType +
                  '"></span>'
              )
          )
          .appendTo(mediaListElt);
      }

      // Remove old Media List and append the new one
      this.domImgGalleryListMedias
        .removeClass("list-loading")
        .empty()
        .append(mediaListElt);

      // Append Pagination
      this.domImgGalleryListPagination.append(mediaListInfos.pagination);
    }

    // Re-enable search type radio buttons
    this.domImgGallerySearchType.removeAttr("disabled");
  };

  /**
   * Prepare the Video Playlist Media List
   */
  WppsnModal.prototype.prepareVideoPlaylistMediaList = function () {
    var mediaListInfos = this.domVideoPlaylistListMedias.data("mediaListInfos");

    // Remove pagination
    this.domVideoPlaylistListPagination.empty();

    // No results
    if (mediaListInfos.s == "no-results") {
      this.domVideoPlaylistListMedias
        .removeClass("list-loading")
        .append('<p class="wppsn-no-results">' + mediaListInfos.sMsg + "</p>");
      this.domVideoPlaylistCounter.text("0");
    }
    // Error
    else if (mediaListInfos.s == "error") {
      this.domVideoPlaylistListMedias
        .removeClass("list-loading")
        .append('<p class="wppsn-error">' + mediaListInfos.sMsg + "</p>");
      this.domVideoPlaylistCounter.text("0");
    }
    // Success
    else if (mediaListInfos.s == "success") {
      var _this = this;

      // Update Media Counter
      this.domVideoPlaylistCounter.text(mediaListInfos.total);

      // Create the HTML Media List and add it to DOM
      var mediaList = mediaListInfos.mediaList;
      var mediaListElt = jQuery("<ul></ul>");

      for (var i in mediaList) {
        var mediaEltInfos = mediaList[i];
        var mediaIsSelected =
          jQuery.inArray(
            mediaEltInfos.id,
            this.videoPlaylistSelectedVideoIds
          ) != -1
            ? true
            : false;

        var mediaEltDetailsButton = jQuery(
          '<a href="" class="media-details-button button">' +
            wppsnModali18n.buttonDetails +
            "</a>"
        ).on("click", function () {
          // Click on "details" button open the the preview Pan
          _this.showVideoPlaylistPreviewPan(
            jQuery(this).parents("li").data("mediaInfos")
          );
          return false;
        });

        var mediaEltSelectButton = jQuery(
          '<a href="" class="media-select-button button-primary">' +
            wppsnModali18n.buttonSelect +
            "</a> "
        ).on("click", function () {
          // Add this img to the selection
          _this.addToVideoPlaylistSelection(
            jQuery(this).parents("li").data("mediaInfos")
          );
          return false;
        });

        if (mediaIsSelected) {
          mediaEltSelectButton.css("display", "none");
        }

        var mediaEltUnselectButton = jQuery(
          '<a href="" class="media-unselect-button button-primary">' +
            wppsnModali18n.buttonUnselect +
            "</a> "
        ).on("click", function () {
          // Remove this img from the selection
          _this.deleteFromVideoPlaylistSelection(
            jQuery(this).parents("li").data("mediaInfos")
          );
          return false;
        });

        if (!mediaIsSelected) {
          mediaEltUnselectButton.css("display", "none");
        }

        // Media Element HTML
        jQuery('<li class="media-item-' + mediaEltInfos.id + '"></li>')
          .data("mediaInfos", mediaEltInfos)
          .append(
            jQuery('<div class="media-item"></div>')
              .append(
                '<div class="media-thumb"><img src="' +
                  mediaEltInfos.thumb +
                  '"></div>'
              )
              .append('<p class="media-title">' + mediaEltInfos.title + "</p>")
              .append(
                jQuery('<p class="media-buttons"></p>')
                  .append(mediaEltDetailsButton)
                  .append(mediaEltSelectButton)
                  .append(mediaEltUnselectButton)
              )
              .append(
                '<span class="media-type media-type-' +
                  mediaEltInfos.phraseaType +
                  '"></span>'
              )
          )
          .appendTo(mediaListElt);
      }

      // Remove old Media List and append the new one
      this.domVideoPlaylistListMedias
        .removeClass("list-loading")
        .empty()
        .append(mediaListElt);

      // Append Pagination
      this.domVideoPlaylistListPagination.append(mediaListInfos.pagination);
    }

    // Re-enable search type radio buttons
    this.domVideoPlaylistSearchType.removeAttr("disabled");
  };

  /**
   * Show Single Media Insert Infos Pan
   * @param {object} mediaInfos Media Infos to use for fullfill the fields
   */
  WppsnModal.prototype.showSingleMediaInsertInfos = function (mediaInfos) {
    // Use the right pan (one for each phraseaType of media)
    var insertPan = this.domPanSingleMediaInsert.find(
      "#wppsn-single-media-insert-" + mediaInfos.phraseaType
    );

    // Check if the opened pan is the one we need with the same media requested : if yes, do nothing
    if (insertPan.is(":visible")) {
      var currentMediaInfos = insertPan.data("mediaInfos");
      if (currentMediaInfos.id == mediaInfos.id) {
        return;
      }
    }

    // Hide all media pans
    this.domSingleMediaInsertPans.hide();

    // Store media Infos in the current pan
    insertPan.data("mediaInfos", mediaInfos);

    // Fullfill the pan's fields
    switch (mediaInfos.phraseaType) {
      case "image":
        // Thumb
        insertPan
          .find("#wppsn-single-media-insert-image-thumb")
          .empty()
          .append('<img src="' + mediaInfos.thumb + '">');

        // Title
        insertPan
          .find("#wppsn-single-media-insert-image-title")
          .val(mediaInfos.title);

        break;

      case "video":
        // Thumb
        insertPan
          .find("#wppsn-single-media-insert-video-thumb")
          .empty()
          .append('<img src="' + mediaInfos.thumb + '">');

        // Title
        insertPan
          .find("#wppsn-single-media-insert-video-title")
          .val(mediaInfos.title);

        break;
    }

    // Show Pan and button
    insertPan.show();
    this.domSingleMediaInsertButtons.show();
  };

  /**
   * Show Single Media Preview Pan
   * @param {object} mediaInfos Media Infos to use for fullfill the fields
   */
  WppsnModal.prototype.showSingleMediaPreviewPan = function (mediaInfos) {
    // Hide all preview Pans
    this.domSingleMediaPreviewPans.hide();

    // Use the right pan (one for each phraseaType of media)
    var previewPan = this.domPanSingleMediaPreview.find(
      "#wppsn-single-media-preview-" + mediaInfos.phraseaType
    );

    // Show Pan
    this.domPanSingleMediaPreview.show();
    previewPan.show();

    // Fullfill the pan's fields

    previewPan.find(".wppsn-media-preview-title").text(mediaInfos.title);

    switch (mediaInfos.phraseaType) {
      case "image":
        previewPan
          .find(".wppsn-media-preview-thumb")
          .empty()
          .append('<img src="' + mediaInfos.preview.thumb_url + '">');

        break;

      case "video":
        // Is there a preview ? then build the video player with all its sources
        if (typeof mediaInfos.preview.nopreview == "undefined") {
          // Build player HTML
          var playerContainer = jQuery(
            '<div class="wppsn-video-player color-light"></div>'
          );
          var playerVideo = jQuery("<video autoplay></video>");

          // console.log(mediaInfos);

          if (typeof mediaInfos.preview.mp4 != "undefined") {
            playerVideo.append(
              '<source type="video/mp4" src="' + mediaInfos.preview.mp4 + '">'
            );
          }

          if (typeof mediaInfos.preview.webm != "undefined") {
            playerVideo.append(
              '<source type="video/webm" src="' + mediaInfos.preview.webm + '">'
            );
          }

          if (typeof mediaInfos.preview.ogg != "undefined") {
            playerVideo.append(
              '<source type="video/ogg" src="' + mediaInfos.preview.ogg + '">'
            );
          }

          playerContainer.append(playerVideo);

          // Append Player in DOM
          previewPan
            .find("#wppsn-single-media-preview-video-player-wrapper")
            .empty()
            .append(playerContainer);

          // Load Player
          previewPan.find(".wppsn-video-player").flowplayer({
            swf: "../../libs/flowplayer/flowplayer.swf",
          });
        }
        // No preview
        else {
          // Show the no-preview image
          previewPan
            .find("#wppsn-single-media-preview-video-player-wrapper")
            .empty()
            .append('<img src="' + mediaInfos.preview.nopreview + '">');
        }

        break;
    }
  };

  /**
   * Show Img Gallery Media Preview Pan
   * @param {object} mediaInfos Media Infos to use for fullfill the fields
   */
  WppsnModal.prototype.showImgGalleryPreviewPan = function (mediaInfos) {
    // Fullfill the pan's fields
    this.domPanImgGalleryPreview
      .find(".wppsn-media-preview-title")
      .text(mediaInfos.title);
    this.domPanImgGalleryPreview
      .find(".wppsn-media-preview-thumb")
      .empty()
      .append('<img src="' + mediaInfos.preview.thumb_url + '">');

    // Show Pan
    this.domPanImgGalleryPreview.show();
  };

  /**
   * Show Videos Playlist Media Preview Pan
   * @param {object} mediaInfos Media Infos to use for fullfill the fields
   */
  WppsnModal.prototype.showVideoPlaylistPreviewPan = function (mediaInfos) {
    // Fullfill the pan's fields
    this.domPanVideoPlaylistPreview
      .find(".wppsn-media-preview-title")
      .text(mediaInfos.title);

    // Is there a preview ? then build the video player with all its sources
    if (typeof mediaInfos.preview.nopreview == "undefined") {
      // Build player HTML
      var playerContainer = jQuery(
        '<div class="wppsn-video-player color-light"></div>'
      );
      var playerVideo = jQuery("<video autoplay></video>");

      if (typeof mediaInfos.preview.mp4 != "undefined") {
        playerVideo.append(
          '<source type="video/mp4" src="' + mediaInfos.preview.mp4 + '">'
        );
      }

      if (typeof mediaInfos.preview.webm != "undefined") {
        playerVideo.append(
          '<source type="video/webm" src="' + mediaInfos.preview.webm + '">'
        );
      }

      if (typeof mediaInfos.preview.ogg != "undefined") {
        playerVideo.append(
          '<source type="video/ogg" src="' + mediaInfos.preview.ogg + '">'
        );
      }

      playerContainer.append(playerVideo);

      // Append Player in DOM
      this.domPanVideoPlaylistPreview
        .find(".wppsn-media-preview-video-player-wrapper")
        .empty()
        .append(playerContainer);

      // Load Player
      this.domPanVideoPlaylistPreview.find(".wppsn-video-player").flowplayer({
        swf: "../../libs/flowplayer/flowplayer.swf",
      });
    }
    // No preview
    else {
      // Show the no-preview image
      previewPan
        .find(".wppsn-media-preview-video-player-wrapper")
        .empty()
        .append('<img src="' + mediaInfos.preview.nopreview + '">');
    }

    // Show Pan
    this.domPanVideoPlaylistPreview.show();
  };

  /**
   * Add an Image to the Image Gallery Selection
   * @param {object} mediaInfos Media Infos
   */
  WppsnModal.prototype.addToImgGallerySelection = function (mediaInfos) {
    var _this = this;

    // Check if the image is not already selected
    if (jQuery.inArray(mediaInfos.id, this.imgGallerySelectedImgIds) == -1) {
      var imgSelectedDetailsLink = jQuery(
        '<a href="" class="selected-details">' +
          wppsnModali18n.linkDetails +
          "</a>"
      ).on("click", function (e) {
        _this.showImgGalleryPreviewPan(mediaInfos);
        return false;
      });

      var imgSelectedDeleteLink = jQuery(
        '<a href="" class="selected-delete">' +
          wppsnModali18n.linkDelete +
          "</a>"
      ).on("click", function (e) {
        _this.deleteFromImgGallerySelection(mediaInfos);
        return false;
      });

      var imgSelected = jQuery(
        '<li class="selected-' + mediaInfos.id + ' clearfix"></li>'
      )
        .data("mediaInfos", mediaInfos)
        .append(
          '<div class="selected-thumb"><img src="' +
            mediaInfos.thumb +
            '"></div>'
        )
        .append('<p class="selected-title">' + mediaInfos.title + "</p>")
        .append(
          jQuery('<p class="selected-buttons"></p>')
            .append(imgSelectedDetailsLink)
            .append(imgSelectedDeleteLink)
        );

      // Add image in the list and in the array of Img Ids
      this.domImgGallerySelectedList.find("ul").append(imgSelected);
      this.imgGallerySelectedImgIds.push(mediaInfos.id);

      // Switch "select" and "unselect" buttons in the main media list
      var mediaItem = this.domImgGalleryListMedias.find(
        ".media-item-" + mediaInfos.id
      );
      mediaItem.find(".media-select-button").hide();
      mediaItem.find(".media-unselect-button").show();

      // Nb of images in the selection
      var nbImagesSelected = this.imgGallerySelectedImgIds.length;
      this.domImgGallerySelectedListWrapper
        .find(".wppsn-media-selection-counter")
        .text(nbImagesSelected);

      // First image to add ? then hide the "no selection" message
      if (nbImagesSelected == 1) {
        this.domImgGallerySelectedList.find(".wppsn-media-no-selection").hide();
      }

      // Show the Create gallery button when at least 2 images are selected
      if (nbImagesSelected > 1) {
        this.domImgGallerySelectedListButtons.show();
      }
    }
  };

  /**
   * Add a Video to the Videos Playlist Selection
   * @param {object} mediaInfos Media Infos
   */
  WppsnModal.prototype.addToVideoPlaylistSelection = function (mediaInfos) {
    var _this = this;

    // Check if the video is not already selected
    if (
      jQuery.inArray(mediaInfos.id, this.videoPlaylistSelectedVideoIds) == -1
    ) {
      var videoSelectedDetailsLink = jQuery(
        '<a href="" class="selected-details">' +
          wppsnModali18n.linkDetails +
          "</a>"
      ).on("click", function (e) {
        _this.showVideoPlaylistPreviewPan(mediaInfos);
        return false;
      });

      var videoSelectedDeleteLink = jQuery(
        '<a href="" class="selected-delete">' +
          wppsnModali18n.linkDelete +
          "</a>"
      ).on("click", function (e) {
        _this.deleteFromVideoPlaylistSelection(mediaInfos);
        return false;
      });

      var videoSelected = jQuery(
        '<li class="selected-' + mediaInfos.id + ' clearfix"></li>'
      )
        .data("mediaInfos", mediaInfos)
        .append(
          '<div class="selected-thumb"><img src="' +
            mediaInfos.thumb +
            '"></div>'
        )
        .append('<p class="selected-title">' + mediaInfos.title + "</p>")
        .append(
          jQuery('<p class="selected-buttons"></p>')
            .append(videoSelectedDetailsLink)
            .append(videoSelectedDeleteLink)
        );

      // Add video in the list and in the array of Img Ids
      this.domVideoPlaylistSelectedList.find("ul").append(videoSelected);
      this.videoPlaylistSelectedVideoIds.push(mediaInfos.id);

      // Switch "select" and "unselect" buttons in the main media list
      var mediaItem = this.domVideoPlaylistListMedias.find(
        ".media-item-" + mediaInfos.id
      );
      mediaItem.find(".media-select-button").hide();
      mediaItem.find(".media-unselect-button").show();

      // Nb of videos in the selection
      var nbVideosSelected = this.videoPlaylistSelectedVideoIds.length;
      this.domVideoPlaylistSelectedListWrapper
        .find(".wppsn-media-selection-counter")
        .text(nbVideosSelected);

      // First video to add ? then hide the "no selection" message
      if (nbVideosSelected == 1) {
        this.domVideoPlaylistSelectedList
          .find(".wppsn-media-no-selection")
          .hide();
      }

      // Show the Create Playlist button when at least 2 videos are selected
      if (nbVideosSelected > 1) {
        this.domVideoPlaylistSelectedListButtons.show();
      }
    }
  };

  /**
   * Remove an Image from the Image Gallery Selection
   * @param {object} mediaInfos Media Infos
   */
  WppsnModal.prototype.deleteFromImgGallerySelection = function (mediaInfos) {
    // Remove image from the list and from the array of Images Ids
    var removeID = mediaInfos.id;
    this.domImgGallerySelectedList
      .find(".selected-" + removeID)
      .fadeOut(function () {
        jQuery(this).remove();
      });
    this.imgGallerySelectedImgIds = jQuery.grep(
      this.imgGallerySelectedImgIds,
      function (v) {
        return v != removeID;
      }
    );

    // Switch "select" and "unselect" buttons in the main media list
    var mediaItem = this.domImgGalleryListMedias.find(
      ".media-item-" + mediaInfos.id
    );
    mediaItem.find(".media-select-button").show();
    mediaItem.find(".media-unselect-button").hide();

    // Nb of images left in the selection
    var nbImagesSelected = this.imgGallerySelectedImgIds.length;
    this.domImgGallerySelectedListWrapper
      .find(".wppsn-media-selection-counter")
      .text(nbImagesSelected);

    // If less than 2 images in the selection, hide the Create gallery button
    if (nbImagesSelected < 2) {
      this.domImgGallerySelectedListButtons.hide();
    }

    // If no more images in the selection, display the msg
    if (nbImagesSelected == 0) {
      this.domImgGallerySelectedList.find(".wppsn-media-no-selection").show();
    }
  };

  /**
   * Remove a Video from the Videos Playlist Selection
   * @param {object} mediaInfos Media Infos
   */
  WppsnModal.prototype.deleteFromVideoPlaylistSelection = function (
    mediaInfos
  ) {
    // Remove video from the list and from the array of Videos Ids
    var removeID = mediaInfos.id;
    this.domVideoPlaylistSelectedList
      .find(".selected-" + removeID)
      .fadeOut(function () {
        jQuery(this).remove();
      });
    this.videoPlaylistSelectedVideoIds = jQuery.grep(
      this.videoPlaylistSelectedVideoIds,
      function (v) {
        return v != removeID;
      }
    );

    // Switch "select" and "unselect" buttons in the main media list
    var mediaItem = this.domVideoPlaylistListMedias.find(
      ".media-item-" + mediaInfos.id
    );
    mediaItem.find(".media-select-button").show();
    mediaItem.find(".media-unselect-button").hide();

    // Nb of videos left in the selection
    var nbVideosSelected = this.videoPlaylistSelectedVideoIds.length;
    this.domVideoPlaylistSelectedListWrapper
      .find(".wppsn-media-selection-counter")
      .text(nbVideosSelected);

    // If less than 2 videos in the selection, hide the Create playlist button
    if (nbVideosSelected < 2) {
      this.domVideoPlaylistSelectedListButtons.hide();
    }

    // If no more videos in the selection, display the msg
    if (nbVideosSelected == 0) {
      this.domVideoPlaylistSelectedList
        .find(".wppsn-media-no-selection")
        .show();
    }
  };

  /**
   * Prepare the Images Gallery creation step 1
   */
  WppsnModal.prototype.createImgGalleryStep1 = function () {
    var _this = this;
    var mediaList = jQuery("<ul></ul>");

    // Show Pan
    this.domPanImgGalleryCreateStep1.show();

    // List Media
    this.domImgGallerySelectedList.find("li").each(function () {
      // Get Media Infos
      var mediaInfos = jQuery(this).data("mediaInfos");

      // Build the li element
      var featuredImgElt = _this.domClonableElements
        .find(".wppsn-set-featured-image-wrapper")
        .clone();

      featuredImgElt.on("click", ".wppsn-set-featured-image", function (e) {
        _this.setFeaturedImage(featuredImgElt, mediaInfos);
        e.preventDefault();
      });

      var mediaElt = jQuery('<li class="clearfix"></li>')
        .data("mediaInfos", mediaInfos)
        .append(
          jQuery(
            '<div class="media-thumb"><img src="' +
              mediaInfos.thumb +
              '"></div>'
          ).append(featuredImgElt)
        )
        .append(
          jQuery('<div class="media-fields"></div>')
            .append(
              jQuery("<p></p>")
                .append("<label>" + wppsnModali18n.mediaTitleLabel + "</label>")
                .append(
                  '<input type="text" name="wppsn-create-media-title" value="' +
                    mediaInfos.title +
                    '" class="wppsn-create-media-title input-text">'
                )
            )
            .append(
              jQuery("<p></p>")
                .append("<label>Downloadable</label>")
                .append(
                  '<input type="checkbox" name="wppsn-create-media-download" onclick="updateCheckboxVal(this);" value="off" class="wppsn-create-media-download">'
                )
            )
            .append(
              jQuery("<p></p>")
                .append(
                  "<label>" + wppsnModali18n.mediaAltTextLabel + "</label>"
                )
                .append(
                  '<input type="text" name="wppsn-create-media-alt-text" value="" class="wppsn-create-media-alt-text input-text">'
                )
            )
            .append(
              jQuery("<p></p>")
                .append(
                  "<label>" + wppsnModali18n.mediaLegendLabel + "</label>"
                )
                .append(
                  '<input type="text" name="wppsn-create-media-legend" value="" class="wppsn-create-media-legend input-text">'
                )
            )
        );

      // Add media element in the UL
      mediaList.append(mediaElt);
    });

    // Add UL in the DOM
    this.domImgGalleryCreateStep1MediaList.empty().append(mediaList);
  };

  /**
   * Prepare the Image Gallery creation Step 2
   */
  WppsnModal.prototype.createImgGalleryStep2 = function () {
    // Show Pan
    this.domPanImgGalleryCreateStep2.show();
  };

  /**
   * Prepare the Videos Playlist creation step 1
   */
  WppsnModal.prototype.createVideoPlaylistStep1 = function () {
    var mediaList = jQuery("<ul></ul>");

    // Show Pan
    this.domPanVideoPlaylistCreateStep1.show();

    // List Media
    this.domVideoPlaylistSelectedList.find("li").each(function () {
      // Get Media Infos
      var mediaInfos = jQuery(this).data("mediaInfos");

      // Build the li element
      var mediaElt = jQuery('<li class="clearfix"></li>')
        .data("mediaInfos", mediaInfos)
        .append(
          '<div class="media-thumb"><img src="' + mediaInfos.thumb + '"></div>'
        )
        .append(
          jQuery('<div class="media-fields"></div>').append(
            jQuery("<p></p>")
              .append("<label>" + wppsnModali18n.mediaTitleLabel + "</label>")
              .append(
                '<input type="text" name="wppsn-create-media-title" value="' +
                  mediaInfos.title +
                  '" class="wppsn-create-media-title input-text">'
              )
          )
        );

      // Add media element in the UL
      mediaList.append(mediaElt);
    });

    // Add UL in the DOM
    this.domVideoPlaylistCreateStep1MediaList.empty().append(mediaList);
  };

  /**
   * Insert the Single Media Shortcode into the TinyMCE
   */
  WppsnModal.prototype.insertSingleMedia = function () {
    // Get the currently visible Insert Pan and so the media
    var currentInsertPan = this.domPanSingleMediaInsert.find(
      ".wppsn-single-media-insert-pan:visible"
    );
    var currentMedia = currentInsertPan.attr("id").split("-")[4];

    // Get The original media Infos
    var mediaInfos = currentInsertPan.data("mediaInfos");

    // Build the media shortcode
    switch (currentMedia) {
      case "image":
        // Begin Shortcode Output
        var output = "[wppsn-image ";

        // Title
        output +=
          'title="' +
          currentInsertPan
            .find("#wppsn-single-media-insert-image-title")
            .val()
            .replace(/\"/g, "&quot;")
            .replace(/\[/g, "")
            .replace(/\]/g, "") +
          '" ';

        // Alt Text
        output +=
          'alt="' +
          currentInsertPan
            .find("#wppsn-single-media-insert-image-alt")
            .val()
            .replace(/\"/g, "&quot;")
            .replace(/\[/g, "")
            .replace(/\]/g, "") +
          '" ';

        // Legend
        output +=
          'legend="' +
          currentInsertPan
            .find("#wppsn-single-media-insert-image-legend")
            .val()
            .replace(/\"/g, "&quot;")
            .replace(/\[/g, "")
            .replace(/\]/g, "") +
          '" ';

        // Download Link
        output +=
          'download="' +
          currentInsertPan
            .find(".wppsn-single-media-insert-image-download-link:checked")
            .val() +
          '" ';

        // Url
        output += 'url="' + mediaInfos.thumb + '"';

        output += ' full_url="' + mediaInfos.download + '"';

        // Close Shortcode
        output += "]";

        break;

      case "video":
        // Begin Shortcode Output
        var output = "[wppsn-video ";

        // Title
        output +=
          'title="' +
          currentInsertPan
            .find("#wppsn-single-media-insert-video-title")
            .val()
            .replace(/\"/g, "&quot;")
            .replace(/\[/g, "")
            .replace(/\]/g, "") +
          '" ';

        // Video files
        //output += 'mp4_preview="' + mediaInfos.preview.mp4 + '" ';
        output += 'mp4="' + mediaInfos.download + '" ';

        if (typeof mediaInfos.preview.webm != "undefined") {
          output += 'webm="' + mediaInfos.preview.webm + '" ';
          //output += 'webm="' + mediaInfos.download + '" ';
        }

        if (typeof mediaInfos.preview.ogg != "undefined") {
          output += 'ogg="' + mediaInfos.preview.ogg + '" ';
          //output += 'ogg="' + mediaInfos.download + '" ';
        }

        // Poster
        output += 'splash="' + mediaInfos.preview.thumb_url + '"';

        // Close Shortcode
        output += "]";

        break;

      default:
        output = "";

        break;
    }

    // Insert Shortcode into TinyMCE
    wppsnDialog.insert(wppsnDialog.local_ed, output);
  };

  /**
   * Insert the Image Gallery Shortcode into the TinyMCE
   * @param {string} displayStyle Style of gallery display (list, grid, carrousel)
   */
  WppsnModal.prototype.insertImgGallery = function (displayStyle) {
    var displayStyle =
      typeof displayStyle == "undefined" ? "list" : displayStyle;

    var allTitles = new Array();
    var allAlts = new Array();
    var allLegends = new Array();
    var allThumbs = new Array();
    var allUrls = new Array();
    var allDownloads = new Array();

    // Get All media Infos
    this.domImgGalleryCreateStep1MediaList.find("li").each(function () {
      var currentMediaElt = jQuery(this);
      var currentMediaInfos = currentMediaElt.data("mediaInfos");

      allTitles.push(
        currentMediaElt
          .find(".wppsn-create-media-title")
          .val()
          .replace(/\"/g, "&quot;")
          .replace(/\[/g, "")
          .replace(/\]/g, "")
      );
      allDownloads.push(
        currentMediaElt
          .find(".wppsn-create-media-download")
          .val()
          .replace(/\"/g, "&quot;")
          .replace(/\[/g, "")
          .replace(/\]/g, "")
      );
      allAlts.push(
        currentMediaElt
          .find(".wppsn-create-media-alt-text")
          .val()
          .replace(/\"/g, "&quot;")
          .replace(/\[/g, "")
          .replace(/\]/g, "")
      );
      allLegends.push(
        currentMediaElt
          .find(".wppsn-create-media-legend")
          .val()
          .replace(/\"/g, "&quot;")
          .replace(/\[/g, "")
          .replace(/\]/g, "")
      );
      allThumbs.push(currentMediaInfos.thumb);
      allUrls.push(currentMediaInfos.download);
    });

    // Build the shortcode
    var output = '[wppsn-img-gallery display="' + displayStyle + '" ';

    // Titles
    output += 'titles="' + allTitles.join(" || ") + '" ';

    // Alt Texts
    output += 'alts="' + allAlts.join(" || ") + '" ';

    // Legend
    output += 'legends="' + allLegends.join(" || ") + '" ';

    output += 'download="' + allDownloads.join(" || ") + '" ';

    // Thumbs
    output += 'thumbs="' + allThumbs.join(" || ") + '" ';

    // Urls
    output += 'urls="' + allUrls.join(" || ") + '" ';

    // Close shortcode
    output += "]";

    // Insert Shortcode into TinyMCE
    wppsnDialog.insert(wppsnDialog.local_ed, output);
  };

  /**
   * Insert the Video Playlist Shortcode into the TinyMCE
   */
  WppsnModal.prototype.insertVideoPlaylist = function () {
    // Begin Shortcode Output
    var output = "[wppsn-videoplaylist ";

    var allTitles = new Array();
    var allUrls = new Array();

    // Get All media Infos
    this.domVideoPlaylistCreateStep1MediaList.find("li").each(function () {
      var currentMediaElt = jQuery(this);
      var currentMediaInfos = currentMediaElt.data("mediaInfos");

      allTitles.push(
        currentMediaElt
          .find(".wppsn-create-media-title")
          .val()
          .replace(/\"/g, "&quot;")
          .replace(/\[/g, "")
          .replace(/\]/g, "")
      );
      allUrls.push(currentMediaInfos.download);
    });

    // Titles
    output += 'titles="' + allTitles.join(" || ") + '" ';

    // Urls
    output += 'mp4s="' + allUrls.join(" || ") + '" ';

    // Splash
    var firstVideoMediaInfos = this.domVideoPlaylistCreateStep1MediaList
      .find("li:first")
      .data("mediaInfos");
    output += 'splash="' + firstVideoMediaInfos.preview.thumb_url + '"';

    // Close shortcode
    output += "]";

    // Insert Shortcode into TinyMCE
    wppsnDialog.insert(wppsnDialog.local_ed, output);
  };

  /**
   * Set a featured Image
   * @param {jQuery element} container  The current container (context)
   * @param {litteral object} mediaInfos Infos about the image
   */
  WppsnModal.prototype.setFeaturedImage = function (container, mediaInfos) {
    var loaderGif = container.find(".wppsn-loader");
    var msgError = container.find(".wppsn-error");
    var msgSuccessPartial = container.find(".wppsn-success-partial");
    var msgSuccess = container.find(".wppsn-success");

    console.log(mediaInfos);

    // Hide messages
    msgError.addClass("visuallyhidden");
    msgSuccessPartial.addClass("visuallyhidden");
    msgSuccess.addClass("visuallyhidden");

    // Show loader
    loaderGif.removeClass("visuallyhidden");

    // Request server for adding the image in Media Library
    jQuery.ajax({
      url: wppsnGlobals.ajaxUrl,
      data: {
        action: "wppsn-add-phraseanet-image-in-media-library",
        url: mediaInfos.preview.thumb_url,
        title: mediaInfos.title,
      },
      success: function (resp) {
        loaderGif.addClass("visuallyhidden");

        // Image added to Media Library with success
        if (resp.imgID != 0) {
          // Wordpress version >= 3.5
          if (wppsnCanSetFeaturedImageAutomatically) {
            var tinywin = tinyMCEPopup.getWin();
            tinywin.wp.media.featuredImage.set(resp.imgID);

            msgSuccess.removeClass("visuallyhidden");
          }
          // Wordpress version < 3.5
          else {
            msgSuccessPartial.removeClass("visuallyhidden");
          }
        }
        // Error
        else {
          container.find(".wppsn-error").removeClass("visuallyhidden");
        }
      },
      dataType: "json",
    });
  };

  // Prevent multiple plugin instantiations
  $.fn[pluginName] = function (options) {
    return this.each(function () {
      if (!$.data(this, "plugin_" + pluginName)) {
        $.data(this, "plugin_" + pluginName, new WppsnModal(this, options));
      }
    });
  };
})(jQuery, window, document);

/**
 * On DOM Ready
 */
jQuery(document).ready(function ($) {
  // Load Wppsn Interface
  jQuery("body").wppsnInterface();
});

function updateCheckboxVal(cb) {
  if (cb.checked) {
    cb.value = "on";
  } else {
    cb.value = "off";
  }
}
