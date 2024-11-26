# Projeto básico para controle de saldo a partir de um processo Pai

Processo Pai é criado com um valor X, Processos filhos podem consumir saldo R$ do Pai valor X até zerar o valor.

Processo pai, pode sofrer alteracoes de valor pra mais, sem restriçoes, para menor desde que tenha saldo(processos vinculados somados nao ultrapasse o valor desejado a ser reduzido no Pai)

Processos filhos podem ser zerados

Processo Pai em Andamento podem ser listados.

Processos Filhos em Andamento podem ser listados.



API Routes


GET /ping - Retorna uma mensagem confirmando que a API está funcionando.

GET /listarProcEmAndamento - Lista os processos em andamento.

GET /todosFilhosEmAndamento - Lista todos os processos filhos em andamento.

POST /processospai/inativar - Inativa um processo pai.

POST /novoprocpai - Cria um novo processo pai.

POST /novoprocfilho - Cria um novo processo filho.
