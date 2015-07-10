jQuery(document).ready(function () {
    // As the I18n form prototype will always have the same prefix it would be a problem
    // for collections having translations (eg. Questions have multiple Answers, and these answers are translated).
    // The current system does not work properly if we try to add 2+ Answers at the same time.
    // This fix replaces any prefix having the "fake prefix" to a new unique prefix on-the-fly.
    function replaceI18nTabTags() {
        $('[data-admin=translatable-content]').each(function (index, el) {
            var fakePrefix = 'I18N_TAB_PREFIX';
            var $el = $(el);

            if ($el.attr('data-prefix') === fakePrefix) {
                // Prefix must be replaced
                var prefix = Math.floor(Math.random() * 10000000 + 1);
                $el.attr('data-prefix', prefix);

                $('[data-toggle=tab]', $el).each(function (index, tab) {
                    var $tab = $(tab);
                    $tab.attr('href', $tab.attr('href').replace(fakePrefix, prefix));
                });

                $('.tab-pane', $el).each(function (index, pane) {
                    var $pane = $(pane);
                    $pane.attr('id', $pane.attr('id').replace(fakePrefix, prefix));
                });
            }
        });
    }

    // Calling it when the page is displayed (for already displayed tabs)
    replaceI18nTabTags();

    // Calling it every time the form is changed
    $(document).on('change', 'form', function() {
        replaceI18nTabTags();
    });
});