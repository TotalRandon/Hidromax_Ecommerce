@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('account.profile') }}">Minha conta</a></li>
                <li class="breadcrumb-item">configurações</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-11 ">
    <div class="container  mt-5">
        <div class="row">
            <div class="col-md-12">
                @include('front.account.common.message')
            </div>
            <div class="col-md-3">
                @include('front.account.common.sidebar')
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">
                            <a href="#" class="text-decoration-none" data-bs-toggle="collapse" data-bs-target="#dadosBasicos" aria-expanded="true" aria-controls="dadosBasicos">
                                Dados básicos
                            </a>
                        </h2>
                    </div>
                    <form action="" name="profileForm" id="profileForm">
                    <div id="dadosBasicos" class="collapse">
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">               
                                        <label for="name">Nome do usuário</label>
                                        <input value="{{ $user->name }}" type="text" name="name" id="name" placeholder="Seu nome" class="form-control">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">                                    
                                        <label for="phone">Telefone ou Celular</label>
                                        <input 
                                            value="{{ '(' . substr($user->phone, 0, 2) . ') ' . substr($user->phone, 2, 5) . '-' . substr($user->phone, 7) }}" 
                                            type="text" 
                                            name="phone" 
                                            id="phone" 
                                            placeholder="número de telefone ou celular" 
                                            class="form-control">
                                        <p></p>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="mb-3">            
                                        <label for="email">Email</label>
                                        <input value="{{ $user->email }}" type="text" name="email" id="email" placeholder="Seu email" class="form-control">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <button class="btn btn-dark">Atualizar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                

                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">
                            <a href="#" class="text-decoration-none" data-bs-toggle="collapse" data-bs-target="#enderecoCollapse" aria-expanded="true" aria-controls="enderecoCollapse">
                                Endereço
                            </a>
                        </h2>
                    </div>
                    <div id="enderecoCollapse" class="collapse">
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">               
                                        <label for="name">Nome</label>
                                        <input value="{{ !empty($address) ? $address->first_name : '' }}" name="first_name" id="first_name" placeholder="Seu nome" class="form-control">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">               
                                        <label for="surname">Sobrenome</label>
                                        <input value="{{ !empty($address) ? $address->last_name : '' }}" type="text" name="last_name" id="last_name" placeholder="Seu sobrenome" class="form-control">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">            
                                        <label for="email">Email</label>
                                        <input value="{{ !empty($address) ? $address->email : '' }}" type="text" name="email" id="email" placeholder="Seu email" class="form-control">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">                                    
                                        <label for="phone">Telefone ou Celular</label>
                                        <input value="{{ !empty($address) ? $address->mobile : '' }}" type="text" name="mobile" id="mobile" placeholder="Número de telefone ou celular" class="form-control">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">                                    
                                        <label for="address">Endereço</label>
                                        <input value="{{ !empty($address) ? $address->address : '' }}" type="text" name="address" id="address" placeholder="Seu endereço atual" class="form-control">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">                                    
                                        <label for="complement">Complemento</label>
                                        <input value="{{ !empty($address) ? $address->apartment : '' }}" type="text" name="apartment" id="apartment" placeholder="Exemplo: casa, ap 01 bloco A" class="form-control">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">                                    
                                        <label for="city">Cidade</label>
                                        <input value="{{ !empty($address) ? $address->city : '' }}" type="text" name="city" id="city" placeholder="Sua cidade" class="form-control">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">                                    
                                        <label for="state">Estado</label>
                                        <select name="state_id" id="state_id" class="form-control">
                                            <option value="">Selecione um Estado</option>
                                            @if($states->isNotEmpty())
                                                @foreach ($states as $state)
                                                    <option value="{{ $state->id }}" {{ (!empty($address) && $address->state_id == $state->id) ? 'selected' : '' }}>{{ $state->name }}</option>

                                                    {{-- <option {{ (!empty($address) && $address->state_id == $state->id) ? 'selected' : '' }} value="{{ $state->id }}">
                                                        {{ $state->name }}
                                                    </option> --}}
                                                @endforeach
                                            @endif
                                        </select>
                                        <p></p>
                                    </div>
                                </div>                                
                                <div class="col-md-4">
                                    <div class="mb-3">                                    
                                        <label for="zip">CEP</label>
                                        <input value="{{ !empty($address) ? $address->zip : '' }}" type="text" name="zip" id="zip" placeholder="Seu código CEP da rua" class="form-control">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <button class="btn btn-dark">Atualizar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
    <script>
        $('#addressForm').submit(function(event){
            event.preventDefault();

            $.ajax({
                url: '{{ route('account.updateAddress') }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response){
                    if (response.status == true) {

                        $('#addressForm #first_name').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        $('#addressForm #last_name').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        $('#addressForm #email').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        $('#addressForm #mobile').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        $('#addressForm #address').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        $('#addressForm #apartment').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        $('#addressForm #city').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        $('#addressForm #state_id').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        $('#addressForm #zip').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');


                        window.location.href = '{{ route('account.profile') }}';

                    } else {
                        var errors = response.errors;
                        if (errors.first_name) {
                            $('#addressForm #first_name').addClass('is-invalid').siblings('p').html(errors.first_name).addClass('invalid-feedback');
                        } else {
                            $('#addressForm #first_name').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        }

                        if (errors.last_name) {
                            $('#addressForm #last_name').addClass('is-invalid').siblings('p').html(errors.last_name).addClass('invalid-feedback');
                        } else {
                            $('#addressForm #last_name').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        }

                        if (errors.email) {
                            $('#addressForm #email').addClass('is-invalid').siblings('p').html(errors.email).addClass('invalid-feedback');
                        } else {
                            $('#addressForm #email').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        }

                        if (errors.mobile) {
                            $('#addressForm #mobile').addClass('is-invalid').siblings('p').html(errors.mobile).addClass('invalid-feedback');
                        } else {
                            $('#addressForm #mobile').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        }

                        if (errors.address) {
                            $('#addressForm #address').addClass('is-invalid').siblings('p').html(errors.address).addClass('invalid-feedback');
                        } else {
                            $('#addressForm #address').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        }

                        if (errors.apartment) {
                            $('#addressForm #apartment').addClass('is-invalid').siblings('p').html(errors.apartment).addClass('invalid-feedback');
                        } else {
                            $('#addressForm #apartment').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        }

                        if (errors.city) {
                            $('#addressForm #city').addClass('is-invalid').siblings('p').html(errors.city).addClass('invalid-feedback');
                        } else {
                            $('#addressForm #city').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        }

                        if (errors.state_id) {
                            $('#addressForm #state_id').addClass('is-invalid').siblings('p').html(errors.state_id).addClass('invalid-feedback');
                        } else {
                            $('#addressForm #state_id').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        }

                        if (errors.zip) {
                            $('#addressForm #zip').addClass('is-invalid').siblings('p').html(errors.zip).addClass('invalid-feedback');
                        } else {
                            $('#addressForm #zip').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        }
                    }
                }
            });
        });

        $('#profileForm').submit(function(event){
            event.preventDefault();

            $.ajax({
                url: '{{ route('account.updateProfile') }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response){
                    if (response.status == true) {

                        $('#profileForm #name').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        $('#profileForm #phone').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        $('#profileForm #email').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');

                        window.location.href = '{{ route('account.profile') }}';

                    } else {
                        var errors = response.errors;
                        if (errors.name) {
                            $('#profileForm #name').addClass('is-invalid').siblings('p').html(errors.name).addClass('invalid-feedback');
                        } else {
                            $('#profileForm #name').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        }

                        if (errors.phone) {
                            $('#profileForm #phone').addClass('is-invalid').siblings('p').html(errors.phone).addClass('invalid-feedback');
                        } else {
                            $('#profileForm #phone').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        }

                        if (errors.email) {
                            $('#profileForm #email').addClass('is-invalid').siblings('p').html(errors.email).addClass('invalid-feedback');
                        } else {
                            $('#profileForm #email').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        }
                    }
                }
            });
        });

    </script>
@endsection