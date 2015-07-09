/*
 * menu
 */

var menuTimeout = null;
var menuId = null;
var current_menu_id = null;

function init_device(){ 
    if(jQuery('.device.visible-sm:visible').length > 0)
    {
      jQuery('body').attr('class','device-sm');
    }
    if(jQuery('.device.visible-md:visible').length > 0)
    {
      jQuery('body').attr('class','device-md');
    }
    if(jQuery('.device.visible-lg:visible').length > 0)
    {
      jQuery('body').attr('class','device-lg');
    }
    if(jQuery('.device.visible-xs:visible').length > 0)
    {
      jQuery('body').attr('class','device-xs');
    }
}

function hide_submenu(func1){
  jQuery('#menu-main-menu>li').removeClass('active');
  jQuery('.sub-menu li').fadeOut(500, function(){
    jQuery('.sub-menu').slideUp(500);
  });

  if(func1){
    func1();
  }
}

function show_submenu(parent){
  parent = jQuery("#" + parent);
  var submenu = jQuery('.sub-menu', parent);

  jQuery(submenu).css({top: jQuery(parent).height()});
  jQuery('#menu-main-menu>li').removeClass('active');
  jQuery(parent).addClass('active');
  
  submenu.slideDown(500, function(){
    jQuery('li', submenu).fadeIn(500);
  });
}

function init_menu(){
  jQuery('#menu-main-menu .sub-menu').parent('li').addClass("parent");
  jQuery('#menu-main-menu .parent > a').append('<span class="caret"></span>')
  current_menu_id = jQuery('.current-menu-parent').attr('id');
  if(current_menu_id)
  { 
    setTimeout("show_submenu(current_menu_id);", 600);
  }

  jQuery('#menu-main-menu>li').hover(function(e){
      clearTimeout(menuTimeout);
      if(jQuery('.sub-menu:visible', this).length > 0)
      {
        return true;
      }

      if(jQuery(this).hasClass('parent')){
        menuId = jQuery(this).attr('id');
        hide_submenu(function(){
          menuTimeout = setTimeout("show_submenu(menuId);", 600);
        });
      }else{
        menuTimeout = setTimeout("hide_submenu();", 600);
      }
   }, function(){});

   jQuery('header').hover(function(){}, function(){
      menuTimeout = setTimeout("hide_submenu();", 600);
   });
}

function toggleMap() {
    
    if (!jQuery('#map').hasClass('map-show')) {
        jQuery('#map').addClass('map-show');
        jQuery('#map').hide();
    }
    
    jQuery('#map').slideToggle("slow", function(){
        jQuery("html, body").animate({ scrollTop: jQuery(document).height() }, 1000);
    });
}

function mapUp() {
    jQuery('#map').slideUp("slow", function(){
        //jQuery("html, body").animate({ scrollTop: jQuery(document).height() }, 1000);
    });
}

function init_map(){
  jQuery('#link-map').click(function(e){
      toggleMap();
      e.stopPropagation();    
  });
  jQuery('#map').click(function(e){
      e.stopPropagation();
  });
  jQuery('body').click(function(){
      mapUp();
  });
}

function search_products(query){
  jQuery('#input-search').attr('disabled', true);
  jQuery('#searched-product-results').html("");
  React.render(
    React.createElement(ProductSearch, {params: "q=" + query}),
    document.getElementById('searched-product-results')
  );
}

function search_journals(query){
  jQuery('#searched-journal-results').html("");
  React.render(
    React.createElement(PostSearch, {params: "post_type=post&category_name=journal&q=" + query}),
    document.getElementById('searched-journal-results')
  );
}

function search_projects(query){
  jQuery('#searched-project-results').html("");
  React.render(
    React.createElement(PostSearch, {params: "post_type=project&q=" + query}),
    document.getElementById('searched-project-results')
  );
}

function init_search(){
  jQuery('#icon-search').on('click', function(){
    jQuery('#search').height(jQuery(document).height());
    jQuery('#search').fadeIn();
  });

  jQuery('#icon-close').on('click', function(){
    jQuery('#search').fadeOut();
  });

  jQuery('#search form').on('submit', function(e){
    e.preventDefault();
    e.stopPropagation();
    var query = jQuery('#input-search').val();

    jQuery('#search-results').show();
    search_products(query);
    search_journals(query);
    search_projects(query);
  });
}

function init_resize(){
  jQuery(window).resize(function(){
    init_device();
  });
}

// init project entry point
jQuery(function($){
  init_menu(); 
  init_device();
  init_map();
  init_resize();
  init_search();
});



