@props(['value', 'title', 'field' => 'sku.keyword'])

@if($value)
    <reactive-base v-cloak :app="config.es_prefix + '_products_' + config.store" :url="config.es_url">
        <reactive-list
            component-id="{{ md5(serialize($value)) }}"
            data-field="{{ $field }}"
            :size="999"
            :default-query="function () { return { query: { terms: { '{{ $field }}': {!!
                is_array($value)
                    ? "['".implode("','", $value)."']"
                    : $value
            !!} } } } }"
        >
            @isset($title)
                <strong class="font-bold text-2xl mt-5" slot="renderResultStats">
                    @lang($title)
                </strong>
            @else
                <div slot="renderResultStats"></div>
            @endif

            <div slot="renderNoResults"></div>

            <div class="relative" slot="render" slot-scope="{ data, loading }" v-if="!loading">
                <slider>
                    <div slot-scope="{ navigate, showLeft, showRight }">
                        <div class="flex mt-5 overflow-x-auto snap" ref="slider">
                            <template v-for="item in data">
                                @include('rapidez::listing.partials.item', ['slider' => true])
                            </template>
                        </div>
                        <button class="absolute left-0 top-1/2 -translate-y-1/2 flex items-center justify-center rounded-full w-12 h-12 bg-white border hover:bg-primary hover:text-white" v-if="showLeft" v-on:click="navigate(-1)">
                            <x-heroicon-o-chevron-left class="w-6 h-6"/>
                        </button>
                        <button class="absolute right-0 top-1/2 -translate-y-1/2 flex items-center justify-center rounded-full w-12 h-12 bg-white border hover:bg-primary hover:text-white" v-if="showRight"  v-on:click="navigate(1)">
                            <x-heroicon-o-chevron-right class="w-6 h-6"/>
                        </button>
                    </div>
                </slider>
            </div>
        </reactive-list>
    </reactive-base>
@endif
