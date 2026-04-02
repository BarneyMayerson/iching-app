@php
  $currentLocale = app()->getLocale();
  $otherLocale = $currentLocale === 'ru' ? 'en' : 'ru';
  $label = $currentLocale === 'ru' ? 'English' : 'Русский';
@endphp

<form method="POST" action="{{ route('language.update') }}" style="display: contents;">
  @csrf
  <input type="hidden" name="language" value="{{ $otherLocale }}">

  <x-filament::dropdown.list.item tag="button" type="submit" icon="heroicon-o-language">
    {{ $label }}
  </x-filament::dropdown.list.item>
</form>
