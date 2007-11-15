<?php

// common to controllers
$lang['halalan_unauthorized'] = 'Kailangang nakapasok ka para makita ang pahina na ito.';

// common to views
$lang['halalan_message_box'] = 'Halalan Message Box';
$lang['halalan_edit'] = 'Baguhin';
$lang['halalan_delete'] = 'Tanggalin';
$lang['halalan_voted'] = 'Voted';
$lang['halalan_name'] = 'Pangalan';
$lang['halalan_unit'] = 'Unit';
$lang['halalan_action'] = 'Aksyon';

// controllers/gate.php
$lang['halalan_gate_title'] = 'Login';
$lang['halalan_login_failure'] = 'Hindi matagumpay na pagpasok.  Boo!';
$lang['halalan_already_voted'] = 'ikaw ay nakaboto na';

// views/gate.php
$lang['halalan_login_label'] = 'Login to Halalan';
$lang['halalan_username'] = 'Username';
$lang['halalan_password'] = 'Password';
$lang['halalan_login_button'] = 'Login';

// controllers/voter.php
$lang['halalan_vote_title'] = 'Vote';
$lang['halalan_vote_no_candidates'] = 'walang kandidato';
$lang['halalan_vote_no_selected'] = 'walang piniling kandidato';
$lang['halalan_vote_not_all_selected'] = 'hindi lahat ng posisyon ay may napiling kandidato';
$lang['halalan_vote_maximum'] = 'lumampas ang boto mo sa dapat';
$lang['halalan_vote_abstain_and_others'] = 'hindi pwedeng pumili ng ibang kandidato kapag napili ang abstain';

$lang['halalan_confirm_vote_title'] = 'Confirm Vote';
$lang['halalan_confirm_vote_from_vote'] = 'kailangang bumoto ka muna';
$lang['halalan_confirm_vote_no_captcha'] = 'walang nilagay na captcha';
$lang['halalan_confirm_vote_not_captcha'] = 'maling nilagay na captcha';
$lang['halalan_confirm_vote_no_pin'] = 'walang nilagay na pin';
$lang['halalan_confirm_vote_not_pin'] = 'maling nilagay na pin';

// views/gate/admin
$lang['halalan_admin_login_label'] = 'Login to Halalan as Admin';

// Admin Main Page
$lang['halalan_admin_title'] = 'Administration';
$lang['halalan_admin_home'] = 'Home';
$lang['halalan_admin_home_label'] = 'Ano ang gusto mong gawin?';
$lang['halalan_admin_add_voter'] = 'Magdagdag ng botante';
$lang['halalan_admin_add_candidate'] = 'Magdagdag ng kandidato';

// Other admin views
$lang['halalan_add_voter'] = 'Magdagdag ng botante';
$lang['halalan_add_voter_submit'] = 'Idagdag';
$lang['halalan_add_voter_details'] = 'Mga detalye ng botante na idadagdag';
$lang['halalan_add_voter_exists'] = 'Meron nang ganitong boter.';
$lang['halalan_add_voter_no_username'] = 'Kailangan ang username.';
$lang['halalan_add_voter_no_firstname'] = 'Kailangan ang first name.';
$lang['halalan_add_voter_no_lastname'] = 'Kailangan ang last name.';
$lang['halalan_add_voter_success'] = 'Matagumpay ang pagdagdag ng botante!';

$lang['halalan_logout_title'] = 'Logout';

// views/logout.php
$lang['halalan_logout_message'] = '<p>Thank you for using Halalan!</p><p>You have been automatically logged out.  Redirecting in 5 seconds...</p><p>Follow this ' . anchor(base_url(), 'link', 'title="Halalan - Login"') . ' if the redirection fails.</p>';


?>