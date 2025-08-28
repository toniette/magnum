```merdaid
flowchart LR
    subgraph API1["API 1"]
        A1["Carga inicial"]
        A6["Buscar Marca"]
        A7["Buscar Veículos por Marca"]
        A8["Atualizar Modelo/Observações"]
    end
    subgraph API2["API 2"]
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