<x-main>
    dd(request());
    @php
// $entries and $trial passed in from controller
        $classes = explode(',',$trial->classlist);
        $courses = explode(',',$trial->courselist);

    @endphp

    <form action="entries.checkout">
        @csrf
    <div id="checkout_button">
        <x-secondary-button>Checkout</x-secondary-button>
    </div>
    </form>
    <div>Entries for {{$trial->name}} - from {{$email}}</div>
    <table>
        <tr>
        <th>Rider</th>
        <th>Course</th>
            <th>Class</th>
            <th>Bike</th>
            <th>Edit</th>
            <th>Remove</th>
        </tr>
    @foreach($entries as $entry)
        <tr>
            <td>{{$entry->name}}</td>
            <td>{{$entry->course}}</td>
            <td>{{$entry->class}}</td>
            <td>{{$entry->make}} {{$entry->size}}</td>
            <td>Edit</td>
            <td>Remove</td>
        </tr>
    @endforeach
    </table>
    <form action="/entries/store" method="POST">
        @csrf
        <input type="hidden" id="trial_id" name="trial_id" value="{{$trial->id}}"/>
        <input type="hidden" id="email" name="email" value="{{$email}}"/>
        <div class="font-bold  font-size-sm text-violet-600">Entry form for {{$trial->name}}</div>


        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" required />
            @error('name')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="licence" class="form-label">{{$trial->auth}} Licence</label>
            <input type="text" name="licence" id="licence" />
            @error('licence')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Contact number</label>
            <input type="text" name="phone" id="phone" required />
            @error('phone')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="course" class="form-label">Course</label>
            <select class="form-select" id="class" name="course">
                @foreach($courses as $course)
                    <option value="{{ $course }}" {{ old('class') == $course ? 'selected' : '' }}>{{ $course }}</option>
                @endforeach
            </select>
            @error('course')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="class" class="form-label">Class</label>
            <select class="form-select" id="class" name="class">
                @foreach($classes as $class)
                    <option value="{{ $class }}" {{ old('class') == $class ? 'selected' : '' }}>{{ $class }}</option>
                @endforeach
            </select>
            @error('class')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="isYouth" class="form-label">Under-18</label>
            <input type="checkbox" name="isYouth" id="isYouth"  />
            @error('isYouth')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="dob" class="form-label">Date of Birth</label>
            <input type="date" name="dob" id="dob"  />
            @error('dob')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>


        <div id="buttons">
            <x-secondary-button>Cancel</x-secondary-button>
            <x-primary-button>Save</x-primary-button>
        </div>
    </form></x-main>