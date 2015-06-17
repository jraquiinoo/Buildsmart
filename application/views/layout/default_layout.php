<html>
	<head>
		<meta charset='utf-8' />
    	<meta name='viewport' content='width=device-width, initial-scale=1.0' />
		<title><?php echo $title; ?></title>
    	<link rel='stylesheet' href='<?php echo base_url() . "application/assets/css/foundation.min.css"; ?>'/>
    	<link rel='stylesheet' href='<?php echo base_url() . "application/assets/css/app.css"; ?>'/>
    	<script src='<?php echo base_url() . "application/assets/js/vendor/modernizr.js"; ?>'></script>
	</head>
	<body>
		<?php $this->load->view($header); ?>
		<main>
			<?php $this->load->view($main_content); ?>
		</main>
		<?php $this->load->view($footer); ?>
		<?php foreach($scripts as $script): ?>
			<script src='<?php echo base_url() . 'application/assets/js/' . $script ?>' type='text/javascript'></script>
		<?php endforeach ?>
	</body>
</html>