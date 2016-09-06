@extends('orchestra/foundation::emails.layouts.alert')

@php($actionColor = $level)

@section('title')
@if($level == 'error')
  {{ isset($title) ? $title : 'Whoops!' }}
@elseif($level == 'warning')
  {{ isset($title) ? $title : 'Warning!' }}
@else
  {{ isset($title) ? $title : 'Hello!' }}
@endif
@stop

@section('content')
<table width="100%" cellpadding="0" cellspacing="0">
  {{-- Logo --}}
  @unless(is_null($logoUrl = config('app.logo')))
  <tr>
    <td class="email-masthead">
      <a class="email-masthead_name" href="{{ handles('app::/') }}" target="_blank">
        <img src="{{ $logoUrl }}" class="email-logo" />
      </a>
    </td>
  </tr>
  @endif

  {{-- Intro --}}
  <tr>
    <td class="content-block">
      @foreach($introLines as $line)
      <p>{{ $line }}</p>
      @endforeach
    </td>
  </tr>

  {{-- Action --}}
  @if(isset($actionText))
  <tr>
    <td class="content-block" itemprop="handler" itemscope itemtype="http://schema.org/HttpActionHandler">
      <a href="{{ $actionUrl }}" class="btn btn-{{ $actionColor }}" itemprop="url"  target="_blank">
        {{ $actionText }}
      </a>
    </td>
  </tr>
  @endif

  {{-- Outro --}}
  <tr>
    <td class="content-block">
      @foreach ($outroLines as $line)
        <p>{{ $line }}</p>
      @endforeach
    </td>
  </tr>

  {{-- Salutation --}}
  <tr>
    <td class="content-block">
      &mdash; {{ memorize('site.name') }}
    </td>
  </tr>
</table>
@stop
