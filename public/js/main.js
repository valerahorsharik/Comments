$(document).ready(function () {
    var commentLvl = 0;
    var parentId = 0;
    $('.comments-form').on('submit', function () {
        var comment = $(this).children('textarea').val();
        $.ajax({
            type: 'POST',
            url: '/comment/save',
            context: $(this),
            data: {comment:comment,
                parentId:parentId},
            error:function(error){
                $(this).prepend("<div class=\"error\">" + error.responseText + "</div>");
            },
            success:function(data){
                $('*[data-lvl="' + commentLvl + '"]').prepend(
                '<li data-comment-id='+ data.commentId +'>\n\
                <div class="comment-info">\n\
                    <div class="info">' + data.date + '</div>\n\
                    <div class="actions">\n\
                                <span class=\'glyphicon glyphicon-edit edit-comment\'></span>\n\
                                <span class=\'glyphicon glyphicon-remove delete-comment\'></span>\n\
                    </div>\n\
                </div>\n\
                <div class="comment-text">\n '
                    + comment +
                '</div>\n\
            </li>');
                parentId = 0;
                commentLvl = 0 ;
                $(this).children('textarea').val('');
            }
        });
        return false;
    })
})

