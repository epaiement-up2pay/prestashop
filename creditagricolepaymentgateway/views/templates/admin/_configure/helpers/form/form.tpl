{*
* Shop System Extensions:
* - Terms of Use can be found at:
* https://github.com/epaiement-up2pay/prestashop/blob/master/_TERMS_OF_USE
* - License can be found under:
* https://github.com/epaiement-up2pay/prestashop/blob/master/LICENSE
*}

{extends file="helpers/form/form.tpl"}
{block name="input"}
    {if $input.type == 'linkbutton'}
		<a class="btn btn-default" id="{$input.id|escape:'htmlall':'UTF-8'}" href="#">
			<i class="icon-check"></i>
            {lFallback s=$input.buttonText mod='creditagricolepaymentgateway'}
		</a>
		<script type="text/javascript">
            $(function () {
                $(document).ready(function(){
                    var translate = {
                        paypal:'{lFallback s='paypal' mod='creditagricolepaymentgateway'}',
                        creditcard:'{lFallback s='creditcard' mod='creditagricolepaymentgateway'}',
                        sepadirectdebit:'{lFallback s='sepadd' mod='creditagricolepaymentgateway'}',
                        sepacredittransfer:'{lFallback s='sepact' mod='creditagricolepaymentgateway'}',
                        ideal:'{lFallback s='ideal' mod='creditagricolepaymentgateway'}',
                        sofort:'{lFallback s='sofortbanking' mod='creditagricolepaymentgateway'}',
                        poipia:'{lFallback s='poi_pia' mod='creditagricolepaymentgateway'}',
                        invoice:'{lFallback s='ratepayinvoice' mod='creditagricolepaymentgateway'}',
                        'alipay-xborder':'{lFallback s='alipay_crossborder' mod='creditagricolepaymentgateway'}',
                        p24:'{lFallback s='ptwentyfour' mod='creditagricolepaymentgateway'}',
                    };
                    $("a[data-toggle=tab]").each(function() {
                        $(this).html(translate[$(this).html().toLowerCase()]);
                    });
                });
                $('#{$input.id}').on('click', function() {
                    $.ajax({
                        type: 'POST',
                        url: '{$ajax_configtest_url|escape:'quotes'}',
                        dataType: 'json',
                        data: {
                            action: 'TestConfig',
                    {foreach $input.send as $datasend}
                    '{$datasend|escape:'htmlall':'UTF-8'}': $('input[name={$datasend|escape:'htmlall':'UTF-8'}]').val(),
                    {/foreach}
                        method: '{$input.method|escape:'htmlall':'UTF-8'}',
                        ajax: true
                },
                    success: function (jsonData) {
                        if (jsonData) {
                            $.fancybox({
                                fitToView: true,
                                content: '<div><fieldset><legend>{lFallback s='text_test_result' mod='creditagricolepaymentgateway'}</legend>' +
                                '<label>{lFallback s='config_status' mod='creditagricolepaymentgateway'}:</label>' +
                                '<div class="margin-form" style="text-align:left;">' + jsonData.status + '</div><br />' +
                                '<label>{lFallback s='text_message' mod='creditagricolepaymentgateway'}:</label>' +
                                '<div class="margin-form" style="text-align:left;">' + jsonData.message + '</div></fieldset></div>'
                            });
                        }
                    }
                });
                });
            });
		</script>
    {else}
        {$smarty.block.parent}
    {/if}
{/block}
