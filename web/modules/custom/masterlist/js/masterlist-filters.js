(function($, Drupal) {
	Drupal.behaviors.filterNeeds = {
		attach: function(context, settings) {

			$('input[type="radio"]', context).once('filterNeeds').each(function() {

        $(this).click(function(e) {

          var need = e.target.value;
          var need_text;

          console.log(need);

           switch (need) {
            case 'B':
              need_text = 'Blindness';
              break;
            case 'LV':
              need_text = 'Low vision';
              break;
            case 'CLL':
              need_text = 'Cognitive, Language and Learning Disabilities (including low literacy)';
              break;
            case 'PHY':
              need_text = 'Physical Disabilities';
              break;
            case 'D/HOH':
              need_text = 'Deaf and Hard of Hearing';
              break;
            default:
              console.log('No need');
          }

          $('#masterlist-help')
            .show()
            .html('You have selected all techniques that may help people with <strong>' + need_text + '</strong>');


          $('.technique').each(function() {

            $(this).removeClass('hidden highlight');

            var technique_needs = $(this).data('needs');

            if (technique_needs.indexOf(need) == -1) {
              $(this).addClass('hidden');
            } else {
              $(this).addClass('highlight');
            }

          });
        });
      });
		}
	}

  Drupal.behaviors.resetNeeds = {
    attach: function(context, settings) {
      
      $('#needs-reset', context).once('resetNeeds').each(function() {
        $(this).click(function(e) {
          $('input[type="radio"]').each(function() {
            $(this).prop('checked', false);
          });

          $('.technique').each(function() {
            $(this).removeClass('hidden highlight');
          });

          $('#masterlist-help').html('').hide();
        });
      });
    }
  }
})(jQuery, Drupal);
