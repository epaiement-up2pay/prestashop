{*
* Shop System Extensions:
* - Terms of Use can be found at:
* https://github.com/epaiement-up2pay/prestashop/blob/master/_TERMS_OF_USE
* - License can be found under:
* https://github.com/epaiement-up2pay/prestashop/blob/master/LICENSE
*}

{extends file="helpers/view/view.tpl"}

{block name="override_tpl"}
    <div class="col-lg-12">
        <a href="{$back_link|escape:'htmlall':'UTF-8'}">< {lFallback s='back_button' mod='creditagricolepaymentgateway'}</a>
        <br><br>
        <div class="panel" style="width: 100%">
            <h3><i class="icon-group"></i> {lFallback s='heading_transaction_details' mod='creditagricolepaymentgateway'}</h3>
            <h2>{lFallback s='text_transaction' mod='creditagricolepaymentgateway'} {$transaction.id|escape:'htmlall':'UTF-8'}</h2>
            <br>
            <br>
            <h3>
                {$payment_method|escape:'htmlall':'UTF-8'} {lFallback s='payment_suffix' mod='creditagricolepaymentgateway'}
            </h3>
            <div>
                <b>
                    {$transaction.type|escape:'htmlall':'UTF-8'}
                </b>
                |
                <b class="badge" style="color: white; background-color: {$transaction.badge}">
                    {$transaction.status|escape:'htmlall':'UTF-8'}
                </b>
            </div>
            <br>
            {if $transaction.status == 'open'}
                <form method="post" class="post-processing">
                    <input type="hidden" name="transaction" value="{$transaction.tx}" />

                    {foreach $possible_operations as $operation}
                        <button type="submit" name="operation" value="{$operation.action}" class="btn btn-primary pointer">
                            {$operation.name}
                        </button>
                    {/foreach}
                </form>
            {/if}

            {if count($possible_operations) === 0}
                <p>{lFallback s='no_post_processing_operations' mod='creditagricolepaymentgateway'}</p>
            {/if}
            <hr>

            <h3>{lFallback s='text_response_data' mod='creditagricolepaymentgateway'}</h3>
            <div class="order_data_column_container table table-striped">
                <table>
                    <tr>
                        <td>
                            <b>{lFallback s='text_total' mod='creditagricolepaymentgateway'}</b>
                        </td>
                        <td>
                            <b>{$transaction.amount|escape:'htmlall':'UTF-8'} {$transaction.currency|escape:'htmlall':'UTF-8'}</b>
                        </td>
                    </tr>
                    {foreach from=$transaction.response key=k item=v}
                        <tr><td>{$k|escape:'htmlall':'UTF-8'}</td><td>{$v|escape:'htmlall':'UTF-8'}</td></tr>
                    {/foreach}
                </table>
            </div>
        </div>
    </div>
{/block}
