@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">

            <div class="d-flex mb-4">

                {!! $invoices->links() !!}

                <div class="ml-auto">
                    <a href="" class="btn btn-primary" data-toggle="modal" data-target="#create">Factuur toevoegen</a>
                </div>
            </div>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header d-flex">
                    <div class="h4 mt-1">Facturen</div>
                    <div class="w-25 ml-4 pl-4">
                        <form method="get" action="{{ route('invoice.search') }}">
                            @csrf
                            <input type="text" name="search" placeholder="Zoeken..." class="form-control">
                        </form>
                    </div>
                    <div class="ml-auto">Totaal € {{ number_format((\App\InvoiceRule::sum('price')), 2) }}</div>
                </div>

                <div class="card-body">

                    @foreach($invoices as $invoice)
                        <div class="d-flex py-2 border-bottom">
                            <div class="w-25">
                                <a href="{{ route('invoice.edit', $invoice->id) }}"><i class="far fa-edit"></i></a>
                                <a href="javascript:;" onclick="window.delete('{{ $invoice->id }}')"><i class="far fa-minus-circle"></i></a>
                                <div class="ml-2 d-inline">{{ date('d-m-Y', strtotime($invoice->date)) }}</div>
                            </div>
                            <div class="flex-grow-1 w-50">
                                <div class="d-inline"><a href="{{ route('invoice.edit', $invoice->id) }}">{{ $invoice->title }}</a>
                                    <div class="d-block text-muted">@if ($invoice->nr) Factuurnummer: {{ $invoice->nr }} - Debiteur: {{ $invoice->debtor->title }} @endif</div>
                                </div>
                            </div>
                            <div class="text-right flex-grow-1 h5 mt-2">
                                € {{ number_format($invoice->rules()->sum('price'), 2) }}

                                <a href="{{ route('invoice.download', $invoice->id) }}" class="ml-2"><i class="fas fa-file-pdf"></i></a>
                            </div>
                        </div>
                    @endforeach

                    <div class="d-flex py-2 mt-4">
                        <div class="flex-grow-1 h3">Totaal</div>
                        <div class="text-right w-25">
                            <span class="h3">€ {{ number_format((\App\InvoiceRule::sum('price')), 2) }}</span>
                        </div>
                    </div>

                </div>
            </div>

            {!! $invoices->links() !!}

        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="create">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" action="{{ route('invoice.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Factuur toevoegen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <script>
                        window.onload = function () {
                            $('.datepicker').datepicker({
                                maxDate: '+30d',
                                numberOfMonths: 2,
                                dateFormat: "yy-mm-dd",
                                showOtherMonths: true,
                                autoSize: true,
                                changeMonth: true,
                                changeYear: true,
                                onSelect: function(date){
                                    $('[name="date"]').val(date);
                                }
                            });
                        }
                    </script>
                    <div class="form-group">
                        <label>Datum factuur</label>
                        <div class="datepicker"></div>
                        <input type="hidden" name="date" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label>Debiteur</label>
                        <select name="debtor_id" class="form-control">
                            @foreach (\App\Debtor::orderBy('title', 'DESC')->get() as $debtor)
                                <option value="{{ $debtor->id }}">{{ $debtor->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Factuurnummer</label>
                        <input type="text" name="nr" value="{{ $invoice_nr }}" class="form-control"></h3>
                    </div>


                    <script>
                        function cloneRow() {

                            $('.b-row').clone().insertAfter(".rows").removeClass('b-row').addClass('mt-2');

                            return false;
                        }

                        function removeRow(object) {
                            $(object).closest('.row').remove();

                            return false;
                        }
                    </script>

                    <div class="d-flex justify-content-between align-bottom">
                        <h4 class="mt-4">Factuurregels</h4>
                        <a href="#" onclick="return cloneRow($(this))" class="mb-3 d-block">Voeg een regel toe</a>
                    </div>

                    <div class="row b-row">
                        <div class="col">
                            <input type="text" name="rules[title][]" value="" placeholder="Omschrijving" class="form-control">
                        </div>
                        <div class="col-2">
                            <input type="text" name=rules[qty][] value="1" placeholder="1" class="form-control">
                        </div>
                        <div class="col-3">
                            <input type="text" name="rules[price][]" value="" class="form-control" placeholder="0.00">
                        </div>
                    </div>

                    <div class="rows"></div>


                </div>
                <div class="modal-footer">
                    <input type="hidden" name="receipt_nr" value="{{ $invoice_nr }}">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Sluit</button>
                    <button type="submit" class="btn btn-primary">Factuur opslaan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    window.delete = function(id) {
        if (confirm('Factuur verwijderen?')) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "delete",
                url: "/invoice/"+id,
                dataType: "HTML",
                success: function (response) {
                    window.location.reload();
                }
            });
        }
    }
</script>

@endsection
