## Conectar Servidor ao GitHub

Gerar a chave SSH no servidor.
```
ssh-keygen -t rsa -b 4096 -C "cesar@celke.com.br"
```
- Senha (Não usar essa senha): 58s6f2a8#R4s3v82e

Imprimir a chave pública gerada.
```
cat ~/.ssh/id_rsa.pub
```

- No GitHub, vá para Settings (Configurações) do seu repositório ou da sua conta, em seguida, vá para SSH and GPG keys e clique em New SSH key. Cole a chave pública no campo fornecido e salve.

Verificar a conexão com o GitHub.
```
ssh -T git@github.com
```

- Se gerar o erro "The authenticity of host 'github.com (xx.xxx.xx.xxx)' can't be established.". Isso é uma medida de segurança para evitar ataques de "man-in-the-middle". Necessário adicionar a chave do host do GitHub ao arquivo de known_hosts do seu servidor.

Digite yes quando for solicitado.
```
Are you sure you want to continue connecting (yes/no/[fingerprint])? yes
```

Verificar a conexão novamente.
```
ssh -T git@github.com
```

- Mensagem de conexão realizada com sucesso. Hi nome-usuario! You've successfully authenticated, but GitHub does not provide shell access.