#{{ $message = app('orchestra.messages')->retrieve() }}

@if($message instanceof Orchestra\Messages\MessageBag)
  #{{ $message->setFormat('<div class="alert alert-:key">:message <button class="close" data-dismiss="alert">×</button></div>') }}

  @foreach(['error', 'info', 'success'] as $key)
  @if($message->has($key))
    {!! implode('', $message->get($key)) !!}
  @endif
  @endforeach
@endif
