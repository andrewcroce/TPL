<?php 
/**
 * The user activation page template
 * 
 **/
get_header(); ?>

  <?php if( have_posts() ) : ?>
    
    <?php while( have_posts() ) : the_post(); 

      $page = new ACFPost($post); ?>

      <article class="activation page" itemscope itemtype="http://schema.org/WebPage">


        <?php tpl_wrapper_header_open(); ?>

          <header>

            <h1 itemprop="headline" class="title"><?php echo $page->post_title; ?></h1>
                        
          </header>

        <?php tpl_wrapper_header_close(); ?>


        <?php tpl_wrapper_content_open(); ?>
        
         <div itemprop="text">
            
            <?php echo $page->filterContent('post_content'); ?>

          </div>

        <?php tpl_wrapper_content_close(); ?>
      

        
        <?php tpl_wrapper_secondary_open(); ?>

            <?php if( get_query_var('activation_status', 0) ){
                tpl_block_activation_status( get_query_var('activation_status') );
            }?>

            <?php tpl_form_activation(); ?>

        <?php tpl_wrapper_secondary_close(); ?>


      </article>


    <?php endwhile; ?>

  <?php endif; ?>

<?php get_footer(); ?>
