<form method="post" action="/comment/save">
    <textarea name="comment" placeholder="Input your comment here..."></textarea>
    <input type="submit" value="Send">
</form>
<?php foreach ($data['comments'] as $comment):?>
    <div class="message" data-id="<?=$comment['id']?>">
        <div class="date"><?=$comment['timestamp']?></div>
        <div class="text"><?=$comment['text']?></div>
    </div>
<?php endforeach; ?>
