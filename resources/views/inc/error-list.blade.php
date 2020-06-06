@foreach ($errors->all() as $error)
    @component('inc.alert')
        {{ $error }}
    @endcomponent
@endforeach