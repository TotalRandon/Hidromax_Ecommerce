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
            <p>Recebemos seu pedido #{{ $order->id }}. Nossa equipe vai analisá-lo e entrará em contato para combinar o pagamento e os próximos passos.</p>
            <p>O pedido ainda não confirma o pagamento nem o início da entrega.</p>
        </div>
    </section>
@endsection
