<x-main>
    @php
//    $trial from request
        $classes = explode(',',$trial->classlist);
        $courses = explode(',',$trial->courselist);
    @endphp
    <form action="/entries/store" method="POST">
        @csrf
        <input type="hidden" id="trial_id" name="trial_id" value="{{$trial->id}}"/>
        <div class="font-bold  font-size-sm text-violet-600">Entry form for {{$trial->name}}</div>


        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" required />
            @error('email')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

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
            <label for="make" class="form-label">Make</label>
            <input type="text" name="make" id="make" required />
            @error('make')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="size" class="form-label">Capacity(cc)</label>
            <input type="text" name="size" id="size" />
            @error('size')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>


        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select class="form-select" id="class" name="type">
                <option value="2T" {{ old('type') == '2T' ? 'selected' : '' }}>2T</option>
                <option value="4T" {{ old('type') == '4T' ? 'selected' : '' }}>4T</option>
                <option value="E-bike" {{ old('type') == 'E-bike' ? 'selected' : '' }}>E-bike</option>
            </select>
            @error('type')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>


        <div id="buttons">
            <x-secondary-button>Cancel</x-secondary-button>
            <x-primary-button>Save</x-primary-button>
        </div>
    </form>
</x-main>