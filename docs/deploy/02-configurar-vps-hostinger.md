## Configurar VPS na Hostinger

- Instalar o Ubuntu com Cloudpanel e Laravel.
- Acessar o Cloudpanel, acessar sites->Adicionar Site->Criar um site PHP->Laravel 13->PHP 8.3.
- Alterar o timezone: date.timezone=America/Sao_Paulo;
- Criar a base de dados "copa".
- Senha (Não usar essa senha): 58s6f2a8#R4s3v82e
- Configurar DNS do domínio: Tipo A; Nome copa; Valor IP da VPS; TTL 300.
- Instalar o SSL.
- Configurar o SSH. No arquivo 03-conectar-o-pc-ao-servidor-com-ssh tem as orientações.
- Conectar ao GitHub via SSH. No arquivo 04-conectar-a-vps-ao-github-com-ssh tem as orientações.