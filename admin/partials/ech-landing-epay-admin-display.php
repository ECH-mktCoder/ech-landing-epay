<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    Ech_Landing_Epay
 * @subpackage Ech_Landing_Epay/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="echPlg_wrap">
    <h1>Landing Page ePay General Settings</h1>

    <div class="lfg_intro">
        <p>To generate ePay, you need to enter the auth token in the below form first. You may copy the below shortcode sample</p>
        <div class="shtcode_container">
            <pre id="sample_shortcode">[display_epay amount=100 duedate=1]</pre>
            <div id="copyMsg"></div>
            <button id="copyShortcode">Copy Shortcode</button>
        </div>
    </div>

    <div class="form_container">
        <form method="post" id="lp_epay_settings_form">
        <?php 
            settings_fields( 'lp_epay_settings' );
            do_settings_sections( 'lp_epay_settings' );
        ?>
            <h2>General</h2>
            <div class="form_row">
                <?php 
                    $isEpayDevEnv = get_option( 'ech_lp_epay_env' );
                    if(empty($isEpayDevEnv) || !$isEpayDevEnv ) {
                        add_option( 'ech_lp_epay_env', 1 );
                    }
                ?>
                <label>Connect to <strong>testing</strong> ePay API : </label>
                <select name="ech_lp_epay_env" id="">
                    <option value="0" <?= ($isEpayDevEnv == "0") ? 'selected' : '' ?>> No </option>
                    <option value="1" <?= ($isEpayDevEnv == "1") ? 'selected' : '' ?>> Yes</option>
                </select>
            </div>

            <div class="form_row">
                <label>Auth Token : </label>
                <input type="text" name="ech_lp_epay_auth_token" value="<?= get_option( 'ech_lp_epay_auth_token' )?>" id="ech_lp_epay_auth_token">
            </div>

            <div class="form_row">
                <label>Whatsapp Contact Link : </label>
                <input type="text" name="ech_whatsapp_contact" value="<?= get_option( 'ech_whatsapp_contact' )?>" id="ech_whatsapp_contact">
            </div>

            <div class="form_row">
                <button type="submit"> Save </button>
            </div>
        </form>
        <div class="statusMsg"></div>
    </div> <!-- form_container -->
</div>

