<?php foreach ($tags as $tag) : ?>
<span rel = '<?php echo $tag['approval']; ?>' data-id = '<?php echo $tag['id']; ?>' data-user-vote = '<?php echo $tag['user_approval']; ?>' data-admin-approval = '<?php echo $tag['admin_approval']; ?>' data-name = '<?php echo strtolower($tag['name']); ?>'><?php echo strtolower($tag['name']); ?></span>
<?php endforeach; ?>