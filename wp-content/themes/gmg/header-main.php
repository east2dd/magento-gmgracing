<?php

/**
 * The Header for our theme.
**/

?>
<div class="device visible-sm"></div>
<div class="device visible-md"></div>
<div class="device visible-lg"></div>
<div class="device visible-xs"></div>

<header>
    <nav class="navbar navbar-default navbar-primary navbar-fixed-top" role="navigation">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        
        <a class="navbar-brand" href="<?php echo get_option('home'); ?>">
          <img class="img-responsive" src="<?php bloginfo('template_directory') ?>/images/logo.png" name="logo" id="logo" title="Logo" alt="Logo" border="0" />
        </a>
        
      </div>
    
      <div class="collapse navbar-collapse navbar-ex1-collapse">
          <?php 
              wp_nav_menu( array('menu' => 'Main Menu', 'theme_location' => 'primary', 'container'=> false, 'menu_class' =>'nav navbar-nav' ) ); 
          ?>
          <form id="search-form" class="navbar-left hidden-xs hidden-sm" role="search" action="/store/catalogsearch/result/">
            <div class="form-group">
              <input type="text" placeholder="Search" name="q">
              <a class="clear" href="#"></a>
            </div>
          </form>
          <form id="search-form" class="navbar-left visible-xs" role="search" action="/">
            <div class="form-group">
              <input type="text" placeholder="Search" name="s">
              <a class="clear" href="#"></a>
            </div>
          </form>
          <ul class="nav navbar-nav navbar-right hidden-sm">
            <li><a href="/store/checkout/cart/">&nbsp;<img src="<?php bloginfo('template_directory') ?>/images/icon-cart-small.png"></a>&nbsp;</li>
            <li><a href="/store/customer/account/login/">Login</a></li>
          </ul>

      </div>
    </nav>
</header>