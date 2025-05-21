
![Logo Convenia](public/img/logo_convenia.png)

---

Construção de API REST como parte do teste técnico para a vaga de Desenvolvedor Back-End Jr.

---

#### Requisitos:
* Docker
* Git
* Apache
* MySQL
* PhpMyAdmin
* Laravel

---

#### Dependências Composer:
* php: ^8.2
* darkaonline/l5-swagger": ^9.0
* laravel/framework: ^12.0
* laravel/sanctum: ^4.0,
* laravel/tinker: ^2.10.1
* league/csv: ^9.23
* tymon/jwt-auth: ^2.2

---

#### Configuração e execução do projeto
* Clonar o repositório atual para sua máquina local:
    `git clone https://github.com/cebpereira/teste_convenia`

* Navegar para a pasta do projeto:
    `cd teste_convenia`

* Copiar o .env.example para o .env:
    `cp .env.example .env`

* Configurar o .env com suas variáveis de ambiente como preferir:

* Para envio de emails é necessário configurar uma plataforma de envio de emails, para testes utilizei o https://mailtrap.io/

* No terminal de comando execute o `make setup` para executar todos os comandos de configuração:

* Aguarde a execução do comando terminar, em caso de sucesso, os containers estarão ativos e o projeto estará rodando via localhost nas seguintes portas:
    * 8080 -> PhpMyAdmin
    * 3306 -> MySQL
    * 80 -> Apache
 
* Utilize o comando abaixo no terminal do linux ou do WSL para entrar no terminal do apache caso necessário:
    * `docker exec -it convenia-site bash`
 
* A rota inicial do projeto é a localhost

* A rota do Swagger estará disponivel em: http://localhost/api/documentation

---

#### Observações

- Caso surja o erro:
    > The stream or file "/var/www/html/convenia/storage/logs/laravel.log" could not be opened in append mode: Failed to open stream: Permission denied The exception occurred while attempting to log: The stream or file "/var/www/html/convenia/storage/logs/laravel.log"\
    * utilize o comando `sudo chmod o+w ./storage/ -R` no terminal para fornecer as permissões necessárias.

- Caso surja o erro:
    > ERROR: The Compose file './docker-compose.yml' is invalid because: 
    > Unsupported config option for services: 'phpmyadmin'
    > Unsupported config option for networks: 'convenia-network'
    * ao utilizar o comando `make setup`, no arquivo `Makefile` altere os comandos de `docker-compose` para `docker compose`, isso deve resolver o erro.

- Caso surja o erro:
    > zsh: command not found: make
    * utilize o comando `sudo apt install build-essential` ou simplesmente `sudo apt install make` no terminal para instalar o make

- Para utilização do processamento e envio de email utilizando queue:
    * acesse o container do apache e dentro dele utilize o comando `php artisan queue:work` para executar os jobs enfileirados

- Em caso de alterações nas annotations do Swagger:
    * acesse o container do apache e dentro dele utilize o comando `php artisan l5-swagger:generate` para gerar uma nova documentação para a API
 
> [!NOTE]
> Em caso de sugestões, correções ou dúvidas:
> [LinkedIn](https://www.linkedin.com/in/cebpereira/),
> [Instagram](https://www.instagram.com/c_elandro/)
> ou pelo email c.elandro.bp@gmail.com
