<?php
ini_set( "display_errors", true );
date_default_timezone_set( "Europe/Moscow" ); // Временная зона сервера
define( "DB_DSN", "mysql:host=localhost;dbname=tables" ); //БД tables
define( "DB_USERNAME", "root" );
define( "DB_PASSWORD", "" );
define( "CLASS_PATH", "classes" );
define( "TEMPLATE_PATH", "templates" );
define( "HOMEPAGE_NUM_ARTICLES", 5 );
define( "ADMIN_USERNAME", "cat" ); //ADMIN_USERNAME имя администратора
define( "ADMIN_PASSWORD", "herring" ); //ADMIN_PASSWORD пароль администратора
require( CLASS_PATH . "/Article.php" ); //включить новый класс Article
require( CLASS_PATH . "/Category.php" ); //включить класс Category

function handleException( $exception ) {
  echo "Извините, возникла проблема. Пожалуйста, попробуйте позже.";
  error_log( $exception->getMessage() );
}

set_exception_handler( 'handleException' );
?>
