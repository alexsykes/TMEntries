<x-club>
    <x-slot:heading>
        Create a new trial
    </x-slot:heading>
    @php
        $courseArray = array("Expert", "Intermediate", "Hard Novice", "Novice", "50/50", "Clubman", "Clubman A", "Clubman B", "Easy");
        $classArray = array("Adult", "Youth", "Twinshock", "Pre-65", "Air-cooled Monoshock", "Over 40", "Over 50", "Youth A", "Youth B", "Youth C", "Youth D");
        $entryMethodArray = array("Enter on day", "TrialMonster", "Online");
        $entrySelectionArray = array("Order of Payment", "Ballot", "Selection", "Other");
        $scoringModeArray = array("Observer", "App", "Punch Cards", "Other");
        $stopAllowedArray = array("Stop permitted", "Non-stop");
        $authorityArray = array("ACU", "AMCA", "Other");
        $restrictionArray = array("Open", "Centre", "Closed to Club", "Other Restriction");

        $classes = $series->classes;
        $courses = $series->courses;

    @endphp



    <form action="/trials/save" method="POST">

        @csrf
        <input type="hidden" name="task" id="task" value="trialData">
        <input type="hidden" name="trialID" id="trialID" value="{{$trial->id}}">
        <div id="Trial" class="tabcontent pt-0">
            <div class="space-y-12">
                <div class="px-4 py-4 mt-0 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">

                        <div id="courseDataDiv" class="col-span-3">
{{--                            <x-form-field>--}}
{{--                                <x-form-label class="pr-0" for="courselist">Courses</x-form-label>--}}
{{--                                <div class=" pl-2 pr-0">--}}
{{--                                    @foreach($courseArray as $course)--}}
{{--                                        <div>--}}
{{--                                            <input  name="courselist[]" type="checkbox" id="courselist" value="{{$course}}"--}}
{{--                                                    {{ (is_array(old('courselist')) and in_array($course, old('courselist'))) ? ' checked' : '' }}--}}
{{--                                            />--}}
{{--                                            <label  class="pl-4 pr-0" for="courselist">{{$course}}--}}
{{--                                            </label>--}}
{{--                                        </div>--}}
{{--                                    @endforeach--}}
{{--                                    <x-form-error name="courselist"/>--}}
{{--                                </div>--}}
{{--                                @error('courselist')--}}
{{--                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>--}}
{{--                                @enderror--}}
{{--                            </x-form-field>--}}


                            <x-form-field>
                                <x-form-label for="customCourses">Courses</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="customCourses" type="text" id="customCourses"
                                                  value="{{$courses}}"
                                                  placeholder="List of courses separated by commas" />
{{--                                    <x-form-error name="customCourses"/>--}}
                                </div>
                                @error('customCourses')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                        </div>

                        <div id="classDataDiv" class="col-span-3">
{{--                            <x-form-field>--}}
{{--                                <x-form-label for="classlist">Classes</x-form-label>--}}
{{--                                <div class=" pl-2 pr-2">--}}
{{--                                    @foreach($classArray as $class)--}}

{{--                                        <div>--}}
{{--                                            <input  name="classlist[]" type="checkbox" id="classlist" value="{{$class}}"--}}

{{--                                                    {{ (is_array(old('classlist')) and in_array($class, old('classlist'))) ? ' checked' : '' }}--}}
{{--                                            />--}}
{{--                                            <label  class="pl-4 pr-2" for="classlist">{{$class}}</label>--}}
{{--                                        </div>--}}
{{--                                    @endforeach--}}
{{--                                    <x-form-error name="classlist"/>--}}
{{--                                </div>--}}
{{--                                @error('classlist')--}}
{{--                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>--}}
{{--                                @enderror--}}
{{--                            </x-form-field>--}}

                            <x-form-field>
                                <x-form-label for="customClasses">Classes</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="customClasses" type="text" id="customClasses"
                                                  value="{{$classes}}"
                                                  placeholder="List of classes separated by commas" />
{{--                                    <x-form-error name="customClasses"/>--}}
                                </div>
                                @error('customClasses')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                        </div>
                        <div id="hasTimePenaltyDiv" class="col-span-full">
                            <x-form-field>
                                <x-form-label for="hasTimePenalty">Time and Observation</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <input name="hasTimePenalty" type="checkbox" id="hasTimePenalty" value="1"
                                            {{old('hasTimePenalty') != null ? 'checked' :''}}
                                    />
                                </div>
                                @error('hasTimePenalty')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                        </div>

                        <div id="startIntervalDiv" class="col-span-3">
                            <x-form-field>
                                <x-form-label for="startInterval">Start interval (seconds)</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="startInterval" type="text" id="startInterval"
                                                  placeholder="Start interval in seconds" />
                                </div>
                                @error('startInterval')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                        </div>

                        <div id="penaltyDiv" class="col-span-3">
                            <x-form-field>
                                <x-form-label for="penaltyDelta">Penalty tariff</x-form-label>
                                <div class="mt-2 col-span-2">
                                    <x-form-input name="penaltyDelta" type="text" id="penaltyDelta"
                                                  placeholder="Number of seconds per point lost" />
                                </div>
                                @error('penaltyDelta')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </x-form-field>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="ml-4 mt-4" id="buttons">
            <a href="/adminTrials"
               class="rounded-md bg-white px-3 py-2 text-sm  drop-shadow-lg text-blue-900 shadow-sm hover:bg-blue-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-900">Cancel</a>

            <button type="submit"
                    class="rounded-md ml-2 bg-blue-600 px-3 py-1 text-sm font-light  border border-blue-800 text-white drop-shadow-lg hover:bg-blue-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                Save
            </button>
        </div>
    </form>
</x-club>