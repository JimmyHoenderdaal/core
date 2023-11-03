<graphql-mutation
    :query="'mutation ($cart_id: String!, $cart_item_id: Int) { removeItemFromCart(input: { cart_id: $cart_id, cart_item_id: $cart_item_id }) { cart { ' + config.queries.cart + ' } } }'"
    :variables="{ cart_id: window.app.mask, cart_item_id: item.id }"
    :notify="{ message: item.product.name+' '+config.translations.cart.remove }"
    :callback="refreshCart"
    v-slot="{ mutate }"
>
    <button :disabled="$root.loading" v-on:click="mutate" title="@lang('Remove')" :dusk="'item-delete-'+index" class="hover:underline">
        @lang('Remove')
    </button>
</graphql-mutation>
