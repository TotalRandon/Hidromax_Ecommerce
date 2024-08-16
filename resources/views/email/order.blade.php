<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
        }
        .table thead th {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container my-4">
        @if ($mailData['userType'] == 'customer')
            <h1 class="display-4">Obrigado por fazer o pedido</h1>
            <h2 class="h4">Segue o código do pedido: #{{ $mailData['order']->id }}</h2>
        @else
            <h1 class="display-4">Você recebeu um novo pedido:</h1>
            <h2 class="h4">ID Pedido: #{{ $mailData['order']->id }}</h2>
        @endif

        <h2 class="h5 mt-4">Endereço de Entrega</h2>
        <address>
            @php
            function formatZip($zip) {
                return substr($zip, 0, 5) . '-' . substr($zip, 5, 3);
            }

            $stateName = \App\Models\States::find($mailData['order']->state_id)->name;
            @endphp
            <strong>{{ $mailData['order']->first_name . ' ' . $mailData['order']->last_name }}</strong><br>
            {{ $mailData['order']->address }}<br>
            {{ $mailData['order']->city }}, {{ formatZip($mailData['order']->zip) }}, {{ ($stateName) }} <br>
            Telefone: {{ $mailData['order']->mobile }}<br>
            Email: {{ $mailData['order']->email }}<br>
            Obs: {{ $mailData['order']->notes }}
        </address>

        <h2 class="h5 mt-4">Produtos</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Produtos</th>
                    <th>Preço</th>
                    <th>Quantidade</th>                                        
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mailData['order']->items as $items)
                <tr>
                    <td>{{ $items->name }}</td>
                    <td>R$ {{ number_format($items->price, 2, ',', '.') }}</td>                                        
                    <td>{{ $items->qty }}</td>
                    <td>R$ {{ number_format($items->total, 2, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr>
                    <th colspan="3" class="text-right">Subtotal:</th>
                    <td>R$ {{ number_format($mailData['order']->subtotal, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <th colspan="3" class="text-right">Envio:</th>
                    <td>R$ {{ number_format($mailData['order']->shipping, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <th colspan="3" class="text-right">Total:</th>
                    <td>R$ {{ number_format($mailData['order']->grand_total, 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
