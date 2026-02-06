<x-admin>
    @php
        $date = date_format(date_create($trial->date), "M jS, Y");

        $isPublished = $trial->published;
        $published = $isPublished ? "checked" : "";

        $isLocked = $trial->isLocked;
        $locked = $isLocked ? "checked" : "";

        $isScoringLocked = $trial->isScoringLocked;
        $scoringLocked = $isScoringLocked ? "checked" : "";

        $isEntryLocked = $trial->isEntryLocked;
        $entryLocked = $isEntryLocked ? "checked" : "";

        $isResultPublished = $trial->isResultPublished;
        $resultPublished = $isResultPublished ? "checked" : "";
    @endphp
    {{--    @dump(($trial))--}}
    <x-slot:heading></x-slot:heading>
    <div class=" mt-00 mb-4  bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-red-600">
            &nbsp{{$trial->name}}</div>

        <form action="/admin/trial/update" method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden" value="{{$trial->id}}" name="id" id="id">
            <div class="grid sm:grid-cols-6 p-2 pr-2">
                <div class="sm:col-span-2"><span class="font-semibold">Date: </span>{{$date}}</div>
                <div class="sm:col-span-2 sm:text-center"><span class="font-semibold">Club: </span>{{$trial->club}}
                </div>
                <div class="sm:col-span-2 sm:text-right"><span class="font-semibold">Venue: </span>{{$venue->name}}
                </div>
                <div class="sm:col-span-3"><span class="font-semibold">Courses: </span>{{$trial->customCourses}}</div>
                <div class="sm:col-span-3 sm:text-right"><span
                            class="font-semibold">Classes: </span>{{$trial->customClasses}}</div>

                <div class="col-span-full font-semibold pt-2 text-red-700">
                    Settings
                </div>

                <div class="col-span-full ">
                    <label class="font-semibold pr-2" for="password">Show / Hide</label>
                    <input type="checkbox" name="published" id="published" {{$locked}} />
                </div>

                <div class="sm:col-span-3">
                    <label class="font-semibold pr-2" for="password">Trial Locked</label>
                    <input type="checkbox" name="isLocked" id="isLocked" {{$locked}} />
                </div>

                <div class="sm:col-span-3">
                    <label class="font-semibold pr-2" for="password">Entry Locked</label>
                    <input type="checkbox" name="isEntryLocked" id="isEntryLocked" {{$entryLocked}} />
                </div>

                <div class="sm:col-span-3">
                    <label class="font-semibold pr-2" for="password">Scoring Locked</label>
                    <input type="checkbox" name="isScoringLocked" id="isScoringLocked" {{$scoringLocked}} />
                </div>

                <div class="sm:col-span-3">
                    <label class="font-semibold pr-2" for="password">Result Published</label>
                    <input type="checkbox" name="isResultPublished" id="isResultPublished" {{$resultPublished}} />
                </div>

                <div class="flex mr-4  mt-4 justify-between" id="buttons">
                    <div>
                        <a href="/admin/trials"
                           class="rounded-md bg-slate-200 px-3 py-1 text-sm outline  outline-black outline-1 drop-shadow-lg text-slate-900 shadow-sm hover:bg-slate-500 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-900">Cancel</a>

                        <button type="submit" name="submitbutton" value="apply"
                                class="rounded-md mt-2 bg-slate-900 ml-2  px-3 py-1 text-sm font-light  border border-black text-white drop-shadow-lg hover:bg-gray-300 hover:text-black focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black">
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class=" mt-00 mb-4  bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="font-bold uppercase text-center w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-red-600">
            Danger! The following
            actions may result in loss
            of data or income. A
            full backup
            should be made before selecting any
            of them.
        </div>
        <div class="grid sm:grid-cols-2 gap-x-4 p-2 pr-2">

            <div class="col-span-full">
                <form action="/admin/trial/backupTrial" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" value="{{$trial->id}}" name="id" id="id">
                    <div class="col-span-full">
                        <div class="font-semibold pt-2 text-red-700">
                            Backup score data
                        </div>
                        <div class="">Download a backup of the score and entry data for this trial.
                        </div>
                        <button type="submit" name="resetScoringButton" value="apply"
                                class="rounded-md mt-2 bg-slate-900  px-3 py-1 text-sm font-light  border border-black text-white drop-shadow-lg hover:bg-gray-300 hover:text-black focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black">
                            Backup scoring data
                        </button>
                    </div>
                </form>
            </div>

            @if($trial->isScoringSetup)
                <div class="col-span-1 mt-2">
                    <form action="/admin/trial/resetScoring" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" value="{{$trial->id}}" name="id" id="id">
                        <div class="col-span-1">
                            <div class="font-semibold pt-2 text-red-700">
                                Reset scoring
                            </div>
                            <div class="">
                                Resetting the scoring for a trial will remove the current scoring setup and should only
                                be used if there has been a major change to the number of sections, laps etc.
                            </div>
                            <button type="submit" name="resetScoringButton" value="apply"
                                    class="rounded-md mt-2 bg-slate-900  px-3 py-1 text-sm font-light  border border-black text-white drop-shadow-lg hover:bg-gray-300 hover:text-black focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black">
                                Reset
                            </button>
                        </div>
                    </form>
                </div>
            @endif
            @if($trial->isResultPublished)
                <div class="col-span-1 mt-2">
                    <form action="/admin/trial/unpublish" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" value="{{$trial->id}}" name="id" id="id">
                        <div class="col-span-1">
                            <div class="font-semibold pt-2 text-red-700">
                                Unpublish results
                            </div>
                            <div class="">
                                Unpublishing a trial will revert the trial to the state immediately BEFORE the Publish
                                button was pressed. This action will cancel all manual changes made after the result was
                                published.
                            </div>
                            <button type="submit" name="unpublishbutton" value="apply"
                                    class="rounded-md mt-2 bg-slate-900  px-3 py-1 text-sm font-light  border border-black text-white drop-shadow-lg hover:bg-gray-300 hover:text-black focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black">
                                Unpublish
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            @if($trial->isResultPublished)
                <div class="col-span-1 mt-2">
                    <form action="/admin/trial/archive" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" value="{{$trial->id}}" name="id" id="id">
                        <div class="col-span-1">
                            <div class=" font-semibold pt-2 text-red-700">
                                Archive this trial
                            </div>
                            <div class="">Archiving a trial will remove the raw scores from the database saving space.
                                It should only be done
                                once the results are confirmed and any protests or queries have been resolved.
                            </div>
                            <button type="submit" name="submitbutton" value="apply"
                                    class="rounded-md mt-2 bg-slate-900  px-3 py-1 text-sm font-light  border border-black text-white drop-shadow-lg hover:bg-gray-300 hover:text-black focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black">
                                Archive
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <div class="col-span-1 mt-2">
                <form action="/admin/trial/refund" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" value="{{$trial->id}}" name="id" id="id">
                    <div class="col-span-1">
                        <div class="font-semibold pt-2 text-red-700">
                            Refund entry fees
                        </div>
                        <div class="">Refunding a trial will return entry fees paid to the original payments accounts.
                            An
                            optional administration fee (up to £3) can be retained to cover costs.
                        </div>
                        <div><label class="font-semibold text-red-700" for="fee">Admin Fee (£)</label>
                            <input class="w-16 pl-2" type="text" name="fee" id="fee"/></div>

                        <div class="mt-2">
                            <button type="submit" name="submitbutton" value="refund"
                                    class="rounded-md mt-2 bg-slate-900  px-3 py-1 text-sm font-light  border border-black text-white drop-shadow-lg hover:bg-gray-300 hover:text-black focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black">
                                Refund entry fees
                            </button>
                            <button type="submit" name="submitbutton" value="refundAll"
                                    class="ml-2 rounded-md mt-2 bg-slate-900  px-3 py-1 text-sm font-light  border border-black text-white drop-shadow-lg hover:bg-gray-300 hover:text-black focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black">
                                Refund all payments
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin>