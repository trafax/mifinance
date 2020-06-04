@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">

            <div class="d-flex mb-4">

                {!! $receipts->links() !!}

                <div class="ml-auto">
                    <a href="" class="btn btn-primary" data-toggle="modal" data-target="#create">Bonnetje toevoegen</a>
                </div>
            </div>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header d-flex">
                    <div class="h4 mt-1">Bonnetjes</div>
                    <div class="w-25 ml-4 pl-4">
                        <form method="get" action="{{ route('receipt.search') }}">
                            @csrf
                            <input type="text" name="search" placeholder="Zoeken..." class="form-control">
                        </form>
                    </div>
                    <div class="ml-auto">Totaal € {{ number_format(App\Receipt::all()->sum('price'), 2) }}</div>
                </div>

                <div class="card-body">

                    @foreach($receipts as $receipt)
                        <div class="d-flex py-2 border-bottom">
                            <div class="w-25">
                                <a href="{{ route('receipt.edit', $receipt->id) }}"><i class="far fa-edit"></i></a>
                                <a href="javascript:;" onclick="window.delete('{{ $receipt->id }}')"><i class="far fa-minus-circle"></i></a>
                                <div class="ml-2 d-inline">{{ date('d-m-Y', strtotime($receipt->date)) }}</div>
                            </div>
                            <div class="flex-grow-1 w-50">
                                <div class="d-inline">{{ $receipt->title }}
                                    <div class="d-block text-muted">{{ $receipt->group->title }}</div>
                                </div>
                            </div>
                            <div class="text-right flex-grow-1 h5 mt-2">
                                € {{ number_format($receipt->price, 2) }}
                            </div>
                        </div>
                    @endforeach

                    <div class="d-flex py-2 mt-4">
                        <div class="flex-grow-1 h3">Totaal</div>
                        <div class="text-right w-25">
                            <span class="h3">€ {{ number_format(App\Receipt::all()->sum('price'), 2) }}</span>
                        </div>
                    </div>

                </div>
            </div>

            {!! $receipts->links() !!}

        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="create">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" action="{{ route('receipt.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Bonnetje toevoegen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <script>
                        window.onload = function () {
                            $('.datepicker').datepicker({
                                maxDate: '+30d',
                                numberOfMonths: 3,
                                dateFormat: "yy-mm-dd",
                                changeMonth: true,
                                changeYear: true,
                                onSelect: function(date){
                                    $('[name="date"]').val(date);
                                }
                            });
                        }
                    </script>
                    <div class="form-group">
                        <label>Datum bonnetje</label>
                        <div class="datepicker"></div>
                        <input type="hidden" name="date" value="{{ date('yy-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label>Groep</label>
                        <select name="group_id" class="form-control">
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Omschrijving bonnetje</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>Prijs</label>
                                <input type="text" name="price" class="form-control" required placeholder="0.00">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Betaald vanuit</label>
                                <select name="paid_out" class="form-control">
                                    <option value="private">Privé rekening</option>
                                    <option value="business">Zakelijke rekening</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Sluit</button>
                    <button type="submit" class="btn btn-primary">Bonnetje opslaan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    window.delete = function(id) {
        if (confirm('Bonnetje verwijderen?')) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "delete",
                url: "/receipt/"+id,
                dataType: "HTML",
                success: function (response) {
                    window.location.reload();
                }
            });
        }
    }
</script>

@endsection
