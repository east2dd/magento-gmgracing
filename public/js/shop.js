var app = angular.module('GmgStoreApp', ['ngCookies']);
var api_path = "/store/api-v1";
jQuery.cookie.json = true;
jQuery.cookie.defaults.expires = 7
jQuery.cookie.defaults.path = "/";

app.config(['$httpProvider', function ($httpProvider) {
    $httpProvider.defaults.transformRequest = function(d, headers){
      jQuery('.loading-animation').addClass('loading');
    }
    
    var $http,
        interceptor = ['$q', '$injector', function ($q, $injector) {
            var error;

            function success(response) {
                // get $http via $injector because of circular dependency problem
                $http = $http || $injector.get('$http');
                if($http.pendingRequests.length < 1) {
                    jQuery('.loading-animation').removeClass('loading');
                }
                return response;
            }

            function error(response) {
                // get $http via $injector because of circular dependency problem
                $http = $http || $injector.get('$http');
                if($http.pendingRequests.length < 1) {
                    jQuery('.loading-animation').removeClass('loading');
                }
                return $q.reject(response);
            }

            return function (promise) {
                jQuery('.loading-animation').addClass('loading');
                return promise.then(success, error);
            }
        }];

    $httpProvider.responseInterceptors.push(interceptor);
}]);

app.controller('GmgStoreCtrl', function ($rootScope, $http, $timeout) {
  $rootScope.yearOptions = [];
  $rootScope.makeOptions = [];
  $rootScope.modelOptions = [];
  $rootScope.categories = [];

  $rootScope.filter = {};
  $rootScope.filterYear = {};
  $rootScope.filterMake = {};
  $rootScope.filterModel = {};

  if (jQuery.cookie('filter')){
    $rootScope.filter = jQuery.cookie('filter');
    $rootScope.selected_car = $rootScope.filter.year + ' ' + $rootScope.filter.make + ' ' + $rootScope.filter.model;
  }

  $rootScope.is_selected_category = function(category){
    var filter = jQuery.cookie('filter');
    return category.id == filter.category
  }

  find_object = function(collection, id){
    return _.find(collection, function(obj){
      return obj.id == id;
    });
  }

  load_years = function(){

    $rootScope.makeOptions = [];
    $rootScope.modelOptions = [];
    $rootScope.categories = [];

    $http.get(api_path + '/category_children', {params: {id: 2}} ).success(function(data) {
      $rootScope.yearOptions = data;
    }).error(function(data) {
      $rootScope.yearOptions = [];
    });
  }

  load_makes = function(){
    $rootScope.makeOptions = [];
    $rootScope.modelOptions = [];
    $rootScope.categories = [];

    $http.get(api_path + '/category_children', {params: {id: $rootScope.filter.year}} ).success(function(data) {
      $rootScope.makeOptions = data;
    }).error(function(data) {
      $rootScope.makeOptions = [];
    });
  }

  load_models = function(){
    $rootScope.modelOptions = [];
    $rootScope.categories = [];
    $http.get(api_path + '/category_children', {params: {id: $rootScope.filter.make}} ).success(function(data) {
      $rootScope.modelOptions = data;
    }).error(function(data) {
      $rootScope.modelOptions = [];
    });
  }

  load_categories = function(){
    $rootScope.categories = [];
    $http.get(api_path + '/category_children', {params: {id: $rootScope.filter.model}} ).success(function(data) {
      $rootScope.categories = data;
    }).error(function(data) {
      $rootScope.categories = [];
    });
  }

  save_filter = function(){
    jQuery.cookie('filter', $rootScope.filter);
  }

  $rootScope.set_category = function(category){
    $rootScope.filter.category = category.id;
    save_filter();
  }

  $rootScope.go = function(){
    var category = find_object($rootScope.modelOptions, $rootScope.filter.model);

    if(!category){
      $rootScope.filter.model = null;
      category = find_object($rootScope.makeOptions, $rootScope.filter.make);
    }

    if (category){
      save_filter();
      window.location.href = category.url;
    }
  }

  $rootScope.$watch("yearOptions", function(v, v1){
    $rootScope.filterYear = find_object($rootScope.yearOptions, $rootScope.filter.year);
  });

  $rootScope.$watch("filter.year", function(v, v1){

    if(v){
      $rootScope.filterYear = find_object($rootScope.yearOptions, v);
      load_makes();
    }
  });


  $rootScope.$watch("makeOptions.length", function(v, v1){
    $rootScope.filterMake = find_object($rootScope.makeOptions, $rootScope.filter.make);
  });
  
  $rootScope.$watch("filter.make", function(v, v1){
    if (v){
      $rootScope.filterMake = find_object($rootScope.makeOptions, v);
      load_models();
    }
  });


  $rootScope.$watch("modelOptions.length", function(v, v1){
    $rootScope.filterModel = find_object($rootScope.modelOptions, $rootScope.filter.model);
  });

  $rootScope.$watch("filter.model", function(v, v1){
    if (v){
      $rootScope.filterModel = find_object($rootScope.modelOptions, v);
      load_categories();
    }
  });

  load_years();
});

//# sourceMappingURL=shop.js.map