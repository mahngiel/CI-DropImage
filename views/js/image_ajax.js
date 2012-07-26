(function() {
/*
This javascript is based off of Craig Buckler's original filedrag.js
	[ filedrag.js - HTML5 File Drag & Drop demonstration ]
	[ Featured on SitePoint.com ]
	[ Originally developed by Craig Buckler (@craigbuckler) of OptimalWorks.net ]

Modified for Mahngiel's (Kris Reeck @mahngiel) CodeIgniter DropImage library
Developed on jQuery 1.7.2
*/

	// output information
	function Output(msg) {
		var m = $("#messages");
		m.html(msg + m.html() );
	}


	// file drag hover
	function FileDragHover(e) {
		e.stopPropagation();
		e.preventDefault();
		e.target.className = (e.type == "dragover" ? "hover" : "");
	}


	// file selection
	function FileSelectHandler(e) {

		// cancel event and hover styling
		FileDragHover(e);

		// fetch FileList object
		var files = e.target.files || e.originalEvent.dataTransfer.files;	

		// uploading one or many?
		if( $('#fileselect').attr('multiple') ) { 		
			// process all File objects
			for (var i = 0, f; f = files[i]; i++) {
				ParseFile(f);
				UploadFile(f);
			}
		} else { 		
			// process only one file
			var f = files[0];
			ParseFile(f);
			UploadFile(f);		
		}
	}

	// output file information
	function ParseFile(file) {
		// display an image
		if (file.type.indexOf("image") == 0) {
			var reader = new FileReader();
			reader.onload = function(e) {
				Output(
					'<img src="' + e.target.result + '" />'
				);
			}
			reader.readAsDataURL(file);
		}
	}


	function UploadFile(file) {

		var xhr = new XMLHttpRequest();
		if (xhr.upload && file.type.indexOf('image') == 0 ) {
			// create progress bar
			var o = $("#progress");
			var progress = o.append('<p>').text('uploading ' + file.name).append('</p>');

			// progress bar
			xhr.upload.addEventListener("progress", function(e) {
				var pc = parseInt( e.loaded / e.total * 100);
				progress.children('p').progressbar({value: pc});
			}, false);

			// file received/failed
			xhr.onreadystatechange = function(e) {
				if (xhr.readyState == 4) {
					progress.addClass = (xhr.status == 200 ? "success" : "failure");
				}
			};

			// start upload
			var param = $('.imageAjax').attr('data-param');
			var entry = $('.imageAjax').attr('data-entry');
			xhr.open("POST", document.location.hostname + 'upload/upload?action=' + param + '&entry=' + entry, true);
			xhr.setRequestHeader("X_FILENAME", file.name.replace(/\s+/g, ''));
			xhr.setRequestHeader("X_REQUESTED_WITH", "XMLHttpRequest");
			xhr.send(file);

		}
	}


	// initialize
	function Init() {

		var fileselect = $("#fileselect"),
			filedrag = $("#filedrag"),
			submitbutton = $("#submitbutton");

		// file select
		fileselect.on("change", FileSelectHandler);

		// is XHR2 available?
		var xhr = new XMLHttpRequest();
		if (xhr.upload) {

			// file drop
			filedrag.bind("dragover", FileDragHover);
			filedrag.bind("dragleave", FileDragHover);
			filedrag.bind("drop", FileSelectHandler);
		}

	}

	// call initialization file
	if (window.File && window.FileList && window.FileReader && $.cookie('csrf_merciless_cookie') ) {
		Init();
	}


})();