{{--
    x-modal
    Props:
      - id: modal id (required, used by the trigger's data-bs-target)
      - title: modal heading (required)
      - footer: when true, renders the footer slot; otherwise no footer
    Slots:
      - default slot: modal body
      - footer: footer content (buttons); only rendered when footer=true
    Trigger example: <button data-bs-toggle="modal" data-bs-target="#{{ $id }}">Open</button>
--}}
@props(['id', 'title', 'footer' => false])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            @if($footer)
                <div class="modal-footer">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
