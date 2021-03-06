# leboncointest
1. Installation de la base de données
Importer le fichier crm.sql.
Vous pouvez créer un utilisateur spécifique pour cette base:
<pre>
CREATE USER 'userAdmin'@'%' IDENTIFIED WITH mysql_native_password AS '***';GRANT ALL PRIVILEGES ON *.* TO 'userAdmin'@'%' REQUIRE NONE WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;GRANT ALL PRIVILEGES ON `crm`.* TO 'userAdmin'@'%';
</pre>
Ensuite configurer la base base dans le source en modifiant le fichier app/Config.php:
<pre>
public function __construct()
{
	$this->settings = [
		"db_user" => "userAdmin",
		"db_pass" => "**@dM1n!LeBonCoin",
		"db_host" => "localhost",
		"db_name" => "crm"
	];
}
</pre>
Pour vous connecter à l'application utiliser le login "lebonoin@test.fr" et le mot de passe "MakTest2019"
2. Optimisation de la base de données
<pre>
ALTER TABLE `crm`.`contacts` ADD FULLTEXT `ft_nom` (`nom`);
ALTER TABLE `crm`.`contacts` ADD FULLTEXT `ft_prenom` (`prenom`);
ALTER TABLE `crm`.`contacts` ADD FULLTEXT `ft_nom_prenom` (`nom`, `prenom`);
</pre>

3. Code source
Le code est accessible via git depuis l'url suivante: https://github.com/makanegaye/leboncointest.git
Exécuter la commande git clone https://github.com/makanegaye/leboncointest.git
Pour rajouter PhpUnit, exécuter la commande "composer require --dev phpunit/phpunit ^8"
Et pour installer les dépendances exécuter "composer install"

4. Paramètrage apache
Vous pouvez créer un virtual host avec afin de faciliter l'accès à l'application avec une url plus sympa:

<VirtualHost *:80>
  ServerName leboncoin.local
  
  DocumentRoot "path_to_project"
  
  <Directory "path_to_project/">
    Options +Indexes +Includes +FollowSymLinks +MultiViews
    AllowOverride All
    Require local
  </Directory>
</VirtualHost>
Ensuite rajouter dans le fichier host la ligne
<pre>127.0.0.1 leboncoin.local</pre>
 
Ensuite redémarrer apache
