select: {
    style: 'multi+shift',
    selector: 'td:first-child'
},
dom: 'lBfrtip',
buttons: [
    'selectAll',
    'selectNone',
    {
        extend: 'collection',
        text: 'Action',
        className: 'btn-primary {{ $button['class'] ?? 'buttons-delete-items' }}-text',
        fade: 0,
        init: function ( dt, node, config ) {
            var that = this;

            dt.on('select.dt.DT deselect.dt.DT', function () {
                that.enable( dt.rows( { selected: true } ).any() );
                var selectedCount = dt.rows( { selected: true } ).count();
                $('a.{{ $button['class'] ?? 'buttons-delete-items' }}-text span').text(selectedCount > 0 ? '(' + selectedCount + ' selected) Action' : 'Action');
            });

            $('#{{ $button['modalTarget'] ?? 'confirm-delete-modal' }}').on('show.bs.modal', function(event) {
                var actionHref = event.relatedTarget ? $(event.relatedTarget).data('href') : $('.{{ $button['class'] ?? 'buttons-delete-items' }}').data('href');
                $('#{{ $button['modalTarget'] ?? 'confirm-delete-modal' }}-action').attr('action', actionHref);
            });

            this.disable();
        },
        buttons: [
            @foreach ($buttons as $button)
            {
                text: '{{ $button['title'] ?? 'Delete' }}',
                action: function ( e, dt ) {
                    var ids = dt.rows( { selected: true } ).data().toArray().map(function(o) { return o.id; }).join();
                    var href = '{{ route($button['route'], '%IDS%') }}';

                    $(e.currentTarget).data('href', href.replace('%IDS%', ids));
                    $('#{{ $button['modalTarget'] ?? 'confirm-delete-modal' }}').modal('toggle');
                },
                className: '{{ $button['class'] ?? 'buttons-delete-items' }}'
            },
            @endforeach
        ]
    }
],
order: [[ 1, 'asc' ]],