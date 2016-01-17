CKEDITOR.plugins.add( 'backup',{
    lang:'ru,en,de',
    init:function(editor){
        var delay = 5000;
        editor.addCommand( 'backup', new CKEDITOR.command(editor, {
        exec: function( editor ) {
        this.toggleState();
        if (editor.getCommand('backup').state === CKEDITOR.TRISTATE_OFF)
          $('#divbackup_'+editor.name).css('display','none');
        else
             $('#divbackup_'+editor.name).css('display','inline');
             editor.backup();
      }
        }));
      if (editor.config.backup_on_start)
        editor.getCommand('backup').state = CKEDITOR.TRISTATE_ON;
    if (editor.config.backup_save_delay)
        var delay = editor.config.backup_save_delay;
           
        editor.ui.addButton( 'Backup',
        {
            label: editor.lang.backup.button,
            command: 'backup',
            icon: this.path + 'images/icon.png'
        });
        
        editor.on( 'instanceReady', function(e) { 
            var display = editor.getCommand('backup').state === CKEDITOR.TRISTATE_OFF ? 'display:none;' : 'display:inline;';
            var div = document.createElement('div'),
                select = 0,
                style =  display + ' margin-left:10px;position:relative;margin-top:5px;overflow:hidden;float:right;',
                bname =  'backup_'+editor.name, init = true, oldtext = '';
            div.setAttribute('style',style);
            div.setAttribute('id','divbackup_'+editor.name);
            if( localStorage.getItem( bname) == undefined )
                localStorage.setItem( bname,'{}'); // создаем наше хранилище
            var format = function(_time){
                var n = new Date(parseInt(_time));
                var frm = function(dd){
                    if ( dd < 10 ) dd = '0' + dd;
                    return dd;
                };
                return n.getHours()+'.'+frm(n.getMinutes())+'.'+frm(n.getSeconds());
            };
            editor.backup = function(del){
                if (editor.getCommand('backup').state === CKEDITOR.TRISTATE_OFF) return;
                var chages = false,now = new Date().getTime(),bu = {};
                if(del!='del'){
                    var text = editor.getSnapshot();
                    if( text!='' ){
                        if( localStorage.getItem( bname) && oldtext && text!=oldtext ){
                            bu = JSON.parse(localStorage.getItem( bname));
                            bu[now] = text;
                            localStorage.setItem( bname,JSON.stringify(bu));
                            chages = true;
                        }
                    }
                }else{
                  var selTime = $('#backuper_'+editor.name).val();
                  var selIndex = $('#backuper_'+editor.name).prop('selectedIndex');
                    if( selTime === '---' && confirm( editor.lang.backup.mess1 ) ){
                        localStorage.setItem( bname,'{}');
                        chages = true;
                    }    else if (selTime !== '---' && confirm( editor.lang.backup.mess2 )) {
                            bu = JSON.parse(localStorage.getItem( bname));
                            var bu1 = {};
                            for(var t in bu) {
                                if (t != (selTime)) {
                                    bu1[t] = bu[t];
                                }
                            }
                            localStorage.setItem( bname,JSON.stringify(bu1));
                            $('#backuper_'+editor.name).prop('selectedIndex',0);
                            bu = bu1;
                            chages = true;
                    }
                }
                if( chages || init){
                    if(init&&localStorage.getItem( bname)){
                        bu = JSON.parse(localStorage.getItem( bname));
                    }
                    var opt = '<option>---</option>';
                    for(var r in bu)
                        opt+='<option value="'+r+'">'+format(r)+'</option>';
                    //select.setHtml(opt); does not work with IE 8
                    $('#backuper_'+editor.name).html(opt);
                    init = false;
                }
                oldtext = text;
            },
            editor.restore = function(){
                if (editor.getCommand('backup').state === CKEDITOR.TRISTATE_OFF) return;
                var text = editor.getSnapshot();
                var val = select.getValue();
                var bu = JSON.parse( localStorage.getItem( bname) );
                if( bu[val]!=undefined && (text==''||confirm( editor.lang.backup.mess)) ){
                    editor.loadSnapshot( bu[val] );
                    restored = true;
                }
            };
            var restored = false;
            var mixer = 0;
            editor.on( 'change',function(){
                clearTimeout(mixer);
                if (!restored) {
                    mixer = setTimeout(function(){
                        editor.backup();
                    },delay);
                }
                restored = false;
            });
            div.innerHTML = '<select style="margin-top:-5px;" id="backuper_'+editor.name+'"></select>&nbsp;<input type="image" value="del" onclick="CKEDITOR.instances[\''+editor.name+'\'].backup(\'del\'); return false;" src="'+CKEDITOR.basePath+'plugins/backup/images/clear.png"/>';
            //div.onchange = editor.restore; IE8 problems
            CKEDITOR.document.getById( editor.ui.spaceId?editor.ui.spaceId("bottom"): 'cke_bottom_'+editor.name ).append(new CKEDITOR.dom.node(div));
            select = CKEDITOR.document.getById( 'backuper_'+editor.name );
            $('#backuper_'+editor.name).change(editor.restore ); 
            editor.backup();
        });
    }
});