/* search reactjs goes right under here */
var ProductItem = React.createClass({displayName: "ProductItem",
  render: function() {
    return (
      React.createElement("div", {className: "product col-sm-4"}, 
        React.createElement("div", {className: "product-image"}, 
          React.createElement("a", {href: this.props.data.url}, 
            React.createElement("img", {className: "img-responsive", src: this.props.data.image})
          )
        ), 
        React.createElement("div", {className: "product-title"}, 
          React.createElement("a", {href: this.props.data.url}, 
            this.props.data.name
          )
        ), 
        React.createElement("div", {className: "product-price"}, 
          this.props.data.price
        )
      )
    );
  }
});

var ProductListMeta = React.createClass({displayName: "ProductListMeta",
  render: function() {
    var viewAllButton;

    if(this.props.data.count < this.props.data.found)
    {
      viewAllButton = React.createElement("a", {className: "view-all", href: "/store/catalogsearch/result?q=" + this.props.data.query}, "View all")
    }
    return (
      React.createElement("div", {className: "meta"}, 
        this.props.data.count, " of ", this.props.data.found, " results", 
        viewAllButton
      )
    );
  }
});

var ProductListBox = React.createClass({displayName: "ProductListBox",
  render: function() {
    var itemNodes = _.map(this.props.data, function (item) {
      return (
        React.createElement(ProductItem, {data: item})
      );
    });
    return (
      React.createElement("div", {className: "product-list row"}, 
        itemNodes
      )
    );
  }
});

var ProductSearch = React.createClass({displayName: "ProductSearch",
  getInitialState: function() {
    return {data: { items:[] }};
  },
  loadDataFromServer: function(){
    if (this.props.params == null){
      return;
    }

    jQuery.ajax({
      url: "/store/api-v1/product_search?" + this.props.params,
      dataType: 'json',
      cache: false,
      success: function(data) {
        this.setState({data: data});
        // stop product search loading
        jQuery(React.findDOMNode(this.refs.loader)).hide();
        jQuery('#input-search').attr('disabled', false);

      }.bind(this),
      error: function(xhr, status, err) {
        console.error(this.props.url, status, err.toString());
      }.bind(this)
    });
  },
  componentDidMount: function() {
    this.loadDataFromServer();
  },
  render: function() {
    return (
      React.createElement("div", {className: "results"}, 
        React.createElement("div", {className: "loader-container", ref: "loader"}, 
          React.createElement("div", {className: "loader"})
        ), 
        React.createElement(ProductListMeta, {data: this.state.data}), 
        React.createElement(ProductListBox, {data: this.state.data.items})
      )
    );
  }
});





var PostItem = React.createClass({displayName: "PostItem",
  render: function() {
    return (
      React.createElement("div", {className: "post col-sm-4"}, 
        React.createElement("div", {className: "post-image"}, 
          React.createElement("a", {href: this.props.data.url}, 
            React.createElement("img", {className: "img-responsive", src: this.props.data.image})
          )
        ), 
        React.createElement("div", {className: "post-title"}, 
          React.createElement("a", {href: this.props.data.url}, 
            this.props.data.name
          )
        ), 
        React.createElement("div", {className: "post-date"}, 
          this.props.data.date
        )
      )
    );
  }
});

var PostListMeta = React.createClass({displayName: "PostListMeta",
  render: function() {
    var viewAllButton;

    if(this.props.data.count < this.props.data.found)
    {
      viewAllButton = React.createElement("a", {className: "view-all", href: "/?"+ this.props.params + "&s=" + this.props.data.query}, "View all")
    }
    return (
      React.createElement("div", {className: "meta"}, 
        this.props.data.count, " of ", this.props.data.found, " results", 
        viewAllButton
      )
    );
  }
});

var PostListBox = React.createClass({displayName: "PostListBox",
  render: function() {
    var itemNodes = _.map(this.props.data, function (item) {
      return (
        React.createElement(PostItem, {data: item})
      );
    });
    return (
      React.createElement("div", {className: "post-list row"}, 
        itemNodes
      )
    );
  }
});

var PostSearch = React.createClass({displayName: "ProductSearch",
  getInitialState: function() {
    return {data: { items:[] }};
  },
  loadDataFromServer: function(){
    if (this.props.params == null){
      return;
    }

    jQuery.ajax({
      url: "/gmg-api-v1.php?" + this.props.params,
      dataType: 'json',
      cache: false,
      success: function(data) {
        this.setState({data: data});
        jQuery(React.findDOMNode(this.refs.loader)).hide();
      }.bind(this),
      error: function(xhr, status, err) {
        console.error(this.props.url, status, err.toString());
      }.bind(this)
    });
  },
  componentDidMount: function() {
    this.loadDataFromServer();
  },
  render: function() {
    return (
      React.createElement("div", {className: "results"}, 
        React.createElement("div", {className: "loader-container", ref: "loader"}, 
          React.createElement("div", {className: "loader"})
        ), 
        React.createElement(PostListMeta, {data: this.state.data, params: this.props.params}), 
        React.createElement(PostListBox, {data: this.state.data.items})
      )
    );
  }
});

//# sourceMappingURL=app.js.map