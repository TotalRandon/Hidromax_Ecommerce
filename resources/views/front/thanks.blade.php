@extends('front.layouts.app')

@section('content')
    <section class="conteiner">
        <div class="col-md-12 text-center py-5">

            @if (Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            @endif
            <h1>Obrigado!</h1>
            <p>Seu pedido {{ $id }}</p>
        </div>
    </section>
@endsection