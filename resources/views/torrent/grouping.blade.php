@extends('layout.default')

@section('title')
    <title>@lang('torrent.torrents') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="@lang('torrent.torrents')">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('torrents') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('torrent.torrents')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('grouping_categories') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('torrent.grouping-categories')</span>
        </a>
    </li>
    <li>
        <a href="#" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $category->name }} @lang('torrent.grouping')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <div class="header gradient light_blue">
            <div class="inner_content">
                <h1>{{ $category->name }} Grouping</h1>
            </div>
        </div>
        @foreach ($torrents as $t)
            @php $client = new \App\Services\MovieScrapper(config('api-keys.tmdb') , config('api-keys.tvdb') , config('api-keys.omdb')) @endphp
            @if ($t->category_id == 2)
                @if ($t->tmdb || $t->tmdb != 0)
                    @php $movie = $client->scrape('tv', null, $t->tmdb); @endphp
                @else
                    @php $movie = $client->scrape('tv', 'tt'. $t->imdb); @endphp
                @endif
            @else
                @if ($t->tmdb || $t->tmdb != 0)
                    @php $movie = $client->scrape('movie', null, $t->tmdb); @endphp
                @else
                    @php $movie = $client->scrape('movie', 'tt'. $t->imdb); @endphp
                @endif
            @endif
            <div class="row">
                <div class="col-sm-12 movie-list">
                    <div class="pull-left">
                        <a href="#">
                            <img src="{{ $movie->poster }}" style="height:200px; margin-right:10px;"
                                 alt="{{ $movie->title }} @lang('torrent.poster')">
                        </a>
                    </div>
                    <h2 class="movie-title">
                        <a href="{{ route('grouping_results', ['category_id' => $t->category_id, 'imdb' => $t->imdb]) }}"
                           title="{{ $movie->title }} ({{ $movie->releaseYear }})">{{ $movie->title }}
                            ({{ $movie->releaseYear }})</a>
                        <span class="badge-user text-bold text-gold">@lang('torrent.rating'):
            <span class="movie-rating-stars">
              <i class="{{ config('other.font-awesome') }} fa-star"></i>
            </span>
                            @if ($user->ratings == 1)
                                {{ $movie->imdbRating }}/10 ({{ $movie->imdbVotes }} @lang('torrent.votes'))
                            @else
                                {{ $movie->tmdbRating }}/10 ({{ $movie->tmdbVotes }} @lang('torrent.votes'))
                            @endif
         </span>
                    </h2>
                    <div class="movie-details">
                        <p class="movie-plot">{{ $movie->plot }}</p>
                        <strong>ID:</strong>
                        <span class="badge-user"><a
                                                    href="http://www.imdb.com/title/{{ $movie->imdb }}">{{ $movie->imdb }}</a></span>
                        <span class="badge-user"><a
                                                    href="https://www.themoviedb.org/{{ strtolower($category->name) }}/{{ $movie->tmdb }}">{{ $movie->tmdb }}</a></span>
                        <strong>@lang('torrent.genre'): </strong>
                        @if ($movie->genres)
                            @foreach ($movie->genres as $genre)
                                <span class="badge-user text-bold text-green">{{ $genre }}</span>
                            @endforeach
                        @endif
                    </div>
                    <br>
                    <ul class="list-inline">
                        @php $count = DB::table('torrents')->where('imdb',$t->imdb)->where('category_id', '=', $category->id)->count(); @endphp
                        <li><i class="{{ config('other.font-awesome') }} fa-files"></i> <strong>@lang('torrent.torrents'): </strong> {{ $count }}</li>
                    </ul>
                </div>
            </div>
        @endforeach
        {{ $torrents->links() }}
    </div>
@endsection
