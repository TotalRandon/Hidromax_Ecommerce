@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('account.profile') }}">Minha conta</a></li>
                <li class="breadcrumb-item">Configurações</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-11 ">
    <div class="container  mt-5">
        <div class="row">
            <div class="col-md-3">
                @include('front.account.common.sidebar')
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">Meus pedidos</h2>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table">
                                <thead> 
                                    <tr>
                                        <th>Pedidos #</th>
                                        <th>Data da compra</th>
                                        <th>Estatus</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($orders->isNotEmpty())
                                        @foreach ($orders as $order)
                                        <tr>
                                            <td>
                                                <a href="{{ route('account.orderDetail', $order->id) }}">{{ $order->id }}</a>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($order->created_at)->locale('pt-BR')->translatedFormat('d M, Y') }}</td>
                                            <td>
                                                @if ($order->status == 'pending')
                                                    <span class="badge bg-danger">Pagamento pendente</span>
                                                @elseif ($order->status == 'shipped')
                                                    <span class="badge bg-info">Enviado</span>
                                                @else
                                                    <span class="badge bg-success">Entrege</span>
                                                @endif
                                            </td>
                                            <td>R$ {{ number_format($order->grand_total, 2, ',', '.') }}</td>
                                        </tr> 
                                        @endforeach 

                                        @else

                                        <tr>
                                            <td colspan="3">Pedidos não encontrados</td>
                                        </tr>

                                    @endif
                                                                          
                                </tbody>
                            </table>
                        </div>   
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection