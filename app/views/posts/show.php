<?php
require APPROOT . '/views/inc/header.php';
?>
<a href="<?php echo URLROOT; ?>/posts" class="btn btn-light"><i class="fa fa-backward"></i>
    Back</a>
<br>
<h1>
    <?php echo $data['post']->title ?>
</h1>
<div class="bg-secondary text-white p-2 mb-3">
    Written By
    <?php echo $data['user']->name ?>
    on
    <?php echo $data['post']->created_at ?>
</div>
<p>
    <?php echo $data['post']->body ?>
</p>

<?php if ($data['post']->user_id == $_SESSION['user_id']) : ?>
<hr>
<div class="row">
    <div class="col">
        <a href="<?php echo URLROOT; ?>/posts/edit/<?php echo $data['post']->id; ?>"
            class="btn btn-dark">Edit</a></div>

    <div class="col">
        <form class="pull-right" action="<?php echo URLROOT; ?>/posts/delete/<?php echo $data['post']->id; ?>"
            method="POST">
            <input type="submit" value="Delete" class="btn btn-danger">
        </form>
    </div>
</div>
<?php endif; ?>
<?php
require APPROOT . '/views/inc/footer.php';
