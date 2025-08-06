<x-mail>
    <div class="m-4 font-semibold">
        @php
            echo $mailshot->subject;
        @endphp
    </div>
    <div class="m-4">
        @php
            echo $mailshot->bodyText;
        @endphp
    </div>
</x-mail>
