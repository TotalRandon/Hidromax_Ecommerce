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
                        @foreach (Cart::content() as $item)
                        <div class="d-flex justify-content-between align-items-center pb-2">
                            <div class="h6 mb-0 text-left flex-grow-1">
                                <span class="mr-1">{{ $item->name }}</span>
                                <span class="mx-1">X</span>
                                <span class="ml-1">{{ $item->qty }}</span>
                            </div>
                            <div class="h6 mb-0 text-right">R$ {{ number_format($item->price * $item->qty, 2, ',', '.') }}</div>
                        </div>
                        @endforeach
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-3">
                            <div class="h6 mb-0 text-left flex-grow-1"><strong>Subtotal</strong></div>
                            <div class="h6 mb-0 text-right"><strong>R$ {{ Cart::subtotal() }}</strong></div>
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
                <div class="card payment-form ">   
                    <h3 class="card-title h5 mb-3">Metodo de pagamento</h3>
                    <div>
                        <input type="radio" name="payment_method" value="cod" id="payment_method_1">
                        <label for="payment_method_1" class="form-check-label">Pagamento na entrega</label>
                    <div>
                        <input type="radio" name="payment_method" value="pix" id="payment_method_3">
                        <label for="payment_method_3" class="form-check-label">Pix</label>
                    </div>
                    <div>
                        <input type="radio" name="payment_method" value="Boleto" id="payment_method_4">
                        <label for="payment_method_4" class="form-check-label">Boleto Bancario</label>
                    </div>
                    <div>
                        <input type="radio" name="payment_method" value="stripe" id="payment_method_2">
                        <label for="payment_method_2" class="form-check-label">Cartão de credito</label>
                    </div>

                    <div class="card_body p-0 d-none mb-2" id="cod-payment-form">
                        <div class="mb-3">
                            <p>Pagamento deve ser pago diretamente na entrega.</p>
                        </div>
                        <div class="pt-4">
                            <button type="submit" class="btn-dark btn btn-block w-100">Finalizar</button>
                        </div>
                    </div>

                    <div class="card_body p-0 d-none mb-2" id="pix-payment-form">
                        <div class="mb-3">
                            <p>Ao escolher o método pix, você será redirecionado a uma página com QRCode para efetuar o pagamento.</p>
                        </div>
                        <div class="pt-4">
                            <button type="submit" class="btn-dark btn btn-block w-100">Finalizar</button>
                        </div>
                    </div>

                    <div class="card_body p-0 d-none mb-2" id="boleto-payment-form">
                        <div class="mb-3">
                            <p>Ao escolher o método boleto, você será redirecionado a uma página com arquivo de boleto bancario para efetuar o pagamento.</p>
                        </div>
                        <div class="pt-4">
                            <button type="submit" class="btn-dark btn btn-block w-100">Finalizar</button>
                        </div>
                    </div>
                    
                    <div class="card-body p-0 d-none mb-2" id="card-payment-form">
                        <div class="mb-3">
                            <label for="card_number" class="mb-2">Número do cartão</label>
                            <input type="text" name="card_number" id="card_number" placeholder="Número do cartão válido" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="card_name" class="mb-2">Nome impresso no cartão</label>
                            <input type="text" name="card_name" id="card_name" placeholder="Nome impresso no cartão" class="form-control">
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="expiry_date" class="mb-2">Validade</label>
                                <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YYYY" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="cvv" class="mb-2">CVV</label>
                                <input type="text" name="cvv" id="cvv" placeholder="123" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="cpf_cnpj" class="mb-2">CPF/CNPJ do titular</label>
                                <input type="text" name="cpf_cnpj" id="cpf_cnpj" placeholder="CPF/CNPJ do titular" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="birth_date" class="mb-2">Data de nascimento</label>
                            <input type="text" name="birth_date" id="birth_date" placeholder="DD/MM/YYYY" class="form-control">
                        </div>
                        <div class="pt-4">
                            <button type="submit" class="btn-dark btn btn-block w-100">Finalizar</button>
                        </div>
                    </div>                        
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
        function hideAllPaymentForms() {
            $("#cod-payment-form").addClass('d-none');
            $("#card-payment-form").addClass('d-none');
            $("#pix-payment-form").addClass('d-none');
            $("#boleto-payment-form").addClass('d-none');
            // Adicione aqui outros formulários de pagamento, se houver.
        }

        // Mostrar/ocultar o formulário de pagamento
        $("#payment_method_1").click(function() {
            hideAllPaymentForms();
            if ($(this).is(":checked")) {
                $("#cod-payment-form").removeClass('d-none');
            }
        }); 

        $("#payment_method_2").click(function() {
            hideAllPaymentForms();
            if ($(this).is(":checked")) {
                $("#card-payment-form").removeClass('d-none');
            }
        });

        $("#payment_method_3").click(function() {
            hideAllPaymentForms();
            if ($(this).is(":checked")) {
                $("#pix-payment-form").removeClass('d-none');
            }
        });

        $("#payment_method_4").click(function() {
            hideAllPaymentForms();
            if ($(this).is(":checked")) {
                $("#boleto-payment-form").removeClass('d-none');
            }
        });

        // Submissão do formulário de pedido
        $("#orderForm").submit(function(event) {
            event.preventDefault();
            $('button[type="submit"]').prop('disabled', true);

            $.ajax({
                url: '{{ route("front.processCheckout") }}',
                type: 'post',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    $("#orderForm .form-control").removeClass('is-invalid');
                    $("#orderForm .invalid-feedback").html('');

                    var errors = response.errors;
                    $('button[type="submit"]').prop('disabled', false);

                    if (response.status == false) {
                        if (errors.first_name) {
                            $("#first_name").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.first_name)
                        }
                        if (errors.last_name) {
                            $("#last_name").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.last_name)
                        }
                        if (errors.email) {
                            $("#email").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.email)
                        }
                        if (errors.address) {
                            $("#address").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.address)
                        }
                        if (errors.apartment) {
                            $("#apartment").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.apartment)
                        }
                        if (errors.city) {
                            $("#city").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.city)
                        }
                        if (errors.state_id) {
                            $("#state_id").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.state_id)
                        }
                        if (errors.zip) {
                            $("#zip").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.zip)
                        }
                        if (errors.mobile) {
                            $("#mobile").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.mobile)
                        }
                    } else {
                        window.location.href = response.redirect_url;                    }
                }
            });
        });

        // Atualizar o resumo do pedido ao mudar o estado
        $("#state_id").change(function() {
            var stateId = $(this).val();
            if (stateId) {
                $.ajax({
                    url: '{{ route("front.getOrderSummery") }}',
                    type: 'post',
                    data: {
                        _token: '{{ csrf_token() }}', // Inclui o token CSRF
                        state_id: stateId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == true) {
                            $("#shippingAmount").html('R$ ' + response.shippingCharge);
                            $("#grandTotal").html('R$ ' + response.grandTotal);
                        }
                    }
                });
            } else {
                $("#shippingAmount").html('R$ 0,00');
                $("#grandTotal").html('R$ ' + '{{ Cart::subtotal() }}');
            }
        });
    });
</script>

@endsection
