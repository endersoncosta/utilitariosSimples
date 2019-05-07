<?php
/**
 * Template used for pages.
 *
 * @package Avada
 * @subpackage Templates
 * 
 * Template name: TesteSearch 
 */

// Do not allow directly accessing this file.

?>
<?php get_header(); ?>
<section id="">

<?php

$sql = "
SELECT DISTINCT u.meta_value as unidade
         FROM wp_postmeta u
         INNER JOIN wp_posts p ON p.ID = u.post_id
         WHERE u.meta_key = 'unidade' AND p.post_type = 'documento' AND p.post_status = 'publish';

";
$unidades = $wpdb->get_results($sql);

$sql = "
SELECT DISTINCT meta_value as tipo FROM wp_postmeta where meta_key = 'tipo';
";

$tipos = $wpdb->get_results($sql);


if(isset($_POST['un']) || isset($_POST['ti']) && $_POST['un'] != "#" && $_POST['ti'] != "#"){
  $sqlAdd = "";

   if(isset($_POST['un']) && $_POST['un'] != "#"){
      $sqlAdd .= " AND u.meta_value = \"". $_POST['un'] ."\"";

      $sql = "
         SELECT DISTINCT t.meta_value as tipo, u.meta_value
         FROM wp_postmeta u
         INNER JOIN wp_postmeta t ON t.post_id = u.post_id
         WHERE u.meta_key = 'unidade' AND u.meta_value = \"".$_POST['un']."\" AND t.meta_key = 'tipo';";


      $tipos = $wpdb->get_results($sql);
   }
   if(isset($_POST['ti']) && $_POST['ti'] != "#"){
      $i=0;

      $sqlAdd .= "AND (";

      $max = count($_POST['ti']);

      while( $i < $max){
         $sqlAdd .= " t.meta_value = \"". $_POST['ti'][$i] ."\"";

         if(!($i+1 == sizeof($_POST['ti']))){
            $sqlAdd .= " OR ";
         }

         $i++;
      }

      $sqlAdd .= ")";
      
   }



   $querystr = "
      SELECT e.id, e.post_title, e.post_status, e.post_date, e.post_type, j.post_title as fileTitle, j.guid as fileLink, u.meta_value as unidade, d.meta_value as descricao, t.meta_value as tipo 

      FROM wp_posts e

      INNER JOIN wp_posts j ON j.post_parent = e.ID
      INNER JOIN wp_postmeta u ON u.post_id = e.ID
      INNER JOIN wp_postmeta d ON d.post_id = e.ID
      INNER JOIN wp_postmeta t ON t.post_id = e.ID

      WHERE e.post_type = 'documento' 
      AND j.post_type = 'attachment' 
      AND e.post_status = 'publish'
      AND u.meta_key = 'unidade'
      AND d.meta_key = 'descricao'
      AND t.meta_key = 'tipo'
      ".$sqlAdd."   

      ORDER BY
      u.meta_value ASC,
      t.meta_value ASC,
      e.post_date DESC;

   ";
   
}else{

   $querystr = "
      SELECT e.id, e.post_title, e.post_status, e.post_date, e.post_type, j.post_title as fileTitle, j.guid as fileLink, u.meta_value as unidade, d.meta_value as descricao, t.meta_value as tipo 

      FROM wp_posts e

      INNER JOIN wp_posts j ON j.post_parent = e.ID
      INNER JOIN wp_postmeta u ON u.post_id = e.ID
      INNER JOIN wp_postmeta d ON d.post_id = e.ID
      INNER JOIN wp_postmeta t ON t.post_id = e.ID

      WHERE e.post_type = 'documento' 
      AND j.post_type = 'attachment' 
      AND e.post_status = 'publish'
      AND u.meta_key = 'unidade'
      AND d.meta_key = 'descricao'
      AND t.meta_key = 'tipo'


      ORDER BY
      u.meta_value ASC,
      t.meta_value ASC,
      e.post_date DESC;

   ";
}

