<script>
    export default {
        props: {
            additionalFilters: {
                type: Array,
                default: () => []
            }
        },

        data: () => ({
            loaded: false,
            attributes: [],
        }),

        render() {
            return this.$scopedSlots.default({
                loaded: this.loaded,
                filters: this.filters,
                sortOptions: this.sortOptions,
                reactiveFilters: this.reactiveFilters,
            })
        },

        mounted() {
            if (localStorage.attributes) {
                this.attributes = JSON.parse(localStorage.attributes)
                this.loaded = true
                return;
            }

            axios.get('/api/attributes')
                 .then((response) => {
                    this.attributes = response.data
                    localStorage.attributes = JSON.stringify(this.attributes)
                    this.loaded = true
                 })
                 .catch((error) => {
                    Notify(window.config.errors.wrong, 'error')
                })
        },

        computed: {
            filters: function () {
                return _.sortBy(_.filter(this.attributes, function (attribute) {
                    return attribute.filter;
                }), 'position')
            },
            sortings: function () {
                return _.filter(this.attributes, function (attribute) {
                    return attribute.sorting;
                })
            },
            reactiveFilters: function () {
                return _.map(this.filters, function (filter) {
                    return filter.code;
                }).concat(this.additionalFilters);
            },
            sortOptions: function () {
                return [
                    {
                        label: window.config.translations.relevance,
                        dataField: '_score',
                        sortBy: 'desc'
                    }
                ].concat(_.flatMap(this.sortings, function (sorting) {
                    return _.map({
                        asc: window.config.translations.asc,
                        desc: window.config.translations.desc
                    }, function (directionLabel, directionKey) {
                        return {
                            label: sorting.name + ' ' + directionLabel,
                            dataField: sorting.code + (sorting.code != 'price' ? '.keyword' : ''),
                            sortBy: directionKey
                        }
                    })
                }))
            }
        }
    }
</script>