<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 16px">

    @if ($mailData['userType'] == 'customer')
        <h1>Obrigado por fazer o pedido</h1>
        <h2>Segue o código do pedido: #{{ $mailData['order']->id }}</h2>
    @else
        <h1>Você recebeu um novo pedido:</h1>
        <h2>ID Pedido: #{{ $mailData['order']->id }}</h2>
    @endif

    <h2>Endereço de Entrega</h2>
    <address>
        <strong>{{ $mailData['order']->first_name.' '.$mailData['order']->last_name }}</strong><br>
        {{ $mailData['order']->address }}<br>
        {{ $mailData['order']->city }}, {{ $mailData['order']->zip }}, {{ getStateInfo($mailData['order']->state_id)->name }}<br>
        Telefone: {{ $mailData['order']->mobile }}<br>
        Email: {{ $mailData['order']->email }}
    </address>

    <h2>Produtos</h2>
    <table cellpadding='3' cellspacing='3' border="0" width='700'>
        <thead>
            <tr style="background-color: #ccc">
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
                <th colspan="3" align='right'>Subtotal:</th>
                <td>R$ {{ number_format($mailData['order']->subtotal, 2, ',', '.') }}</td>
            </tr>
            
            <tr>
                <th colspan="3" align='right'>Envio:</th>
                <td>R$ {{ number_format($mailData['order']->shipping, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <th colspan="3" align='right'>Total:</th>
                <td>R$ {{ number_format($mailData['order']->grand_total, 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>	
</body>
</html>