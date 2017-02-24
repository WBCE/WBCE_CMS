<?php
//no direct file access
if (count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));
?>

<section id="settingtools">
    <h2><?=$categoryName?></h2>
    <ul>
		<?php foreach ($myTools as $tool): ?>
			<?php $toolLink=ADMIN_URL.$typeDir."/$toolFile?tool=".$tool['directory']; ?>
			<li class="fg6 section">
				<div class="fg2 graphic">
					<a class="title" href="<?=$toolLink?>">
						<i class="fa fa-3x <?=$tool['icon']?>"></i>
					</a>
				</div>
				<div class="fg10 description">
					<a href="<?=$toolLink?>">
						<span class="title"><?=$tool['name']?></span>
					</a>
					<?=$tool['description']?>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
