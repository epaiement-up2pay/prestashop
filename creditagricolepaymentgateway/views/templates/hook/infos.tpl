{*
* Shop System Extensions:
* - Terms of Use can be found at:
* https://github.com/epaiement-up2pay/prestashop/blob/master/_TERMS_OF_USE
* - License can be found under:
* https://github.com/epaiement-up2pay/prestashop/blob/master/LICENSE
*}

<img src="../modules/creditagricolepaymentgateway/logo.png">
<br>
<p><strong>{lFallback s='pay_with_gateway' mod='creditagricolepaymentgateway'}</strong></p>
<div class="btn-group">
    <a class="btn btn-default" id="wirecardTransactions" href="{$link->getAdminLink('WirecardTransactions')|escape:'html':'UTF-8'}">
        <i class="icon-money"></i>
        {lFallback s='text_list' mod='creditagricolepaymentgateway'}
    </a>
    <a class="btn btn-default" id="WirecardSupport" href="{$link->getAdminLink('WirecardSupport')|escape:'html':'UTF-8'}">
        {lFallback s='text_support' mod='creditagricolepaymentgateway'}
    </a>
    <a class="btn btn-default" id="WirecardShopPluginInformation" target=_blank href="https://github.com/epaiement-up2pay/prestashop/wiki/Terms-of-Use">
        {lFallback s='terms_of_use' mod='creditagricolepaymentgateway'}
    </a>
    <a class="btn btn-default" id="WirecardGeneralSettings" href="{$link->getAdminLink('WirecardGeneralSettings')|escape:'html':'UTF-8'}">
        {lFallback s='general_settings' mod='creditagricolepaymentgateway'}
    </a>
</div>


