```merdaid
flowchart LR
    subgraph API1["INITIATOR"]
        A1["Carga inicial"]
        A6["Buscar Marca"]
        A7["Buscar Veículos por Marca"]
        A8["Atualizar Modelo/Observações"]
    end
    subgraph API2["INGESTOR"]
        B1["Consumir Marcas da Fila"]
        B2["Buscar Códigos e Modelos na FIPE"]
        B3["Salvar em SQL"]
    end
    subgraph FIPE["Serviço FIPE"]
        F1["marcas/modelos/anos"]
    end
    subgraph Infra["Infraestrutura"]
        Q["Fila"]
        DB[("Banco de Dados SQL")]
        C[("Cache Redis")]
    end
    A1 -- Busca Marcas --> FIPE
    B2 -- Busca Modelos --> FIPE
    FIPE -- Marcas --> Q
    Q --> B1
    B1 -- Marcas --> B2
    B2 -- Códigos, Marcas, Modelos --> B3
    B3 --> DB
    A6 -- Consulta Marcas --> C
    C --> DB & DB
    A7 -- Consulta Veículos --> C
    A8 -- Salvar Alterações --> DB
```

# Sistema de Consulta e Armazenamento de Dados de Veículos
Este projeto consiste em dois serviços que interagem com um serviço externo (FIPE) para buscar,
armazenar e atualizar informações sobre marcas e modelos de veículos.
A arquitetura do sistema é composta por dois serviços principais,
uma fila para processamento assíncrono, um banco de dados SQL para armazenamento
persistente e um cache Redis para otimização de consultas.

## Componentes do Sistema
1. **Serviço Iniciador (API1)**:
   - Responsável por iniciar o processo de busca de marcas e modelos de veículos.
   - Permite consultas rápidas de marcas e veículos utilizando o cache Redis.
   - Permite a atualização manual de modelos e observações no banco de dados SQL.
2. **Serviço Ingestor (API2)**:
   - Consome marcas da fila e busca os códigos e modelos correspondentes na FIPE.
   - Salva os dados obtidos no banco de dados SQL.
   - Atualiza o cache Redis com os novos dados.
3. **Serviço FIPE**:
   - Serviço externo que fornece informações sobre marcas, modelos, anos e detalhes de veículos.
4. **Fila**:
   - Utilizada para comunicação assíncrona entre o Serviço Iniciador e o Serviço Ingestor.
5. **Banco de Dados SQL**:
   - Armazena todas as informações sobre marcas, modelos, anos e detalhes dos veículos.
6. **Cache Redis**:
   - Utilizado para armazenar temporariamente dados frequentemente acessados, melhorando a performance das consultas.

## Tecnologias Utilizadas
- PHP 8.4
- Laravel 12
- SQLite
- Redis
- Docker e Docker Compose

## Configuração do Ambiente
1. Clone o repositório:
    ```bash
    git clone https://github.com/toniette/magnum.git
    cd magnum
    ```
2. Instale as dependências:
    ```bash
    docker run --rm -u "$(id -u):$(id -g)" \
    -v $(pwd)/initiator:/var/www/html \
    -w /var/www/html laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
    
    docker run --rm -u "$(id -u):$(id -g)" \
    -v $(pwd)/ingestor:/var/www/html \
    -w /var/www/html laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
    ```
3. Copie os arquivos de ambiente:
    ```bash
    cp initiator/.env.example initiator/.env && \
    cp ingestor/.env.example ingestor/.env
    ```
4. Configure os arquivos `.env` para ambos os serviços, ajustando as variáveis de ambiente conforme necessário.
5. Inicie os containers:
    ```bash
    docker-compose up -d
    ```
6. Ajuste o ownership dos diretórios:
    ```bash
    docker exec initiator chown -R www-data:www-data /var/www/html/storage && \
    docker exec ingestor chown -R www-data:www-data /var/www/html/storage && \
    docker exec ingestor chown -R www-data:www-data /var/www/html/bootstrap/cache && \
    docker exec initiator chown -R www-data:www-data /var/www/html/bootstrap/cache
    ```
7. Gere as chaves de aplicação:
    ```bash
    docker exec -it initiator php artisan key:generate && \
    docker exec -it ingestor php artisan key:generate
    ```
8. Execute as migrações do banco de dados:
    ```bash
    docker exec -it initiator php /var/www/html/artisan migrate && \
    docker exec -it ingestor php /var/www/html/artisan migrate
    ```
9. Inicie o processo de ingestão inicial:
    ```bash
    docker exec -it ingestor php /var/www/html/artisan queue:work --queue=ingest --sleep=3 --tries=3 --timeout=90
    ```