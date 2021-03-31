@extends('layouts.app')

@section('content')
<form method="post" action="{{ route('invoice.update', $invoice) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="d-flex mb-4">
                    <div class="ml-auto">
                        <button type="submit" class="btn btn-primary">Factuur opslaan</button>
                    </div>
                </div>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">Factuur bewerken</div>

                    <div class="card-body">

                        <script>
                            window.onload = function () {
                                var parsedDate = $.datepicker.parseDate('yy-mm-dd', '{{ $invoice->date }}');

                                $('.datepicker').datepicker({
                                    maxDate: '+30d',
                                    numberOfMonths: 3,
                                    dateFormat: "yy-mm-dd",
                                    onSelect: function(date){
                                        $('[name="date"]').val(date);
                                    }
                                });

                                $('.datepicker').datepicker('setDate', parsedDate);
                            }
                        </script>

                        <div class="form-group">
                            <label>Datum</label>
                            <div class="datepicker"></div>
                            <input type="hidden" name="date" value="{{ $invoice->date }}">
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Debiteur</label>
                                    <select name="debtor_id" class="form-control">
                                        @foreach($debtors as $debtor)
                                            <option {{ $invoice->debtor_id == $debtor->id ? 'selected' : '' }} value="{{ $debtor->id }}">{{ $debtor->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Factuurnummer</label>
                            <input type="text" name="nr" value="{{ $invoice->nr }}" class="form-control" required>
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

                        <div class="rows">
                            @foreach ($invoice->rules as $rule)
                                <div class="row mb-2">
                                    <div class="col">
                                        <input type="text" name="rules[title][]" value="{{ $rule->pivot->title }}" placeholder="Omschrijving" class="form-control">
                                    </div>
                                    <div class="col-2">
                                        <input type="text" name=rules[qty][] value="{{ $rule->pivot->qty }}" placeholder="1" class="form-control">
                                    </div>
                                    <div class="col-3">
                                        <input type="text" name="rules[price][]" value="{{ $rule->pivot->price }}" class="form-control" placeholder="0.00">
                                    </div>
                                </div>
                            @endforeach
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
