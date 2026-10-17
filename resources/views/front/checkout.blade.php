@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop') }}">Loja</a></li>
                <li class="breadcrumb-item">Checkout</li>
            </ol>
        </div>
    </div>
</section>

<section class="section-9 pt-4">
    <div class="container">
        <form action="" id="orderForm" name="orderForm" method="POST">
        @csrf <!-- Adicionando CSRF Token -->
        <div class="row">
            <div class="col-md-8">
                <div class="sub-title">
                    <h2>Endereço de entrega</h2>
                </div>
                <div class="card shadow-lg border-0">
                    <div class="card-body checkout-form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Nome" value="{{ (!empty($customerAddress)) ? $customerAddress->first_name : ''}}">
                                    <p></p>
                                </div>            
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Sobrenome" value="{{ (!empty($customerAddress)) ? $customerAddress->last_name : ''}}">
                                    <p></p>
                                </div>            
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="text" name="email" id="email" class="form-control" placeholder="Email" value="{{ (!empty($customerAddress)) ? $customerAddress->email : ''}}">
                                    <p></p>
                                </div>            
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <textarea name="address" id="address" cols="30" rows="3" placeholder="Endereço" class="form-control">{{ (!empty($customerAddress)) ? $customerAddress->address : '' }}</textarea>
                                    <p></p>
                                </div>            
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="text" name="apartment" id="apartment" class="form-control" placeholder="Complento, apartamento 1 bloco 1, casa, etc. (opcional)" value="{{ (!empty($customerAddress)) ? $customerAddress->apartment : '' }}">
                                    <p></p>
                                </div>            
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <input type="text" name="city" id="city" class="form-control" placeholder="Cidade" value="{{ (!empty($customerAddress)) ? $customerAddress->city : '' }}">
                                    <p></p>
                                    <small class="form-text text-muted">Entrega gratuita em Americana, Nova Odessa, Piracicaba, Limeira, Sumaré, Monte Mor e Iracemápolis (SP).</small>
                                </div>            
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <select name="state_id" id="state_id" class="form-control">
                                        <option value="">Selecione um estado</option>
                                        @if ($states->isNotEmpty())
                                            @foreach ($states as $state)
                                                <option value="{{ $state->id }}" {{ (!empty($customerAddress) && $customerAddress->state_id == $state->id) ? 'selected' : '' }}>{{ $state->name }}</option>
                                            @endforeach                                    
                                        @endif
                                    </select>
                                    <p></p>                                    
                                </div>            
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <input type="text" name="zip" id="zip" class="form-control" placeholder="CEP" value="{{ (!empty($customerAddress)) ? $customerAddress->zip : '' }}">
                                    <p></p>
                                </div>            
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Numero de celular ou telefone" value="{{ (!empty($customerAddress)) ? $customerAddress->mobile : '' }}">
                                    <p></p>
                                </div>            
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <textarea name="order_notes" id="order_notes" cols="30" rows="2" placeholder="Notas (opcional)" class="form-control"></textarea>
                                </div>            
                            </div>
                        </div>
                    </div>
                </div>    
            </div>
            <div class="col-md-4">
                <div class="sub-title">
                    <h2>Resumo do pedido</h3>
                </div>                    
                <div class="card cart-summary">
                    <div class="card-body">
                        @foreach ($checkoutItems as $item)
                        <div class="d-flex justify-content-between align-items-center pb-2">
                            <div class="h6 mb-0 text-left flex-grow-1">
                                <span class="mr-1">{{ $item['name'] }}</span>
                                <span class="mx-1">X</span>
                                <span class="ml-1">{{ $item['qty'] }}</span>
                            </div>
                            <div class="h6 mb-0 text-right">R$ {{ $item['total'] }}</div>
                        </div>
                        @endforeach
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-3">
                            <div class="h6 mb-0 text-left flex-grow-1"><strong>Subtotal</strong></div>
                            <div class="h6 mb-0 text-right"><strong>R$ {{ number_format((float) $grandTotal, 2, ',', '.') }}</strong></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div class="h6 mb-0 text-left flex-grow-1"><strong>Entrega</strong></div>
                            <div class="h6 mb-0 text-right"><strong id="shippingAmount">R$ {{ number_format($totalShippingCharge, 2, ',', '.') }}</strong></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                            <div class="h5 mb-0 text-left flex-grow-1"><strong>Total</strong></div>
                            <div class="h5 mb-0 text-right"><strong id="grandTotal">R$ {{ number_format($grandTotal, 2, ',', '.') }}</strong></div>
                        </div>                            
                    </div>
                </div>
                <div class="card payment-form p-3">
                    <h3 class="card-title h5 mb-3">Pagamento</h3>
                    <p>Depois do envio, nossa equipe analisará o pedido e entrará em contato para combinar o pagamento e os próximos passos.</p>
                    <div id="deliveryMessage" class="small text-muted mb-3" role="status">Informe uma cidade atendida em São Paulo para confirmar a entrega gratuita.</div>
                    <div id="checkoutMessage" class="small text-danger mb-3" role="alert"></div>
                    <button type="submit" class="btn-dark btn btn-block w-100">Finalizar pedido</button>
                </div>
            </div>
        </div>
        </form>
    </div>
</section>
@endsection

@section('customJs')
<script>
    $(document).ready(function() {
        $("#orderForm").submit(function(event) {
            event.preventDefault();
            var submitButton = $(this).find('button[type="submit"]');
            $('#checkoutMessage').empty();
            submitButton.prop('disabled', true);

            $.ajax({
                url: '{{ route("front.processCheckout") }}',
                type: 'post',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    window.location.href = response.redirect_url;
                },
                error: function(xhr) {
                    submitButton.prop('disabled', false);
                    $("#orderForm .form-control").removeClass('is-invalid').siblings('p').empty().removeClass('invalid-feedback');
                    var response = xhr.responseJSON || {};
                    var errors = response.errors || {};
                    Object.keys(errors).forEach(function(field) {
                        var input = $('#orderForm [name="' + field + '"]');
                        input.addClass('is-invalid').siblings('p').addClass('invalid-feedback').text(errors[field][0]);
                    });
                    if (response.message && Object.keys(errors).length === 0) {
                        $('#checkoutMessage').text(response.message);
                    }
                }
            });
        });

        function updateDeliveryMessage() {
            $.ajax({
                url: '{{ route("front.getOrderSummery") }}',
                type: 'post',
                data: {
                    _token: '{{ csrf_token() }}',
                    state_id: $('#state_id').val(),
                    city: $('#city').val()
                },
                dataType: 'json',
                success: function(response) {
                    $('#shippingAmount').text('R$ ' + response.shippingCharge);
                    $('#grandTotal').text('R$ ' + response.grandTotal);
                    $('#deliveryMessage').text(response.deliveryMessage)
                        .toggleClass('text-danger', !response.deliveryAvailable)
                        .toggleClass('text-success', response.deliveryAvailable);
                }
            });
        }

        $('#state_id, #city').on('change blur', updateDeliveryMessage);
        if ($('#city').val()) {
            updateDeliveryMessage();
        }
        });
</script>

@endsection
