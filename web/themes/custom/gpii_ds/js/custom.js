/**
 * @file
 * Global utilities.
 *
 */
(function($, Drupal) {

  'use strict';

  Drupal.behaviors.gpii_ds = {
    attach: function(context, settings) {

      // Custom code here
      var selectedNeed = "";
      $("#alert-selection").hide();
		
		  function resetFilters() {
			  selectedNeed = "";
			  $('.filter-button').removeClass('selected');
			  $('.filter-button').attr('aria-checked', 'False');
			  $('.technique').show();
		  }

      function filterNeeds() {
        var techniques = $('.technique');
        techniques.each(function() {
          var needs_string = $(this).data("needs");
          var needs_array = needs_string.split(", ");
          if (needs_array.indexOf(selectedNeed) < 0) {
            $(this).hide();
          }
        });
      }

      function alertText(need) {
        var needText = "ula";
        switch (need) {
          case 'B':
            needText = "Blindness";
            break;
          case 'LV':
            needText = "Low Vision";
            break;
          case 'PHY':
            needText = "Physical Disabilities";
            break;
          case 'D/HOH':
            needText = "Deafness and Hardness of Hearing";
            break;
          case "CLL":
            needText = "Cognitive, Language and Learning Disabilities (including low literacy)";
            break;
          default:
            needText = "";
        }
        $("#alert-selection").html(`You have selected all techniques that might help people with <b>${needText}</b>`).show();
      }

      $(".filter-button").on('click', function(e) {
        e.stopPropagation();
        $('#reset-filters').removeClass('btn-light');
        $('#reset-filters').addClass('btn-outline-secondary');
        selectedNeed = $(this).data('need');
        resetFilters();
        if ($(this).attr('aria-checked') == 'True') {
          $(this).attr('aria-checked', 'False');
          $(this).removeClass('selected');
        } else {
          $(this).attr('aria-checked', 'True');
          $(this).addClass('selected');
          selectedNeed = $(this).data('need');
          alertText(selectedNeed);
          filterNeeds();
        }
      });

      $('#reset-filters').on('click', function(e) {
        e.stopPropagation();
        $(this).removeClass('btn-outline-secondary');
        $(this).addClass('btn-light');
        $('#alert-selection').html("").hide();
        resetFilters();
      });
    }
  };

})(jQuery, Drupal);