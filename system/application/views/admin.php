<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Halalan - Administration - <?= $title; ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <?php if (isset($meta) && !empty($meta)): ?>
  <?= $meta; ?>
  <?php endif; ?>
  <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>public/stylesheets/admin.css" />
  <script type="text/javascript" src="<?= base_url(); ?>public/javascripts/domTT/domLib.js"></script>
  <script type="text/javascript" src="<?= base_url(); ?>public/javascripts/domTT/domTT.js"></script>
  <script type="text/javascript" src="<?= base_url(); ?>public/javascripts/domTT/domTT_drag.js"></script>
  <script type="text/javascript" src="<?= base_url(); ?>public/javascripts/main.js"></script>
</head>
<body>
<div id="wrap">
	<div id="header">
		<div id="header_bg">
			<div id="header_left">
				<h1>
					<?= anchor(site_url(), 'Halalan'); ?>
					<span>administration</span>
				</h1>
			</div>
			<div id="header_right">
				<p>LOGGED IN AS <?= strtoupper($username); ?> | <?= anchor('gate/logout', 'LOGOUT'); ?></p>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<div id="menu">
		<ul>
			<li><?= anchor('admin/home', 'HOME', array('title'=>'Home')); ?></li>
			<li><?= anchor('admin/candidates', 'CANDIDATES', array('title'=>'Manage Candidates')); ?></li>
			<li><?= anchor('admin/parties', 'PARTIES', array('title'=>'Manage Parties')); ?></li>
			<li><?= anchor('admin/positions', 'POSITIONS', array('title'=>'Manage Positions')); ?></li>
			<li><?= anchor('admin/voters', 'VOTERS', array('title'=>'Manage Voters')); ?></li>
		</ul>
	</div>
	<div id="content">
		<?= $body; ?>
	</div>
	<div id="footer">
		<div id="footer_bg">
			<div id="footer_left">
				<p>&copy; University of the Philippines Linux Users' Group (UnPLUG)</p>
			</div>
			<div id="footer_right">
				<p>Powered by Halalan 1.1.0</p>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>
</body>
</html>