<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    Ech_Landing_Epay
 * @subpackage Ech_Landing_Epay/public/partials
 */
?>

<?php 

    $transID = get_query_var('transid', 'none');
    if ($transID == 'none') { echo "<script>window.location.replace('/')</script>"; }


    $plugin_info = new Ech_Landing_Epay();
    $plugin_public = new Ech_Landing_Epay_Public($plugin_info->get_plugin_name(), $plugin_info->get_version());
 
    $getInfo = $plugin_public->getPaymentInfoByTransID($transID);
    $infoData = json_decode($getInfo, true);

    /* echo '<pre>';
    print_r($infoData);
    echo '</pre>'; */
?>


<?php if ( isset($infoData['status']) && $infoData['status'] == "PAID" ): ?>
    <?php 
        $paymentDetails = $infoData['paymentDetails'][0];
        if ( $paymentDetails['status'] == "COMPLETED" ) {

            $amount = $infoData['amount'] / 100; // 變回非兩位小數值
            // send email to customer
            $headers = array(
                            'Content-Type: text/html; charset=UTF-8',
                            'Reply-To: ' . $infoData['additionalInfo']['epayEmailReplyTo']
                        );
            $toEmail = $infoData['additionalInfo']['email'];
            $subject = $infoData['additionalInfo']['epayEmailSubject'];
            $message = '<p>親愛的會員</p>
                        <p>您好，多謝您對'.$infoData['additionalInfo']['epayEmailSender'].'的支持，「網上預付$'.$amount.'獨家禮遇」已成功付款，付款參考編號為'.$infoData['clientTransactionId'].'。到店或完成療程後即獲贈以下禮遇！</p>'
                        . $infoData['additionalInfo']['epayEmailPriceContent'] .
                        '<p>如有任何疑問，請回覆向我們查詢。</p>
                        <br>
                        <p>謹啟<br>'.$infoData['additionalInfo']['epayEmailSender'].'</p>
                        '; 
            
            $isEmailSent = wp_mail( $toEmail, $subject, $message, $headers);
            if ( $isEmailSent ) {
                echo '<div class="ech_epay_result_container">';
                    echo '<h2>成功付款​</h2>';
                    echo '<p>您好，多謝您對'.$infoData['additionalInfo']['epayEmailSender'].'的支持，「網上預付$'.$amount.'獨家禮遇」已成功付款。</p>';

                    if ( !empty(get_option( 'ech_whatsapp_contact' )) ) {
                        echo '如有任何疑問，請WhatsApp與我們聯絡: <a href="'.get_option( 'ech_whatsapp_contact' ).'" target="_blank">'.get_option( 'ech_whatsapp_contact' ).'</a>';
                    }

                echo '</div>'; //.ech_epay_result_container
            } else {
                echo '<div class="ech_epay_result_container">';
                    echo '<h2>成功付款​</h2>';
                    echo '<p>您好，多謝您對'.$infoData['additionalInfo']['epayEmailSender'].'的支持，「網上預付$'.$amount.'獨家禮遇」已成功付款。交易編號: '. $infoData['clientTransactionId'].'。請截屏這頁再顯示給門市職員</p>';

                    if ( !empty(get_option( 'ech_whatsapp_contact' )) ) {
                        echo '如有任何疑問，請WhatsApp與我們聯絡: <a href="'.get_option( 'ech_whatsapp_contact' ).'" target="_blank">'.get_option( 'ech_whatsapp_contact' ).'</a>';
                    }
                echo '</div>'; //.ech_epay_result_container

            }// if ( $isEmailSent )
        } // $paymentDetails['status'] == "COMPLETED" 

    ?>

<?php else: ?>
    <div class="ech_epay_result_container">
        <h2>付款失敗</h2>
        <p>很抱歉，有關「網上預付$<?=$amount?>獨家禮遇」付款失敗。</p>
        
        <?php if ( !empty(get_option( 'ech_whatsapp_contact' )) ): ?>
            <p>如有任何疑問，請WhatsApp與我們聯絡: <a href="<?=get_option( 'ech_whatsapp_contact' )?>" target="_blank"><?=get_option( 'ech_whatsapp_contact' )?></a></p>
        <?php endif; ?>
    </div> <!-- ech_epay_result_container -->


<?php endif; // infoData['state'] == PAID ?>


