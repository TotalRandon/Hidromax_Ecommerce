@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Pedido: #{{ $order->id }}</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('orders.index') }}" class="btn btn-primary">Voltar</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                @include('admin.message')
                <div class="card">
                    <div class="card-header pt-3">
                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                            @php
                                function formatZip($zip) {
                                    return substr($zip, 0, 5) . '-' . substr($zip, 5, 3);
                                }
                            @endphp
                            <h1 class="h5 mb-3">Endereço de Entrega</h1>
                            <address>
                                <strong>{{ $order->first_name.' '.$order->last_name }}</strong><br>
                                {{ $order->address }}<br>
                                {{ $order->city }}, {{ formatZip($order->zip) }}, {{ $order->stateName }}<br>
                                Telefone: {{ $order->mobile }}<br>
                                Email: {{ $order->email }}<br>
                                @if (!empty($order->notes))
                                    Obs: {{ $order->notes }}
                                @else
                                    Obs: n/a
                                @endif
                            </address>
                            <strong>Data de Envio: </strong>
                            @if (!empty($order->shipped_date))
                                {{ \Carbon\Carbon::parse($order->shipped_date)->locale('pt-BR')->translatedFormat('d M, Y H:i') }}
                            @else
                                n/a  
                                @endif
                            </div>
                            
                            <div class="col-sm-4 invoice-col">
                                <b>ID Pedido:</b> {{ $order->id }}<br>
                                <b>Total:</b> R${{ number_format($order->grand_total, 2, ',', '.') }}<br>
                                <b>Aprovação:</b>
                                    @if ($order->approval_status == 'awaiting')
                                        <span class="badge bg-warning text-dark">Aguardando aprovação</span>
                                    @elseif ($order->approval_status == 'approved')
                                        <span class="badge bg-success">Aprovado</span>
                                    @else
                                        <span class="badge bg-danger">Rejeitado</span>
                                    @endif
                                <br>
                                <b>Entrega:</b>
                                    @if ($order->status == 'pending')
                                        <span class="badge bg-secondary">Aguardando separação</span>
                                    @elseif ($order->status == 'shipped')
                                        <span class="badge bg-info">Enviado</span>
                                    @elseif ($order->status == 'cancelled')
                                        <span class="badge bg-danger">Cancelado</span>
                                    @else
                                        <span class="badge bg-success">Entregue</span>
                                    @endif
                                <br>
                                <b>Pagamento:</b> {{ $order->payment_status == 'paid' ? 'Pago' : 'Não pago' }}<br>
                                @if ($order->approver)
                                    <b>Decisão por:</b> {{ $order->approver->name }}<br>
                                    <b>Decisão em:</b> {{ optional($order->approval_decided_at)->format('d/m/Y H:i') }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-3">								
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Produtos</th>
                                    <th width="100">Preço</th>
                                    <th width="100">Quantidade</th>                                        
                                    <th width="100">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orderItems as $items)
                                <tr>
                                    <td>{{ $items->name }}</td>
                                    <td>R$ {{ number_format($items->price, 2, ',', '.') }}</td>                                        
                                    <td>{{ $items->qty }}</td>
                                    <td>R$ {{ number_format($items->total, 2, ',', '.') }}</td>
                                </tr>
                                @endforeach
                                
                                <tr>
                                    <th colspan="3" class="text-right">Subtotal:</th>
                                    <td>R$ {{ number_format($order->subtotal, 2, ',', '.') }}</td>
                                </tr>
                                
                                <tr>
                                    <th colspan="3" class="text-right">Envio:</th>
                                    <td>R$ {{ number_format($order->shipping, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-right">Total:</th>
                                    <td>R$ {{ number_format($order->grand_total, 2, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>								
                    </div>                            
                </div>
            </div>
            <div class="col-md-3">
                    @if ($order->approval_status == 'awaiting')
                    <div class="card">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Aprovação do superior</h2>
                            <p>O pedido não poderá seguir para separação ou entrega até ser aprovado.</p>
                            <form method="post" id="approvalDecisionForm">
                                @csrf
                                <button type="button" class="btn btn-success approval-decision" data-decision="approved">Aprovar pedido</button>
                                <button type="button" class="btn btn-outline-danger approval-decision" data-decision="rejected">Rejeitar</button>
                            </form>
                        </div>
                    </div>
                    @endif
                    <div class="card">
                    <form action="" method="post" name="changeOrderStatusForm" id="changeOrderStatusForm">
                        @csrf
                        <div class="card-body">
                            <h2 class="h4 mb-3">Etapa da entrega</h2>
                            <div class="mb-3">
                                <select name="status" id="status" class="form-control">
                                    <option value="pending" {{ ($order->status == 'pending') ? 'selected' : ''}}>Aguardando separação</option>
                                    <option value="shipped" {{ ($order->status == 'shipped') ? 'selected' : '' }} {{ $order->approval_status != 'approved' ? 'disabled' : '' }}>Enviado</option>
                                    <option value="delivered" {{ ($order->status == 'delivered') ? 'selected' : '' }} {{ $order->approval_status != 'approved' ? 'disabled' : '' }}>Entregue</option>
                                    <option value="cancelled" {{ ($order->status == 'cancelled') ? 'selected' : ''}}>Cancelado</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="payment_status">Situação do pagamento (registro manual)</label>
                                <select name="payment_status" id="payment_status" class="form-control">
                                    <option value="not paid" {{ $order->payment_status == 'not paid' ? 'selected' : '' }}>Não pago</option>
                                    <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }} {{ $order->approval_status != 'approved' || $order->status == 'cancelled' ? 'disabled' : '' }}>Pago</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="">Data de envio</label>
                                <input placeholder="Data de envio" value="{{ $order->shipped_date }}" type="text" name="shipped_date" id="shipped_date" class="form-control">
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary">Atualizar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="" method="post" name="sendInvoiceEmail" id="sendInvoiceEmail">
                            <h2 class="h4 mb-3">Enviar resumo do pedido por e-mail</h2>
                            <div class="mb-3">
                                <select name="userType" id="userType" class="form-control">
                                    <option value="customer">Cliente</option>                                                
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary">Enviar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJs')
<script>
    $(document).ready(function(){
        $('#shipped_date').datetimepicker({
            format: 'Y-m-d H:i:s'
        });
    });

    $('#changeOrderStatusForm').submit(function(event){
        event.preventDefault();

        if(confirm('Você tem certeza que deseja mudar o estatus do pedido ?')) {
            $.ajax({
            url: '{{ route('orders.changeOrderStatus', $order->id) }}',
            type: 'post',
            data: $(this).serializeArray(),
            dataType: 'json',
            success: function(response){
                window.location.href='{{ route('orders.detail', $order->id) }}';
            },
            error: function(xhr){ alert((xhr.responseJSON && xhr.responseJSON.message) || 'Não foi possível atualizar o pedido.'); }
        });
        }

        
    });

    $('.approval-decision').on('click', function(){
        const decision = $(this).data('decision');
        const label = decision === 'approved' ? 'aprovar' : 'rejeitar';
        if (!confirm('Confirma ' + label + ' este pedido?')) return;

        $.ajax({
            url: '{{ route('orders.decideApproval', $order->id) }}',
            type: 'post',
            data: $('#approvalDecisionForm').serialize() + '&decision=' + encodeURIComponent(decision),
            dataType: 'json',
            success: function(){ window.location.reload(); },
            error: function(xhr){ alert((xhr.responseJSON && xhr.responseJSON.message) || 'Não foi possível registrar a decisão.'); }
        });
    });

    $('#sendInvoiceEmail').submit(function(event){
        event.preventDefault();

        if(confirm('Você tem certeza que deseja enviar o email do pedido ?')) {
            $.ajax({
            url: '{{ route('orders.sendInvoiceEmail', $order->id) }}',
            type: 'post',
            data: $(this).serializeArray(),
            dataType: 'json',
            success: function(response){
                window.location.href='{{ route('orders.detail', $order->id) }}';
            }
        });
        }
    });
    
</script>
@endsection
