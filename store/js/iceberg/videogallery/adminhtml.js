// -----------------------------------------------------------------------------------
//  Icbeberg Commerce 2010


//  Video Gallery
// -----------------------------------------------------------------------------------
    
var newVideoRowTemplate = '<tr id="{{containerId}}-video-new-{{id}}">'
	+ '<td class="cell-image"><img src="{{thumbnail}}" width="100" alt="{{label}}" /></td>'
    + '<td class="cell-label"><input class="input-text" type="hidden" name="{{attrName}}[new_videos][{{id}}][url]" value="{{url}}"><input class="input-text" type="text" name="{{attrName}}[new_videos][{{id}}][label]" value="{{label}}"><strong>New Video.  Changes must be saved.</strong></td>'
    + '<td class="cell-description"><textarea class="input-text" name="{{attrName}}[new_videos][{{id}}][description]">{{description}}</textarea></td>'
    + '<td class="cell-provider" style="display:none">{{provider}}</td>'
    + '<td class="cell-value" style="display:none">{{value}}</td>'
    + '<td class="cell-position"><input class="input-text validate-number" name="{{attrName}}[new_videos][{{id}}][position]" type="text" value="{{position}}"></td>'
    + '<td class="cell-disable a-center"><input type="checkbox" {{disabled}} name="{{attrName}}[new_videos][{{id}}][disabled]" value="checked"></td>'
    + '<td class="last">'
    + '<button title="Delete Tier" type="button" class="scalable delete icon-btn delete-product-option" onclick="$(\'{{containerId}}-video-new-{{id}}\').remove(); return false;">'
    + '<span>Delete</span></button></td>'
    + '</tr>';
    
