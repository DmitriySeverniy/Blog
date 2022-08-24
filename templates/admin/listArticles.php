<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>

      <h1>Все статьи</h1>

<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>

<?php if ( isset( $results['statusMessage'] ) ) { ?>
        <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
<?php } ?>

      <table>
        <tr>
          <th>Дата публикации</th>
          <th>Статья</th>
          <th>Категория</th>
        </tr>

<?php foreach ( $results['articles'] as $article ) { ?>
        <tr onclick="location='admin.php?action=editArticle&amp;articleId=<?php echo $article->id?>'">
          <td><?php echo date('j M Y', $article->publicationDate)?></td>
          <td>
            <?php echo $article->title?>
          </td>
          <td>
            <?php echo $results['categories'][$article->categoryId]->name?>
          </td>
        </tr>
<?php } ?>

      </table>
      <p><?php echo $results['totalRows']?> статей<?php echo ( $results['totalRows'] != 1 ) ? '' : '' ?> всего.</p>
      <p><a href="admin.php?action=newArticle">Добавить новую статью</a></p>
<?php include "templates/include/footer.php" ?>

