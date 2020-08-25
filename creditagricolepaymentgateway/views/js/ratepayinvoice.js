/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/LICENSE
 * @author Crédit Agricole
 * @copyright Copyright (c) 2020 Crédit Agricole, Einsteinring 35, 85609 Aschheim, Germany
 * @license MIT License
 */

var form = null;

$(document).ready(function () {
    $("form").submit(function (event) {
        form = $(this);
        let paymentMethod = $("input[name='payment-option']:checked").data("module-name");
        if (paymentMethod === "wd-ratepayinvoice" && $("#invoiceDataProtectionCheckbox").prop("checked") === false) {
            event.preventDefault();
            event.stopPropagation();
            event.stopImmediatePropagation();

            let hint = document.getElementById("invoiceDataProtectionHint");
            hint.style.display = "block";

            $("#payment-confirmation button").removeAttr("disabled");
        }
    });
});







