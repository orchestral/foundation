@php
$message = app('orchestra.messages')->copy();
$content = $content ?? '<div class="alert alert-:key">:message <button class="close" data-dismiss="alert">×</button></div>';
@endphp

@if($message instanceof Illuminate\Contracts\Support\MessageBag)
  @php
  $message->setFormat($content);
  @endphp

  @foreach(['error', 'info', 'success'] as $key)
  @if($message->has($key))
    {!! implode('', $message->get($key)) !!}
  @endif
  @endforeach
@endif
