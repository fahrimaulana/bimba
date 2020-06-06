@if(Session::has('notif_success'))
    @component('inc.alert', ['type' => 'success'])
        {{ Session::get('notif_success') }}
    @endcomponent
@endif