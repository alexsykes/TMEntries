<x-guest-layout>
    @php
        $classes = explode(',',$trial->classlist);
        $courses = explode(',',$trial->courselist);
 @endphp
<form>
    @csrf
    <div class="font-bold  font-size-sm text-violet-600">Entry form for {{$trial->name}}</div>
    <x-primary-button>Save</x-primary-button>
    <x-secondary-button>Cancel</x-secondary-button>
    <x-input-label>Name</x-input-label>
    <x-text-input>Name</x-text-input>


    <x-input-label>Class</x-input-label>
    @foreach($classes as $class)
        <x-dropdown-link>{{$class}}</x-dropdown-link>
    @endforeach


    <x-input-label>Course</x-input-label>
    @foreach($courses as $course)
        <x-dropdown-link>{{$course}}</x-dropdown-link>
    @endforeach
{{--    </x-dropdown>--}}
</form>
</x-guest-layout>