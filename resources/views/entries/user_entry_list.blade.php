<x-main>
    <x-slot:heading>Entries for {{$user->name}}</x-slot:heading>

    @php
        /* Paid status
            0 - New entry within limit, not paid
            1 - Confirmed entry
            2 - Withdrawn, having paid, waiting for refund
            3 - Refunded entries
            4 - Reserve - invoiced, awaiting payment
            5 - Reserve - not paid
            6 - Removed
            7 - Manual entry - unpaid
            8 - Manual entry - paid
            9 - Manual entry - FoC
        */

    $status = array('Awaiting payment', 'Confirmed Entry','Awaiting Refund', 'Refunded', 'Reserve - awaiting payment', 'Reserve', 'Removed by admin', 'Manual Entry - to pay', 'Manual Entry - paid', 'Manual Entry - FoC' );
    @endphp

<div class="space-y-4">
    <div class="px-4 py-4 mt-6 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300">
        <table>
        @foreach($entries as $class)
                <tr><td colspan="4" class="font-bold text-blue-800 pt-2">{{$status[$class[0]->status]}}</td></tr>
            @foreach($class as $entry)
              <tr>
                  <td class="">{{$entry->name}}</td>
                  <td class="pl-4">{{$entry->course}}</td>
                  <td class="pl-4">{{$entry->class}}</td>
                  <td class="pl-4">{{$entry->make}} {{$entry->size}}</td>
              </tr>
            @endforeach

        @endforeach
        </table>
    </div>
</div>
</x-main>