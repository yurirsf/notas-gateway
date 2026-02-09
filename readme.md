# RBC Notas
<img width="8128" height="1985" alt="Licenca Domain HTTP Flow-2026-02-09-172533" src="https://github.com/user-attachments/assets/f1698380-b37b-4d68-bdcd-470787fc8da9" />

O RBC Notas é um Gateway de Integração projetado para conectar o ERP Superlógica ao integrador de Notas.
Por limitação do integrador atual, o webhook é enviado apenas para uma url específica, passando um header de autorização com as seguintes informações:
```json
{
    "tipo": "NFS-e",
    "empresaId": "string",
    "nfeId": "string",
    "nfeIdExterno": "string",
    "nfeStatus": "string",
    "nfeMotivoStatus": "string",
    "nfeLinkPdf": "http://api.enotasgw.com.br/file/(...)/pdf",
    "nfeLinkXml": "http://api.enotasgw.com.br/file/(...)/xml",
    "nfeNumero": "string",
    "nfeCodigoVerificacao": "string",
    "nfeNumeroRps": "string",
    "nfeSerieRps": "string",
    "nfeDataCompetencia": "date"
}

```
O que o RBC Notas faz é identificar a licença pelo campo `empresaId` cadastrado no banco de dados e encaminhar a requisição para a licença.


# O que é o Route-based Content (RBC)?

O conceito de Route-based Content no projeto refere-se à inteligência de Roteamento Dinâmico de Conteúdo.
O sistema processa o payload, identifica a licença do cliente e roteia a informação para o endpoint específico do parceiro no formato: {licenca}.superlogica.com/endpoint.
Isso permite que uma única API centralize milhares de integrações, tratando cada payload de forma isolada e personalizada conforme a origem.

# Como Funciona?

<img width="703" height="706" alt="Diagrama RBC Notas drawio" src="https://github.com/user-attachments/assets/34f37722-7cfd-40a1-b0df-cafa001cd040" />


Cadastro da Licença: A licença é cadastrada apenas com o token de integração e o nome da licença;
Webhook da Nota: O integrador envia o webhook para o endpoint que identifica a licença e agenda o envio dos dados;
Agendamento: O payload é imediatamente colocado em uma fila Messenger Symfony com Redis.
Processamento Assíncrono: Um worker consome a fila e executa as regras de negócio baseadas em Clean Architecture, isolando a lógica de atualização da nota de qualquer dependência externa.
Despacho Dinâmico: O RBC identifica o destino correto via banco de dados e entrega a nota fiscal processada para a URL dinâmica da empresa.
