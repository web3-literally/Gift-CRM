function lockScreen(){
    $('.lock').show();
}
function unlockScreen(){
    $('.lock').hide();
}
function ajaxFn(data,dataType,fn){
        lockScreen();
        $.ajax(
        {
                url:'./partials/giftAjax.php',
                type:'post',
                data:data,
                dataType:dataType,
                success:function(res){
                    unlockScreen();
                    fn(res);
                },
                error: function(err){
                    unlockScreen(); 
                    fn(err);
                }
        });
}
var initFolderHandle = function(){
    var cur_id,cur_level,cur_type;
    var clipboard = {type:'copy',id:0};
    function constructTree(list){
        for (var index = 0; index < list.length; index++) {
            const item = list[index];
            var $ul  = $('>ul',$('.folder-item[cur_id='+item.pid+']').parent());
            var html = '<li class="tree-item closed"><a class="folder-item" cur_id='+item.id+' level='+item.level+' type='+item.type+'>'
                + '<input type="checkbox"> ' + (item.type == 1 ? '<i class="open-close-folder fa fa-caret-right"></i> ' : "") + '<i class="fa ' + (item.type == 1 ? "fa-folder" : "fa-list") + '"></i> <b>' + item.name + '</b></a><ul></ul></li>';
            $ul.append(html);  
            constructTree(item.list || []);  
        }
    }
    $('.file-explorer').append('<li id="root" class="tree-item closed"><a class="folder-item active" cur_id="0" level="0" type="1">'
        +'<input type="checkbox"> <i class="open-close-folder fa fa-caret-right"></i>  <i class="fa fa-folder"></i> <b>Gift Organizer</b></a><ul></ul></li>')
    .append('<div class="contextmenu">'
            +'<li class="btn-folder-add-to"><a><i class="fa fa-plus text-green"></i> Add</a></li>'
            +'<li class="btn-folder-remove"><a><i class="fa fa-trash text-red"></i> Remove</a></li>'
            +'<li class="btn-folder-copy"><a><i class="fa fa-copy text-blue"></i> Copy</a></li>'
            +'<li class="btn-folder-cut"><a><i class="fa fa-cut text-red"></i> Cut</a></li>'
            +'<li class="btn-folder-paste"><a><i class="fa fa-paste text-green"></i> Paste</a></li>'
            +'<li class="btn-folder-rename"><a><i class="fa fa-exchange text-blue"></i> Rename</a></li>'
            +'</div>')
    .click(function () {
        $('.folder-item').removeClass('focus');
        $('.contextmenu').hide();
    });            
    $('#root').on('click','.open-close-folder',function(){
       $(this).toggleClass('fa-caret-right')
       .toggleClass('fa-caret-down');
       $(this).parent().parent().toggleClass('closed');
    });  
    $('#root').on('click', '.folder-item', function(){
        $('.folder-item').removeClass('active')
        $(this).addClass('active');
    });
    $('#root').on('contextmenu','.folder-item',function(e){
        $('.folder-item').removeClass('focus');
        $(this).addClass('focus');
        var rect = $('.file-explorer')[0].getBoundingClientRect();
        var x = e.pageX - rect.x;
        var y = e.pageY - rect.y;
        cur_id = $(this).attr('cur_id');
        cur_level = $(this).attr('level');
        cur_type = $(this).attr('type');
        $('.contextmenu li').show();
        if($(this).attr('type')==2){
            $('.btn-folder-add-to').hide();
            $('.btn-folder-paste').hide();
        }
        if($(this).attr('level')==0){
            $('.btn-folder-remove').hide();
            $('.btn-folder-copy').hide();
            $('.btn-folder-cut').hide();
            $('.btn-folder-rename').hide();
        }
        if(clipboard.id == 0)
            $('.btn-folder-paste').hide();
        else{    
            var ids = []
            $(this).parents('.tree-item').each(function(index,item){
                    ids.push($('>a',item).attr('cur_id'));
            });
            if(ids.indexOf(clipboard.id)>-1)  ///// block the folder to itself or itself's children folder
                $('.btn-folder-paste').hide();
        }
        

        $('.contextmenu').css({top:y,left:x}).show();
        e.preventDefault();
    });
    $('.btn-select').click(function(){
        var state = $(this).attr('state');
        if (state == "select") 
        {
             $(this).attr('state',"unselect");
             $(this).text('Select');
        }
        else{
            $(this).attr('state',"select");
            $(this).text('Unselect');
        }
        if(state != "select")$('#root input[type="checkbox"]').show();
        else $('#root input[type="checkbox"]').hide();
    });
    $('.btn-folder-add').click(function(){
        cur_id = $('.folder-item.active').attr('cur_id')
        cur_level = $('.folder-item.active').attr('level');
        cur_type = $('.folder-item.active').attr('type');
        if(cur_type==2){
           toastr.warning('Can not add folder/list to list!');  
           return;
        }
        $('.add-to-folder').val(cur_id);
        $('.add-to-folder-level').val(cur_level);
        var folderName = $('.folder-item[cur_id="' + cur_id + '"] b').text();
        $('.append-to-label').text(folderName);
        $('#addModal').modal();
    });
    $('.btn-folder-add-to').click(function(){
        $('.add-to-folder').val(cur_id);
        $('.add-to-folder-level').val(cur_level);
        var folderName = $('.folder-item[cur_id="' + cur_id + '"] b').text();
        $('.append-to-label').text(folderName);
        $('#addModal').modal();
    });
    $('.btn-folder-rename').click(function(){
       
        var $b = $('.folder-item[cur_id="' + cur_id + '"] b');
        var name = $b.text();
        $b.html('');
        $('<input class="name-editor" type="text" value="'+name+'">').appendTo($b)
        .focus()
        .blur(function(){
           $b.text(name);  
        })
        .keyup(function(e){
            if(e.key == 'Enter'){
                if(!this.value.length)return;
                var data = {type:'change-name',name:this.value};
                data.id = $('.folder-item[cur_id="' + cur_id + '"]').attr('cur_id');
                
                ajaxFn(data,'json',function(res){
                    if(res.success){
                        toastr.success('successfully changed!');
                        $b.text(data.name);
                    }
                    else{
                        toastr.warning('Emit error while change on the server!');
                        $b.text(name);
                    }
                })

           }
        })[0].setSelectionRange(0,name.length);
        
    });
    $('.btn-folder-remove').click(function () {
        
        var $b = $('.folder-item[cur_id="' + cur_id + '"] b');
        var name = $b.text();
        bootbox.confirm('Do you want remove the '+(cur_type==1?"folder":"list")+' "'+name+'" realy?',function(isConfirmed){
            if (isConfirmed){
                ajaxFn({id:cur_id,type:"remove"}, 'json', function (res) {
                    if (res.success) {
                        toastr.success('successfully removed!');
                        $('.folder-item[cur_id="' + cur_id + '"]').parent().remove();
                    }
                    else {
                        toastr.warning('Emit error while remove on the server!');
                        $b.text(name);
                    }
                })
            }
        });
    });
    $('.btn-folder-copy').click(function(){
         clipboard.type = 'copy';
         clipboard.id = cur_id;
    });
    $('.btn-folder-cut').click(function () {
        clipboard.type = 'cut';
        clipboard.id = cur_id;
    });
    $('.btn-folder-paste').click(function () {
        var level = $('.folder-item[cur_id="' + clipboard.id + '"]').attr('level') 
        var childrens =$('.folder-item',$('.folder-item[cur_id="' + clipboard.id + '"]').parent());
        var leafLevel = level;
        childrens.each(function(index,item){
           var l = $(item).attr('level');
            if ($(item).attr('type')==2)return;
           if(l>leafLevel)leafLevel=l;
        });
        ////// saved low level to leaf
        stairs =  leafLevel - level +  1;
        if(cur_level*1+stairs>4)
        {
            toastr.warning('Can not paste beacuse the folder level will big than 4!<br>'+
                           'source level is '+stairs+', and current folder level is '+cur_level);
            return;
        }
        if(clipboard.type == 'cut'){

         ajaxFn({type:'cut-paste',from:clipboard.id,to:cur_id},'json',function(res){
             if(res.success){
                 toastr.success('Successfully pasted!');
                 var ele = $('.folder-item[cur_id="' + clipboard.id + '"]').parent();
                 $('.folder-item[cur_id="' + cur_id + '"]').next().append(ele[0].outerHTML);
                 ele.remove();
                 ele = $('.folder-item[cur_id="' + clipboard.id + '"]').parent();
                 var fixLevel = cur_level - level + 1;
                 $('.folder-item', ele).each(function (index, item) {
                     $(item).attr('level', $(item).attr('level') * 1 + fixLevel);
                 })
             }
             else{
                 toastr.warning('Error while pasting!');
             }
         });   
         

        }
        if(clipboard.type == 'copy'){
            ajaxFn({ type: 'copy-paste', from: clipboard.id, to: cur_id,level:cur_level }, 'json', function (res) {
                if (res.success) {
                    toastr.success('Successfully pasted!');
                    constructTree(res.list);
                }
                else {
                    toastr.warning('Error while pasting!');
                }
            });   
        }
        
        
    });
    $('.btn-save-folder').click(function(){
        var data = {};
        data.pid = $('.add-to-folder').val();
        var level = $('.add-to-folder-level').val();
        data.type = $('.folder-type').val();
        data.name = $('.folder-name').val();
        if(!data.name){
            toastr.warning('Input folder name correctely!');
            return;
        }
        if(level ==4 && data.type == 1){
            toastr.warning('Can not add new folder on level 4 folder!');
            return;
        }
        ajaxFn({data:data,type:'add-folder'},'json',function(res){
            if(res.success){
                toastr.success('Successfully saved!');
                var html  = '<li class="tree-item closed"><a class="folder-item" cur_id=' + res.id + ' level=' + (1*level+1) + ' type='+data.type+'>'
                    + '<input type="checkbox"> '+(data.type==1?' <i class="open-close-folder fa fa-caret-right"></i> ':"")+'<i class="fa '+(data.type==1?"fa-folder":"fa-list")+'"></i> <b>' + data.name + '</b></a><ul></ul></li>';     
                $('>ul',$('.folder-item[cur_id="'+cur_id+'"]').parent()).append(html);
                $('#addModal').modal('hide');
            }
            else{

                toastr.warning('Failed to save folder!');
            }
        });
    });
    
    ajaxFn({type:'get-folder'},'json',function(res){
        constructTree(res);
    });
}
$(document).ready(function(){
    initFolderHandle();
})