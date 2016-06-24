@extends('orchestra/foundation::emails.layouts.alert')

@section('title')
@if($level == 'error')
  {{ isset($subject) ? $subject : 'Whoops!' }}
@elseif($level == 'warning')
  {{ isset($subject) ? $subject : 'Warning!' }}
@else
  {{ isset($subject) ? $subject : 'Hello!' }}
@endif
@stop

@section('content')
<table width="100%" cellpadding="0" cellspacing="0">
  {{-- Logo --}}
  @if(isset($logoUrl) && ! is_null($logoUrl))
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
  <tr>
    <td class="content-block" itemprop="handler" itemscope itemtype="http://schema.org/HttpActionHandler">
      <a href="{{ $actionUrl }}" class="btn btn-{{ $actionColor }}" itemprop="url"  target="_blank">
        {{ $actionText }}
      </a>
    </td>
  </tr>

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
      &mdash; {{ $application }}
    </td>
  </tr>
</table>
@stop
