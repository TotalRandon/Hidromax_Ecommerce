<!DOCTYPE html>
<html class="no-js" lang="pt-br" />
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Hidromax | Materiais para Construção</title>
	<meta name="description" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=no" />

	<meta name="HandheldFriendly" content="True" />
	<meta name="pinterest" content="nopin" />

	<meta property="og:locale" content="en_AU" />
	<meta property="og:type" content="website" />
	<meta property="fb:admins" content="" />
	<meta property="fb:app_id" content="" />
	<meta property="og:site_name" content="" />
	<meta property="og:title" content="" />
	<meta property="og:description" content="" />
	<meta property="og:url" content="" />
	<meta property="og:image" content="" />
	<meta property="og:image:type" content="image/jpeg" />
	<meta property="og:image:width" content="" />
	<meta property="og:image:height" content="" />
	<meta property="og:image:alt" content="" />

	<meta name="twitter:title" content="" />
	<meta name="twitter:site" content="" />
	<meta name="twitter:description" content="" />
	<meta name="twitter:image" content="" />
	<meta name="twitter:image:alt" content="" />
	<meta name="twitter:card" content="summary_large_image" />
	

	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/slick.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/slick-theme.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/ion.rangeSlider.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/style.css') }}" />

	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;500&family=Raleway:ital,wght@0,400;0,600;0,800;1,200&family=Roboto+Condensed:wght@400;700&family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">

	<!-- Fav Icon -->
	<link rel="shortcut icon" type="image/x-icon" href="{{ asset('front-assets/images/favicon.png') }}"/>

	<meta name="csrf-token" content="{{ csrf_token() }}">

	<style>
        .top-info-bar {
            font-size: 14px;
            padding: 10px 0;
        }
        .top-info-bar a {
            margin-right: 15px;
            text-decoration: none;
        }
        .top-info-bar a:hover {
            text-decoration: underline;
        }
        .custom-logo {
            max-width: 200px; /* Ajuste conforme necessário */
            height: auto;
        }
        .search-form {
            width: 100%;
            max-width: 400px; /* Ajuste conforme necessário */
        }
    </style>

</head>
<body data-instant-intensity="mousedown">

	<div class="top-info-bar bg-dark">
		<div class="container">
			<div class="row align-items-center justify-content-between text-light">
				<div class="col-12 d-md-none">
					<a href="https://wa.me/+5519984177899" class="text-white d-block">Compre ou faça seu orçamento pelo WhatsApp (19) 98417-7899</a>
				</div>
				<div class="col-md-6 d-none d-md-block">
					<a href="https://g.co/kgs/cfvoxGV" class="text-white">LOJA | SBO</a>
					<a href="https://wa.me/+5519984177899" class="text-white">Compre ou faça seu orçamento pelo WhatsApp (19) 98417-7899</a>
				</div>
				<div class="col-md-6 d-none d-md-block text-end">
					<a href="#" class="text-white">Dicas de segurança</a>
					<a href="#" class="text-white">Precisa de ajuda?</a>
				</div>
			</div>
		</div>
	</div>

	<div class="top-header" style="background-color: #091442; padding: 10px 0;">
		<div class="container">
			<div class="row align-items-center justify-content-between">
				<!-- Logotipo -->
				<div class="col-lg-2 col-3 text-left">
					<a href="{{ route('front.home') }}" class="text-decoration-none">
						<img src="{{ asset('front-assets/images/Hidromax_logo.png') }}" alt="Hidromax Logo" class="img-fluid" style="max-width: 130px;">
					</a>
				</div>
				
				<!-- Campo de busca -->
				<div class="col-lg-7 col-6">
					<form class="search-form">
						<div class="input-group">
							<input type="text" placeholder="O que você procura?" class="form-control" aria-label="Procurar produtos" style="border-radius: 20px 0 0 20px; border: none; padding: 10px;">
							<button class="btn btn-light" type="submit" style="border-radius: 0 20px 20px 0; border: none;">
								<i class="fa fa-search" style="font-size: 1.2rem; color: #091442;"></i>
							</button>
						</div>
					</form>
				</div>
	
				<!-- Conta e Carrinho -->
				<div class="col-lg-3 col-3 d-flex align-items-center justify-content-end">
					@auth
					<a href="{{ route('account.profile') }}" class="nav-link text-white me-4">
						Minha Conta <i class="fa fa-user" style="font-size: 1.5rem;"></i>
					</a>
					@else
					<a href="{{ route('account.login') }}" class="nav-link text-white me-4">
						Login ou Registrar <i class="fa fa-user" style="font-size: 1.5rem;"></i>
					</a>
					@endauth

					<a href="{{ route('front.cart') }}" class="position-relative">
						<i class="fas fa-shopping-cart text-white" style="font-size: 1.5rem;"></i>
						{{-- <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
							3 --}}
							<span class="visually-hidden">itens no carrinho</span>
						</span>
					</a>
				</div>
			</div>
		</div>
	</div>
	
