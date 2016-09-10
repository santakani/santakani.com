@extends('layouts.app', [
    'title' => $design->text('name') . ' - ' . trans('designer.designs'),
    'body_id' => 'design-show-page',
    'body_classes' => ['design-show-page', 'show-page', 'design-page'],
    'active_nav' => 'design',
    'og_title' => $design->text('name'),
    'og_url' => $design->url,
    'og_description' => $design->excerpt('content'),
    'og_image' => empty($design->image_id)?'':$design->image->fileUrl('medium'),
    'twitter_card_type' => 'summary_large_image',
    'has_share_buttons' => true,
])

@section('header')
    <div id="gallery-wrap" class="gallery-wrap">
        <ul id="gallery" class="gallery">
            @forelse ($design->gallery_images as $image)
                <li data-thumb="{{ $image->fileUrl('largethumb') }}"
                    data-src="{{ $image->fileUrl('large') }}">
                    <img src="{{ $image->fileUrl('largethumb') }}" width="600" height="600"/>
                </li>
            @empty
                <li data-thumb="{{ url('img/placeholder/square.png') }}"
                    data-src="{{ url('img/placeholder/square.png') }}">
                    <img src="{{ url('img/placeholder/square.png') }}" width="600" height="600"/>
                </li>
            @endforelse
        </ul>
    </div><!-- /#gallery-wrap -->
    <div class="info">
        <h1 class="name">
            <div class="btn-group pull-right">
                @if (Auth::check() && Auth::user()->can('edit-design', $design))
                    <a id="edit-button" class="btn btn-default" href="{{ url()->current() . '/edit' }}">
                        <i class="fa fa-pencil"></i> {{ trans('common.edit') }}
                    </a>
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a id="transfer-button" href="#" data-toggle="modal" data-target="#transfer-modal"><i class="fa fa-fw fa-exchange"></i> {{ trans('common.transfer') }}</a></li>
                        @if (Auth::user()->can('delete-design', $design))
                            <li><a id="delete-button" href="#"><i class="fa fa-fw fa-trash"></i> {{ trans('common.delete') }}</a></li>
                        @endif
                    </ul>
                @else
                    <a id="report-button" class="btn btn-default" href="mailto:support@santakani.com?subject=[Report Design Page] {{ $design->text('name') }}&body=URL {{ $design->url }}">
                        <i class="fa fa-flag"></i> {{ trans('common.report') }}
                    </a>
                @endif
            </div>
            {{ $design->text('name') }}
        </h1>
        @if ($design->price && $design->currency)
            <h2 class="price">
                {{ $design->price }}
                <small title="{{ App\Localization\Currencies::name($design->currency) }}">{{ $design->currency }}</small>
            </h2>
            @if ($design->currency !== 'EUR')
                <p class="text-muted">~ {{ $design->eur_price }} <span title="{{ trans('currency.eur_name') }}">EUR</span></p>
            @endif
        @endif

        <p class="buttons">
            <a class="btn btn-lg btn-default {{ empty($design->webshop)?'disabled':'' }}" href="{{ $design->webshop }}" target="_blank"><i class="fa fa-lg fa-shopping-basket"></i> {{ trans('common.buy') }}</a>
            @include('components.buttons.like', ['class' => 'btn-lg','likeable' => $design])
        </p><!-- /.buttons -->

        <p>{{ trans('common.tags') }}</p>
        <p>@include('components.tag-list', ['tags' => $design->tags])</p>

        <p>{{ trans('designer.designer') }}</p>
        <p>
            <a href="{{ $design->designer->url }}">
                {{ $design->designer->text('name') }} ({{ $design->designer->city->full_name }})
            </a>
        </p>
    </div><!-- /.info -->
@endsection

@section('main')
    <div id="content" class="page-content">
        {!! $design->html('content') !!}
    </div><!-- /#content.page-content -->
    <div id="sidebar" class="sidebar">
        <h3>{{ trans('designer.designer') }}</h3>
        <p>
            <img class="img-responsive" src="{{ $design->designer->image->fileUrl('largethumb') }}" width="600" height="600"/>
        </p>
        <p><strong><a href="{{ $design->designer->url }}">{{ $design->designer->text('name') }}</a></strong></p>
        <p><em>{{ $design->designer->city->full_name }}</em></p>
        <blockquote>{{ $design->designer->text('tagline') }}</blockquote>
        <p>{{ $design->designer->excerpt('content') }} <a href="{{ $design->designer->url }}">{{ strtolower(trans('common.more')) }}</a></p>
    </div>
@endsection

@if (Auth::check() && Auth::user()->can('edit-design', $design))
    @push('modals')
        @include('components.modals.transfer-modal', ['user' => $design->user])
    @endpush
@endif
