@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">

            <div class="d-flex mb-4">

                {{-- {!! $groups->links() !!} --}}

                <div class="ml-auto">
                    <a href="" class="btn btn-primary" data-toggle="modal" data-target="#create">Groep toevoegen</a>
                </div>
            </div>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header d-flex">
                    <div>Groepen</div>
                    <div class="ml-auto">Totaal € {{ $total }}</div>
                </div>

                <div class="card-body">

                    @foreach($groups as $group)

                        @php $sum = array_key_first(request()->all()) == 'income' ? $group->incomes(session()->get('bookyear') ?? date('Y'))->sum('price') : $group->receipts(session()->get('bookyear') ?? date('Y'))->sum('price'); @endphp

                        <div class="d-flex py-2 border-bottom">
                            <div class="flex-grow-1">
                                <a href="javascript:;" onclick="window.edit('{{ $group->id }}')"><i class="far fa-edit"></i></a>
                                <a href="javascript:;" onclick="window.delete('{{ $group->id }}')"><i class="far fa-minus-circle"></i></a>
                                <div id="row_{{ $group->id }}" class="d-inline ml-2" data-original-content="{{ $group->title }}">{{ $group->title }}</div>
                            </div>
                            <div class="text-right w-25">
                                <label class="h3 mb-0">€ {{ number_format($sum, 2) }}</label>
                                {{-- <span class="d-block text-muted">Uitgegeven</span> --}}
                            </div>
                            {{-- <div class="text-right w-25">
                                <label class="h3 mb-0">{{ $group->receipts->count() }}</label> <span class="d-block text-muted">Bonnetjes</span>
                            </div> --}}
                        </div>
                    @endforeach

                    <div class="d-flex py-2 mt-4">
                        <div class="flex-grow-1 h3">Totaal</div>
                        <div class="text-right w-25">
                            <span class="h3">
                                € {{ $total }}
                            </span>
                        </div>
                        {{-- <div class="text-right w-25">
                            <span class="h3">
                                {{ App\Group::with('receipts')->get()->pluck('receipts')->collapse()->count() }}
                            </span>
                        </div> --}}
                    </div>

                </div>
            </div>

            {{-- {!! $groups->links() !!} --}}

        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="create">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="{{ route('group.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Groep toevoegen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Groepnaam</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="type" value="{{  array_key_first(request()->all()) }}">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Sluit</button>
                    <button type="submit" class="btn btn-primary">Groep opslaan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    window.edit = function(id) {
        var originContent = $('#row_' + id).data('original-content');
        $('#row_' + id).html('<form class="d-inline" onsubmit="return window.update('+id+')"><input type="text" name="title" value="'+originContent+'" required class="form-control d-inline w-50">' +
        '<a href="javascript:;" onclick="$(this).submit()" class="ml-3 mt-1 h4 text-success"><i class="far fa-check-circle"></i></a></form>');
    };

    window.update = function(id) {
        var newValue = $('#row_' + id).find('input').val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "put",
            url: "/group/"+id,
            data: {title: newValue},
            dataType: "HTML",
            success: function (response) {
                $('#row_' + id).html(newValue);
                $('#row_' + id).data('original-content', newValue);
            }
        });
        return false;
    };

    window.delete = function(id) {
        if (confirm('Groep verwijderen?')) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "delete",
                url: "/group/"+id,
                dataType: "HTML",
                success: function (response) {
                    window.location.reload();
                }
            });
        }
    }
</script>
@endsection
