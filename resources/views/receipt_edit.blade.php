@extends('layouts.app')

@section('content')
<form method="post" action="{{ route('receipt.update', $receipt) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <input type="hidden" name="referrer" value="{{ request()->headers->get('referer') }}">

    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="d-flex mb-4">
                    <div class="ml-auto">
                        <button type="submit" class="btn btn-primary">Bonnetje opslaan</button>
                    </div>
                </div>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">Bonnetje bewerken</div>

                    <div class="card-body">

                        <script>
                            window.onload = function () {
                                var parsedDate = $.datepicker.parseDate('yy-mm-dd', '{{ $receipt->date }}');

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
                            <label>Datum bonnetje</label>
                            <div class="datepicker"></div>
                            <input type="hidden" name="date" value="{{ $receipt->date }}">
                        </div>
                        <div class="form-group">
                            <label>Bonnummer</label>
                            <input type="text" name="receipt_nr" value="{{ $receipt->receipt_nr ? $receipt->receipt_nr : $receipt_nr }}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Bon</label>
                            <div class="custom-file">
                                <input type="file" name="receipt_file" class="custom-file-input" id="validatedCustomFile">
                                <label class="custom-file-label" for="validatedCustomFile">Upload bonnetje...</label>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Groep</label>
                            <select name="group_id" class="form-control">
                                @foreach($groups as $group)
                                    <option {{ $receipt->group_id == $group->id ? 'selected' : '' }} value="{{ $group->id }}">{{ $group->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Omschrijving bonnetje</label>
                            <input type="text" name="title" value="{{ $receipt->title }}" class="form-control" required>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Prijs</label>
                                    <input type="text" name="price" class="form-control" required placeholder="0.00" value="{{ $receipt->price }}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Betaald vanuit</label>
                                    <select name="paid_out" class="form-control">
                                        <option value="private">Privé rekening</option>
                                        <option value="business" {{ $receipt->paid_out == 'business' ? 'selected' : '' }}>Zakelijke rekening</option>
                                    </select>
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
