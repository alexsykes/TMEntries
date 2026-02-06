<?php

$adminFee = $adminFee / 100;
$amount = $refunded_amount / 100;
if ($adminFee > 0) {
    $amount = $amount - $adminFee;
}
$refundText = "A refund of £" . $amount . " has now been transferred to your account."
?>
<x-automail><p>Dear Entrant, </p>
    <p>As you may have already heard, we have reluctantly been forced to cancel this event. {{$refundText}}</p>
    <p>The entries were: <?php echo $entryData; ?></p>
    <p>Thank you for entering with TrialMonster</p>
</x-automail>