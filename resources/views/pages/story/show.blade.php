@extends('layouts.app', [
    'title' => $story->text('title') . ' - ' . trans('story.story'),
    'body_id' => 'story-show-page',
    'body_classes' => ['story-show-page', 'story-page', 'show-page'],
    'active_nav' => 'story',
    'og_title' => $story->text('title'),
    'og_url' => $story->url,
    'og_description' => $story->excerpt('content'),
    'og_image' => empty($story->image_id)?'':$story->image->fileUrl('medium'),
    'twitter_card_type' => 'summary_large_image',
    'has_share_buttons' => true,
])

@section('header')
    <div class="page-cover"
        @if ($story->image_id)
            style="background-image:url({{ $story->image->large_file_url }})"
        @endif
        >

        <div class="raster raster-dark-dot"></div>

        <div class="buttons">
            @include('components.buttons.like', ['likeable' => $story])
            @if (Auth::check() && Auth::user()->can('edit-story', $story))
                <div class="btn-group">
                    <a id="edit-button" class="btn btn-default" href="{{ url()->current() . '/edit' }}">
                        <i class="fa fa-lg fa-pencil"></i> {{ trans('common.edit') }}
                    </a>
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-lg fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        @if (Auth::user()->can('delete-story', $story))
                            <li><a id="delete-button" href="#"><i class="fa fa-fw fa-trash"></i> {{ trans('common.delete') }}</a></li>
                        @endif
                    </ul>
                </div><!--/.btn-group -->
            @endif
        </div>

        <h1 class="title">{{ $story->text('title') }}</h1>

        <p class="author">
            <a href="{{ $story->user->url }}">
                <img class="avatar" src="{{ $story->user->small_avatar_url }}"
                    srcset="{{ $story->user->medium_avatar_url }} 3x, {{ $story->user->large_avatar_url }} 6x"
                    width="50" height="50">
                <span class="user-name">{{ $story->user->name }}</span>
            </a>
        </p>

        <p class="date">{{ $story->created_at->formatLocalized(App\Localization\Languages::dateFormat()) }}</p>

        @include('components.tag-list', [
            'tags' => $story->tags,
        ])

    </div><!-- .container -->
@endsection

@section('main')
    <div id="page-content" class="page-content">{!! $story->html('content') !!}</div>
@endsection
