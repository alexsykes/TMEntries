<?php

$adminFee = $adminFee / 100;
$amount = $refunded_amount / 100;
if ($adminFee > 0) {
    $amount = $amount - $adminFee;
}
$refundText = "a refund of £" . $amount . " has now been transferred to your account."
?>
<x-email.new><p><b>Dear Entrant, </b></p>
    <p>Further to our recent email, we can confirm that {{$refundText}}</p>
    <p>The entries were: <?php echo $entryData; ?></p>
    <p><b>Thank you for entering with TrialMonster</b></p>
</x-email.new>