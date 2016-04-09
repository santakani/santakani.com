@extends('layout.app')

@section('content')
<div id="place-wrapper" class="clearfix">
    <div id="place-map" data-spy="affix" data-offset-top="300">
        <div id="place-map-inner"></div>
    </div>
    <div id="place-list">
        @foreach ($places as $place)
            <article id="place-{{ $place->id }}" class="place">
                <div class="image" style="background-image:url({{ $place->getImage()->getThumbUrl() }})">
                    <span class="type">{{ $place->type }}</span>
                </div>
                <div class="text">
                    <h3 class="title">
                        <a href="{{ url('/place/' . $place->id) }}">
                            {{ $place->getTranslation()->name }}
                        </a>
                        <small>
                            <i class="fa fa-heart-o"></i> 59
                            <i class="fa fa-comment-o"></i> 14
                        </small>
                    </h3>
                    <p class="tags">
                        <span class="tag">grocery</span>
                        <span class="tag">tableware</span>
                        <span class="tag">knitwear</span>
                    </p>
                    <div class="contnet">
                        {!! $place->getTranslation()->content !!}
                    </div>
                </div>
            </article>
        @endforeach
    </div><!-- #place-list -->
</div>
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&callback=initMap"></script>
@endsection
