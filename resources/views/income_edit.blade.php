@extends('layouts.app')

@section('content')
<form method="post" action="{{ route('income.update', $income) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="d-flex mb-4">
                    <div class="ml-auto">
                        <button type="submit" class="btn btn-primary">Inkomen opslaan</button>
                    </div>
                </div>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">Inkomen bewerken</div>

                    <div class="card-body">

                        <script>
                            window.onload = function () {
                                var parsedDate = $.datepicker.parseDate('yy-mm-dd', '{{ $income->date }}');

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
                            <input type="hidden" name="date" value="{{ $income->date }}">
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Factuur</label>
                                    <div class="custom-file">
                                        <input type="file" name="receipt_file" class="custom-file-input" id="validatedCustomFile">
                                        <label class="custom-file-label" for="validatedCustomFile">Upload factuur...</label>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Groep</label>
                                    <select name="group_id" class="form-control">
                                        @foreach($groups as $group)
                                            <option {{ $income->group_id == $group->id ? 'selected' : '' }} value="{{ $group->id }}">{{ $group->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Debiteur</label>
                                    <select name="debtor_id" class="form-control">
                                        @foreach($debtors as $debtor)
                                            <option {{ $income->debtor_id == $debtor->id ? 'selected' : '' }} value="{{ $debtor->id }}">{{ $debtor->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Omschrijving</label>
                            <input type="text" name="title" value="{{ $income->title }}" class="form-control" required>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Prijs</label>
                                    <input type="text" name="price" class="form-control" required placeholder="0.00" value="{{ $income->price }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
