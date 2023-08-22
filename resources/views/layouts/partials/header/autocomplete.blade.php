@php $inputClasses = 'border !border-gray-200 !text-sm !min-h-0 outline-none !h-auto rounded !p-2 !bg-white w-full' @endphp

<label for="autocomplete-input" class="sr-only">@lang('Search')</label>
<input
    id="autocomplete-input"
    placeholder="@lang('Search')"
    class="{{ $inputClasses }}"
    v-on:focus="$root.loadAutocomplete = true; window.setTimeout(() => window.document.getElementById('autocomplete-input').focus(), 200)"
    v-if="!$root.loadAutocomplete">

<autocomplete v-if="$root.loadAutocomplete" v-cloak>
    <x-rapidez::reactive-base>
        <data-search
            placeholder="@lang('Search')"
            v-on:value-selected="search"
            component-id="autocomplete"
            :inner-class="{ input: '{{ $inputClasses }}' }"
            class="[&_*]:!m-0"
            :data-field="Object.keys(config.searchable)"
            :field-weights="Object.values(config.searchable)"
            :show-icon="false"
            fuzziness="AUTO"
            :debounce="100"
            :size="9"
        >
            <div
                slot="render"
                slot-scope="{ downshiftProps: { isOpen }, data: suggestions }">
                <ul class="{{ config('rapidez.z-indexes.header-dropdowns') }} absolute left-2/3 right-auto mt-px flex w-screen -translate-x-1/2 transform flex-wrap rounded-b-lg border bg-white shadow-xl sm:left-1/2 sm:w-full lg:w-[960px] lg:rounded-t-lg xl:ml-0 xl:rounded-t-lg" v-if="isOpen && suggestions.length">
                    <li
                        class="my-4 flex w-1/2 px-4 sm:w-1/2 md:w-1/3"
                        v-for="suggestion in suggestions"
                        :key="suggestion.source._id">
                        <a :href="suggestion.source.url | url" class="flex flex-wrap flex-1" key="suggestion.source._id">
                            <img :src="'/storage/{{ config('rapidez.store') }}/resizes/80x80/magento/catalog/product' + suggestion.source.thumbnail + '.webp'" class="self-center object-contain lg:w-3/12" />
                            <div class="flex flex-grow flex-wrap px-2 lg:w-1/2">
                                <strong class="hyphens block w-full">@{{ suggestion.source.name }}</strong>
                                <div class="self-end">@{{ suggestion.source.price | price }}</div>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </data-search>
    </x-rapidez::reactive-base>
</autocomplete>
