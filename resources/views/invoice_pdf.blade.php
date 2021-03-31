<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Factuur F{{ $invoice->nr }}</title>
    <style>
        * {
            font-family:Arial, Helvetica, sans-serif;
            font-size: 14px;
        }
        header {
            display: flex;
            justify-content: space-between
        }
        .logo {
            position: absolute; left: -40px;
            width: 180px;
        }
        footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 100px;

            /** Extra personal styles **/
            text-align: center;
            /* line-height: 35px; */
        }
        .border {border-top: #CCC solid 1px; height: 1px;}
        th {text-align: left;}
        tr.row td {
            padding-bottom: 10px;
        }
    </style>
</head>
<body>
    <header>
        <table width="100%" style="margin-bottom: 50px;">
            <tr>
                <td><img class="logo" src="{{ asset('images/driestroom.png') }}"></td>
                <td style="text-align: right; color: #777; font-size: 13px;">
                    <br>
                    Driestroomhuis Leef!<br>
                    Galjoenstraat 81<br>
                    1784 RC Den Helder<br>
                    info@driestroomhuisleef.nl
                </td>
            </tr>
        </table>
    </header>
    <div style="margin-top: 150px; margin-bottom: 80px;">
        {{ $invoice->debtor->title }}<br>
        {!! nl2br($invoice->debtor->address) !!}
    </div>

    <table>
        <tr>
            <td>Factuurnummer:</td><td>F{{ $invoice->nr }}</td>
        </tr>
        <tr>
            <td>Factuurdatum:</td><td>{{ $invoice->date->format('d-m-Y') }}</td>
        </tr>
    </table>

    <br>
    <div class="border"></div>
    <br>

    <table width="100%">
        <tr>
            <th>Omschrijving</th>
            <th>Aantal</th>
            <th style="text-align: right;">Prijs</th>
        </tr>
        <tr>
            <td colspan="3"><br></td>
        </tr>
        @foreach ($invoice->rules as $rule)
            <tr class="row">
                <td>{{ $rule->pivot->title }}</td>
                <td>{{ $rule->pivot->qty }}</td>
                <td style="text-align: right;">€ {{ number_format($rule->pivot->price, 2, ',', '.') }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="3"><br></td>
        </tr>
        <tr style="font-weight: bold;">
            <td colspan="2" style="text-align: right;">Totaal</td>
            <td style="text-align: right;">€ {{ number_format($invoice->rules()->sum('price'), 2, ',', '.') }}</td>
        </tr>
    </table>

    <footer>
        Wij verzoeken u vriendelijk het bedrag van <strong>€ {{ number_format($invoice->rules()->sum('price'), 2, ',', '.') }}</strong> binnen 30 dagen over te maken naar<br>
        <strong>NL06 TRIO 0788 9545 71</strong> t.n.v. Driestroomhuis Leef onder vermelding van het factuurnummer <strong>F{{ $invoice->nr }}</strong>
        <br>
        <br>
        <br>
        <i style="font-size: 12px;">KvK nummer: 73895504 | e-mail: info@driestroomhuisleef.nl | website: driestroomhuisleef.nl</i>
    </footer>
</body>
</html>
