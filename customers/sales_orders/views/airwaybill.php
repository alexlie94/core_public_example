<!DOCTYPE html>
<html>

<head>
	<title>Airway Bill</title>
	<style>
		body {
			font-family: Arial, sans-serif;
		}

		.airway-bill {
			border: 1px solid #000;
			padding: 20px;
			width: 300px;
			margin: 0 auto;
		}

		.company-logo {
			text-align: center;
		}

		.shipment-details {
			margin-top: 20px;
		}

		.sender-info,
		.receiver-info {
			width: 50%;
			float: left;
		}

		.clear {
			clear: both;
		}
	</style>
</head>

<body>
	<div class="airway-bill">
		<div class="company-logo">
			<img src="your-company-logo.png" alt="Company Logo">
		</div>
		<div class="shipment-details">
			<h2>Shipment Details</h2>
			<div class="sender-info">
				<h3>Sender:</h3>
				<p>Sender Name: John Doe</p>
				<p>Address: 123 Main Street, City</p>
				<p>Phone: +1 (555) 555-5555</p>
			</div>
			<div class="receiver-info">
				<h3>Receiver:</h3>
				<p>Receiver Name: Jane Smith</p>
				<p>Address: 456 Elm Street, City</p>
				<p>Phone: +1 (555) 555-5555</p>
			</div>
			<div class="clear"></div>
			<h3>Shipment Information:</h3>
			<p>AWB Number: 123456789</p>
			<p>Origin: City A</p>
			<p>Destination: City B</p>
			<p>Weight: 10 kg</p>
		</div>
	</div>
</body>

</html>