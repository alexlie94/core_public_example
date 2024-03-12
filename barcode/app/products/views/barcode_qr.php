<html>

<head>
	<title>Reprint Label</title>
	<style type="text/css">
		body {
			font-family: arial;
		}

		.border-garis {
			/*border:solid 1px #000000;*/
			margin-bottom: 10px;
		}

		/*.border-garis{width: 9.5cm;height: 3.5cm; border:solid 1px #000000;margin-bottom: 10px;}*/
		.left {
			float: left;
		}

		.right {
			float: right;
		}

		.clear {
			clear: both;
		}

		.w-2.5cm {
			width: 2.5cm;
		}

		.w-2cm {
			width: 2cm;
		}

		.w-1.5cm {
			width: 1.5cm;
		}

		.w-5cm {
			width: 4cm;
		}

		.f-14 {
			font-size: 14px;
			font-weight: bold;
		}

		.f-12 {
			font-size: 12px;
		}

		.f-11 {
			font-size: 11px;
		}

		.f-8 {
			font-size: 8px;
		}

		.h-2.5cm {
			height: 2.5cm;
		}

		.h-1cm {
			height: 1cm;
		}

		.h-0.5cm {
			height: 0.5cm;
			padding-bottom: 3px;
		}

		@media print {
			.border-garis {
				page-break-after: always;
			}

			.border-garis {
				margin-left: 10px;
			}
		}
	</style>
</head>

<body>
	<?php
	if (isset($all_data) && count($all_data) > 0) {

		foreach ($all_data as $key => $value) {
			for ($i = 1; $i <= $value['qty']; ++$i) {
	?>
				<div class="border-garis">
					<div>
						<div class="left">
							<img src="<?= base_url() ?>products/generateqrcode/<?= $value['sku']; ?>/3" />
						</div>
						<div class="left w-5cm" style="margin-top: 10px;">
							<div class="f-14"><?= $value['color']; ?></div>
							<div class="f-14"><?= $value['size']; ?></div>
							<div class="f-12"><?= $value['sku']; ?></div>
							<div class="f-12"><?= $value['brand']; ?></div>
							<div class="f-12"><?= $value['product']; ?></div>
						</div>
					</div>
					<div class="clear" style="margin-top: -20px;">
					</div>
				</div>
	<?php }
		}
	} ?>
</body>

</html>