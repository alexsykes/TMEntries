<x-guest-layout>
    <title>Help::Calendar</title>

    <div id="container" class="w-full">
{{--        <div class="font-semibold  text-lg text-blue-700">iPhone</div>--}}
{{--        <div id="iphone" class="grid sm:grid-cols-2 gap-4">--}}
{{--            <div class="col-span-1 left-0">--}}
{{--                <embed--}}
{{--                    type="video/mp4"--}}
{{--                    src="{{ asset('storage/media/iphone.mp4') }}"--}}
{{--                    width="350"--}}
{{--                    height="700"--}}
{{--                />--}}
{{--            </div>--}}
{{--            <div class="col-span-1">--}}
{{--                <ul class="list-disc">--}}
{{--                    <li>Click on Date of Birth widget.</li>--}}
{{--                    <li>When it opens, click on Month Date at top left hand side.</li>--}}
{{--                    <li>Roll the tumblers to correct year.</li>--}}
{{--                    <li>Enter month and date in normal view.</li>--}}
{{--                </ul>--}}
{{--            </div>--}}
{{--        </div>--}}


        <div class="font-semibold  text-lg text-blue-700">Android - Click for video</div>
        <div id="iphone" class="grid sm:grid-cols-2 gap-4">
            <div class="col-span-1 left-0">
                <video controls width="350">
                    <source type="video/mp4"
                            src="{{ asset('storage/media/android.mp4') }}"
                    />
                </video>
            </div>
            <div class="col-span-1">
                <ul class="list-disc">
                    <li>Click on Date of Birth widget.</li>
                    <li>When it opens, click on Month Date at top left hand side.</li>
                    <li>Roll the tumblers to correct year.</li>
                    <li>Enter month and date in normal view.</li>
                </ul>
            </div>
        </div>

        <div class="font-semibold mt-4 text-lg text-blue-700">iPhone - Click for video</div>
        <div id="iphone" class="grid sm:grid-cols-2 gap-4">
            <div class="col-span-1 left-0">
                <video controls width="350">
                    <source type="video/mp4"
                            src="{{ asset('storage/media/iphone.mp4') }}"
                    />
                </video>
            </div>
            <div class="col-span-1">
                <ul class="list-disc">
                    <li>Click on Date of Birth widget.</li>
                    <li>When it opens, click on Month Date at top left hand side.</li>
                    <li>Roll the tumblers to correct year.</li>
                    <li>Enter month and date in normal view.</li>
                </ul>
            </div>
        </div>

    </div>
</x-guest-layout>
