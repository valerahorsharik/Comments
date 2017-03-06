<form class='comments-form' method="post" action="/comment/save">
    <textarea rows='4' name="comment" placeholder="Input your comment here..."></textarea>
    <input type="submit" value="Send">
</form>
<div class="comments">
    <ul class="comments-list" data-lvl='0'>
        <?php foreach ($data['comments'] as $comment): ?>
            <li data-comment-id='<?= $comment['id'] ?>'>
                <div class="comment-info">
                    <div class="info"><?= $comment['timestamp'] ?></div>
                    <div class="actions">
                        <?php if (!$data['guest']): ?>
                            <?php if ($comment['user_id'] == $_SESSION['user']['id']): ?>
                                <span class='glyphicon glyphicon-edit edit-comment'></span>
                                <span class='glyphicon glyphicon-remove delete-comment'></span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="comment-text">
                    <?= $comment['text'] ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>