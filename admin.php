<?php

require( "config.php" );
session_start();
$action = isset( $_GET['action'] ) ? $_GET['action'] : "";
$username = isset( $_SESSION['username'] ) ? $_SESSION['username'] : "";

if ( $action != "login" && $action != "logout" && !$username ) {
  login();
  exit;
}

switch ( $action ) {
  case 'login':
    login();
    break;
  case 'logout':
    logout();
    break;
  case 'newArticle':
    newArticle();
    break;
  case 'editArticle':
    editArticle();
    break;
  case 'deleteArticle':
    deleteArticle();
    break;
  case 'listCategories':
    listCategories();
    break;
  case 'newCategory':
    newCategory();
    break;
  case 'editCategory':
    editCategory();
    break;
  case 'deleteCategory':
    deleteCategory();
    break;
  default:
    listArticles();
}


function login() {

  $results = array();
  $results['pageTitle'] = "Вход администратора | Новости какого-то Кота";

  if ( isset( $_POST['login'] ) ) {

    // User has posted the login form: attempt to log the user in

    if ( $_POST['username'] == ADMIN_USERNAME && $_POST['password'] == ADMIN_PASSWORD ) {

      // Login successful: Create a session and redirect to the admin homepage
      $_SESSION['username'] = ADMIN_USERNAME;
      header( "Location: admin.php" );

    } else {

      // Login failed: display an error message to the user
      $results['errorMessage'] = "Неверное имя пользователя или пароль. Пожалуйста, попробуйте снова.";
      require( TEMPLATE_PATH . "/admin/loginForm.php" );
    }

  } else {

    // User has not posted the login form yet: display the form
    require( TEMPLATE_PATH . "/admin/loginForm.php" );
  }

}


function logout() {
  unset( $_SESSION['username'] );
  header( "Location: admin.php" );
}


function newArticle() {

  $results = array();
  $results['pageTitle'] = "Новая статья";
  $results['formAction'] = "newArticle";

  if ( isset( $_POST['saveChanges'] ) ) {

    // User has posted the article edit form: save the new article
    $article = new Article;
    $article->storeFormValues( $_POST );
    $article->insert();
    header( "Location: admin.php?status=changesSaved" );

  } elseif ( isset( $_POST['cancel'] ) ) {

    // User has cancelled their edits: return to the article list
    header( "Location: admin.php" );
  } else {

    // User has not posted the article edit form yet: display the form
    $results['article'] = new Article;
    $data = Category::getList();
    $results['categories'] = $data['results'];
    require( TEMPLATE_PATH . "/admin/editArticle.php" );
  }

}


function editArticle() {

  $results = array();
  $results['pageTitle'] = "Редактировать статью";
  $results['formAction'] = "editArticle";

  if ( isset( $_POST['saveChanges'] ) ) {

    // User has posted the article edit form: save the article changes

    if ( !$article = Article::getById( (int)$_POST['articleId'] ) ) {
      header( "Location: admin.php?error=articleNotFound" );
      return;
    }

    $article->storeFormValues( $_POST );
    $article->update();
    header( "Location: admin.php?status=changesSaved" );

  } elseif ( isset( $_POST['cancel'] ) ) {

    // User has cancelled their edits: return to the article list
    header( "Location: admin.php" );
  } else {

    // User has not posted the article edit form yet: display the form
    $results['article'] = Article::getById( (int)$_GET['articleId'] );
    $data = Category::getList();
    $results['categories'] = $data['results'];
    require( TEMPLATE_PATH . "/admin/editArticle.php" );
  }

}


function deleteArticle() {

  if ( !$article = Article::getById( (int)$_GET['articleId'] ) ) {
    header( "Location: admin.php?error=articleNotFound" );
    return;
  }

  $article->delete();
  header( "Location: admin.php?status=articleDeleted" );
}


function listArticles() {
  $results = array();
  $data = Article::getList();
  $results['articles'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $data = Category::getList();
  $results['categories'] = array();
  foreach ( $data['results'] as $category ) $results['categories'][$category->id] = $category;
  $results['pageTitle'] = "Все статьи";

  if ( isset( $_GET['error'] ) ) {
    if ( $_GET['error'] == "articleNotFound" ) $results['errorMessage'] = "Ошибка: Статья не найдена.";
  }

  if ( isset( $_GET['status'] ) ) {
    if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Ваши изменения были сохранены.";
    if ( $_GET['status'] == "articleDeleted" ) $results['statusMessage'] = "Статья удалена.";
  }

  require( TEMPLATE_PATH . "/admin/listArticles.php" );
}


function listCategories() {
  $results = array();
  $data = Category::getList();
  $results['categories'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $results['pageTitle'] = "Категории статей";

  if ( isset( $_GET['error'] ) ) {
    if ( $_GET['error'] == "categoryNotFound" ) $results['errorMessage'] = "Ошибка: Категория не найдена.";
    if ( $_GET['error'] == "categoryContainsArticles" ) $results['errorMessage'] = "Ошибка: Категория содержит статьи. Удалите статьи или отнесите их к другой категории, прежде чем удалять эту категорию.";
  }

  if ( isset( $_GET['status'] ) ) {
    if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Ваши изменения были сохранены.";
    if ( $_GET['status'] == "categoryDeleted" ) $results['statusMessage'] = "Категория удалена.";
  }

  require( TEMPLATE_PATH . "/admin/listCategories.php" );
}


function newCategory() {

  $results = array();
  $results['pageTitle'] = "Новая категория статей";
  $results['formAction'] = "newCategory";

  if ( isset( $_POST['saveChanges'] ) ) {

    // User has posted the category edit form: save the new category
    $category = new Category;
    $category->storeFormValues( $_POST );
    $category->insert();
    header( "Location: admin.php?action=listCategories&status=changesSaved" );

  } elseif ( isset( $_POST['cancel'] ) ) {

    // User has cancelled their edits: return to the category list
    header( "Location: admin.php?action=listCategories" );
  } else {

    // User has not posted the category edit form yet: display the form
    $results['category'] = new Category;
    require( TEMPLATE_PATH . "/admin/editCategory.php" );
  }

}


function editCategory() {

  $results = array();
  $results['pageTitle'] = "Редактировать категорию статьи";
  $results['formAction'] = "editCategory";

  if ( isset( $_POST['saveChanges'] ) ) {

    // User has posted the category edit form: save the category changes

    if ( !$category = Category::getById( (int)$_POST['categoryId'] ) ) {
      header( "Location: admin.php?action=listCategories&error=categoryNotFound" );
      return;
    }

    $category->storeFormValues( $_POST );
    $category->update();
    header( "Location: admin.php?action=listCategories&status=changesSaved" );

  } elseif ( isset( $_POST['cancel'] ) ) {

    // User has cancelled their edits: return to the category list
    header( "Location: admin.php?action=listCategories" );
  } else {

    // User has not posted the category edit form yet: display the form
    $results['category'] = Category::getById( (int)$_GET['categoryId'] );
    require( TEMPLATE_PATH . "/admin/editCategory.php" );
  }

}


function deleteCategory() {

  if ( !$category = Category::getById( (int)$_GET['categoryId'] ) ) {
    header( "Location: admin.php?action=listCategories&error=categoryNotFound" );
    return;
  }

  $articles = Article::getList( 1000000, $category->id );

  if ( $articles['totalRows'] > 0 ) {
    header( "Location: admin.php?action=listCategories&error=categoryContainsArticles" );
    return;
  }

  $category->delete();
  header( "Location: admin.php?action=listCategories&status=categoryDeleted" );
}

?>
