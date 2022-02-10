BileMo
Openclassrooms / Projet 7 : Créez un web service exposant une API.
Utilisation de Symfony 5, des bundles JMS Serializer et Hateoas pour créer l'application demandée par l'entreprise BileMo.

Environnement :

Symfony 5.4
LexikJWTAuthenticationBundle "^2.14"
JMSSerializerBundle "^4.0"
HateoasBundle "^2.4"
Nelmio/api-doc-bundle "^4.8"

Composer "1.11.99.4"
PHP "7.2.5"
Workbench

Installation

-Copy repository

git clone https://github.com/marue59/BileMo_OCR.git

- Configure BDD connect on .env file

DATABASE_URL=mysql://DB_USER:DB_PASSWORD@127.0.0.1/DB_NAME?serverVersion=SERVER_VERSION

- Install the dependencies

composer install

- Create database

php bin/console doctrine:database:create

- Migrate database table

php bin/console doctrine:migrations:migrate

- Load fixtures in database

php bin/console doctrine:fixtures:load

- Generate the SSH keys JWTAuthentication and add passphrase key in.env file JWT_PASSPHRASE=Your_key

mkdir config/jwt
openssl genrsa -out config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem

- USE WITH POSTMAN
  Install and configure Postman (Official site)

URL - your domain name and the following access link

Acces : api/login_check
You can test the API with this customer account, already available:

{
"username":"customers0@email.com",
"password":"password"
}

DOCUMENTATION
The documentation in JSON format is available at the following link: api/doc
