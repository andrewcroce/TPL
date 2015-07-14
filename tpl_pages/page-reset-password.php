<?php 
/**
 * The login page template
 * 
 **/
get_header(); ?>

  <?php if( have_posts() ) : ?>
    
    <?php while( have_posts() ) : the_post(); 

      $page = new ACFPost($post); ?>

      <article class="reset-password page" itemscope itemtype="http://schema.org/WebPage">


        <?php tpl_wrapper_header_open(); ?>

          <header>

            <h1 itemprop="headline" class="title"><?php echo $page->post_title; ?></h1>
                        
          </header>

        <?php tpl_wrapper_header_close(); ?>


        <?php tpl_wrapper_content_open(); ?>

        
         <div itemprop="text">

            <?php if( get_query_var('reset_status', 0) ){

                tpl_block_password_reset_status( get_query_var('reset_stage', 'reset'),  get_query_var('reset_status') );
            
            } ?>   
            
            <?php if( get_query_var('reset_stage', 0) == 'new' ) : ?>
                
                <?php echo $page->filterContent('new_password_content'); ?>

            <?php else : ?>

                <?php echo $page->filterContent('request_reset_content'); ?>
                
            <?php endif; ?>

          </div>

        <?php tpl_wrapper_content_close(); ?>
      

        
        <?php tpl_wrapper_secondary_open(); ?>

          <?php tpl_form_reset_password(); ?>

        <?php tpl_wrapper_secondary_close(); ?>


      </article>


    <?php endwhile; ?>

  <?php endif; ?>

<?php get_footer(); ?>