<header class="bg-dark">
	<div class="container">
		<nav class="navbar navbar-expand-xl" id="navbar">
			<a href="{{ route('front.home') }}" class="text-decoration-none mobile-logo">
				<span class="h2 text-uppercase text-primary bg-dark"> < Explorar</span>
				<span class="h2 text-uppercase text-white px-2">Categorias</span>
			</a>
			<button class="navbar-toggler menu-btn" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      			<!-- <span class="navbar-toggler-icon icon-menu"></span> -->
				  <i class="navbar-toggler-icon fas fa-bars"></i>
    		</button>
    		<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<!-- <li class="nav-item">
						<a class="nav-link active" aria-current="page" href="index.php" title="Products">Home</a>
					</li> -->
					@if (getCategories()->isNotEmpty())
						@foreach (getCategories()->slice(0, 5) as $category)
							<li class="nav-item dropdown">
								<button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
									{{ $category->name }}
								</button>
								@if ($category->sub_category->isNotEmpty())
									<ul class="dropdown-menu dropdown-menu-dark">
										@foreach ($category->sub_category as $subCategory)
											<li><a class="dropdown-item nav-link" href="{{ route('front.shop',[$category->slug, $subCategory->slug]) }}">{{ $subCategory->name }}</a></li>
										@endforeach
									</ul>
								@endif
							</li>
						@endforeach
						@if (getCategories()->count() > 5)
							<li class="nav-item dropdown">
								<button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
									Mais Categorias
								</button>
								<ul class="dropdown-menu dropdown-menu-dark">
									@foreach (getCategories()->slice(5) as $category)
										<li><a class="dropdown-item nav-link" href="{{ route('front.shop', $category->slug) }}">{{ $category->name }}</a></li>
									@endforeach
								</ul>
							</li>
						@endif
					@endif
				</ul>              
			</div>
      	</nav>
  	</div>
</header>

<main>
    @yield('content')
</main>

<footer class="mt-5" style="background-color: #091442">
	<div class="container pb-5 pt-3">
		<div class="row">
			<div class="col-md-4">
				<div class="footer-card">
					<h3>Entrar em contato</h3>
					<p>Av. Alonson Keese dodson. <br>
					300, Santa Bárbara D'Oeste, São Paulo <br>
					maxm_ferro@yahoo.com.br <br>
					Telefone: +55 19 3457-4904 <br>
                    Whatapp: +55 19 98417-7899
                    </p> 
				</div>
			</div>

			<div class="col-md-4">
				<div class="footer-card">
					<h3>Links importantes</h3>
					<ul>
						<li><a href="about-us.php" title="About">Sobre</a></li>
						<li><a href="contact-us.php" title="Contact Us">Entre em contato</a></li>						
						<li><a href="#" title="Privacy">Termos de privacidade</a></li>
						<li><a href="#" title="Privacy">Termos e condições</a></li>
						<li><a href="#" title="Privacy">Politica de rembolso e troca</a></li>
					</ul>
				</div>
			</div>

			<div class="col-md-4">
				<div class="footer-card">
					<h3>Minha conta</h3>
					<ul>
						<li><a href="{{ route("account.login") }}" title="Login">Login</a></li>
						<li><a href="{{ route("account.register") }}" title="Registro">Registrar</a></li>
						<li><a href="{{ route("account.orders") }}" title="Meus pedidos">Meus pedidos</a></li>						
					</ul>
				</div>
			</div>			
		</div>
	</div>
	<div class="copyright-area">
		<div class="container">
			<div class="row">
				<div class="col-12 mt-3">
					<div class="copy-right text-center">
						<p>© Copyright 2024 Hidromax materias para construção. Todos os direitos reservados</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>
<script src="{{ asset('front-assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('front-assets/js/bootstrap.bundle.5.1.3.min.js') }}"></script>
<script src="{{ asset('front-assets/js/instantpages.5.1.0.min.js') }}"></script>
<script src="{{ asset('front-assets/js/lazyload.17.6.0.min.js') }}"></script>
<script src="{{ asset('front-assets/js/slick.min.js') }}"></script>
<script src="{{ asset('front-assets/js/ion.rangeSlider.min.js') }}"></script>
<script src="{{ asset('front-assets/js/custom.js ') }}"></script>

<script>
window.onscroll = function() {myFunction()};

var navbar = document.getElementById("navbar");
var sticky = navbar.offsetTop;

function myFunction() {
  if (window.pageYOffset >= sticky) {
    navbar.classList.add("sticky")
  } else {
    navbar.classList.remove("sticky");
  }
}

$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});

function addToCart(id) {
            
    $.ajax({
    	url: '{{ route("front.addToCart") }}',
        type: 'post',
        data: {id:id},
        dataType: 'json',
        success: function(response) {
            if (response.status == true) {
                window.location.href="{{ route('front.cart') }}";
            } else {
                alert(response.message);
            }
        }
    })

}
</script>

@yield('customJs')
</body>
</html>