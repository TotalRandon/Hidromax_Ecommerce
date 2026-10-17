# Contexto do projeto Hidromax Ecommerce

Este arquivo registra os principais objetivos e fluxos identificados no TCC e uma leitura inicial do sistema atual. Use-o como contexto de produto em futuras alterações; confirme no código antes de assumir que um requisito já está implementado.

## Objetivo do produto

Criar uma loja virtual para a Hidromax Materiais para Construção, ampliando a visibilidade e o alcance de clientes além da loja física. O trabalho acadêmico caracteriza a solução como protótipo. Os perfis principais são clientes/compradores e colaboradores administradores.

## Funcionalidades previstas

- Loja pública: navegar pelo catálogo, ver produtos, pesquisar e filtrar por categoria, subcategoria, marca, preço e ordenação.
- Conta do cliente: cadastro, login, perfil e endereço, histórico e detalhes dos próprios pedidos.
- Compra: carrinho, cálculo de frete por estado, checkout, confirmação e acompanhamento do pedido.
- Administração: acesso restrito; painel com indicadores; manutenção de categorias, subcategorias, marcas e produtos; configuração de frete por estado; gestão de pedidos e envio de e-mail.
- Requisitos citados como adiados/incompletos no documento: cupons, pagamento integrado (meios de pagamento aparecem como inoperantes no protótipo), refinamento de busca/filtros e notificações de pedido/status em tempo real. A implantação em hospedagem também aparece como objetivo.

## Regra comercial confirmada: entregas

- A matriz física fica em Santa Bárbara d'Oeste, SP.
- A loja fará entregas sem cobrança de frete para Americana, Nova Odessa, Piracicaba, Limeira, Sumaré, Monte Mor e Iracemápolis.
- Não há modalidade de entrega especial no momento.
- Não integrar transportadora ou serviço externo de cotação por enquanto.
- No checkout, a cidade de destino deve ser validada contra essa lista; pedidos para outras cidades não devem ser apresentados como entregáveis gratuitamente. A interface deve informar ao cliente que a entrega está disponível nas cidades atendidas e que o frete é gratuito.
- A lista deve ficar centralizada/configurável para que uma mudança futura não exija procurar e alterar regras espalhadas pelo código.

## Regra comercial confirmada: aprovação de pedidos

- Todo pedido deve ser aprovado por um superior antes de avançar para separação, expedição ou entrega.
- O sistema deve manter o pedido em um estado explícito de aguardando aprovação após o checkout.
- Somente um usuário autorizado como superior pode aprovar ou rejeitar o pedido; registrar quem tomou a decisão e quando.
- Bloquear qualquer avanço para etapas de entrega enquanto a aprovação não tiver sido concedida.
- A aprovação é uma etapa operacional distinta da confirmação de pagamento. Como ainda não há provedor integrado, não presumir que aprovação significa pagamento recebido.

## Implementação iniciada

- A lista de cidades atendidas está centralizada em `config/delivery.php` e a validação/normalização fica em `app/Support/DeliveryPolicy.php`.
- O antigo cadastro administrativo de fretes por estado foi retirado da navegação/rotas ativas, pois não corresponde à regra local de entrega gratuita.
- Novos pedidos entram como `approval_status = awaiting`; a decisão grava o usuário e a data. O perfil administrativo existente (`role = 2`) é usado como superior autorizado.
- O estoque é conferido no checkout e novamente na aprovação; a baixa acontece apenas quando o superior aprova, protegida por transação e bloqueio das linhas de produto.
- O estado de aprovação é separado do andamento da entrega (`status`) e do pagamento (`payment_status`). As opções de pagamento sem integração foram removidas do checkout; pagamento fica pendente para combinação após a aprovação.
- A migration preserva como aprovados os pedidos antigos já enviados, entregues ou cancelados; pedidos antigos ainda pendentes entram na fila de aprovação. A migration nova precisa ser aplicada no ambiente local/produção antes de usar a aplicação atualizada.

