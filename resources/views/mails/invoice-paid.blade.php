<x-automail>

    <div class="mt-2 font-semibold text-lg text-blue-800">Your payment has been received and your entry
        for {{$entry->name}} has now been
        confirmed.
    </div>
    <div>Entry details are as follows:</div>
    <div>Course: {{$entry->course}}</div>
    <div>Class: {{$entry->class}}</div>
    <div>Machine: {{$entry->make}} {{$entry->size}}</div>
    <div>If you wish to make changes, withdraw your entry or have any other queries, please Reply-to this email quoting your entryID :{{$entry->id}}.</div>
    <div class="mt-2 font-semibold text-lg text-blue-800">Thank you for using TrialMonster</div>

</x-automail>
