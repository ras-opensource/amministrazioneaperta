//DecoupledEditor.create(document.querySelector('#editor'), editorConfig).then(editor => {
//	document.querySelector('#editor-toolbar').appendChild(editor.ui.view.toolbar.element);
//	document.querySelector('#editor-menu-bar').appendChild(editor.ui.view.menuBarView.element);

//	return editor;
//});
import("./main.js")
.then(function(module)
{
    console.log("Modulo CKEditor5 caricato");
})
.catch(function(e){
    console.error(e);
});

webix.protoUI({
    name:"ckeditor5",
    $init:function(config){
        var html = "<div class='webix_ck_menubar'></div><div class='webix_ck_toolbar'></div>";
        var editor = "<div class='webix_ck_editor'></div>";
        html += config.mode == "document" ? ("<div class='webix_ck_body'>"+editor+"</div>") : editor;

        this.$view.innerHTML = html;
        this._waitEditor = webix.promise.defer();
        this.$ready.push(this._require_ckeditor());
    },
    defaults:{
        config:
        {
            cdn: false
        }
    },
    _require_ckeditor:function(){
        //console.log("_require_ckeditor",this);
        return webix.bind(this._render_ckeditor,this);
    },
    _render_ckeditor:function()
    {
        //console.log("_render_ckeditor",this);
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
        //console.log("_finalize_init",this);
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
            this._set_width(this.$width);
        }, this));

        this._set_height(this.$height);
        this._set_width(this.$width);
        this.setValue(this.config.value);
    },
    $setSize:function(x,y){
        if (webix.ui.view.prototype.$setSize.call(this, x, y) && this._body_container)
        {
            this._set_height(y);
            this._set_width(x);
        }
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
    _set_width:function(x){
        //console.log("Imposto la dimensione a: "+height);
        let width=x-2;

        if(this._tools_container) this._tools_container.style.width = width+"px";
        if(this._menu_container) this._menu_container.style.width = width+"px";
        if(this._body_container) 
        {
            //console.log("Imposto la larghezza a: "+width);
            this._body_container.style.width = (width-19)+"px";
        }
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

webix.protoUI({
    name:"ckeditor5_field",
    $init:function(config){
        var html = "<div class='webix_ck_menubar'></div><div class='webix_ck_toolbar'></div>";
        var editor = "<div class='webix_ck_editor'></div>"; 
        html += config.mode == "document" ? ("<div class='webix_ck_body'>"+editor+"</div>") : editor;
        if(config.label)
        {
            let label_div="";
            let labelPosition="row";
            if(config.labelPosition && config.labelPosition=="top") labelPosition="column";
            label_div="<div style='height:100%;width:100%;padding: 0px;margin:0px; display: flex; flex-direction:'"+labelPosition+";";

            let labelAlign="left";
            if(config.labelAlign && config.labelAlign=="center") labelAlign="center";
            if(config.labelAlign && config.labelAlign=="right") labelAlign="right";

            let labelWidth=0;
            if(config.labelWidth && config.labelWidth>0) labelWidth=config.labelWidth;

            html=label_div+"'><div class='webix_ck_label' style='height: 32px; min-width:"+labelWidth+"px; font-weight: bold; margin: 0px; padding: 5px; padding-right: 10px; text-align:"+labelAlign+"'>"+config.label+"</div><div style='border: 1px solid #ccd7e6;'>"+html+"</div></div>";
        }

        this.$view.innerHTML = html;
        this._waitEditor = webix.promise.defer();
        this.$ready.push(this._require_ckeditor());
    },
    defaults:{
        config:
        {
            cdn: false
        }
    },
    _require_ckeditor:function(){
        //console.log("_require_ckeditor",this);
        return webix.bind(this._render_ckeditor,this);
    },
    _render_ckeditor:function()
    {
        //console.log("_render_ckeditor",this);
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
        //console.log("_finalize_init",this.$view);
        this._label_container = this.$view.querySelector(".webix_ck_label");
        this._tools_container = this.$view.querySelector(".webix_ck_toolbar");
        this._tools_container.appendChild(editor.ui.view.toolbar.element);
        this._menu_container = this.$view.querySelector(".webix_ck_menubar");
        this._menu_container.appendChild(editor.ui.view.menuBarView.element);
        if(this.config.label) this._body_container = this.$view.childNodes[0].childNodes[1].childNodes[2];
        else this._body_container = this.$view.childNodes[2];

        this._editor = editor;		
        this._waitEditor.resolve(this._editor);

        // correct height on focus/blur
        editor.ui.focusTracker.on("change:isFocused", webix.bind(function(){
            this._set_height(this.$height);
            this._set_width(this.$width);
        }, this));

        this._set_height(this.$height);
        this._set_width(this.$width);
        this.setValue(this.config.value);
    },
    $setSize:function(x,y){
        if (webix.ui.view.prototype.$setSize.call(this, x, y) && this._body_container)
        {
            this._set_height(y);
            this._set_width(x);
        }
    },
    _set_height:function(y){
        var label = this._label_container, labelH=label ? label.clientHeight : 0;
        if(!this.config.labelPosition || this.config.labelPosition !="top") labelH=0;

        var toolbar = this._tools_container,
            toolH = toolbar ? toolbar.clientHeight+2 : 2;
        var menuBar	= this._menu_container,
            menuH = menuBar ? menuBar.clientHeight+2 : 2;// 2px for borders
        var height = (y-toolH-menuH-labelH)+"px";
        //console.log("Imposto la dimensione a: "+height);
        if(this._body_container) this._body_container.style.height = height;
    },
    _set_width:function(x){
        //console.log("Imposto la dimensione a: "+height);
        let labelWidth= this._label_container ? this._label_container.clientWidth+2 : 2;
        if(this.config.labelPosition && this.config.labelPosition=="top") labelWidth = 2;
        let width=(x-labelWidth);

        if(this._tools_container) this._tools_container.style.width = width+"px";
        if(this._menu_container) this._menu_container.style.width = width+"px";
        if(this._body_container) 
        {
            //console.log("Imposto la larghezza a: "+width);
            this._body_container.style.width = (width-19)+"px";
        }
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