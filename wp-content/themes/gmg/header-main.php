<?php

/**
 * The Header for our theme.
**/

?>
<div class="device visible-sm"></div>
<div class="device visible-md"></div>
<div class="device visible-lg"></div>
<div class="device visible-xs"></div>

<div id="search-form-container">
    <form id="search-form" class="navbar-left" role="search" action="/search-result/">
      <div class="container">
        <div class="row">
          <a class="clear" href="#"></a>
          <p>
            <label>SEARCH</label>
          </p>
          <p>
            <input type="text" name="q" placeholder=" TYPE TO SEARCH" id="input-search" autofocus="autofocus">
          </p>
        </div>
      </div>
    </form>
</div>

<header>
    <nav class="navbar navbar-default navbar-primary navbar-fixed-top" role="navigation">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-menu">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        
        <a class="navbar-brand" href="<?php echo get_option('home'); ?>">
          <img class="img-responsive" src="<?php bloginfo('template_directory') ?>/images/logo.png" name="logo" id="logo" title="Logo" alt="Logo" border="0" />
        </a>
      </div>
    
      <div id="navbar-collapse-menu" class="collapse navbar-collapse">
          <?php 
              wp_nav_menu( array('menu' => 'Main Menu', 'theme_location' => 'primary', 'container'=> false, 'menu_class' =>'nav navbar-nav' ) ); 
          ?>
          <ul class="nav navbar-nav navbar-right hidden-sm">
            <li>
              <a href="#" id="icon-search">&nbsp;<img src="/public/shared/images/search.png"></a>
            </li>
            <li><a href="/store/checkout/cart/">&nbsp;<img src="<?php bloginfo('template_directory') ?>/images/icon-cart-small.png"></a>&nbsp;</li>
            <li><a href="/store/customer/account/login/">Login</a></li>
          </ul>
      </div>
    </nav>
</header>
<?php
  
?>
<script>
  jQuery(function(){
    jQuery('#icon-search').on('click', function(){
      jQuery('#search-form-container').show();
    });

    jQuery('#search-form a').on('click', function(){
      jQuery('#search-form-container').hide();
    });
  });
</script>