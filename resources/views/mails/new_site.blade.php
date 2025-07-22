<x-mail>
    <x-slot:heading>
        Heading
    </x-slot:heading>
    <div class="m-4">
<p>Dear {{$user->name}},</p>
<p>I am writing to you as your email - {{$user->email}} - is registered as a user of the TrialMonster.uk website. A number of upgrades have recently been applied to the site which should improve its security and reliability.</p>
<p><b>How does this affect me? </b>Your login details - username, email address and password - have all been transferred to the new system so there is no need to register again although <b>you will need to change your password</b>.</p>
<p><b>Do I need to do anything now? </b>Only if you are wishing to enter a trial. Unregistered 'Guest' users are able to check out details of results and future trials without logging in. <b>If you wish to enter a trial, you will need to update your password</b>.</p>
<p><b>How do I update my password? </b>The simplest way to update your password is to request a <b>Password Reset</b> by clicking on this link - <a href="https://trialmonster.uk/forgot-password">https://trialmonster.uk/forgot-password</a>. Enter your registered email address, then click the Email Password Reset Link button which will send an email with a link to your email address. Once you receive that email, click on the Reset Password button and, when the page loads, enter your password. </p>
<p><b>I no longer wish to be registered with this site. How do I close my account?</b> Simply click on the following link - <a href="https://trialmonster.uk/close-my-account/{{$user->id}}/{{$user->email}}">https://trialmonster.uk/close-my-account</a> - and your account will be closed.</p>

<p>Thank you for using TrialMonster,<br>Alex - TrialMonster Developer</p>

        <p>Dear &lt;&gt;,&nbsp;</p>
        <p>We have recently received reports of unauthorised and illegal riding taking place at one of our venues. Although there is no evidence that any of our members have been involved, the possible impact on our membership could be severe including the loss of use for the venue.</p>
        <p>All members are asked to support the club by reporting any illegal use at any of our venues to the club so that we can take appropriate action.&nbsp;</p>
        <p>Thank you for your support,</p>
        <p>&lt;&gt;</p>
        <p>&nbsp;</p>


    </div>
</x-mail>