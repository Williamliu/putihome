LWH.HTML5Player = function( opts ) {
	$.extend(this.settings, opts);
	var _self = this;
	this.mediaObj = null;


	//internal  method	
	var _constructor = function() {
		// create media object :  audio or video
		var tmp_obj = $(_self.settings.type, _self.settings.container);
		if( tmp_obj.length <= 0 ) {
			tmp_obj = document.createElement(_self.settings.type);
			$(_self.settings.container).append(tmp_obj);
		} else {
			tmp_obj = tmp_obj[0];
		}
		_self.mediaObj = tmp_obj;
		
		_self.settings.type = _self.settings.type.toLowerCase();
	};
	_constructor();
}

with(LWH.HTML5Player) {
	prototype.mediaType = {
	  	audio: {
		  	mp3: 	"audio/mp3",
		  	ogg: 	"audio/ogg",
		  	wav: 	"audio/wav"
	  	},
	  	video: {
		  	mp4: 	"video/mp4",
		  	ogg: 	"video/ogg",
		  	webm: 	"video/webm"
	  	}
  	};

	prototype.settings = {
		play_id:	-1,
		container:	"body",
		type: 		"audio"   // "audio" , "video"; default set to 'audio"
	};

	prototype.nav		= {
		play_id:	-1,
		play_sn:	-1
	};
	
	prototype.bSupport = function() {
		return !!this.mediaObj.canPlayType;
	};
	
	prototype.tSupport = function( extName ) {   // "mp3", "ogg", "webm"
		extName = extName.toLowerCase();
		return !!(this.mediaObj.canPlayType && this.mediaObj.canPlayType(this.mediaType[this.settings.type][extName]));
	};
	
	prototype.playlist = [];
	

}