$pageposts = $wpdb->get_results($querystr, OBJECT);

$unidadeAnterior = null;
$unidadeAtual = null;
$tipoAtual = null;
$tipoAnterior = null;
$numeradorUnidade = 0;
$numeradorTipo = 0;
$contador = 0;


/*

   Lista de variáveis para não se perder

   id,
   post_title,
   post_status,
   post_modified,
   post_type,
   fileTitle,
   fileLink,
   unidade,
   descricao,
   tipo

*/


?>

<div id="docList">


<input class="search" placeholder="Digite o termo desejado" />
<hr />
<form action="" method="post">

<div>
<select name="un" id="un" onchange="this.form.submit()">
<?php if(isset($_POST['un']) && $_POST['un'] != "#"){?>
      <option value="#">-Selecione-</option>
      <option value="#" selected><?php echo $_POST['un'] ?></option>
   <?php }else{ ?>
   
   <option value="#">-Selecione-</option>
   <?php } ?>
   <?php if ($unidades): ?>
      <?php foreach ($unidades as $unidade): ?>
         <option value="<?php echo $unidade->unidade ?>"><?php echo $unidade->unidade ?></option>
      <?php endforeach; ?>
   <?php endif; ?>
</select>
</div>




<div>
   <?php if ($tipos): ?>
      <?php foreach ($tipos as $tipo): ?>
      <input type="checkbox" name="ti[]" value="<?php echo $tipo->tipo ?>"><?php echo $tipo->tipo ?><br />
      <?php endforeach; ?>
   <?php endif; ?>
   </div>



   <button value="Refresh Page" onClick="window.location.reload();">Cancelar Filtros</button>
<button type="submit">Refinar Busca</button>
</form>

<ul class="list">
<?php if ($pageposts): ?>
 <?php global $post; ?>
 <?php foreach ($pageposts as $post): ?>
 <?php setup_postdata($post); ?>
 

 <?php 

   $unidadeAtual = $post->unidade;
   if ($unidadeAtual != $unidadeAnterior){
      $numeradorUnidade++;
      ?>
      

   <h1 class="unidade"> <?php echo $post->unidade; ?></h1>
   <hr />
   
   <?php
 }
   $unidadeAnterior = $unidadeAtual;
 ?>

<?php 
 
 $tipoAtual = $post->tipo;
 if ($tipoAtual != $tipoAnterior){ 
   $numeradorTipo++;
   ?>
 <h2 class="divisaoTipo<?php echo $numeradorTipo?>"> <?php echo $post->tipo; ?></h2>
 <hr />
 
 <?php
}
 $tipoAnterior = $tipoAtual;

   $contador++;
?>

   

   
 <li class="<?php echo "unidade" . $numeradorUnidade; ?> <?php echo "tipo" . $numeradorTipo; ?>" style="background-color:#ddd;">
   <h2 class="title"><?php the_title(); ?><small class="date" style="text-align:right;float:right; text-transform:capitalize;"><?php the_time('d/m/Y') ?></small></h2>
   <p class="description"><?php echo $post->descricao; ?></p>
 </li>


 <?php endforeach; ?>
 <?php else : ?>
    <h2 class="center">Not Found</h2>
    <p class="center">Sorry, but you are looking for something that isn't here.</p>
 <?php endif; ?>

</ul>

<div class="not-found" style="display:none"><h1>Nada foi encontrado aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</h1></div>
	
</section>



<script src="//cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>


<script>
   var options = {
      valueNames: [ 'title', 'date', 'description' ]
   };

   var docList = new List('docList', options);

   $('.search').on('keyup', function(event) { // Fired on 'keyup' event
      if($('.list').children().length === 0) { // Checking if list is empty
         $('.not-found').css('display', 'block'); // Display the Not Found message
      } else {
         $('.not-found').css('display', 'none'); // Hide the Not Found message
      }
   });
</script>


<?php
get_footer();


/* Omit closing PHP tag to avoid "Headers already sent" issues. */
