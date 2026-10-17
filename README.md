<div align="center">
  <img src="public/front-assets/images/Hidromax_logo.png" alt="Hidromax Materiais para Construção" width="190">

  <h1 style="color:#091442">Hidromax Ecommerce</h1>
  <p><strong>Materiais para construção com compra online e entrega regional.</strong></p>

  <img alt="PHP 8.1+" src="https://img.shields.io/badge/PHP-8.1%2B-091442?style=for-the-badge&logo=php&logoColor=F7CA0D">
  <img alt="Laravel 10" src="https://img.shields.io/badge/Laravel-10-091442?style=for-the-badge&logo=laravel&logoColor=F7CA0D">
  <img alt="MySQL" src="https://img.shields.io/badge/MySQL-configur%C3%A1vel-091442?style=for-the-badge&logo=mysql&logoColor=F7CA0D">
</div>

<br>

<div align="center">
  <table>
    <tr>
      <td bgcolor="#091442"><span style="color:#FFFFFF"><strong>LOJA ONLINE</strong></span></td>
      <td bgcolor="#F7CA0D"><span style="color:#091442"><strong>CATÁLOGO</strong></span></td>
      <td bgcolor="#091442"><span style="color:#FFFFFF"><strong>ENTREGA REGIONAL</strong></span></td>
      <td bgcolor="#F7CA0D"><span style="color:#091442"><strong>GESTÃO DE PEDIDOS</strong></span></td>
    </tr>
  </table>
</div>

## Sobre o projeto

O **Hidromax Ecommerce** é a loja virtual da Hidromax Materiais para Construção, localizada em Santa Bárbara d'Oeste, São Paulo. O sistema aproxima a loja física de clientes da região e oferece uma área administrativa para a equipe gerenciar produtos e pedidos.

O projeto foi desenvolvido como Trabalho de Conclusão de Curso e está em evolução para atender o fluxo comercial acordado com a loja.

## O que o sistema oferece

| Área | Funcionalidades |
| --- | --- |
| **Loja** | Página inicial, catálogo, navegação por categorias e subcategorias, marcas e detalhes de produtos. |
| **Cliente** | Cadastro, login, perfil, endereço, carrinho e consulta dos próprios pedidos. |
| **Checkout** | Conferência de produtos e estoque, validação da cidade de entrega e registro do pedido. |
| **Administração** | Painel protegido, manutenção do catálogo e gerenciamento, aprovação e acompanhamento de pedidos. |
| **Comunicação** | Envio de e-mail relacionado ao pedido, conforme a configuração de e-mail do ambiente. |

## Regras de negócio

### Entrega

- A entrega é gratuita nas cidades atendidas: **Americana, Nova Odessa, Piracicaba, Limeira, Sumaré, Monte Mor e Iracemápolis**, no estado de São Paulo.
- Pedidos para cidades fora dessa área não podem ser finalizados como entrega disponível.
- Não há modalidade de entrega especial nem cálculo por transportadora nesta versão.
- A lista de cidades fica centralizada em [`config/delivery.php`](config/delivery.php).

### Aprovação do pedido

- Todo pedido enviado pelo cliente fica **Em análise** até uma pessoa autorizada aprová-lo ou rejeitá-lo.
- Somente contas administrativas com `role = 2` podem decidir sobre pedidos.
- Um pedido aprovado pode seguir para as etapas de separação e entrega; enquanto aguarda análise ou está rejeitado, o sistema bloqueia o avanço.
- A aprovação é diferente da confirmação do pagamento. O pagamento é combinado com o cliente; não há gateway integrado.
- A aprovação registra quem tomou a decisão e quando. O estoque é validado novamente e baixado quando o pedido é aprovado.

### Fluxo resumido

```mermaid
flowchart LR
    A[Cliente finaliza o pedido] --> B[Pedido em análise]
    B -->|Aprovado por role 2| C[Pagamento combinado]
    C --> D[Separação e entrega]
    B -->|Rejeitado| E[Pedido rejeitado]
```

## Tecnologias

- **Backend:** PHP 8.1+ e Laravel 10
- **Interface:** Blade, JavaScript e Ajax
- **Dados:** banco relacional configurado pelo Laravel; o ambiente de desenvolvimento usa MySQL
- **Frontend:** Vite para compilar os arquivos de `resources/css` e `resources/js`; a interface também possui assets em `public/front-assets`
- **Carrinho:** `hardevine/shoppingcart`

## Como executar localmente

