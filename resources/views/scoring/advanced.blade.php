<x-club>
    @php
        //    dd($trial);
                $numSections = $trial->numSections;
            $numLaps = $trial->numLaps;
            $courses = explode(',', $trial->customCourses);
        //    dd($numSections, $numLaps);
    @endphp
    <x-slot:heading>{{$trial->name}}</x-slot:heading>
    <div
        class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">

        <div class="font-bold text-center w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-red-600">
            Danger - Score data can be Destroyed!
        </div>
        <div class="text-center pt-2 font-semibold text-red-600">Caution - You are advised to save a backup of your
            trial's entry and score data BEFORE making any changes below
        </div>
        <div class="w-full p-4 text-center">
            <form action="/backup/request" method="post">
                @csrf
                <input type="hidden" name="trialID" value="{{$trial->id}}">
                <button type="submit" name="action" value="backup"
                        class="rounded-md ml-2 bg-red-600 px-3 py-1 text-sm font-light  border border-red-800 text-white drop-shadow-lg hover:bg-red-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                    Email me a Backup
                </button>
            </form>
        </div>

        {{--   Start of choices     --}}
        <div class="p-4 space-y-4">
            {{--                 Cancel section for all courses --}}
            <form action="/scores/manage" method="post">
                @csrf
                <input type="hidden" name="trialID" value="{{$trial->id}}">
                <div class="flex  w-full space-x-4 justify-normal">
                    <div class="pt-2 font-semibold text-red-600">Cancel a section for all riders</div>
                    <div><label>Section: </label><input class="" type="number" name="sectionNumber" placeholder=""
                                                        min="1"
                                                        max="{{$numSections}}"/></div>
                    <div class="pt-2">
                        <button type="submit" name="action" value="cancelSectionAll"
                                class=" rounded-md ml-2 bg-red-600 px-3 py-1 text-sm font-light  border border-red-800 text-white drop-shadow-lg hover:bg-red-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                            Go
                        </button>
                    </div>
                </div>
            </form>

            <form action="/scores/manage" method="post">
                @csrf
                <input type="hidden" name="trialID" value="{{$trial->id}}">
                {{--                Cancel section for specific course --}}
                <div class="flex  w-full space-x-4 justify-normal">
                    <div class="pt-2 font-semibold text-red-600">Cancel a section for a course</div>
                    <div><label>Section: </label><input class="" type="number" placeholder=""
                                                        name="sectionNumber"
                                                        min="1" max="{{$numSections}}"/></div>
                    <div><label>Course: </label><select name="sectionCourse">
                            {{--                        <option value="">--Please choose an option--</option>--}}
                            @foreach($courses as $course)
                                <option value="{{ $course }}">{{ $course }}</option>
                            @endforeach
                        </select></div>

                    <div class="pt-2">
                        <button type="submit" name="action" value="cancelSectionCourse"
                                class=" rounded-md ml-2 bg-red-600 px-3 py-1 text-sm font-light  border border-red-800 text-white drop-shadow-lg hover:bg-red-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                            Go
                        </button>
                    </div>
                </div>
            </form>

            <form action="/scores/manage" method="post">
                @csrf
                <input type="hidden" name="trialID" value="{{$trial->id}}">
                <div class="flex  w-full space-x-4 justify-normal">
                    <div class="pt-2 font-semibold text-red-600">Reduce number of laps for all riders</div>
                    <div><label>Number of laps to count: </label><input class="" type="number" name="numLapsAll"
                                                                        value="{{$numLaps}}" placeholder="" min="1"
                                                                        max="{{$numLaps}}"/></div>
                    <div class="pt-2">
                        <button type="submit" name="action" value="reduceLapsAll"
                                class=" rounded-md ml-2 bg-red-600 px-3 py-1 text-sm font-light  border border-red-800 text-white drop-shadow-lg hover:bg-red-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                            Go
                        </button>
                    </div>
                </div>
            </form>
            <form action="/scores/manage" method="post">
                @csrf
                <input type="hidden" name="trialID" value="{{$trial->id}}">
                <div class="flex  w-full space-x-4 justify-normal">
                    <div class="pt-2 font-semibold text-red-600">Reduce number of laps for a course</div>
                    <div><label>Number of laps to count: </label><input class="" type="number" name="numLapsCourse"
                                                                        value="{{$numLaps}}" placeholder="" min="1"
                                                                        max="{{$numLaps}}"/></div>
                    <div><label>Course: </label><select name="course">
                            {{--                        <option value="">--Please choose an option--</option>--}}
                            @foreach($courses as $course)
                                <option value="{{ $course }}">{{ $course }}</option>
                            @endforeach
                        </select></div>
                    <div class="pt-2">
                        <button type="submit" name="action" value="reduceLapsCourse"
                                class=" rounded-md ml-2 bg-red-600 px-3 py-1 text-sm font-light  border border-red-800 text-white drop-shadow-lg hover:bg-red-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                            Go
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-club>
