//DecoupledEditor.create(document.querySelector('#editor'), editorConfig).then(editor => {
//	document.querySelector('#editor-toolbar').appendChild(editor.ui.view.toolbar.element);
//	document.querySelector('#editor-menu-bar').appendChild(editor.ui.view.menuBarView.element);

//	return editor;
//});
webix.protoUI({
    name:"ckeditor5",
    $init:function(config){
        var html = "<div class='webix_ck_menubar'></div><div class='webix_ck_toolbar'></div>";
        var editor = "<div class='webix_ck_editor'></div>";
        html += config.mode == "document" ? ("<div class='webix_ck_body'>"+editor+"</div>") : editor;

        this.$view.innerHTML = html;
        this._waitEditor = webix.promise.defer();
        this.$ready.push(this._require_ckeditor);
    },
    defaults:{
        config:
        {
            cdn: false
        }
    },
    _require_ckeditor:function(){
        //if (this.config.cdn === false){
        //return this._render_ckeditor();

        //};

        
        // we use DecoupledEditor only
        //var cdn = this.config.cdn || "https://cdn.ckeditor.com/ckeditor5/18.0.0/decoupled-document";
        import("./main.js").then((module) =>{
            //console.log("import",module,this);
            this._render_ckeditor();
        });
        /*
        webix.require(["./main.js"])
            .then( webix.bind(this._render_ckeditor, this) )
            .catch(function(e){
                console.log(e);
            });*/
    },
    _render_ckeditor:function()
    {
        //console.log("window",window);
        //console.log("editorConfig",window.ckeditor5_config);
        //console.log("editor",window.ckeditor5_editor);

        this.config.config = window.ckeditor5_config;
        var config = webix.extend({
            toolbar: {
                shouldNotGroupWhenFull: true
            }
        }, this.config.config, true);

        var editor = window.ckeditor5_editor//support Document Editor built with online builder

        editor.create(this.$view.querySelector(".webix_ck_editor"), config)
            .then(webix.bind(this._finalize_init, this))
            .catch(function(e){
                console.error(e);
            });					
    },
    _finalize_init:function(editor){
        this._tools_container = this.$view.querySelector(".webix_ck_toolbar");
        this._tools_container.appendChild(editor.ui.view.toolbar.element);
        this._menu_container = this.$view.querySelector(".webix_ck_menubar");
        this._menu_container.appendChild(editor.ui.view.menuBarView.element);
        this._body_container = this.$view.childNodes[2];

        this._editor = editor;		
        this._waitEditor.resolve(this._editor);

        // correct height on focus/blur
        editor.ui.focusTracker.on("change:isFocused", webix.bind(function(){
            this._set_height(this.$height);
        }, this));

        this._set_height(this.$height);
        this.setValue(this.config.value);
    },
    $setSize:function(x,y){
        if (webix.ui.view.prototype.$setSize.call(this, x, y) && this._body_container)
            this._set_height(y);
    },
    _set_height:function(y){
        var toolbar = this._tools_container,
            toolH = toolbar ? toolbar.clientHeight+2 : 2;
        var menuBar	= this._menu_container,
            menuH = menuBar ? menuBar.clientHeight+2 : 2;// 2px for borders
        var height = (y-toolH-menuH)+"px";
        //console.log("Imposto la dimensione a: "+height);
        this._body_container.style.height = height;

    },
    getEditor:function(wait){
        return wait ? this._waitEditor : this._editor;
    },
    setValue:function(value){
        this.config.value = value;
        this.getEditor(true).then(function(editor){
            editor.setData(value);
        });
    },
    getValue:function(value){
        return this._editor ? this._editor.getData() : this.config.value;
    }
}, webix.ui.view);