### Pré-requisitos

- PHP 8.1 ou superior com as extensões exigidas pelo Laravel
- Composer
- MySQL ou outro banco compatível e configurado para esta aplicação
- Node.js e npm para instalar dependências e compilar os assets do Vite

### Instalação no Windows PowerShell

Na pasta do projeto, execute:

```powershell
composer install
Copy-Item .env.example .env
```

Edite o arquivo `.env` e configure pelo menos `APP_URL` e as variáveis `DB_*` com os dados do seu banco. Depois execute:

```powershell
php artisan key:generate
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

Abra [http://127.0.0.1:8000](http://127.0.0.1:8000) no navegador.

> **Banco existente:** faça um backup antes de aplicar migrations. Em desenvolvimento, `php artisan migrate --seed` cria/atualiza a estrutura e popula categorias, subcategorias, marcas e estados definidos pelos seeders. Não use `migrate:fresh` em uma base com dados que deseja preservar.

### Desenvolvimento do frontend

Para recompilar os assets automaticamente durante alterações em arquivos atendidos pelo Vite:

```powershell
npm run dev
```

Para gerar os arquivos de produção:

```powershell
npm run build
```

## Acessos e rotas úteis

| Endereço | Acesso | Descrição |
| --- | --- | --- |
| `/` | Público | Página inicial da loja. |
| `/shop` | Público | Catálogo de produtos. |
| `/product/{slug}` | Público | Detalhes de um produto. |
| `/cart` | Público | Carrinho de compras. |
| `/checkout` | Cliente autenticado | Finalização do pedido. |
| `/account/register` | Público | Cadastro de cliente. |
| `/account/login` | Público | Login de cliente. |
| `/account/my-orders` | Cliente autenticado | Pedidos da conta conectada. |
| `/admin/login` | Público | Login administrativo. |
| `/admin/dashboard` | Administrador autenticado | Painel administrativo. |
| `/admin/orders` | Administrador autenticado | Lista e análise dos pedidos. |

O login administrativo usa a tabela `users`, assim como as contas de clientes; o painel exige `role = 2`. O repositório não documenta uma senha administrativa padrão. Configure uma conta autorizada no ambiente local e não publique credenciais no Git.

## Organização do código

```text
app/
├── Http/Controllers/   # Fluxos da loja e do painel administrativo
├── Http/Middleware/    # Proteção das áreas autenticadas
├── Models/             # Modelos Eloquent
└── Support/            # Regras auxiliares, incluindo política de entrega
config/                 # Configurações da aplicação e cidades atendidas
database/
├── migrations/         # Evolução da estrutura do banco
└── seeders/            # Dados iniciais de desenvolvimento
public/front-assets/    # CSS, imagens, fontes e outros assets da loja
resources/views/        # Templates Blade da loja, administração e e-mail
routes/web.php          # Rotas HTTP da aplicação
```

## Antes de publicar

- Configure `APP_ENV=production`, `APP_DEBUG=false`, URL, banco, sessões, e-mail e armazenamento no ambiente de produção.
- Use HTTPS e credenciais exclusivas e fortes para o painel e para os serviços externos.
- Aplique migrations com um plano de backup e restauração; nunca inclua `.env` ou senhas no repositório.
- Configure e valide o envio de e-mails no ambiente real.
- Revise backups, logs, permissões de arquivos e proteção dos dados pessoais dos clientes.
- Defina e integre um meio de pagamento apenas quando o fluxo comercial e o provedor forem escolhidos.

## Estado das integrações

Esta versão não possui provedor de pagamento, cálculo de frete por transportadora, entrega especial ou atualização de status em tempo real. Os pedidos seguem o processo de análise e contato da equipe descrito nas regras de negócio.

## Identidade visual

O README acompanha a paleta encontrada na interface da Hidromax:

<table>
  <tr>
    <td bgcolor="#091442" width="180"><span style="color:#FFFFFF"><strong>Azul-marinho</strong><br><code>#091442</code></span></td>
    <td bgcolor="#F7CA0D" width="180"><span style="color:#091442"><strong>Amarelo</strong><br><code>#F7CA0D</code></span></td>
    <td bgcolor="#001D3D" width="180"><span style="color:#FFFFFF"><strong>Azul profundo</strong><br><code>#001D3D</code></span></td>
  </tr>
</table>

<div align="center">
  <sub>Hidromax Materiais para Construção · Santa Bárbara d'Oeste, SP</sub>
</div>
