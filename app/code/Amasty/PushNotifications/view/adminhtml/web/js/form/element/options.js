define([
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/select',
    'Magento_Ui/js/modal/modal'
], function (_, uiRegistry, select, modal) {
    'use strict';

    return select.extend({
        initialize: function () {
            this._super();

            this.showDependedField(this.value(), 100);

            return this;
        },

        onUpdate: function (value) {
            this.showDependedField(value);

            return this._super();
        },
        
        showDependedField: function (value, time = 0) {
            var field1 = uiRegistry.get('index = customer_groups');
            var field2 = uiRegistry.get('index = customer_segments');

            if (field1 !== undefined && field2 !== undefined) {
                if (field1.visibleValue === value) {
                    field1.show();
                } else {
                    field1.hide();
                }

                if (field2.visibleValue === value) {
                    field2.show();
                } else {
                    field2.hide();
                }
            } else {
                var self = this;

                setTimeout(function () {
                    self.showDependedField(value, time)
                }, time);
            }
        }
    });
});