## Stack e arquitetura observadas

- O TCC descreve PHP, Laravel, Blade, JavaScript/Ajax, Git/GitHub e banco relacional.
- O repositório usa Laravel 10 e PHP ^8.1, Blade e JavaScript. Há dependência `hardevine/shoppingcart` para o carrinho.
- A aplicação segue organização MVC: rotas em `routes/web.php`, controladores em `app/Http/Controllers`, modelos Eloquent em `app/Models`, templates em `resources/views` e esquema em `database/migrations`.
- O texto do TCC menciona SQL Server em um trecho e MySQL em outro; o driver efetivo é configurável em `config/database.php`/`.env`. Confirmar ambiente antes de qualquer decisão operacional.

## Fluxos confirmados no código (leitura inicial)

- Rotas públicas incluem home, loja, detalhe de produto, carrinho, checkout e confirmação.
- Cadastro/login de cliente e páginas de perfil/pedidos estão agrupadas em `/account`.
- Administração fica sob `/admin` e usa middleware/guard próprios; oferece cadastro de catálogo, frete, pedidos e dashboard.
- O checkout cria endereço e pedido, persiste itens e reduz estoque quando o controle de quantidade está habilitado; o estado de pagamento é salvo como `not paid` e o pedido como `pending`. Não foi identificada integração de gateway de pagamento no fluxo lido.

## Observações técnicas iniciais para futuras tarefas

Estes pontos são indícios encontrados em leitura estática, não uma auditoria completa nem uma lista exaustiva:

1. **Integridade do checkout e estoque:** pedido, itens e baixa de estoque são salvos em etapas sem transação visível. Uma falha no meio pode deixar pedido/estoque inconsistentes. O checkout também deve validar disponibilidade e preço no servidor imediatamente antes de gravar.
2. **Pagamento:** o pedido é criado como não pago, mas o fluxo atual aparenta finalizar e limpar o carrinho sem confirmação de pagamento por provedor. Separar pedido criado, pagamento pendente e pagamento confirmado; só confirmar venda/baixa conforme regra de negócio acordada.
3. **Validação/autorização:** revisar validação de quantidade e identificadores nas ações de carrinho, validação de status em mudança de pedido e autorização de todas as rotas administrativas. Usar regras explícitas e mensagens consistentes.
4. **Busca e catálogo:** tratar slugs inexistentes sem erro, validar filtros recebidos, preservar filtros ao paginar e implementar busca textual se necessária. Confirmar que detalhes públicos não exibem produtos inativos.
5. **Pedidos do cliente:** o detalhe consulta itens usando o ID informado mesmo se o pedido não existir ou não pertencer ao usuário; embora o pedido seja filtrado por `user_id`, a consulta de itens deve derivar do pedido autorizado. Tratar pedido inexistente com 404.
6. **Indicadores administrativos:** conferir se receita considera apenas pedidos pagos e se os estados de cancelamento usam os mesmos valores gravados no sistema; hoje o dashboard soma valores de pedidos não cancelados, independentemente de pagamento confirmado.
7. **Persistência de endereço:** verificar e completar a gravação do endereço ao editar o perfil; a ação lida valida e responde sucesso, mas não persiste os campos nessa implementação.
8. **Qualidade e operação:** ampliar testes automatizados para cadastro, permissões, carrinho, estoque, frete e ciclo de pedido; revisar configuração de produção, e-mail, backups, logs, proteção de dados e observabilidade antes de publicar.

## Direção recomendada

Priorizar primeiro correção e segurança do ciclo de pedido (validação de entrada, transação, estoque, aprovação obrigatória e estados de pagamento/pedido), incluindo a validação de cidade e frete gratuito segundo as regras comerciais confirmadas acima; depois cobertura de testes e confiabilidade operacional. Em seguida evoluir busca/experiência de catálogo e integração de pagamento quando as regras comerciais estiverem definidas. Confirmar alterações de política comercial com a loja antes de codificá-las.
