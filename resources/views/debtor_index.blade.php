@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">

            <div class="d-flex mb-4">

                <div class="ml-auto">
                    <a href="" class="btn btn-primary" data-toggle="modal" data-target="#create">Debiteur toevoegen</a>
                </div>
            </div>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header d-flex">
                    <div class="h4 mt-1">Debiteuren</div>
                </div>

                <div class="card-body">

                    @foreach($debtors as $debtor)
                        <div class="d-flex py-2 {{ ! $loop->last ? 'border-bottom' : '' }}">
                            <div class="w-25">
                                <a href="javascript:;" onclick="window.edit('{{ $debtor->id }}')"><i class="far fa-edit"></i></a>
                                <a href="javascript:;" onclick="window.delete('{{ $debtor->id }}')"><i class="far fa-minus-circle"></i></a>
                                <div id="row_{{ $debtor->id }}" class="d-inline ml-2" data-original-content="{{ $debtor->title }}">{{ $debtor->title }}</div>
                            </div>
                            <div class="text-right flex-grow-1 h5 mt-2">
                                <label class="h3 mb-0">€ {{ number_format($debtor->incomes(session()->get('bookyear') ?? date('Y'))->sum('price'), 2) }}</label>
                                <span class="d-block text-muted small">Opgebracht</span>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="create">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="{{ route('debtor.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Debiteur toevoegen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Naam</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Sluit</button>
                    <button type="submit" class="btn btn-primary">Debiteur opslaan</button>
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
            url: "/debtor/"+id,
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
        if (confirm('Debiteur verwijderen?')) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "delete",
                url: "/debtor/"+id,
                dataType: "HTML",
                success: function (response) {
                    window.location.reload();
                }
            });
        }
    }
</script>

@endsection
