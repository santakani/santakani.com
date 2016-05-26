@extends('layout.app', [
    'title' => 'Places',
    'body_id' => 'place-index-page',
    'body_class' => 'place-index-page place-page index-page',
])

@section('main')
<div id="place-map">
    <div id="place-map-inner">
        <div id="place-map-draw"></div>
    </div>
</div>

<div id="place-list">
    @foreach ($places as $place)
        <article id="place-{{ $place->id }}" class="place"
            data-id="{{ $place->id }}" data-latitude="{{ $place->latitude }}"
            data-longitude="{{ $place->longitude }}">
            <div class="image" style="background-image:url({{ $place->image?$place->image->file_urls['thumb']:'http://placehold.it/200x200?text=NO+IMAGE' }})">
                <span class="type">{{ $place->type }}</span>
            </div>
            <div class="text">
                <h3 class="title">
                    <span class="dot"></span>
                    <a href="{{ $place->url }}">
                        {{ $place->text('name') }}
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
                <div class="content">
                    {!! $place->text('content') !!}
                </div>
            </div>
        </article>
    @endforeach
</div><!-- #place-list -->
@endsection