VideoGallery_Admin = Class.create();
VideoGallery_Admin.prototype = {
	newVideoTemplate: new Template(newVideoRowTemplate, new RegExp('(^|.|\\r|\\n)({{\\s*(\\w+)\\s*}})', "")),
    videos : [],
    
    newIncrement: 0,
    idIncrement :1,
    containerId :'',
    container :null,
    newVideoCheckUrl: null,
    
    attrName: null,
    
    initialize : function(containerId , attrName , checkUrl) {
        this.containerId = containerId, this.container = $(this.containerId);
        this.newVideoCheckUrl = checkUrl;
        this.attrName = attrName;
        
        this.videos = this.getElement('save').value.evalJSON();
    },
    getElement : function(name) {
        return $(this.containerId + '_' + name);
    },
    updateVideo : function(value_id) {
    	var index = this.getIndexByValueId(value_id);

    	if( index == undefined)
    		index = this.videos.length;
    	
        if( value_id == 'new' )
        {
        	// THIS CASE IS NO LONGER IN USE
        	this.videos[index] = { 
        			value_id: 'new' , 
        			url: this.getFileElement(value_id, 'cell-url input').value ,
        			label: this.getFileElement(value_id, 'cell-label input').value ,
        			position: this.getFileElement(value_id, 'cell-position input').value ,
        			disabled: (this.getFileElement(value_id,'cell-disable input').checked ? 1 : 0)
        	}
        }else{
    
	        this.videos[index].label = this
	                .getFileElement(value_id, 'cell-label input').value;
	        this.videos[index].description = this
            		.getFileElement(value_id, 'cell-description textarea').value;
	        this.videos[index].provider = this.getFileElement(value_id,
	       			'cell-provider input').value;
	        this.videos[index].value = this.getFileElement(value_id,
	        		'cell-value input').value;
	        this.videos[index].position = this.getFileElement(value_id,
	                'cell-position input').value;
	        this.videos[index].removed = (this.getFileElement(value_id,
	                'cell-remove input').checked ? 1 : 0);
	        this.videos[index].disabled = (this.getFileElement(value_id,
	                'cell-disable input').checked ? 1 : 0);
        }
        
        try
        {
        	this.getElement('save').value = this.videos.toJSON();
        }
        catch (e)
        {
        	this.getElement('save').value = Object.toJSON(this.videos);
        }
        //this.updateState(value_id);
        this.container.setHasChanges();
    },
    getIndexByValueId : function(value_id) {
        var index;
        this.videos.each( function(item, i) {
            if (item.value_id == value_id) {
                index = i;
            }
        });
        return index;
    },
    getFileElement : function(value_id, element) {
        var selector = '#' + this.prepareId(value_id) + ' .' + element;
        var elems = $$(selector);
        if (!elems[0]) {
            try {
                console.log(selector);
            } catch (e2) {
                alert(selector);
            }
        }

        return $$('#' + this.prepareId(value_id) + ' .' + element)[0];
    },
    prepareId : function(value_id) {
        return this.containerId + '-video-' + value_id;
    },
    loadImage : function(value_id) {
        var index = this.getIndexByValueId(value_id);
        var image = this.videos[ index ];
        this.getFileElement(value_id, 'cell-image img').src = image.url;
        this.getFileElement(value_id, 'cell-image img').show();
        this.getFileElement(value_id, 'cell-image .place-holder').hide();
    },
    addNewVideoButton : function()
    {
    	value_id = 'new';
    	
		urlEl = this.getFileElement(value_id, 'cell-url input');
		labelEl = this.getFileElement(value_id, 'cell-label input');
		descriptionEl = this.getFileElement(value_id, 'cell-description textarea');
		positionEl = this.getFileElement(value_id, 'cell-position input');
		disabledEl = this.getFileElement(value_id,'cell-disable input');
		
		data = {
			url: urlEl.value ,
			label: this.cleanString( labelEl.value ) , 
			position: isNaN(parseInt(positionEl.value)) ? 0 : parseInt( positionEl.value ) , 
			disabled: disabledEl.checked ? 'checked' : '',
			description: this.cleanString( descriptionEl.value )
		}
		
		this.checkVideo( data , true );
    },
    checkVideo : function ( data , clear )
    {
    	obj = this;
    	new Ajax.Request( this.newVideoCheckUrl , {
		  method: 'post',
		  parameters: { url: data.url },
		  onSuccess: function(transport) {
			jsonResponse=transport.responseText.evalJSON();
			
		    if ( jsonResponse.result )
		    {
		      if ( clear )
		      {
		    	  obj.getFileElement('new', 'cell-url input').value='';
		    	  obj.getFileElement('new', 'cell-label input').value='';
		    	  obj.getFileElement('new', 'cell-description textarea').value='';
		    	  obj.getFileElement('new', 'cell-position input').value=data.position+1;
		      }

		      data.thumbnail = jsonResponse.thumbnail;
		      data.provider = jsonResponse.provider;
		      data.value = jsonResponse.value;
		      if ( data.label == '' )
		      {
		      	data.label = jsonResponse.label;
		      }
		      
		      if ( data.description == '' )
		      {
		      	data.description = jsonResponse.description;
		      }
		      
		      
		      
		      obj.addNewVideoRow( data );
		      
		      obj.getElement('error').hide();
		      
		    }else{
		      obj.getElement('error').innerHTML = jsonResponse.message ? jsonResponse.message : 'No supported videos were found at the given URL.';
		      obj.getElement('error').show();
		    }
		  }
		});
    },
    addNewVideoRow : function ( data )
    {
    	data.containerId = this.containerId;
    	data.id = ++this.newIncrement;
    	data.attrName = this.attrName;

    	Element.insert( $(this.containerId + '_list'), {
            bottom : this.newVideoTemplate.evaluate(data)
        });
    	
    	this.container.setHasChanges();
    },
    addYoutubeVideo : function ( value , url, thumbnail, label, description )
    {
    	data = {
    			value: value,
    			url: url,
    			thumbnail: thumbnail,
    			label: this.cleanString( label ),
    			provider: 'youtube',
    			value_id: 'new',
    			position: 0,
    			disabled: false,
    			description: description
    	}
    	
    	this.addNewVideoRow( data );
    },
    cleanString : function ( str )
    {
    	return String( str ).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }
};