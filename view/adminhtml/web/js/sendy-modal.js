require(
    [
        'jquery',
        'uiRegistry',
        'Magento_Ui/js/modal/modal'
    ],
    function (
        $,
        registry,
        modal
    ) {
        var options = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            title: $.mage.__('Sendy shipment'),
            modalClass: 'sendy-modal',
            focus: 'none',
            buttons: [
                {
                    text: $.mage.__('Close'),
                    class: 'action-secondary action-dismiss',
                    click: function () {
                        this.closeModal();
                    }
                },
                {
                    text: $.mage.__('Submit'),
                    class: 'action-primary',
                    click: function () {
                        $('#order-view-sendy-shipping-form').submit();
                    }
                }
            ]
        };

        modal(options, $('#sendy-modal'));
        $("#sendy_shipping").click(function() {
            $("#sendy-modal").modal('openModal');
        });
        sendyModal = _.extend({
            showPopup: function(action, selections) {
                $("#sendy-modal").modal('openModal');
                $('#order-view-sendy-shipping-form').find('input[name="selected[]"]').remove();
                if (!$('#order-view-sendy-shipping-form').find('input[name="form_key"]').length) {
                    $('#order-view-sendy-shipping-form').append(
                        '<input name="form_key" class="hidden" value="' + FORM_KEY + '">'
                    );
                }
                selections.selected.forEach(
                    (val) => $('#order-view-sendy-shipping-form').append(
                        '<input name="selected[]" class="hidden" value="'+val+'">'
                    )
                );
            }
        });
        registry.set('sendyModal', sendyModal);
        return sendyModal;

    }
);

