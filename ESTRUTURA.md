# Organização do SOSMulher

O projeto mantém Laravel, Blade e MySQL, com CSS comum e JavaScript em public.

## Onde editar

- routes/web.php: rotas explícitas das páginas e formulários.
- routes/api.php: rotas originais de consulta e localização das tornozeleiras.
- app/Http/Controllers/VitimaController.php: cadastro, consulta, edição e status de vítimas.
- app/Http/Controllers/AgressorController.php: cadastro, consulta, edição e status de agressores.
- app/Http/Controllers/OcorrenciaController.php: ocorrências e finalização do atendimento.
- app/Http/Controllers/RelatorioController.php: consulta dos relatórios.
- app/Http/Controllers/LocalSeguroController.php: solicitações e aprovação de locais seguros.
- app/Http/Controllers/PerfilController.php: perfil e foto do usuário autenticado.
- app/Http/Controllers/PainelController.php: consultas e gráficos da home e estatísticas.
- app/Http/Controllers/AcessoController.php e SenhaController.php: acesso e recuperação de senha.
- app/Http/Controllers/Api: controllers originais da tornozeleira.
- app/Models: tabelas e relacionamentos existentes.
- resources/views/inicio.blade.php: entrada pública.
- resources/views/autenticacao: formulários de acesso.
- resources/views/administracao: uma pasta por funcionalidade; lista lista os registros, formulario cadastra/edita e detalhes exibe.
- resources/views/estruturas: estrutura comum das páginas.
- resources/views/parciais: menu, ícones e trechos visuais compartilhados.
- public/css/estilo.css: estilos das telas, em CSS comum.
- public/js/principal.js: interações comuns.
- public/img: imagens, incluindo a logo.
- resources/views/parciais/paginacao.blade.php: paginação com CSS próprio.
- tests/Feature: testes dos fluxos usando banco SQLite em memória.

## Fluxo de um formulário

A rota chama o controller da funcionalidade. O controller valida os campos,
salva pelo Model e redireciona com uma mensagem. O Blade contém HTML explícito,
CSRF, os valores anteriores do formulário e os dados recebidos do controller.

Não há mais controller genérico escolhendo tabelas e campos pelo nome da rota.
Os nomes de tabelas, colunas, URLs e campos dos formulários foram preservados.

## Verificação

Execute php artisan test para verificar os fluxos. CSS e JavaScript são carregados diretamente de public; npm run build é opcional. Os testes não usam o banco MySQL
da aplicação. Esta reorganização não executa migrations nem altera registros.

A API original da tornozeleira foi recuperada do commit 2b06e15.
As telas de monitoramento/dispositivos ainda não estão conectadas à API.

## Nomes em português

As telas e os controllers da aplicação usam nomes em português. Por exemplo:
resources/views/administracao/ocorrencia/lista.blade.php.
Os arquivos convencionais do Laravel, Composer e Vite mantêm seus nomes originais.
As URLs e os nomes internos das rotas foram preservados para manter compatibilidade.
