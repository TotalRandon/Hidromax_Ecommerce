@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                <li class="breadcrumb-item"><a class="white-text" href="#">Shop</a></li>
                <li class="breadcrumb-item">Checkout</li>
            </ol>
        </div>
    </div>
</section>

<section class="section-9 pt-4">
    <div class="container">
        <form action="" id="orderForm" name="orderForm" method="POST">
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
                <div class="card cart-summery">
                    <div class="card-body">

                        @foreach (Cart::content() as $item)
                        <div class="d-flex justify-content-between pb-2">
                            <div class="h6">{{ $item->name }} X {{ $item->qty }}</div>
                            <div class="h6">R$ {{ $item->price * $item->qty }}</div>
                        </div>
                        
                        @endforeach
                        
                        <div class="d-flex justify-content-between summery-end">
                            <div class="h6"><strong>Subtotal</strong></div>
                            <div class="h6"><strong>R$ {{ Cart::subtotal() }}</strong></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <div class="h6"><strong>Entrega</strong></div>
                            <div class="h6"><strong>R$ 0,00</strong></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2 summery-end">
                            <div class="h5"><strong>Total</strong></div>
                            <div class="h5"><strong>R$ {{ Cart::subtotal() }}</strong></div>
                        </div>                            
                    </div>
                </div>   
                
                <div class="card payment-form ">   
                    <h3 class="card-title h5 mb-3">Metodo de pagamento</h3>

                    <div>
                        <input checked type="radio" name="payment_method" value="cod" id="payment_method_1">
                        <label for="payment_method_1" class="form-check-label">Pagamento na entrega</label>
                    </div>

                    <div>
                        <input type="radio" name="payment_method" value="stripe" id="payment_method_2">
                        <label for="payment_method_2" class="form-check-label">Stripe</label>
                    </div>
                    
                    <div class="card-body p-0 d-none mb-2" id="card-payment-form">
                        <div class="mb-3">
                            <label for="card_number" class="mb-2">Card Number</label>
                            <input type="text" name="card_number" id="card_number" placeholder="Valid Card Number" class="form-control">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="expiry_date" class="mb-2">Expiry Date</label>
                                <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YYYY" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="cvv" class="mb-2">CVV Code</label>
                                <input type="text" name="cvv" id="cvv" placeholder="123" class="form-control">
                            </div>
                        </div>
                        <div class="pt-4">
                            <button type="submit" class="btn-dark btn btn-block w-100">Pay Now</button>
                        </div>
                    </div>                        
                </div>
 
                <!-- CREDIT CARD FORM ENDS HERE -->
                
            </div>
        </div>
        </form>
    </div>
</section>
@endsection

@section('customJs')
    <script>
        $("#payment_method_1").click(function(){
            if($(this).is(":checked") == true) {
                $("#card-payment-form").addClass('d-none');
            }
        });

        $("#payment_method_2").click(function(){
            if($(this).is(":checked") == true) {
                $("#card-payment-form").removeClass('d-none');
            }
        });

        $("#orderForm").submit(function(event){
            event.preventDefault();

            $('button[type="submit"]').prop('disabled', true);

            $.ajax({
                url: '{{ route("front.processCheckout") }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response){
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

                        if (errors.state) {
                            $("#state").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.state)
                        }

                        if (errors.zip) {
                            $("#zip").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.zip)
                        }

                        if (errors.mobile) {
                            $("#mobile").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.mobile)
                        }
                    } else {
                        window.location.href="{{ url('/thanks/') }}/" + response.orderId;
                    }
                }
            });
        }); 
    </script>
@endsection
