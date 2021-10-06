(function ($, Drupal) {
  Drupal.behaviors.asuFooter = {
    attach: function (context, settings) {
     $('#asu-footer')
     .once('asu-footer')
      .each(function () {
        const pushGAEvent = (args) => {
          const { dataLayer } = window;
          const event = {
            event: 'link',
            action: 'click',
            name: 'onclick',
            region: 'footer',
            ...args,
          };
          if (dataLayer) dataLayer.push(event);
        };

        const elements = document.querySelectorAll('[data-ga-footer]');
        console.log(elements);
        elements.forEach((element) =>
          element.addEventListener('focus', () => {
            const args = {
              type: element.getAttribute('data-ga-footer-type').toLowerCase(),
              section: element.getAttribute('data-ga-footer-section').toLowerCase(),
              text: element.getAttribute('data-ga-footer').toLowerCase(),
            };
            pushGAEvent(args);
          })
        );
      });
    }
  }
}(jQuery, Drupal));