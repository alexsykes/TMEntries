<?php
$html = "<p><b>Dear $email,</b></p><p>As you may know, " . $trialClub . "'s " . $trialName . " has unfortunately been cancelled." . $refundText . $refunded_amount . " to your account.</p><p>This refund is for the following entries: $entryData</p><p>You will be sent a further confirmation email when the refund is completed. If you have any queries, please reply to this email.</p><p><b>Thank you for entering with TrialMonster.</b></p>";
?>
<x-email.new>
    <?php echo $html;
    ?>
</x-email.new>
