@extends('layouts.app')

@section('content')
<form method="post" action="{{ route('debtor.update', $debtor) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="d-flex mb-4">
                    <div class="ml-auto">
                        <button type="submit" class="btn btn-primary">Debiteur opslaan</button>
                    </div>
                </div>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">Debiteur bewerken</div>

                    <div class="card-body">

                        <div class="form-group">
                            <label>Naam</label>
                            <input type="text" name="title" value="{{ $debtor->title }}" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Adres</label>
                            <textarea name="address" class="form-control">{{ $debtor->address }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
