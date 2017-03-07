$(document).ready(function () {
    /**
     * 
     * Contains a lvl of the comment for displaying on the page
     * 
     * @type int
     */
    var commentLvl = 0;

    /**
     * 
     * Contains a parent id of the comment,
     * which we wanna create/update/delete
     * 
     * @type int
     */
    var parentId = 0;

    /**
     * Creating a new comment in DB
     */
    $(document).on('submit', '.comments-form.new', function () {
        var comment = $(this).children('textarea').val();
        $.ajax({
            type: 'POST',
            url: '/comment/save',
            context: $(this),
            data: {comment: comment,
                parentId: parentId},
            error: function (error) {
                $(this).prepend("<div class=\"error\">" + error.responseText + "</div>");
            },
            success: function (data) {
                $('*[data-lvl="' + commentLvl + '"]').prepend(
                        '<li data-comment-id=' + data.commentId + '>\n\
                <div class="comment-info">\n\
                    <div class="info">' + data.date + '</div>\n\
                    <div class="actions">\n\
                                <span class=\'glyphicon glyphicon-comment comment-comment\'></span>\n\
                                <span class=\'glyphicon glyphicon-edit edit-comment\'></span>\n\
                                <span class=\'glyphicon glyphicon-remove delete-comment\'></span>\n\
                    </div>\n\
                </div>\n\
                <div class="comment-text">\n '
                        + comment +
                        '</div>\n\
            </li>');
                parentId = 0;
                commentLvl = 0;
                $(this).children('textarea').val('');
            }
        });
        return false;
    });


    /**
     * Deleting the comment from DB
     */
    $(document).on('click', '.delete-comment', function () {
        var commentId = $(this).closest('li').data('comment-id');
        $.ajax({
            type: 'POST',
            url: '/comment/delete',
            context: $(this).closest('li'),
            data: {commentId: commentId},
            error: function (error) {
                $(this).prepend("<div class=\"error\">" + error.responseText + "</div>");
            },
            success: function () {
                $(this).remove();
            }
        });
    });

    /**
     * Show form for editing the comment.
     */
    $(document).on('click', '.edit-comment', function () {
        var commentTextDiv = $(this).closest('li').children('.comment-text');
        var text = commentTextDiv.html();

        commentTextDiv.html('<form class=\'edit-form\' method="post" action="/comment/edit">\n\
                                <textarea rows=\'4\' name="comment" placeholder="Input your comment here..." >' + text + '</textarea>\n\
                                <input type="submit" value="Save">\n\
                            </form>');
    });

    /**
     * Edit the comment
     */
    $(document).on('submit', '.edit-form', function () {
        var commentId = $(this).closest('li').data('comment-id');
        var commentTextDiv = $(this).closest('li').children('.comment-text');
        var text = $(this).children('textarea').val();
        $.ajax({
            type: 'POST',
            url: '/comment/edit',
            context: commentTextDiv,
            data: {commentId: commentId,
                text: text},
            error: function (error) {
                $(this).prepend("<div class=\"error\">" + error.responseText + "</div>");
            },
            success: function () {
                $(this).html(text);
            }
        });
        return false;
    });

    /**
     * Display form for adding new comment for the existing comment
     */
    $(document).on('click', '.comment-comment', function () {
        var parent = $(this).closest('li');
        var parentId = parent.data('comment-id');

        parent.children('.comment-text').after('<form class=\'comments-form comment\' method="post" action="/comment/existing">\n\
                                <textarea rows=\'3\' name="comment" placeholder="Input your comment here..." ></textarea>\n\
                                <input type="submit" value="Comment">\n\
                            </form>');
        parent.children('.comments-form.comments').focus();
    });

    /**
     * Saving new comment in DB which has a parent id
     */
    $(document).on('submit', '.comments-form.comment', function () {
        var parent = $(this).closest('li');
        var parentId = parent.data('comment-id');
        var text = $(this).children('textarea').val();
        $.ajax({
            type: 'POST',
            url: '/comment/existing',
            context: parent,
            data: {parentId: parentId,
                text: text},
            error: function (error) {
                $(this).prepend("<div class=\"error\">" + error.responseText + "</div>");
            },
            success: function (data) {
                if ($(this).children('.comments-list').length > 0) {
                    $(this).children('.comments-list').append(
                            '<li data-comment-id=' + data.commentId + '>\n\
                <div class="comment-info">\n\
                    <div class="info">' + data.date + '</div>\n\
                    <div class="actions">\n\
                                <span class=\'glyphicon glyphicon-comment comment-comment\'></span>\n\
                                <span class=\'glyphicon glyphicon-edit edit-comment\'></span>\n\
                                <span class=\'glyphicon glyphicon-remove delete-comment\'></span>\n\
                    </div>\n\
                </div>\n\
                <div class="comment-text">\n '
                            + text +
                            '</div>\n\
            </li>');
                    $(this).children('.comments-form.comment').remove();
                }
            }
        });
        return false;
    });
})

