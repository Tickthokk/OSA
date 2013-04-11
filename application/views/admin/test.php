<?php foreach (range(1,100) as $i) : ?>
<img src="/assets/images/icons/<?php echo $icons[array_rand($icons)]; ?>.svg" id='test1' class='svg achievement_icon' data-color='<?php echo sprintf('#%06X', (mt_rand(0, 0xFFFFFF) / 2) + (0xFFFFFF / 2)); ?>' data-bg='<?php echo sprintf('#%06X', (mt_rand(0, 0xFFFFFF) / 2)); ?>' />
<?php endforeach; ?>
<?php /*
<img src="/assets/images/icons/ace.svg" id='test1' class='svg achievement_icon' data-color='<?php echo sprintf('#%06X', mt_rand(0, 0xFFFFFF)); ?>' data-bg='<?php echo sprintf('#%06X', mt_rand(0, 0xFFFFFF)); ?>' />
<img src="/assets/images/icons/pocket-bow.svg" id='test2' class='svg achievement_icon' data-color='<?php echo sprintf('#%06X', mt_rand(0, 0xFFFFFF)); ?>' data-bg='<?php echo sprintf('#%06X', mt_rand(0, 0xFFFFFF)); ?>' />
<img src="/assets/images/icons/quick-slash.svg" id='test3' class='svg achievement_icon' data-color='<?php echo sprintf('#%06X', mt_rand(0, 0xFFFFFF)); ?>' data-bg='<?php echo sprintf('#%06X', mt_rand(0, 0xFFFFFF)); ?>' />
<img src="/assets/images/icons/dragon-breath.svg" id='test4' class='svg achievement_icon' data-color='<?php echo sprintf('#%06X', mt_rand(0, 0xFFFFFF)); ?>' data-bg='<?php echo sprintf('#%06X', mt_rand(0, 0xFFFFFF)); ?>' />
<img src="/assets/images/icons/anvil.svg" id='test5' class='svg achievement_icon' data-color='<?php echo sprintf('#%06X', mt_rand(0, 0xFFFFFF)); ?>' data-bg='<?php echo sprintf('#%06X', mt_rand(0, 0xFFFFFF)); ?>' />
<img src="/assets/images/icons/breastplate.svg" id='test6' class='svg achievement_icon' data-color='<?php echo sprintf('#%06X', mt_rand(0, 0xFFFFFF)); ?>' data-bg='<?php echo sprintf('#%06X', mt_rand(0, 0xFFFFFF)); ?>' />
<img src="/assets/images/icons/crystal-eye.svg" id='test7' class='svg achievement_icon' data-color='<?php echo sprintf('#%06X', mt_rand(0, 0xFFFFFF)); ?>' data-bg='<?php echo sprintf('#%06X', mt_rand(0, 0xFFFFFF)); ?>' />
<img src="/assets/images/icons/fountain.svg" id='test8' class='svg achievement_icon' data-color='<?php echo sprintf('#%06X', mt_rand(0, 0xFFFFFF)); ?>' data-bg='<?php echo sprintf('#%06X', mt_rand(0, 0xFFFFFF)); ?>' />
<img src="/assets/images/icons/light-bulb.svg" id='test9' class='svg achievement_icon' data-color='<?php echo sprintf('#%06X', mt_rand(0, 0xFFFFFF)); ?>' data-bg='<?php echo sprintf('#%06X', mt_rand(0, 0xFFFFFF)); ?>' />
*/