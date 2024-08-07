@extends('rapidez::layouts.app')

@section('title', __('Search for').': '.request()->q)
@section('description', __('Search for').': '.request()->q)
@section('robots', 'NOINDEX,NOFOLLOW')

@section('content')
    <div class="container">
        <x-rapidez::title tag="h1" class="text-3xl">@lang('Search for'): {{ request()->q }}</x-rapidez::title>
        <x-rapidez::listing query="{
            bool: {
                must: [
                    { terms: { visibility: [3, 4] } },
                    { bool: { should: [
                        {
                            multi_match: {
                                query: '{{ request()->q }}',
                                fields: Object.entries(config.searchable).map((value) => value[0]+'^'+value[1]),
                                type: 'best_fields',
                                operator: 'or',
                                fuzziness: 'AUTO',
                            },
                        },
                        {
                            multi_match: {
                                query: '{{ request()->q }}',
                                fields: Object.entries(config.searchable).map((value) => value[0]+'^'+value[1]),
                                type: 'phrase',
                                operator: 'or',
                            }
                        },
                        {
                            multi_match: {
                                query: '{{ request()->q }}',
                                fields: Object.entries(config.searchable).map((value) => value[0]+'^'+value[1]),
                                type: 'phrase_prefix',
                                operator: 'or',
                            }
                        },
                    ] } }
                ],
            }
        }"/>
    </div>
@endsection
