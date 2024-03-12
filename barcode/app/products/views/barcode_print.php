<STYLE TYPE="text/css" media="screen,print">
	@font-face {
		font-family: barcode_128;
		src: url("../../assets/resources/barcode/code128.ttf");
		font-size: 20px;
	}

	.barcode {
		font-weight: normal;
		font-style: normal;
		line-height: normal;
		font-family: 'barcode_128', sans-serif;
		font-size: 30px;
	}
</STYLE>

<?php
if (count($param) > 0 && $action != "packing") {
	$x = 0;
	foreach ($param as $key => $value) {
		$for = explode("|", $value);

		foreach ($for as $key => $value) {
			$for[$key] = str_replace("__", " ", $value);
		}

		list($sku, $brand, $product, $color, $size, $qty) = $for;
?>

		<?php for ($i = 1; $i <= $qty; ++$i) {
			$x++; ?>
			<?php if ($x % 2 != 0) { ?>
				<div style="height: 65px;margin-bottom:<?php echo ($x != count($param) - 1 && count($param) % 2 == 0 || $x != count($param) && count($param) % 2 != 0) ? '20px' : '0px' ?>;margin-top: 12px;">
				<?php } ?>
				<div style="float: <?php echo ($x % 2 == 0) ? 'right;margin-right:-4px;' : 'left;margin-left:5px;';  ?>">
					<div style="font-family: sans-serif;font-size: 7pt"><?php echo ucwords($brand) ?></div>
					<div style="font-family: sans-serif;font-size: 7pt"><?php echo ucwords($product) ?></div>
					<div style="font-family: sans-serif;font-size: 7pt"><?php echo ucwords($color) . ' - ' . $size; ?> </div>
					<div style="margin-top: 5px;margin-left: -5px"><img style="width: 170px" src="<?= base_url() ?>products/generate/<?php echo $sku; ?>" /></div>
				</div>
				<?php if ($x % 2 == 0) { ?>
				</div>
			<?php } ?>
			<?php //&& $x != count($param) 
			?>
			<?php if ($x % 2 == 0 && $x != $qty) { ?>
				<div style="clear: both;height: 5px"></div>
			<?php } ?>
		<?php } ?>
	<?php } ?>
<?php } ?>